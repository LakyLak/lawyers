<?php

use app\core\Application;

class m0002_CREATE_Citizens
{
    public function up()
    {
        $db  = Application::$app->db;
        $sql = "
            CREATE TABLE citizens (
            citizenId INT AUTO_INCREMENT PRIMARY KEY,
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
        $sql = "DROP TABLE citizens;";
        $db->pdo->exec($sql);
    }
}
