<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Form12bb_repository extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Form12bb_model',        'Form');
        $this->load->model('Form12bb_hra_model',    'Hra');
        $this->load->model('Form12bb_ltc_model',    'Ltc');
        $this->load->model('Form12bb_loan_model',   'Loan');
        $this->load->model('Form12bb_deductions_model', 'Ded');
        $this->load->library('form_validation');
    }

    public function get_full(int $form_id): ?array
    {
        $form = $this->Form->get($form_id);
        if (!$form) return null;

        $form['hra']        = $this->Hra->list_by_form($form_id);
        $form['ltc']        = $this->Ltc->list_by_form($form_id);
        $form['loans']      = $this->Loan->list_by_form($form_id);
        $form['deductions'] = $this->Ded->list_by_form($form_id);

        return $form;
    }

    public function save_step(string $step_name, array $inputs): array
    {
        try {
            log_message('debug', 'Repo::save_step ' . $step_name);
            $form_id = isset($inputs['form_id']) && $inputs['form_id'] !== '' ? (int)$inputs['form_id'] : null;

            $valid = $this->validate_step($step_name, $inputs);
            if (!$valid['ok']) {
                return [
                    'status'  => 'failed',
                    'message' => 'Validation failed for ' . $step_name . '. Please correct the errors and try again.',
                    'errors'  => $valid['errors'],
                    'step'    => $step_name,
                ];
            }

            $result = $this->create_or_update($form_id, $step_name, $inputs);

            return array_merge([
                'status'  => 'success',
                'message' => 'Form saved successfully.',
                'step'    => $step_name,
            ], $result);
        } catch (Throwable $e) {
            log_message('error', 'Repo::save_step error: ' . $e->getMessage());
            return [
                'status'  => 'failed',
                'message' => 'An error occurred while saving the form.',
                'errors'  => [],
                'step'    => $step_name,
            ];
        }
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
                if (!isset($data['deductions']) || !is_array($data['deductions']) || empty($data['deductions'])) {
                    $errors['deductions'] = 'Deductions must be a non-empty array.';
                } else {
                    $idx = 0;
                    foreach ($data['deductions'] as $row) {
                        if (!isset($row['type']) || trim((string)$row['type']) === '') {
                            $errors["deductions[$idx][type]"] = 'Deduction Type is required.';
                        } elseif (mb_strlen($row['type']) > 100) {
                            $errors["deductions[$idx][type]"] = 'Deduction Type must be at most 100 characters.';
                        }

                        if (!isset($row['amount']) || !is_numeric($row['amount'])) {
                            $errors["deductions[$idx][amount]"] = 'Deduction Amount must be numeric.';
                        }
                        if (isset($row['evidence']) && mb_strlen((string)$row['evidence']) > 200) {
                            $errors["deductions[$idx][evidence]"] = 'Deduction Evidence must be at most 200 characters.';
                        }
                        $idx++;
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

        return ['ok' => empty($errors), 'errors' => $errors];
    }

    private function create_or_update(?int $form_id, string $step_name, array $data): array
    {
        if ($form_id) {
            switch ($step_name) {
                case 'employee_details':
                    $this->updateEmployeeDetails($form_id, $data);
                    break;
                case 'housing_rent_allowance':
                    $this->updateHra($form_id, $data);
                    break;
                case 'leave_travel_concession':
                    $this->updateLtc($form_id, $data);
                    break;
                case 'interest_on_loan':
                    $this->updateLoans($form_id, $data);
                    break;
                case 'deductions':
                    $deductions = isset($data['deductions']) && is_array($data['deductions']) ? $data['deductions'] : [];
                    $this->updateDeductions($form_id, $deductions);
                    break;
                default:
                    break;
            }
            return ['action' => 'updated', 'form_id' => $form_id];
        }

        if ($step_name !== 'employee_details') {
            throw new InvalidArgumentException('Form ID is required for steps other than employee_details.');
        }

        $new_id = $this->createNewForm($data);
        return ['action' => 'created', 'form_id' => $new_id];
    }

    public function createNewForm(array $form): int
    {
        $new_data['employee_name'] = $form['employee_name'] ?? null;
        $new_data['pan'] = $form['pan'] ?? null;
        $new_data['father_name'] = $form['father_name'] ?? null;
        $new_data['place'] = $form['place'] ?? null;
        $new_data['mobile'] = $form['mobile_no'] ?? null;
        $new_data['email'] = $form['email'] ?? null;
        $new_data['address'] = $form['address'] ?? null;

        $form_id = $this->Form->create($new_data);
        if (!$form_id) {
            throw new RuntimeException('Failed to create new Form 12BB.');
        }
        return $form_id;
    }

    public function updateEmployeeDetails(int $form_id, array $data): bool
    {
        $this->db->trans_start();
        $new_data['employee_name'] = $data['employee_name'] ?? null;
        $new_data['pan'] = $data['pan'] ?? null;
        $new_data['father_name'] = $data['father_name'] ?? null;
        $new_data['place'] = $data['place'] ?? null;
        $new_data['mobile'] = $data['mobile_no'] ?? null;
        $new_data['email'] = $data['email'] ?? null;
        $new_data['address'] = $data['address'] ?? null;
        $this->Form->update($form_id, $new_data);

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function updateHra(int $form_id, array $data): bool
    {
        $this->db->trans_start();
        $newData = [
            'rent_paid'        => $data['hra_rent_paid'] ?? 0,
            'landlord_name'    => $data['hra_landlord_name'] ?? null,
            'landlord_pan'     => $data['hra_landlord_pan'] ?? null,
            'landlord_address' => $data['hra_landlord_address'] ?? null,
            'evidence'         => $data['hra_evidence'] ?? null,
        ];
        $existing = $this->Hra->list_by_form($form_id);
        log_message('debug', 'Existing HRA records: ' . json_encode($existing));
        if (empty($existing)) {
            $newData['form_id'] = $form_id;
            $this->Hra->create($newData);
        } else {
            $this->Hra->update($existing[0]['id'], $newData);
        }
        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function updateLtc(int $form_id, array $data): bool
    {
        $this->db->trans_start();
        $new_data = [
            'amount'   => $data['ltc_amount'] ?? 0,
            'evidence' => $data['ltc_evidence'] ?? null,
        ];
        $existing = $this->Ltc->list_by_form($form_id);
        if (empty($existing)) {
            $new_data['form_id'] = $form_id;
            $this->Ltc->create($new_data);
        } else {
            $this->Ltc->update($existing[0]['id'], $new_data);
        }
        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function updateLoans(int $form_id, array $data): bool
    {
        $this->db->trans_start();
        $new_data = [
            'interest_payable' => $data['home_loan_interest_payable'] ?? 0,
            'loan_provider'      => $data['home_loan_lender_name'] ?? null,
            'lender_pan'       => $data['home_loan_lender_pan'] ?? null,
            'loan_provider_address'   => $data['home_loan_lender_address'] ?? null,
            'evidence'         => $data['home_loan_evidence'] ?? null,
        ];

        $existing = $this->Loan->list_by_form($form_id);
        log_message('debug', 'Existing Loan records: ' . json_encode($existing));
        if (empty($existing)) {
            $new_data['form_id'] = $form_id;
            $this->Loan->create($new_data);
        } else {
            $this->Loan->update($existing[0]['id'], $new_data);
        }
        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function updateDeductions(int $form_id, array $deductions): bool
    {
        $this->db->trans_start();
        $this->Ded->delete_by_form($form_id);
        foreach ($deductions as $r) {
            $r['form_id'] = $form_id;
            $this->Ded->create($r);
        }
        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function create_with_children(array $form, array $hra = [], array $ltc = [], array $loans = [], array $deductions = []): int
    {
        $this->db->trans_start();

        $form_id = $this->Form->create($form);

        foreach ($hra as $row) {
            $row['form_id'] = $form_id;
            $this->Hra->create($row);
        }
        foreach ($ltc as $row) {
            $row['form_id'] = $form_id;
            $this->Ltc->create($row);
        }
        foreach ($loans as $r) {
            $r['form_id'] = $form_id;
            $this->Loan->create($r);
        }
        foreach ($deductions as $r) {
            $r['form_id'] = $form_id;
            $this->Ded->create($r);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            throw new RuntimeException('Failed to save Form 12BB and children.');
        }

        return $form_id;
    }
}
