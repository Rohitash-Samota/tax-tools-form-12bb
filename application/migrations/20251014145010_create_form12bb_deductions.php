
<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_Create_form12bb_deductions extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'form_12bb_deductions_id' => ['type'=>'INT','constraint'=>11,'unsigned'=>TRUE,'auto_increment'=>TRUE],
            'form_12bb_id' => ['type'=>'INT','constraint'=>11,'unsigned'=>TRUE],
            'section' => ['type'=>'VARCHAR','constraint'=>20,'null'=>TRUE],
            'type'    => ['type'=>'VARCHAR','constraint'=>100,'null'=>TRUE],  // Life Insurance, FD...
            'amount'  => ['type'=>'DECIMAL','constraint'=>'12,2','null'=>TRUE],
            'evidence'=> ['type'=>'TEXT','null'=>TRUE],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('form_12bb_deductions_id', TRUE);
        $this->dbforge->add_key('form_12bb_id');
        $this->dbforge->create_table('form_12bb_deductions', TRUE, ['ENGINE'=>'InnoDB']);
    }

    public function down()
    {
        $this->dbforge->drop_table('form_12bb_deductions', TRUE);
    }
}
