<?php

use app\core\Application;

class m0003_CREATE_Appointments
{
    public function up()
    {
        $db  = Application::$app->db;
        $sql = "
            CREATE TABLE appointments (
            appointmentId INT AUTO_INCREMENT PRIMARY KEY,
            lawyerId INT(11) NOT NULL,
            citizenId INT(11) NOT NULL, 
            date DATE NOT NULL, 
            hour INT(11) NOT NULL, 
            status INT(11) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;
        ";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db  = Application::$app->db;
        $sql = "DROP TABLE appointments;";
        $db->pdo->exec($sql);
    }
}
