<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_form12bb_loan extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'form_12bb_loan_id' => ['type'=>'INT','constraint'=>11,'unsigned'=>TRUE,'auto_increment'=>TRUE],
            'form_12bb_id' => ['type'=>'INT','constraint'=>11,'unsigned'=>TRUE],
            'interest_payable' => ['type'=>'DECIMAL','constraint'=>'12,2','null'=>TRUE],
            'loan_provider'    => ['type'=>'VARCHAR','constraint'=>100,'null'=>TRUE],
            'loan_provider_address' => ['type'=>'TEXT','null'=>TRUE],
            'lender_pan' => ['type'=>'VARCHAR','constraint'=>10,'null'=>TRUE],
            'evidence'   => ['type'=>'TEXT','null'=>TRUE],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('form_12bb_loan_id', TRUE);
        $this->dbforge->add_key('form_12bb_id');
        $this->dbforge->create_table('form_12bb_loan', TRUE, ['ENGINE'=>'InnoDB']);
    }

    public function down()
    {
        $this->dbforge->drop_table('form_12bb_loan', TRUE);
    }
}
