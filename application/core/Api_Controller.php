<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->output->set_content_type('application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }
    }

    protected function json($data, int $status = 200) {
        return $this->output->set_status_header($status)->set_output(json_encode($data));
    }
}
