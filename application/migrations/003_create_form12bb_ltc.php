<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_form12bb_ltc extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>TRUE,'auto_increment'=>TRUE],
            'form_id' => ['type'=>'INT','constraint'=>11,'unsigned'=>TRUE],
            'amount'  => ['type'=>'DECIMAL','constraint'=>'12,2','null'=>TRUE],
            'evidence'=> ['type'=>'TEXT','null'=>TRUE],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('form_id');
        $this->dbforge->create_table('form_12bb_ltc', TRUE, ['ENGINE'=>'InnoDB']);
    }

    public function down()
    {
        $this->dbforge->drop_table('form_12bb_ltc', TRUE);
    }
}
