<?php
require 'Form.php';

assert(version_compare(PHP_VERSION, '5.4', '>='), '
PHP 5.4 required

This sample make uses 5.4 for the closure $this support, always available <?=,
short array syntax and built-in web server for convenience only.  5.4 is not
required to use Form itself, in fact I think it could go as way back as 5.0.');

// Defining the form using a class because it's actually cool to name forms.
// But we could have also pass the fields to the constructor.

class RegistrationForm extends Form {
    public function init() {
        $this->fields = [
            'email' =>
                function($value) {
                    Form::check(!empty($value), 'Email is required');
                    Form::check(filter_var($value, FILTER_VALIDATE_EMAIL), 'Invalid email');
                },
            'password' =>
                function($value) {
                    Form::check(strlen($value) >= 6, 'Password must be at least 6 characters');
                },
            'password_confirmation' =>
                function($value) {
                    Form::check($this->password == $value, 'Password confirmation must match');
                },
            'picture' =>
                function($value) {
                    Form::check($value['error'] != UPLOAD_ERR_NO_FILE, 'Picture is required');
                    Form::check($value['size'] < 307200, 'Please upload a picture less than 300k');
                    Form::check(in_array($value['type'], ['image/gif', 'image/jpeg', 'image/png']),
                        'Uploaded picture must be a gif, jpeg or png');
                }
        ];
    }

}

// Do this in your controller or something

$form = new RegistrationForm($_POST + $_FILES);
?>


<?php if ($form->errors): ?>
    <ul class="errors">
        <?php foreach ($form->errors as $key => $message): ?>
            <li><?= htmlentities($message) ?></li>
        <?php endforeach ?>
    </ul>
<?php elseif (!empty($form->values)): ?>
    <div>
        Email: <?= $form->email ?><br>
        Password: <?= $form->password ?><br>
        Picture: <?= $form->picture['name'] ?><br>
    </div>
<?php endif ?>


<form action="" method="post" enctype="multipart/form-data">

    <div class="<?= $form->error('email', 'error') ?>">
        <label>Email</label>
        <div>
            <input type="text" id="email" name="email" value="<?= htmlentities($form->email) ?>">
        </div>
        <?php if ($form->error('email')): ?>
            <div class="error">
                <?= htmlentities($form->error('email')) ?>
            </div>
        <?php endif ?>
    </div>

    <div class="<?= $form->error('password', 'error') ?>">
        <label>Password</label>
        <div>
            <input type="password" id="password" name="password" value="<?= htmlentities($form->password) ?>">
        </div>
        <?php if ($form->error('password')): ?>
            <div class="error">
                <?= htmlentities($form->error('password')) ?>
            </div>
        <?php endif ?>
    </div>

    <div class="<?= $form->error('password_confirmation', 'error') ?>">
        <label>Confirm</label>
        <div>
            <input type="password" id="password_confirmation" name="password_confirmation" value="<?= htmlentities($form->password_confirmation) ?>">
        </div>
        <?php if ($form->error('password_confirmation')): ?>
            <div class="error">
                <?= htmlentities($form->error('password_confirmation')) ?>
            </div>
        <?php endif ?>
    </div>

    <div class="<?= $form->error('picture', 'error') ?>">
        <label>Picture</label>
        <div>
            <input type="file" id="picture" name="picture">
        </div>
        <?php if ($form->error('picture')): ?>
            <div class="error">
                <?= htmlentities($form->error('picture')) ?>
            </div>
        <?php endif ?>
    </div>

    <div>
        <input type="submit" value="Submit">
    </div>
</form>
