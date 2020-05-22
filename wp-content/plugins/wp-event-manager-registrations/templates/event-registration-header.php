<?php do_action('before_event_registration_header',$registration);?>
<?php echo get_event_registration_avatar( $registration->ID ) ?>
<h3>
    <?php echo $registration->post_title; ?>	
</h3>
<?php do_action('after_event_registration_header', $registration);?>