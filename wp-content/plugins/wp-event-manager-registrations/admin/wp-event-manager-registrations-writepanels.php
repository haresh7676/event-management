<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'WP_Event_Manager_Writepanels' ) && defined( 'EVENT_MANAGER_PLUGIN_DIR' ) ) {
	include( EVENT_MANAGER_PLUGIN_DIR . '/admin/wp-event-manager-writepanels.php' );
}

if ( class_exists( 'WP_Event_Manager_Writepanels' ) ) {
	class WP_Event_Manager_Registrations_Writepanels extends WP_Event_Manager_Writepanels {

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post', array( $this, 'save_post' ), 1, 2 );
			add_action( 'event_manager_registrations_save_event_registration', array( $this, 'save_event_registration_data' ), 1, 2 );
		}

		/**
		 * Event registration fields
		 *
		 * @return array
		 */
		public function event_registration_fields() {
			global $post;

			/**
			 * Get all custom registration field from backend
			 */
			$fields = get_option("event_registration_form_fields");
			if (!empty($fields)) {
			    foreach ($fields as $key => $option){
			        $options[$key] = $option;
			    }
			}
			
			$fields = apply_filters( 'event_manager_registrations_event_registration_fields', $fields );
			
			if(isset($fields['full-name'])){
			    unset($fields['full-name']);
			}
			if(isset($fields['email-address'])){
			    unset($fields['email-address']);
			}
			$fields['_attendee_email'] = array(
			    'label'       => __( 'Contact Email', 'wp-event-manager-registrations' ),
			    'placeholder' => __( 'you@yourdomain.com', 'wp-event-manager-registrations' ),
			    'description' => ''
			);
			
			$fields['_event_registration_author'] = array(
			    'label'       => __( 'Posted by', 'wp-event-manager' ),
			    'type'        => 'author',
			    'placeholder' => ''
			);
			
			$fields['post_parent'] = array(
			    'label'       => __( 'Event Listing ID', 'wp-event-manager' ),
			    'type'        => 'text',
			    'placeholder' => 'Post ID of the event ID registered for',
			    'value'       => $post->post_parent
			);
		
			return $fields;
		}

		/**
		 * add_meta_boxes function.
		 */
		public function add_meta_boxes() {
			add_meta_box( 'event_registration_save', __( 'Save Registration', 'wp-event-manager-registrations' ), array( $this, 'event_registration_save' ), 'event_registration', 'side', 'high' );
			add_meta_box( 'event_registration_data', __( 'Event Registration Data', 'wp-event-manager-registrations' ), array( $this, 'event_registration_data' ), 'event_registration', 'normal', 'high' );
			add_meta_box( 'event_registration_notes', __( 'Registration Notes', 'wp-event-manager-registrations' ), array( $this, 'registration_notes' ), 'event_registration', 'side', 'default' );
			remove_meta_box( 'submitdiv', 'event_registration', 'side' );
		}

		/**
		 * Publish meta box
		 */
		public function event_registration_save( $post ) {
			$statuses = get_event_registration_statuses();
			?>
			<div class="submitbox" id="submitpost">
				<div id="minor-publishing">
					<div id="misc-publishing-actions">
						<div class="misc-pub-section misc-pub-post-status">
							<div id="post-status-select">
								<select name='post_status' id='post_status'>
									<?php
									foreach ( $statuses as $key => $label ) {
										$selected = selected( $post->post_status, $key, false );
										echo "<option{$selected} value='" . esc_attr( $key ) . "'>" . esc_html( $label ) . "</option>";
									}
									?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div id="major-publishing-actions">
					<div id="delete-action">
						<a class="submitdelete deletion" href="<?php echo get_delete_post_link( $post->ID ); ?>"><?php _e( 'Move to Trash', 'wp-event-manager-registrations' ); ?></a>
					</div>
					<div id="publishing-action">
						<span class="spinner"></span>
						<input name="save" class="button button-primary" type="submit" value="<?php _e( 'Save', 'wp-event-manager-registrations' ); ?>">
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<?php
		}

		/**
		 * Event registration data
		 * @param mixed $post
		 */
		public function event_registration_data( $post ) {
			global $post, $thepostid;

			$thepostid = $post->ID;

			echo '<div class="wp_event_manager_meta_data">';

			wp_nonce_field( 'save_meta_data', 'event_manager_registrations_nonce' );

			do_action( 'event_registration_data_start', $thepostid );

			foreach ( $this->event_registration_fields() as $key => $field ) {
				$type = ! empty( $field['type'] ) ? $field['type'] : 'text';

				if ( method_exists( $this, 'input_' . $type ) ) {
					call_user_func( array( $this, 'input_' . $type ), $key, $field );
				} else {
					do_action( 'event_manager_registrations_input_' . $type, $key, $field );
				}
			}

			do_action( 'event_registration_data_end', $thepostid );

			echo '</div>';
		}

		/**
		 * Triggered on Save Post
		 *
		 * @param mixed $post_id
		 * @param mixed $post
		 */
		public function save_post( $post_id, $post ) {
			if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) return;
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
			if ( is_int( wp_is_post_revision( $post ) ) ) return;
			if ( is_int( wp_is_post_autosave( $post ) ) ) return;
			if ( empty( $_POST[ 'event_manager_registrations_nonce' ] ) || ! wp_verify_nonce( $_POST['event_manager_registrations_nonce'], 'save_meta_data' ) ) return;
			if ( ! current_user_can( 'edit_post', $post_id ) ) return;
			if ( $post->post_type !== 'event_registration' ) return;

			do_action( 'event_manager_registrations_save_event_registration', $post_id, $post );
		}

		/**
		 * Save registration Meta
		 *
		 * @param mixed $post_id
		 * @param mixed $post
		 */
		public function save_event_registration_data( $post_id, $post ) {
			global $wpdb;

			foreach ( $this->event_registration_fields() as $key => $field ) {

				if( '_event_registration_author' === $key ) {
					$wpdb->update( $wpdb->posts, array( 'post_author' => $_POST[ $key ] > 0 ? absint( $_POST[ $key ] ) : 0 ), array( 'ID' => $post_id ) );
				}

				elseif ( 'post_parent' === $key ) {
					// WP Handles this field
				}

				else {
					$type = ! empty( $field['type'] ) ? $field['type'] : '';

					switch ( $type ) {
						case 'textarea' :
							update_post_meta( $post_id, $key, wp_kses_post( stripslashes( $_POST[ $key ] ) ) );
						break;
						case 'checkbox' :
							if ( isset( $_POST[ $key ] ) ) {
								update_post_meta( $post_id, $key, 1 );
							} else {
								update_post_meta( $post_id, $key, 0 );
							}
						break;
						default :
							if ( is_array( $_POST[ $key ] ) ) {
								update_post_meta( $post_id, $key, array_filter( array_map( 'sanitize_text_field', $_POST[ $key ] ) ) );
							} else {
								update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
							}
						break;
					}
				}
			}
		}

		/**
		 * registration_notes metabox
		 */
		public static function registration_notes( $post ) {
			event_registration_notes( $post );
			?>
			<script type="text/javascript">
				jQuery(function(){
					jQuery('#event_registration_notes')
						.on( 'click', '.event-registration-note-add input.button', function() {
							var button                     = jQuery(this);
							var registration_id             = button.data('registration_id');
							var event_registration            = jQuery(this).closest('#event_registration_notes');
							var event_registration_note       = event_registration.find('textarea');
							var disabled_attr              = jQuery(this).attr('disabled');
							var event_registration_notes_list = event_registration.find('ul.event-registration-notes-list');

							if ( typeof disabled_attr !== 'undefined' && disabled_attr !== false ) {
								return false;
							}
							if ( ! event_registration_note.val() ) {
								return false;
							}

							button.attr( 'disabled', 'disabled' );		

							var data = {
								action: 		'add_event_registration_note',
								note: 			event_registration_note.val(),
								registration_id: registration_id,
								security: 		'<?php echo wp_create_nonce( "event-registration-notes" ); ?>'
							};

							jQuery.post( '<?php echo admin_url('admin-ajax.php'); ?>', data, function( response ) {
								event_registration_notes_list.append( response );
								button.removeAttr( 'disabled' );
								event_registration_note.val( '' );
							});

							return false;
						})
						.on( 'click', 'a.delete_note', function() {
							var answer = confirm( '<?php echo __( 'Are you sure you want to delete this? There is no undo.', 'wp-event-manager-registrations' ); ?>' );
							if ( answer ) {
								var button  = jQuery(this);
								var note    = jQuery(this).closest('li');
								var note_id = note.attr('rel');

								var data = {
									action: 		'delete_event_registration_note',
									note_id:		note_id,
									security: 		'<?php echo wp_create_nonce( "event-registration-notes" ); ?>'
								};

								jQuery.post( '<?php echo admin_url('admin-ajax.php'); ?>', data, function( response ) {
									note.fadeOut( 500, function() {
										note.remove();
									});
								});
							}
							return false;
						});
				});
			</script>
			<?php
		}
	}

	new WP_Event_Manager_Registrations_Writepanels();
}