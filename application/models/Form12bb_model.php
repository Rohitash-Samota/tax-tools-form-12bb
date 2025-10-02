<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form12bb_model extends CI_Model
{
    protected $table = 'form_12bb';

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
        $row = $this->db->where('id', $id)->get($this->table)->row_array();
        return $row ?: null;
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->where('id', $id)->update($this->table, $data);
    }

    public function delete(int $id): bool
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function list(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        if (!empty($filters)) $this->db->where($filters);
        return $this->db->order_by('id', 'DESC')->limit($limit, $offset)->get($this->table)->result_array();
    }
}
