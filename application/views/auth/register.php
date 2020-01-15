<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
        'id'	=> 'username',
        'placeholder' => 'Username',
        'class' => 'form-control',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
    'id'	=> 'email',
    'placeholder' => 'E-mail',
    'class' => 'form-control',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$password = array(
	'name'	=> 'password',
    'id'	=> 'password',
    'placeholder' => 'Password',
    'class' => 'form-control',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
    'id'	=> 'confirm_password',
    'placeholder' => 'Confirm Password',
    'class' => 'form-control',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
?>

<div class="col-lg-6 col-md-8">
    <div class="card bg-secondary shadow border-0">
        <div class="card-header bg-transparent pb-5">
            <div class="text-muted text-center mt-2 mb-3"><small>Sign up with</small></div>
            <div class="text-center">
            <a href="#" class="btn btn-neutral btn-icon mr-4">
                <span class="btn-inner--icon"><img src="../assets/img/icons/common/github.svg"></span>
                <span class="btn-inner--text">Github</span>
            </a>
            <a href="#" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="../assets/img/icons/common/google.svg"></span>
                <span class="btn-inner--text">Google</span>
            </a>
            </div>
        </div>
        <div class="card-body px-lg-4 py-lg-4">
            <div class="text-center text-muted mb-4">
            <small>Or sign up with credentials</small>
            </div>
            <form role="form" action="<?php echo base_url(); ?>auth/register" method="post" autocomplete="off">
            <div class="form-group">
                <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                </div>
                <?php echo form_input($username); ?>
                <span class="text-danger" style="display:block"><?php echo form_error($username['name']); ?><?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?></span>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-alternative mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                </div>
                    <?php echo form_input($email); ?>
                    <span class="text-danger" style="display:block"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></span>
                </div>
            </div>
            <div class="form-group row">
                
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                        <?php echo form_input($password); ?>
                        <span class="text-danger" style="display:block"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></span>
                    </div>
                </div>

                <div class="col-md-6 col-sm-12 col-xm-12">
                    <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                        <?php echo form_input($confirm_password); ?>
                        <span class="text-danger" style="display:block"><?php echo form_error($confirm_password['name']); ?><?php echo isset($errors[$confirm_password['name']])?$errors[$confirm_password['name']]:''; ?></span>
                    </div>
                </div>
            </div>
            <div class="row my-4">
                <div class="col-12">
                <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" id="customCheckRegister" type="checkbox">
                    <label class="custom-control-label" for="customCheckRegister">
                    <span class="text-muted">I agree with the <a href="#!">Privacy Policy</a></span>
                    </label>
                </div>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary mt-4">Create account</button>
            </div>
            </form>
        </div>
    </div>
</div>