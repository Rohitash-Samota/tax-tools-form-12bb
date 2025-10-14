<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_form12bb_hra extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'form_12bb_hra_id' => ['type'=>'INT','constraint'=>11,'unsigned'=>TRUE,'auto_increment'=>TRUE],
            'form_12bb_id' => ['type'=>'INT','constraint'=>11,'unsigned'=>TRUE],
            'rent_paid' => ['type'=>'DECIMAL','constraint'=>'12,2','null'=>TRUE],
            'landlord_name' => ['type'=>'VARCHAR','constraint'=>100,'null'=>TRUE],
            'landlord_address' => ['type'=>'TEXT','null'=>TRUE],
            'landlord_pan' => ['type'=>'VARCHAR','constraint'=>10,'null'=>TRUE],
            'evidence' => ['type'=>'TEXT','null'=>TRUE],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('form_12bb_hra_id', TRUE);
        $this->dbforge->add_key('form_12bb_id'); // index for relation
        $this->dbforge->create_table('form_12bb_hra', TRUE, ['ENGINE'=>'InnoDB']);
    }

    public function down()
    {
        $this->dbforge->drop_table('form_12bb_hra', TRUE);
    }
}
