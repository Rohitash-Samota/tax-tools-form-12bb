<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Class Tax_tools
 *
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property Form12bb_repository $formRepo
 * @property Json $json
 */

// Step 1:  => employee_details
// Step 2:  => housing_rent_allowance
// Step 3:  => leave_travel_concession
// Step 4:  => interest_on_loan
// Step 5:  => deductions

class Tax_tools extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation']);
        $this->load->model('Form12bb_repository', 'formRepo');
    }

    public function index()
    {
        try {
            $inputs = $this->input->get();
            $form_id = $inputs['form_id'] ?? null;

            if ($form_id) {
                $data = $this->formRepo->get_full((int)$form_id);
                if (!$data) {
                    show_404();
                }
            } else {
                $data = [];
            }
        } catch (Throwable $e) {
            log_message('error', $e->getMessage());
            show_error('An error occurred while loading the form.');
            return ['code' => 'failed', 'errors' => ['An error occurred while loading the form.'], 'message' => 'An error occurred while loading the form.'];
        }
    }

    public function save_form_12bb($step_name = 'employee_details', $inputs = [])
    {
        try {
            log_message('debug', "Saving step: " . $step_name);
            if (empty($inputs)) {
                $inputs = $this->input->post();
            }
            $form_id = $inputs['form_id'] ?? null;
            
            if (!$this->validate_step($step_name, $inputs)) {
                return ['status' => 'failed', 'errors' => validation_errors(), 'message' => 'Validation failed for ' . $step_name . '. Please correct the errors and try again.'];
            }
            $response = $this->createAndUpdateForm($form_id, $step_name, $inputs);

            return ['code' => 'success', 'form_id' => $form_id, 'message' => 'Form saved successfully.', 'step_name' => $step_name, 'data' => $response];
        } catch (Throwable $e) {
            log_message('error', $e->getMessage());
            show_error('An error occurred while saving the form.');
            return ['code' => 'failed', 'errors' => ['An error occurred while saving the form.'], 'message' => 'An error occurred while saving the form.'];
        }
    }

    private function validate_step(string $step, array $data): bool
    {
        $this->form_validation->reset_validation();
        switch ($step) {
            case 'employee_details':
                $this->form_validation->set_rules('name', 'Name', 'required|string|max_length[100]');
                $this->form_validation->set_rules('pan', 'PAN', 'required|alpha_numeric|exact_length[10]');
                $this->form_validation->set_rules('father_name', 'Father Name', 'required|string|max_length[100]');
                $this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric|exact_length[10]');
                $this->form_validation->set_rules('email', 'Email', 'valid_email|max_length[100]');
                $this->form_validation->set_rules('place', 'Place', 'string|max_length[100]');
                $this->form_validation->set_rules('address', 'Address', 'string');
                break;

            case 'housing_rent_allowance':
                $this->form_validation->set_rules('rent_paid', 'Rent Paid', 'required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('landlord_name', 'HRA Landlord Name', 'required|string|max_length[100]');
                $this->form_validation->set_rules('landlord_pan', 'HRA Landlord PAN', 'alpha_numeric|exact_length[10]');
                $this->form_validation->set_rules('landlord_address', 'HRA Landlord Address', 'string|max_length[200]');
                $this->form_validation->set_rules('evidence', 'HRA Evidence', 'string|max_length[200]');
                break;

            case 'leave_travel_concession':
                $this->form_validation->set_rules('amount', 'LTA Amount', 'required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('evidence', 'LTA Evidence', 'string|max_length[200]');
                break;

            case 'interest_on_loan':
                $this->form_validation->set_rules('interest_payable', 'Interest Payable', 'required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('lender_name', 'Lender Name', 'required|string|max_length[100]');
                $this->form_validation->set_rules('lender_pan', 'Lender PAN', 'alpha_numeric|exact_length[10]');
                $this->form_validation->set_rules('lender_address', 'Lender Address', 'string|max_length[200]');
                $this->form_validation->set_rules('evidence', 'Loan Evidence', 'string|max_length[200]');
                break;

            case 'deductions':
                $this->form_validation->set_rules('deductions', 'Deductions', 'required|array');
                $this->form_validation->set_rules('deductions.*.type', 'Deduction Type', 'required|string|max_length[100]');
                $this->form_validation->set_rules('deductions.*.amount', 'Deduction Amount', 'required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('deductions.*.evidence', 'Deduction Evidence', 'string|max_length[200]');
                break;

            default:
                return false;
        }

        return $this->form_validation->run($data);
    }

    private function createAndUpdateForm(?int &$form_id, string $step_name, array $data): array
    {
        if ($form_id) {
            if ($step_name == 'employee_details') {
                $this->formRepo->updateEmployeeDetails($form_id, $data);
                return ['status' => 'updated', 'form_id' => $form_id];
            } else if ($step_name == 'housing_rent_allowance') {
                $this->formRepo->updateHra($form_id, $data);
                return ['status' => 'updated', 'form_id' => $form_id];
            } else if ($step_name == 'leave_travel_concession') {
                $this->formRepo->updateLtc($form_id, $data);
                return ['status' => 'updated', 'form_id' => $form_id];
            } else if ($step_name == 'interest_on_loan') {
                $this->formRepo->updateLoans($form_id, $data);
                return ['status' => 'updated', 'form_id' => $form_id];
            } else if ($step_name == 'deductions') {
                $this->formRepo->updateDeductions($form_id, $data);
                return ['status' => 'updated', 'form_id' => $form_id];
            }

            return ['status' => 'updated', 'form_id' => $form_id, 'message' => 'No changes made for step: ' . $step_name];
        } else {
            if ($step_name != 'employee_details') {
                throw new InvalidArgumentException('Form ID is required for steps other than employee_details.');
            } else {
                $form_id = $this->formRepo->createNewForm($data);
            }
            return ['status' => 'created', 'form_id' => $form_id, 'message' => 'Form created successfully.'];
        }
    }

}
