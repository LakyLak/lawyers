<?php


namespace app\core\db;

use app\core\Application;
use app\core\Model;

abstract class DbModel extends Model
{
    const BELONGS_TO_ONE = 1;   //
    const BELONGS_TO_MANY = 2;  //  exercise belongs to many exerciseInstances (no key)
    const HAS_ONE    = 3;       //  exerciseInstance has only one exercise
    const HAS_MANY   = 4;       //

    abstract public function tableName(): string;

    abstract public function attributes(): array;

    abstract public function relations(): array;

    public function __get($item)
    {
        if (in_array($item, array_keys($this->relations()))) {
            return $this->getRelated($item);
        }
    }

    public function getRelated($relation)
    {
        $relationData = $this->relations()[$relation];

        $relationType  = $relationData[0];
        $relationClass = $relationData[1];
        $foreignKey    = $relationData[2];

        if ($relationType == self::BELONGS_TO_MANY || $relationType == self::HAS_MANY) {
            return $relationClass::findAll([$foreignKey => $this->{$foreignKey}]);
        }

        if ($relationType == self::HAS_ONE || $relationType == self::BELONGS_TO_ONE) {
            return $relationClass::find([$foreignKey => $this->{$foreignKey}]);
        }
    }

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $attributesToBind = array_map(function($a) {
            return ":$a";
        }, $attributes);

        $primaryKey = self::getPrimaryKeyName($tableName);

        if ($primaryKey && $record = self::find([$primaryKey => $this->{$primaryKey}])) {
            $updateAttributes = array_map(function($a) {
                return "$a = :$a";
            }, $attributes);
            $sql = "UPDATE $tableName SET " . implode(', ', $updateAttributes) . "
                WHERE $primaryKey = :$primaryKey;";
            $statement = $this->prepare($sql);
            $statement->bindValue(":$primaryKey", $this->{$primaryKey});
        } else {
            $sql = "INSERT INTO $tableName (" . implode(', ', $attributes) . ")
                VALUES (" . implode(', ', $attributesToBind) . ");";
            $statement = $this->prepare($sql);
        }

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();

        return true;
    }

    public function delete(array $criteria = [])
    {
        $tableName = $this->tableName();

        if (empty($criteria)) {
            $primaryKey = self::getPrimaryKeyName($tableName);
            $sql = "DELETE FROM $tableName WHERE $primaryKey = :$primaryKey";
            $statement = $this->prepare($sql);
            $statement->bindValue(":$primaryKey", $this->{$primaryKey});
        } else {
            $conditions = [];
            foreach ($criteria as $name => $value) {
                $conditions[] = "$name = :$name";
            }

            $sql = "DELETE FROM $tableName WHERE " . implode(' AND ', $conditions) . ";";
            
            $statement = $this->prepare($sql);

            foreach ($criteria as $name => $value) {
                $statement->bindValue(":$name", $this->{$name});
            }
        }

        if ($statement->execute()) {
            return true;
        }
        return false;
    }

    private static function find(array $params, bool $fetchAll = false)
    {
        $table = static::tableName();

        $sql = '';
        if (!empty($params)) {
            $attributes = array_keys($params);
            $sql = 'WHERE ' . implode('AND ', array_map(function($a) {
                return "$a = :$a ";
            }, $attributes));
        }

        $statement = self::prepare("SELECT * FROM $table $sql");

        foreach ($params as $key => $param) {
            $statement->bindValue(":$key", $param);
        }

        try {
            $statement->execute();
        } catch (\Exception $e) {
            $handle = fopen($_SERVER['DOCUMENT_ROOT'] .'/logs/exception.txt','a+');
            fwrite($handle, $e->getMessage() . PHP_EOL);
        }

        if ($fetchAll) {
            $result = $statement->fetchAll(\PDO::FETCH_CLASS);

            return self::changeToModel($result, static::class);
        }
        $result = $statement->fetchObject(static::class);

        return self::changeToModel($result, static::class);
    }

    public static function changeToModel($data, string $className)
    {
        if (is_array($data) && empty($data)) {
            return [];
        }

        if (!is_array($data) && empty($data)) {
            return null;
        }
        
        $result = [];
        if (!is_array($data)) {
            $object =  new $className();
            foreach ($data as $key => $value) {
                $object->{$key} = $value;
            }
            return $object;
        }

        foreach ($data as $std) {
            $object =  new $className();
            foreach ($std as $key => $value) {
                $object->{$key} = $value;
            }
            $result[] = $object;
        }

        return $result;
    }

    public static function findAll(array $attributes = []): array
    {
        return self::find($attributes, true);
    }

    public static function findByAttributes(array $attributes = []): ?self
    {
        return self::find($attributes);
    }

    public static function findAllByAttributes(array $attributes = []): array
    {
        return self::findAll($attributes);
    }

    public static function findByPk(int $primaryKey): ?self
    {
        $table = static::tableName();

        $primaryKeyName = static::getPrimaryKeyName($table);
        return self::find([$primaryKeyName => $primaryKey]);
    }

    public static function getPrimaryKeyName(string $table): string
    {
        $statement = self::prepare(
            "SELECT COLUMN_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = :table
                AND CONSTRAINT_NAME = 'PRIMARY'"
        );

        $statement->bindValue(":table", $table, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch(\PDO::FETCH_COLUMN);
    }

    public function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }

    public function getDisplayName()
    {
        if (method_exists($this, 'displayName')) {
            return $this->displayName();
        }
        return $this->username ?? '';
    }
}
