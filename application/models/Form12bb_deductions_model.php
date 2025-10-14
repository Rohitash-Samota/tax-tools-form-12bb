<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form12bb_deductions_model extends CI_Model
{
    protected $table = 'form_12bb_deductions';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function create(array $data): int
    {
        $this->db->insert($this->table, $data);
        return (int)$this->db->insert_id();
    }

    public function get(int $id): ?array
    {
        $row = $this->db->where('form_12bb_deductions_id', $id)->get($this->table)->row_array();
        return $row ?: null;
    }

    public function list_by_form(int $form_id): array
    {
        return $this->db->where('form_12bb_id', $form_id)->get($this->table)->result_array();
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->where('form_12bb_deductions_id', $id)->update($this->table, $data);
    }

    public function delete(int $id): bool
    {
        return $this->db->where('form_12bb_deductions_id', $id)->delete($this->table);
    }

    public function delete_by_form(int $form_id): bool
    {
        return $this->db->where('form_12bb_id', $form_id)->delete($this->table);
    }
}
