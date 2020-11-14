<?php

use app\core\Application;

class m0001_CREATE_Lawyers
{
    public function up()
    {
        $db  = Application::$app->db;
        $sql = "
            CREATE TABLE lawyers (
            lawyerId INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            firstName VARCHAR(255) NOT NULL,
            lastName VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ) ENGINE=INNODB;
        ";
        $db->pdo->exec($sql);
    }

    public function down()
    {
        $db  = Application::$app->db;
        $sql = "DROP TABLE lawyers;";
        $db->pdo->exec($sql);
    }
}
