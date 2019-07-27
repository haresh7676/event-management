<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$i = 1;
$number_of_steps = ($show_login_step) ? count($steps) + 1 : count($steps);

?>

<!-- The steps tabs -->
<div class="wpmc-tabs-wrapper">
    <ul class="wpmc-tabs-list wpmc-<?php echo $number_of_steps; ?>-tabs">
    <?php if ( $show_login_step ) : ?>
        <li class="wpmc-tab-item current wpmc-login">
            <div class="wpmc-tab-number"><?php echo $i = $i + 1; ?></div>
            <div class="wpmc-tab-text"><?php echo $options['t_login']; ?></div>
        </li>
    <?php endif; ?>
        <li class="wpmc-tab-item">
            <div class="wpmc-tab-number"><?php echo 1; ?></div>
            <div class="wpmc-tab-text">Ticket</div>
        </li>
    <?php
    foreach( $steps as $_id => $_step ) :
      $class = ( ! $show_login_step && $i == 1) ? ' current' : '';
      ?>
        <li class="wpmc-tab-item<?php echo $class; ?> wpmc-<?php echo $_id; ?>">
            <div class="wpmc-tab-number"><?php echo $i = $i + 1; ?></div>
            <div class="wpmc-tab-text"><?php echo $_step['title']; ?></div>
        </li>
    <?php endforeach; ?>
        <li class="wpmc-tab-item">
            <div class="wpmc-tab-number"><?php echo 4; ?></div>
            <div class="wpmc-tab-text">Confirmation</div>
        </li>
	</ul>
</div>
