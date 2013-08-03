<?php
require 'Form.php';

// Defining the form using a class because it's actually cool to name forms.
// But we could have also pass the fields to the constructor.

class RegistrationForm extends Form {
    public function init() {
        $this->fields = array(
            'email' =>
                function($value) {
                    Form::check(!empty($value), 'Please specify your email');
                    Form::check(filter_var($value, FILTER_VALIDATE_EMAIL), 'Invalid email');
                },
            'password' =>
                function($value) {
                    Form::check(strlen($value) >= 6, 'Password must be at least 6 characters');
                },
            'password_confirmation' =>
                // php < 5.4 does not support $this in closure
                array($this, 'validatePasswordConfirmation'),
        );
    }

    public function validatePasswordConfirmation($value) {
        Form::check($this->password == $value, 'Password confirmation must match');
    }
}

$form = new RegistrationForm($_POST);
if (empty($form->errors)) {
    // no erros on the form
}

?>
<form class="form-horizontal" id="registration" method="post" action="">
    <fieldset>
        <legend>Registration</legend>


        <?php if ($form->errors): ?>
            <ul class="errors">
                <?php foreach ($form->errors as $key => $message): ?>
                    <li><?= htmlentities($message) ?></li>
                <?php endforeach ?>
            </ul>
        <?php endif ?>


        <div class="control-group <?= $form->error('email', 'error') ?>">
            <label class="control-label">Email</label>
            <div class="controls">
                <input type="text" id="email" name="email" value="<?= htmlentities($form->email) ?>">
            </div>
            <?php if ($form->error('email')): ?>
                <div class="error">
                    <?= htmlentities($form->error('email')) ?>
                </div>
            <?php endif ?>
        </div>

        <div class="control-group <?= $form->error('password', 'error') ?>">
            <label class="control-label">Password</label>
            <div class="controls">
                <input type="password" id="password" name="password" value="<?= htmlentities($form->password) ?>">
            </div>
            <?php if ($form->error('password')): ?>
                <div class="error">
                    <?= htmlentities($form->error('password')) ?>
                </div>
            <?php endif ?>
        </div>

        <div class="control-group <?= $form->error('password_confirmation', 'error') ?>">
            <label class="control-label">Confirm</label>
            <div class="controls">
                <input type="password" id="password_confirmation" name="password_confirmation" value="<?= htmlentities($form->password_confirmation) ?>">
            </div>
            <?php if ($form->error('password_confirmation')): ?>
                <div class="error">
                    <?= htmlentities($form->error('password_confirmation')) ?>
                </div>
            <?php endif ?>
        </div>

        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-success">Create My Account</button>
            </div>
        </div>

    </fieldset>
</form>
