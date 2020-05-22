<div id="event-manager-event-registrations">
	<a href="<?php echo esc_url( add_query_arg( 'download-csv', true ) ); ?>" class="event-registrations-download-csv"><?php _e( 'Download CSV', 'wp-event-manager-registrations' ); ?></a>
	<p><?php printf( __( 'The event registrations for "%s" are listed below.', 'wp-event-manager-registrations' ), '<a href="' . get_permalink( $event_id ) . '">' . get_the_title( $event_id ) . '</a>' ); ?></p>
	
	<?php do_action('single_event_registration_dashboard_before'); ?>
	
	<div class="event-registrations">
		<form class="filter-event-registrations" method="GET">
		    <div class="col-md-4 ">
		        <input type="text" name="registration_byname" class="registration_byname" placeholder="<?php _e( 'Type text and press enter', 'wp-event-manager-registrations' ); ?>" value="<?php echo $registration_byname; ?>">
		    </div>
			<div class="col-md-4">
				<select name="registration_status" class="registration_status">
					<option value=""><?php _e( 'Filter by status', 'wp-event-manager-registrations' ); ?>...</option>
					<?php foreach ( get_event_registration_statuses() as $name => $label ) : ?>
						<option value="<?php echo esc_attr( $name ); ?>" <?php selected( $registration_status, $name ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="col-md-4">
				<select name="registration_orderby" class="registration_orderby">
					<option value=""><?php _e( 'Newest first', 'wp-event-manager-registrations' ); ?></option>
					<option value="name" <?php selected( $registration_orderby, 'name' ); ?>><?php _e( 'Sort by name', 'wp-event-manager-registrations' ); ?></option>					
				</select>
				<input type="hidden" name="action" value="show_registrations" />
				<input type="hidden" name="event_id" value="<?php echo absint( $_GET['event_id'] ); ?>" />
				<?php if ( ! empty( $_GET['page_id'] ) ) : ?>
					<input type="hidden" name="page_id" value="<?php echo absint( $_GET['page_id'] ); ?>" />
				<?php endif; ?>
			</div>
		</form>
		<ul class="event-registrations">
			<?php foreach ( $registrations as $registration ) : ?>
				<li class="event-registration" id="registration-<?php esc_attr_e( $registration->ID ); ?>">
					<header>
						<?php event_registration_header( $registration ); ?>
						<div class="pull-right">
						<?php 
						$check_in = get_post_meta( $registration->ID , '_check_in',true );	
						if(isset($check_in) && $check_in == true )
						{
						      $checkin_hidden =   'hidden';
						      $undo_hidden = '';
						}
						else
						{
							$checkin_hidden = '';
							$undo_hidden = 'hidden';
						}
						echo "<span class='".$checkin_hidden."'><a href='#' class='button-secondary registration-checkin'  data-value='1' data-registration-id='".$registration->ID."'>". __( 'Check in', 'wp-event-manager-registrations' )."</a></span>";	
						echo "<span class='".$undo_hidden."'><a href='#' class='button-secondary registration-uncheckin' data-value='0' data-registration-id='".$registration->ID."' >". __( 'Undo Check in', 'wp-event-manager-registrations' )."</a></span>";
						?>
						
						</div>
					</header>
					
					<section class="event-registration-content">
						<?php do_action('event_registration_dashboard_meta_start',$registration->ID);?>
						<?php event_registration_meta( $registration ); ?>
						<?php /* No need now: event_registration_content( $registration ); */ ?>
						<?php do_action('event_registration_dashboard_meta_end',$registration->ID);?>
					</section>
					<section class="event-registration-edit">
						<?php event_registration_edit( $registration ); ?>
					</section>
					<section class="event-registration-notes">
						<?php event_registration_notes( $registration ); ?>
					</section>
					<footer>
						<?php event_registration_footer( $registration ); ?>
					</footer>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php get_event_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>
	</div>
	<?php do_action('single_event_registration_dashboard_after');?>
</div>