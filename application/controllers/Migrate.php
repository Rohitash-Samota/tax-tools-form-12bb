<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('migration');
    }

    public function index()
    {
        if ($this->migration->current() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo "Migrations ran successfully.\n";
        }
    }
    // php index.php migrate latest for create latest migration
    public function latest()
    {
        if ($this->migration->latest() === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo "Migrated to latest version.\n";
        }
    }

    // php index.php migrate version 3 for create specific migration
    public function version($target = null)
    {
        $target = (int) $target;
        if (!$target) show_404();
        if ($this->migration->version($target) === FALSE) {
            show_error($this->migration->error_string());
        } else {
            echo "Migrated to version {$target}.\n";
        }
    }
}
