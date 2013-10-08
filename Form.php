<?php

class Form
{
    public $values = array();
    public $errors = array();
    public $fields = array();

    public function __construct(array $values, $fields = null)
    {
        $this->init();
        if ($fields) $this->fields = $fields;
        $this->values = array_intersect_key($values, $this->fields);

        if ($values) {
            foreach ($this->fields as $name => $callback) {
                try {
                    call_user_func($callback, $this->get($values, $name));
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
        return $this->get($this->values, $name);
    }

    public function error($name, $message = null)
    {
        $value = $this->get($this->errors, $name);
        return $message && $value ? $message : $value;
    }

    private function get(array $array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}

