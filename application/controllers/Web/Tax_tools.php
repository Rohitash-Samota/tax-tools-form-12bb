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
    }

    public function index()
    {
        try {
            $data = [];
            $data['page_title'] = 'Form 12BB : Generator Form 12BB & Claim your Tax benefits - Tax2win';
            $data['scripts'] = [base_url('/assets/js/form_12bb/script.js')];
            $data['csrf'] = $this->getCsrf();

            $data['content'] = $this->load->view('form_12bb/index', $data, TRUE);
            $this->load->view('layouts/app', $data);
        } catch (Throwable $e) {
            log_message('error', 'Index error: ' . $e->getMessage());
            show_error('An error occurred while loading the form.');
        }
    }

    public function loadData()
    {
        try {
            $inputs  = $this->input->get();
            $form_id = isset($inputs['form_id']) ? (int)$inputs['form_id'] : 0;

            if (!$form_id) {
                return $this->json([
                    'status' => 'failed',
                    'message' => 'Form ID is required to load data.',
                    'csrf' => $this->getCsrf(),
                ], 400);
            }

            $data = $this->formRepo->get_full($form_id);

            if (!$data) {
                return $this->json([
                    'status' => 'failed',
                    'message' => 'Invalid Form ID. No data found.',
                    'csrf' => $this->getCsrf(),
                ], 404);
            }

            return $this->json([
                'status' => 'success',
                'data'   => $data,
                'csrf'   => $this->getCsrf(),
            ]);
        } catch (Throwable $e) {
            log_message('error', 'loadData error: ' . $e->getMessage());
            return $this->json([
                'status' => 'failed',
                'message' => 'An error occurred while loading the form.',
                'csrf' => $this->getCsrf(),
            ], 500);
        }
    }

    public function saveAndUpdateForm()
    {
        try {
            $inputs    = $this->input->post() ?: [];
            $step_name = $this->input->post('step') ?: 'employee_details';
            if ($step_name == 'deductions') {
                $inputs['deductions'] = $this->formateDeductions($inputs);
            }
            $result = $this->formRepo->save_step($step_name, $inputs);

            if (!is_array($result)) {
                $result = ['status' => 'success', 'data' => $result];
            }

            $result['csrf'] = $this->getCsrf(); // rotate token back to client

            return $this->json($result);
        } catch (Throwable $e) {
            log_message('error', 'saveAndUpdateForm error: ' . $e->getMessage());
            return $this->json([
                'status' => 'failed',
                'message' => 'An error occurred while saving the form.',
                'csrf' => $this->getCsrf(),
            ], 500);
        }
    }

    public function createPdf()
    {
        try {
            $inputs  = $this->input->get();
            $form_id = isset($inputs['form_id']) ? (int)$inputs['form_id'] : 0;

            if (!$form_id) {
                throw new InvalidArgumentException('Form ID is required to generate PDF.');
            }

            $data = $this->formRepo->get_full($form_id);
            if (!$data) {
                throw new InvalidArgumentException('Invalid Form ID. No data found.');
            }

            $html = $this->load->view('form12bb/pdf', $data, TRUE);

            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans');

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->loadHtml($html, 'UTF-8');
            $dompdf->render();

            $canvas = $dompdf->get_canvas();
            $w = $canvas->get_width();
            $h = $canvas->get_height();
            $canvas->page_text($w - 120, $h - 28, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 9, array(0, 0, 0));
            $canvas->page_text(40, $h - 28, date('Y-m-d H:i'), null, 9, array(0, 0, 0));

            $filename = 'Form-12BB-' . $form_id . '.pdf';
            $dompdf->stream($filename, ['Attachment' => false]);
            return;
        } catch (Throwable $e) {
            log_message('error', 'PDF error: ' . $e->getMessage());
            show_error('An error occurred while generating the PDF.', 500);
        }
    }

    private function getCsrf()
    {
        return [
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash(),
        ];
    }

    public function getCsrfToken()
    {
        return $this->json($this->getCsrf());
    }

    private function formateDeductions($inputs)
    {
        $deductions = [];

        $groups = [
            'dedn_eighty_c' => '80C',
            'dedn_other'    => 'Other',
        ];

        foreach ($groups as $groupKey => $sectionName) {
            $types     = isset($inputs[$groupKey]) ? $inputs[$groupKey] : [];
            $amounts   = isset($inputs[$groupKey . '_amount']) ? $inputs[$groupKey . '_amount'] : [];
            $evidences = isset($inputs[$groupKey . '_evidence']) ? $inputs[$groupKey . '_evidence'] : [];

            if (is_array($types)) {
                foreach ($types as $index => $typeName) {
                    $deductions[] = [
                        'section'  => $sectionName,                  // e.g. "80C" or "Other"
                        'type'     => $typeName,                     // e.g. "Life Insurance Premium"
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
}
