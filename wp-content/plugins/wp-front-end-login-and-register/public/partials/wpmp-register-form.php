<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://www.daffodilsw.com/
 * @since      1.0.0
 *
 * @package    Wp_Mp_Register_Login
 * @subpackage Wp_Mp_Register_Login/public/partials
 */

?>

<div id="wpmpRegisterSection" class="signin-box signup-box">
    <?php
    $wpmp_form_settings = get_option('wpmp_form_settings');
    $form_heading = empty($wpmp_form_settings['wpmp_signup_heading']) ? 'Register' : $wpmp_form_settings['wpmp_signup_heading'];

    // check if the user already login
    if (!is_user_logged_in()) :

        ?>

        <form name="wpmpRegisterForm" id="wpmpRegisterForm" method="post">
            <div class="signin-title">
                <h5><?php _e($form_heading, $this->plugin_name); ?></h5>
            </div>

            <div id="wpmp-reg-loader-info" class="wpmp-loader" style="display:none;">
                <img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
                <span><?php _e('Please wait ...', $this->plugin_name); ?></span>
            </div>
            <div id="wpmp-register-alert" class="alert alert-danger" role="alert" style="display:none;"></div>
            <div id="wpmp-mail-alert" class="alert alert-danger" role="alert" style="display:none;"></div>
            <?php if ($token_verification): ?>
                <div class="alert alert-info" role="alert"><?php _e('Your account has been activated, you can login now.', $this->plugin_name); ?></div>
            <?php endif; ?>

            <div class="form-group row">
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="wpmp_fname" id="wpmp_fname" placeholder="First Name">
                </div>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="wpmp_lname" id="wpmp_lname" placeholder="Last Name">
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Organization (Optional)" name="wpmp_organization" id="wpmp_organization">
            </div>            
            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="wpmp_password" id="wpmp_password" placeholder="Password" >
                </div>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="wpmp_password2" id="wpmp_password2" placeholder="Confirm Password" >
                </div>
            </div>
            <div class="form-group">                
                <input type="text" class="form-control" name="wpmp_email" id="wpmp_email" placeholder="Email">
            </div>            

            <?php if ($wpmp_form_settings['wpmp_enable_captcha'] == '1') { ?>
                <div class="form-group">
                    <label class="control-label" id="captchaOperation"></label>
                    <input type="text" placeholder="Captcha answer" class="form-control" name="wpmp_captcha" />
                </div>
            <?php } ?>

            <input type="hidden" name="wpmp_current_url" id="wpmp_current_url" value="<?php echo get_permalink(); ?>" />
            <input type="hidden" name="redirection_url" id="redirection_url" value="<?php echo get_permalink(); ?>" />

            <?php
            // this prevent automated script for unwanted spam
            if (function_exists('wp_nonce_field'))
                wp_nonce_field('wpmp_register_action', 'wpmp_register_nonce');

            ?>
            <div class="form-group position-relative">                
                <div class="login-btn-box"><button type="submit" class="btn login-btn">
                <?php
                $submit_button_text = empty($wpmp_form_settings['wpmp_signup_button_text']) ? 'Register' : $wpmp_form_settings['wpmp_signup_button_text'];
                _e($submit_button_text, $this->plugin_name);

                ?></button></div>
              </div>
        </form>
        <div class="or-dvd-line"><span>or</span></div>

        <div class="sgnup-wth">sign up <br>with</div>

        <div class="login-options">
        <div class="login-options-inr">
            <a class="btn fb-btn"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/facebook-icn.png" alt=""></a>
            <a class="btn gp-btn" href="<?php echo site_url(); ?>/wp-login.php?loginSocial=google" data-plugin="nsl" data-action="connect" data-redirect="<?php echo site_url(); ?>/events/" data-provider="google" data-popupwidth="600" data-popupheight="600"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/google-ps-icn.png" alt=""></a>
        </div>
        </div>
        <?php
    else:
        $current_user = wp_get_current_user();
        $logout_redirect = (empty($wpmp_form_settings['wpmp_logout_redirect']) || $wpmp_form_settings['wpmp_logout_redirect'] == '-1') ? '' : $wpmp_form_settings['wpmp_logout_redirect'];

        echo 'Logged in as <strong>' . ucfirst($current_user->user_login) . '</strong>. <a href="' . wp_logout_url(get_permalink($logout_redirect)) . '">Log out ? </a>';
    endif;

    ?>     
</div>