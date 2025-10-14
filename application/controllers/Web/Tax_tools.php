<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Tax Tools Controller
 * @author  Rohit Samota
 * @link    https://www.tax2win.in
 * @package Tax2win
 */
class Tax_tools extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form', 'security']);
        $this->load->library(['session']);
        $this->load->model('Form12bb_repository', 'formRepo');
        $this->load->library('dompdf_lib');
        $this->load->library('form_validation');
    }

    public function index()
    {
        try {
            $data = [];
            $data['page_title'] = 'Form 12BB : Generator Form 12BB & Claim your Tax benefits - Tax2win';
            $data['scripts'] = [base_url('/assets/js/form_12bb/script.js')];
            $data['csrf'] = $this->get_csrf();

            $data['content'] = $this->load->view('form_12bb/index', $data, TRUE);
            $this->load->view('layouts/app', $data);
        } catch (Throwable $e) {
            log_message('error', 'Index error: ' . $e->getMessage());
            show_error('An error occurred while loading the form.');
        }
    }

    public function load_data()
    {
        try {
            $inputs  = $this->input->get();
            $form_id = isset($inputs['form_id']) ? (int)$inputs['form_id'] : 0;

            if (!$form_id) {
                return $this->json([
                    'status' => 'failed',
                    'message' => 'Form ID is required to load data.',
                    'csrf' => $this->get_csrf(),
                ], 400);
            }

            $data = $this->formRepo->get_full($form_id);

            if (!$data) {
                return $this->json([
                    'status' => 'failed',
                    'message' => 'Invalid Form ID. No data found.',
                    'csrf' => $this->get_csrf(),
                ], 404);
            }

            return $this->json([
                'status' => 'success',
                'data'   => $data,
                'csrf'   => $this->get_csrf(),
            ]);
        } catch (Throwable $e) {
            return $this->json([
                'status' => 'failed',
                'message' => 'An error occurred while loading the form.',
                'csrf' => $this->get_csrf(),
            ], 500);
        }
    }

    public function save_and_upadateForm()
    {
        try {
            $inputs    = $this->input->post() ?: [];
            $step_name = $this->input->post('step') ?: 'employee_details';

            $valid_result = $this->validate_step($step_name, $inputs);

            if (isset($valid_result['status']) && $valid_result['status'] === 'failed') {
                $valid_result['csrf'] = $this->get_csrf();
                return $this->json($valid_result, 422);
            }

            if ($step_name == 'deductions') {
                $inputs['deductions'] = $this->formate_deductions($inputs);
            }


            $result = $this->formRepo->save_step($step_name, $inputs);

            if (isset($result['status']) && $result['status'] === 'failed') {
                $result['csrf'] = $this->get_csrf();
                return $this->json($result, 400);
            }
            if (!is_array($result)) {
                $result = ['status' => 'success', 'data' => $result];
            }

            $result['csrf'] = $this->get_csrf();
            return $this->json($result);
        } catch (Throwable $e) {
            return $this->json([
                'status' => 'failed',
                'message' => 'An error occurred while saving the form.',
                'csrf' => $this->get_csrf(),
            ], 500);
        }
    }

    public function form_12bb_pdf($form_id)
    {
        $form_id = (int)$form_id;
        if (!$form_id) show_404();

        $data = $this->formRepo->get_full($form_id);
        if (!$data) show_404();

        $html = $this->load->view('form_12bb/pdf', ['data' => $data], true);

        $disposition = $this->input->get('disposition') === 'download' ? 'download' : 'inline';
        $this->dompdf_lib->render_html($html);

        $this->dompdf_lib->stream("Form12BB_{$form_id}.pdf", $disposition === 'download');
    }


    private function get_csrf()
    {
        return [
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash(),
        ];
    }

    public function get_csrf_token()
    {
        return $this->json($this->get_csrf());
    }

    private function formate_deductions($inputs)
    {
        $deductions = [];
        
        foreach (FORM_12BB_DEDUCTIONS_GROUP as $groupKey => $sectionName) {
            $types     = isset($inputs[$groupKey]) ? $inputs[$groupKey] : [];
            $amounts   = isset($inputs[$groupKey . '_amount']) ? $inputs[$groupKey . '_amount'] : [];
            $evidences = isset($inputs[$groupKey . '_evidence']) ? $inputs[$groupKey . '_evidence'] : [];

            if (is_array($types)) {
                foreach ($types as $index => $typeName) {
                    if (!$typeName) continue;
                    $deductions[] = [
                        'section'  => $sectionName,
                        'type'     => $typeName,
                        'amount'   => isset($amounts[$index]) ? (float)$amounts[$index] : 0,
                        'evidence' => isset($evidences[$index]) ? $evidences[$index] : null,
                    ];
                }
            }
        }

        return $deductions;
    }


    private function json(array $payload, int $status = 200)
    {
        if (!isset($payload['status']) && !isset($payload['name'])) {
            $payload = array_merge(['status' => 'success'], $payload);
        }

        return $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function validate_step(string $step, array $data): array
    {
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($data);
        $errors = [];

        switch ($step) {
            case 'employee_details':
                $this->form_validation->set_rules('employee_name', 'Name', 'trim|required|max_length[100]');
                $this->form_validation->set_rules(
                    'pan',
                    'PAN',
                    'trim|required|regex_match[/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/]',
                    ['regex_match' => 'Invalid PAN format. Example: ABCDE1234F']
                );
                $this->form_validation->set_rules('father_name', 'Father Name', 'trim|required|max_length[100]');
                $this->form_validation->set_rules(
                    'mobile_no',
                    'Mobile',
                    'trim|required|regex_match[/^[6-9][0-9]{9}$/]',
                    ['regex_match' => 'Enter a valid 10-digit Indian mobile number.']
                );
                $this->form_validation->set_rules(
                    'email',
                    'Email',
                    'trim|required|regex_match[/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i]',
                    ['regex_match' => 'Invalid email address.']
                );
                $this->form_validation->set_rules('place', 'Place', 'trim|max_length[100]');
                $this->form_validation->set_rules('address', 'Address', 'trim');
                break;

            case 'housing_rent_allowance':
                $this->form_validation->set_rules('hra_rent_paid', 'Rent Paid', 'trim|numeric');
                $this->form_validation->set_rules('hra_landlord_name', 'HRA Landlord Name', 'trim|max_length[100]');
                $this->form_validation->set_rules('hra_landlord_pan', 'HRA Landlord PAN', 'trim|alpha_numeric|exact_length[10]|regex_match[/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/]', ['regex_match' => 'Invalid PAN format. Example: ABCDE1234F']);
                $this->form_validation->set_rules('hra_landlord_address', 'HRA Landlord Address', 'trim|max_length[200]');
                $this->form_validation->set_rules('hra_evidence', 'HRA Evidence', 'trim|max_length[200]');
                break;

            case 'leave_travel_concession':
                $this->form_validation->set_rules('ltc_amount', 'LTC Amount', 'trim|numeric');
                $this->form_validation->set_rules('ltc_evidence', 'LTC Evidence', 'trim|max_length[200]');
                break;

            case 'interest_on_loan':
                $this->form_validation->set_rules('home_loan_interest_payable', 'Interest Payable', 'trim|numeric');
                $this->form_validation->set_rules('home_loan_lender_name', 'Lender Name', 'trim|max_length[100]');
                $this->form_validation->set_rules('home_loan_lender_pan', 'Lender PAN', 'trim|alpha_numeric|exact_length[10]|regex_match[/^[A-Za-z]{5}[0-9]{4}[A-Za-z]$/]', ['regex_match' => 'Invalid PAN format. Example: ABCDE1234F']);
                $this->form_validation->set_rules('home_loan_lender_address', 'Lender Address', 'trim|max_length[200]');
                $this->form_validation->set_rules('home_loan_evidence', 'Loan Evidence', 'trim|max_length[200]');
                break;

            case 'deductions':
                foreach (FORM_12BB_DEDUCTIONS_GROUP as $groupKey => $sectionName) {
                    $types     = $data[$groupKey] ?? [];
                    $amounts   = $data[$groupKey . '_amount'] ?? [];
                    $evidences = $data[$groupKey . '_evidence'] ?? [];

                    foreach ((array)$types as $i => $type) {
                        $type = trim((string)$type);
                        $amt  = $amounts[$i] ?? '';
                        $evi  = trim((string)($evidences[$i] ?? ''));

                        if ($type !== '') $foundAny = true;

                        if ($amt !== '' && $type === '')
                            $errors["{$groupKey}[{$i}][type]"] = 'Type required when amount is given.';
                        if ($type !== '' && mb_strlen($type) > 100)
                            $errors["{$groupKey}[{$i}][type]"] = 'Type max 100 chars.';
                        if ($amt !== '' && !is_numeric($amt))
                            $errors["{$groupKey}[{$i}][amount]"] = 'Amount must be numeric.';
                        if ($amt > 0 && $evi === '')
                            $errors["{$groupKey}[{$i}][evidence]"] = 'Evidence required when amount > 0.';
                        if ($evi !== '' && mb_strlen($evi) > 200)
                            $errors["{$groupKey}[{$i}][evidence]"] = 'Evidence max 200 chars.';
                    }
                }
                break;

            default:
                $errors['_step'] = 'Invalid step.';
        }

        $ok = $this->form_validation->run();
        if (!$ok) {
            $fv_errors = $this->form_validation->error_array();
            $errors = array_merge($errors, $fv_errors);
        }

        $valid = ['ok' => empty($errors), 'errors' => $errors];

        if (!$valid['ok']) {
            return [
                'status'  => 'failed',
                'message' => 'Validation failed for ' . $step . '. Please correct the errors and try again.',
                'errors'  => $valid['errors'],
                'step'    => $step,
            ];
        }
        return $valid;
    }
}
