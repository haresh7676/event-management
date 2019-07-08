<?php /* Template Name: Login Page */ ?>
<?php get_header(); ?>
<div class="container-fluid login-main">
    <div class="row">
        <!-- left side -->
        <div class="col-sm-12 col-lg-6 p-0">
            <div class="login-bg">
                <div class="lg-lft-box">
                    <div class="login-bg-sdw"></div>
                    <div class="icon-logo"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-icn.png" alt=""></div>
                </div>
            </div>
        </div>
        <!-- right side -->
        <div class="col-sm-12 col-lg-6 p-0 lgn-lft-img">
            <div class="lg-rgt-box">
                <div class="login-header">
                    <h1>Find & Create Geeky, Nerdy Events In Your Neighborhood</h1>
                </div>
                <div class="signin-box">
                    <div class="signin-title">
                        <h5>Sign In</h5>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12"><span class="fm-icn eml-icn"></span><input type="text" placeholder="Email"></div>
                        <div class="col-sm-12"><span class="fm-icn psw-icn"></span><input type="password" placeholder="Password"></div>
                    </div>
                    <div class="form-group position-relative">

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck" name="example1">
                            <label class="custom-control-label" for="customCheck">Remember me</label>
                        </div>
                        <div class="pull-right login-btn-box"><input type="submit" class="btn login-btn" value="login"></div>
                    </div>
                    <div class="creat-text">
                        <a href="#">Create account</a>
                        <a href="#">Forgot password?</a>
                    </div>
                    <div class="or-dvd-line"><span>or</span></div>
                    <div class="login-options">
                        <div class="login-options-inr">
                            <a class="btn tw-btn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-icn.png" alt=""></a>
                            <a class="btn fb-btn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/facebook-icn.png" alt=""></a>
                            <a class="btn gp-btn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/google-ps-icn.png" alt=""></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
