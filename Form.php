<?php

class Form
{
    public $values = array();
    public $errors = array();
    public $fields = array();

    public function __construct(array $values, $fields = null)
    {
        $this->init();
        $this->values = $values;
        if ($fields) $this->fields = $fields;

        if ($values) {
            foreach ($this->fields as $name => $callback) {
                try {
                    call_user_func($callback, $this->array_get($values, $name));
                } catch (InvalidArgumentException $e) {
                    $this->errors[$name] = $e->getMessage();
                }
            }
        }
    }

    protected function init()
    {
        // initialize $fields when subclassing
    }

    public static function check($expression, $message)
    {
        if (false == $expression) {
            throw new InvalidArgumentException($message);
        }
    }

    public function __get($name)
    {
        return $this->array_get($this->values, $name);
    }

    public function error($name, $message = null)
    {
        $value = $this->array_get($this->errors, $name);
        return $message && $value ? $message : $value;
    }

    private function array_get(&$array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

