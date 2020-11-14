<?php


namespace app\core;


abstract class Model
{
    const RULE_REQUIRED = 'required';
    const RULE_EMAIL    = 'email';
    const RULE_MIN      = 'min';
    const RULE_MAX      = 'max';
    const RULE_MATCH    = 'match';
    const RULE_UNIQUE   = 'unique';
    const RULE_RANGE    = 'range';

    public $errors = [];

    public $errorMessages = [
        self::RULE_REQUIRED => 'This field is required',
        self::RULE_EMAIL    => 'This field must be valid email address',
        self::RULE_MIN      => 'Min length of this field must be {min}',
        self::RULE_MAX      => 'Max length of this field must be {max}',
        self::RULE_MATCH    => 'This field must be the same as {match}',
        self::RULE_UNIQUE   => 'Record with this {unique} already exists',
        self::RULE_RANGE    => 'This field is not in accepted data range',
    ];

    public function loadData(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules(): array;


    public function validate()
    {
        $handle = fopen($_SERVER['DOCUMENT_ROOT'] .'/logs/log.txt','a+');
        fwrite($handle, 'validate' . PHP_EOL);
        
        foreach ($this->rules() as $attributeName => $attributeRules) {
            foreach ($attributeRules as $key => $value) {
                if (is_numeric($key)) {
                    $rule = $value;
                } else {
                    $rule      = $key;
                    $parameter = $value;
                }

                $attribute = $this->{$attributeName};

                if ($rule === self::RULE_REQUIRED && !$attribute) {
                    $this->addErrorForRule($attributeName, self::RULE_REQUIRED);
                }
                if ($rule === self::RULE_EMAIL && !filter_var($attribute, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attributeName, self::RULE_EMAIL);
                }
                if ($rule === self::RULE_MIN && strlen($attribute) < $parameter) {
                    $this->addErrorForRule($attributeName, self::RULE_MIN, $parameter);
                }
                if ($rule === self::RULE_MAX && strlen($attribute) > $parameter) {
                    $this->addErrorForRule($attributeName, self::RULE_MAX, $parameter);
                }
                if ($rule === self::RULE_MATCH && $attribute !== $this->{$parameter}) {
                    $this->addErrorForRule($attributeName, self::RULE_MATCH, $parameter);
                }
                if ($rule === self::RULE_UNIQUE) {
                    $className = $parameter;
                    $table = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT * FROM $table WHERE $attributeName = :$attributeName;");
                    $statement->bindValue(":$attributeName", $attribute);
                    $statement->execute();
                    $record = $statement->fetchObject();

                    if ($record) {
                        $this->addErrorForRule($attributeName, self::RULE_UNIQUE, $attributeName);
                    }
                }
                if ($rule === self::RULE_RANGE && !in_array($attribute, array_keys($parameter))) {
                    $this->addErrorForRule($attributeName, self::RULE_RANGE);
                }
            }
        }
        
        fwrite($handle, 'errors' . PHP_EOL);
        fwrite($handle, print_r($this->errors, true) . PHP_EOL);

        return empty($this->errors);
    }

    protected function addErrorForRule(string $attribute, string $rule, $parameter = null)
    {
        $message = $this->errorMessages[$rule] ?? '';
        if ($parameter) {
            $message = str_replace("{{$rule}}", $parameter, $message);
        }

        $this->errors[$attribute][] = $message;
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function getErrors(string $attribute)
    {
        return $this->errors[$attribute] ?? null;
    }

//    public function hasErrors(string $attribute)
//    {
//        return $this->errors[$attribute];
//    }
//
//    public function getDisplayError(string $attribute)
//    {
//        return $this->errors[$attribute][0];
//    }
}
