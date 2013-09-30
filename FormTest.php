<?php
require 'Form.php';

class FormTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $basicFields = array(
            'first_name' =>
                function ($value) {
                    if (!empty($value)) {
                        Form::check(mb_strlen($value, 'UTF-8') < 40, 'First name must not exceed 40 characters');
                    }
                },
            'multiple[]' =>
                function ($values) {
                    Form::check(count($values) >= 3, 'Please choose at least 3 options');
                },
            'image' =>
                function ($value) {
                    Form::check($value['size'] < 3000, 'Please upload a file less than 300k');
                },
        );
        $basicValues = array('first_name' => 'Bob', 'image' => array('size' => 999999), 'foo' => 'bar');
        $this->emptyForm = new Form(array());
        $this->basicForm = new Form($basicValues, $basicFields);
    }

    public function testEmptyForm()
    {
        $this->assertEmpty($this->emptyForm->values);
        $this->assertEmpty($this->emptyForm->errors);
        $this->assertEmpty($this->emptyForm->fields);
    }

    public function testBasicForm()
    {
        $this->assertEquals('Bob', $this->basicForm->first_name);
        $this->assertNull($this->basicForm->foo);
        $this->assertNull($this->basicForm->multiple);
        $this->assertEquals('Please upload a file less than 300k', $this->basicForm->error('image'));
        $this->assertEquals('error', $this->basicForm->error('image', 'error'));
        $this->assertNull($this->basicForm->error('first_name'));
        $this->assertNull($this->basicForm->error('first_name', 'error'));
    }
}

