<?php
/**
 * Provide a public-facing view for the reset password form
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
<?php 
if(isset($_GET['wpmp_reset_password_token'])){
$is_url_has_token = $_GET['wpmp_reset_password_token']; }else{
$is_url_has_token ='';
 } ?>
<div id="wpmpResetPasswordSection" class=" <?php echo empty($is_url_has_token) ? ' hidden' : 'ds' ?>">   
    <?php
    $wpmp_form_settings = get_option('wpmp_form_settings');

    $resetpassword_form_heading = empty($wpmp_form_settings['wpmp_resetpassword_heading']) ? 'Reset Password' : $wpmp_form_settings['wpmp_resetpassword_heading'];
    $resetpassword_button_text = empty($wpmp_form_settings['wpmp_resetpassword_button_text']) ? 'Reset password' : $wpmp_form_settings['wpmp_resetpassword_button_text'];
    $returntologin_button_text = empty($wpmp_form_settings['wpmp_returntologin_button_text']) ? 'Return to Login' : $wpmp_form_settings['wpmp_returntologin_button_text'];           

    ?>
    <div class="signin-title">
        <h5><?php _e($resetpassword_form_heading, $this->plugin_name); ?></h5>
    </div>


    <div id="wpmp-resetpassword-loader-info" class="wpmp-loader" style="display:none;">
        <img src="<?php echo plugins_url('images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
        <span><?php _e('Please wait ...', $this->plugin_name); ?></span>
    </div>
    <div id="wpmp-resetpassword-alert" class="alert alert-danger" role="alert" style="display:none;"></div>

    <form name="wpmpResetPasswordForm" id="wpmpResetPasswordForm" method="post">
        <?php
        // check if the url has token
        if (!$is_url_has_token) :

            ?>
            <div class="form-group row">
                <div class="col-sm-12"><span class="fm-icn eml-icn"></span><input class="form-control" name="wpmp_rp_email" id="wpmp_rp_email" type="Email" placeholder="Email"></div>                
            </div>
            <input type="hidden" name="wpmp_current_url" id="wpmp_current_url" value="<?php echo get_permalink(); ?>" />
            <?php
        else:

            ?>
            <div class="form-group row">
                <div class="col-sm-12"><span class="fm-icn eml-icn"></span><input class="form-control" name="wpmp_newpassword" id="wpmp_newpassword" type="password" placeholder="New Password"></div>               
            </div>
            <input type="hidden" name="wpmp_rp_email" id="wpmp_rp_email" value="<?php echo $_GET['email'] ?>" />
            <input type="hidden" name="wpmp_reset_password_token" id="wpmp_reset_password_token" value="<?php echo $_GET['wpmp_reset_password_token']; ?>" />

        <?php
        endif;

        ?>
        <?php
        // this prevent automated script for unwanted spam
        if (function_exists('wp_nonce_field'))
            wp_nonce_field('wpmp_resetpassword_action', 'wpmp_resetpassword_nonce');

        ?>
        <div class="buttonwpr center">
           <button type="submit" class="btn defulat-custom-btn"><?php _e($resetpassword_button_text, $this->plugin_name); ?></button>
        <button type="button" id="btnReturnToLogin" class="btn defulat-custom-btn"><?php _e($returntologin_button_text, $this->plugin_name); ?></button>
        </div>

    </form>
</div>
