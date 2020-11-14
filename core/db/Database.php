<?php


namespace app\core\db;


use app\core\Application;

class Database
{
    public static $migrations_path;
    public $pdo;

    /**
     * Database constructor.
     *
     * @param $pdo
     */
    public function __construct(array $config)
    {
        self::$migrations_path = Application::$root_directory . '/db/migrations/';
        $dsn       = $config['dsn'] ?? '';
        $user      = $config['user'] ?? '';
        $password  = $config['password'] ?? '';
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function applyMigrations()
    {
        $this->createMigrationTable();

        $appliedMigrations = $this->getAppliedMigrations() ?? [];
        $files = scandir(self::$migrations_path);

        $migrations = array_diff($files, $appliedMigrations, ['.', '..']);

        foreach ($migrations as $migration) {
            require_once self::$migrations_path . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            echo 'Applying migration ' . $className . PHP_EOL;
            $instance->up();
            echo 'Applied migration ' . $className . PHP_EOL;
        }

        if (!empty($migrations)) {
            $this->saveMigrations($migrations);
        } else {
            echo 'All migrations are applied' . PHP_EOL;
        }
    }

    private function createMigrationTable()
    {
        $this->pdo->exec(
            "
            CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ) 
            ENGINE=INNODB;"
        );
    }

    private function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations;");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

      function saveMigrations(array $migrations)
    {
        $queryString = implode(",", array_map(function ($m) {
            return "('$m')";
        }, $migrations));

        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES {$queryString} ");
        $statement->execute();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }
}
