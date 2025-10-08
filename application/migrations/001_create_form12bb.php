<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_form12bb extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type'=>'INT','constraint'=>11,'unsigned'=>TRUE,'auto_increment'=>TRUE],
            'employee_name' => ['type'=>'VARCHAR','constraint'=>100],
            'father_name'   => ['type'=>'VARCHAR','constraint'=>100],
            'pan'           => ['type'=>'VARCHAR','constraint'=>10],
            'mobile'        => ['type'=>'VARCHAR','constraint'=>10,'null'=>TRUE],
            'email'         => ['type'=>'VARCHAR','constraint'=>100,'null'=>TRUE],
            'place'         => ['type'=>'VARCHAR','constraint'=>100,'null'=>TRUE],
            'address'       => ['type'=>'TEXT','null'=>TRUE],
            'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('form_12bb', TRUE, ['ENGINE'=>'InnoDB']);
    }

    public function down()
    {
        $this->dbforge->drop_table('form_12bb', TRUE);
    }
}
