<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
    'placeholder' => 'Username or E-mail',
    'class' => 'form-control',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = 'Email or login';
} else if ($login_by_username) {
	$login_label = 'Login';
} else {
	$login_label = 'Email';
}
$password = array(
	'name'	=> 'password',
    'id'	=> 'password',
    'placeholder' => 'Password',
    'class' => 'form-control',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
    'id'	=> 'remember',
    'class' => 'custom-control-input',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
?>


<div class="col-lg-5 col-md-7">
    <div class="card bg-secondary shadow border-0">
        <div class="card-header bg-transparent pb-5">
            <div class="text-muted text-center mt-2 mb-3"><small>Sign in with</small></div>
            <div class="btn-wrapper text-center">
            <a href="#" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="<?php echo base_url(); ?>assets/img/icons/common/github.svg"></span>
                <span class="btn-inner--text">Github</span>
            </a>
            <a href="#" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="<?php echo base_url(); ?>assets/img/icons/common/google.svg"></span>
                <span class="btn-inner--text">Google</span>
            </a>
            </div>
        </div>
        <div class="card-body px-lg-5 py-lg-5">
            <div class="text-center text-muted mb-4">
            <small>Or sign in with credentials</small>
            </div>
            <form role="form" action="<?php echo base_url(); ?>auth/login" autocomplete="off" method="post">
                <div class="form-group mb-3">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                        </div>
                        <?php echo form_input($login); ?>
                        <span class="text-danger" style="display:block"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                        </div>
                        <?php echo form_password($password); ?>
                        <span class="text-danger" style="display:block"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></span>
                    </div>
                </div>
                <div class="custom-control custom-control-alternative custom-checkbox">
                    <?php echo form_checkbox($remember); ?>
                    <label class="custom-control-label" for="remember">
                    <span class="text-muted">Remember me</span>
                    </label>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary my-4">Sign in</button>
                </div>
            </form>

            <div class="row mt-3">
                <div class="col-6">
                <a href="#" class="text-gray"><small>Forgot password?</small></a>
                </div>
                <div class="col-6 text-right">
                <a href="<?php echo base_url(); ?>register" class="text-gray"><small>Create new account</small></a>
                </div>
            </div>
        </div>
    </div>
</div>