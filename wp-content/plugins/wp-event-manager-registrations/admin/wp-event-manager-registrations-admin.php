<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * WP_Event_Manager_Registrations_Admin class.
 */
class WP_Event_Manager_Registrations_Admin {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		include( 'wp-event-manager-registrations-writepanels.php' );
		include( 'wp-event-manager-registrations-form-editor.php' );
		include( 'wp-event-manager-registrations-notifications.php' );
		include( 'wp-event-manager-registrations-settings.php' );

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 12 );
		add_filter( 'event_manager_admin_screen_ids', array( $this, 'screen_ids' ) );
		add_filter( 'manage_edit-event_listing_columns', array( $this, 'event_columns' ), 12 );
		add_action( 'manage_event_listing_posts_custom_column', array( $this, 'event_custom_columns' ), 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );
		add_filter( 'manage_edit-event_registration_columns', array( $this, 'columns' ) );
		add_action( 'manage_event_registration_posts_custom_column', array( $this, 'custom_columns' ), 2 );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
		add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );
		add_action( 'parse_query', array( $this, 'search_meta' ) );
		add_filter( 'get_search_query', array( $this, 'search_meta_label' ) );
		add_filter( 'request', array( $this, 'request' ) );
		add_filter( 'manage_edit-event_registration_sortable_columns', array( $this, 'sortable_columns' ) );
		add_action( 'admin_footer-edit.php', array( $this, 'add_custom_statuses' ) );

		$this->settings_page = new WP_Event_Manager_Registrations_Settings();
	}

	/**
	 * admin_menu function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_menu() {
		add_submenu_page( 'edit.php?post_type=event_registration', __( 'Settings', 'wp-event-mager-registrations' ), __( 'Settings', 'wp-event-manager-registrations' ), 'manage_options', 'event-registrations-settings', array( $this->settings_page, 'output' ) );
	}

	/**
	 * Add screen ids to JM
	 * @param  array $ids
	 * @return array
	 */
	public function screen_ids( $ids ) {
		$ids[] = 'edit-event_registration';
		$ids[] = 'event_registration';
		return $ids;
	}

	/**
	 * Add registrations column
	 * @param  array $columns
	 * @return array
	 */
	public function event_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $column ) {
			$new_columns[ $key ] = $column;

			if ( 'event_expires' === $key ) {
				$new_columns[ 'event_registrations' ] = __( 'Registrations', 'wp-event-manager-registrations' );
			}
		}

		return $new_columns;
	}

	/**
	 * custom_columns function.
	 *
	 * @access public
	 * @param mixed $column
	 * @return void
	 */
	public function event_custom_columns( $column ) {
		global $post;

		if ( 'event_registrations' === $column ) {
			echo ( $count = get_event_registration_count( $post->ID ) ) ? '<a href="' . admin_url( 'edit.php?s&post_status=all&post_type=event_registration&_event_listing=' . $post->ID ) . '">' . $count . '</a>' : '&ndash;';
		}
	}

	/**
	 * Enqueue admin scripts
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'wp-event-manager-registrations-menu', EVENT_MANAGER_REGISTRATIONS_PLUGIN_URL . '/assets/css/menu.min.css', '', EVENT_MANAGER_REGISTRATIONS_VERSION );
		wp_enqueue_style( 'wp-event-manager-registrations-admin', EVENT_MANAGER_REGISTRATIONS_PLUGIN_URL . '/assets/css/admin.min.css', '', EVENT_MANAGER_REGISTRATIONS_VERSION );
		
		$ajax_url         = WP_Event_Manager_Ajax::get_endpoint();
		wp_register_script( 'wp-event-manager-registration-admin', EVENT_MANAGER_REGISTRATIONS_PLUGIN_URL . '/assets/js/admin-registration.min.js', array('jquery'), EVENT_MANAGER_REGISTRATIONS_VERSION, true);
		wp_localize_script( 'wp-event-manager-registration-admin', 'event_manager_registrations_registration_admin', array( 
							'ajaxUrl' 	 => $ajax_url)
						  );
		wp_enqueue_script( 'wp-event-manager-registration-admin');
	}

	/**
	 * enter_title_here function.
	 *
	 * @access public
	 * @return void
	 */
	public function enter_title_here( $text, $post ) {
		if ( $post->post_type == 'event_registration' ) {
			return __( 'Attendee name', 'wp-event-manager-registrations' );
		}
		return $text;
	}

	/**
	 * post_updated_messages function.
	 *
	 * @access public
	 * @param array $messages
	 * @return array
	 */
	public function post_updated_messages( $messages ) {
		$messages['event_registration'] = array(
			0  => '',
			1  => __( 'Event registration updated.', 'wp-event-manager-registrations' ),
			2  => __( 'Custom field updated.', 'wp-event-manager-registrations' ),
			3  => __( 'Custom field deleted.', 'wp-event-manager-registrations' ),
			4  => __( 'Event registration updated.', 'wp-event-manager-registrations' ),
			5  => '',
			6  => __( 'Event registration published.', 'wp-event-manager-registrations' ),
			7  => __( 'Event registration saved.', 'wp-event-manager-registrations' ),
			8  => __( 'Event registration submitted.', 'wp-event-manager-registrations' ),
			9  => '',
			10 => __( 'Event registration draft updated.', 'wp-event-manager-registrations' )
		);

		return $messages;
	}

	/**
	 * columns function.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return void
	 */
	public function columns( $columns ) {
		if ( ! is_array( $columns ) ) {
			$columns = array();
		}

		unset( $columns['title'], $columns['date'] );

		$columns["registration_status"]      = __( "Status", 'wp-event-manager-registrations' );
		$columns["attendee"]               = __( "Attendee", 'wp-event-manager-registrations' );
		$columns["event"]                     = __( "Event registered for", 'wp-event-manager-registrations' );	
		$columns['registration_notes']       = '<span class="registration_notes_head tips" data-tip="' . esc_attr__( 'Notes', 'wp-event-manager-registrations' ) . '">' . esc_attr__( 'Notes', 'wp-event-manager-registrations' ) . '</span>';				
		$columns["event_registration_posted"]  = __( "Posted", 'wp-event-manager-registrations' );
		$columns["check_in"]  = __( "Check in", 'wp-event-manager-registrations' );
		$columns['event_registration_actions'] = __( "Actions", 'wp-event-manager-registrations' );

		return $columns;
	}

	/**
	 * custom_columns function.
	 *
	 * @access public
	 * @param mixed $column
	 * @return void
	 */
	public function custom_columns( $column ) {
		global $post;

		switch ( $column ) {
			case "registration_status" :
				echo '<span class="status">' . $post->post_status . '</a>';
			break;
			case "attendee" :
				echo '<a href="' . admin_url('post.php?post=' . $post->ID . '&action=edit') . '" class="tips attendee_name" data-tip="' . sprintf( __( 'Registration ID: %d', 'wp-event-manager-registrations' ), $post->ID ) . '">' . $post->post_title . '</a>';

				if ( $email = get_post_meta( $post->ID, '_attendee_email', true ) ) {
					echo '<br/><a href="mailto:' . esc_attr( $email ) . '">' . esc_attr( $email ) . '</a>';
					echo get_avatar( $email , 42 );
				}
				echo '<div class="hidden" id="inline_' . $post->ID . '"><div class="post_title">' . $post->post_title . '</div></div>';
			break;
			case 'event' :
				$event = get_post( $post->post_parent );

				if ( $event && $event->post_type === 'event_listing' ) {
					echo '<a href="' . get_permalink( $event->ID ) . '">' . $event->post_title . '</a>';
				} elseif ( $event = get_post_meta( $post->ID, '_event_registered_for', true ) ) {
					echo esc_html( $event );
				} else {
					echo '<span class="na">&ndash;</span>';
				}
			break;			
			case 'registration_notes' :
				printf( _n( '%d note', '%d notes', $post->comment_count, 'wp-event-manager-registrations' ), $post->comment_count );
			break;
			case "event_registration_posted" :
				echo '<strong>' . date_i18n( __( 'M j, Y', 'wp-event-manager-registrations' ), strtotime( $post->post_date ) ) . '</strong><span>';
				echo ( empty( $post->post_author ) ? __( 'by a guest', 'wp-event-manager-registrations' ) : sprintf( __( 'by %s', 'wp-event-manager-registrations' ), '<a href="' . get_edit_user_link( $post->post_author ) . '">' . get_the_author() . '</a>' ) ) . '</span>';
			break;
			
			case 'check_in' :	
				$check_in = get_post_meta( $post->ID , '_check_in',true );	
				if(isset($check_in) && $check_in == true ){
				      $checkin_hidden =   'hidden';
				      $undo_hidden = '';
				}
				else{
					$checkin_hidden = '';
					$undo_hidden = 'hidden';
				}
				echo "<span class='".$checkin_hidden."'><a class='button-secondary tickets_checkin' data-value='1' data-post-id='".$post->ID."'>".__('Check in','wp-event-manager-registrations')."</a></span>";
				echo "<span class='".$undo_hidden."'><a class='tickets_uncheckin'  data-value='0' data-post-id='".$post->ID."' href='#'>".__('Undo Check in','wp-event-manager-registrations')."</a></span>";
				echo "<input type='hidden' name='parent_event_id' id='parent_event_id' class='parent_event_id' value='". wp_get_post_parent_id( $post->ID ) ."' />";
			break;
						
			case "event_registration_actions" :
				echo '<div class="actions">';
				$admin_actions           = array();
				if ( $post->post_status !== 'trash' ) {
					$admin_actions['view']   = array(
						'action'  => 'view',
						'name'    => __( 'View', 'wp-event-manager-registrations' ),
						'url'     => get_edit_post_link( $post->ID )
					);
					$admin_actions['delete'] = array(
						'action'  => 'delete',
						'name'    => __( 'Delete', 'wp-event-manager-registrations' ),
						'url'     => get_delete_post_link( $post->ID )
					);
				}

				$admin_actions = apply_filters( 'event_manager_event_registrations_admin_actions', $admin_actions, $post );

				foreach ( $admin_actions as $action ) {
					printf( '<a class="icon-%s button tips" href="%s" data-tip="%s">%s</a>', esc_attr( $action['action'] ), esc_url( $action['url'] ), esc_attr( $action['name'] ), esc_attr( $action['name'] ) );
				}

				echo '</div>';

			break;
		}
	}

	/**
	 * Filter registrations
	 */
	public function restrict_manage_posts() {
		global $typenow, $wp_query, $wpdb;

		if ( 'event_registration' != $typenow ) {
			return;
		}

		// Customers
		?>
		<select id="dropdown_event_listings" name="_event_listing">
			<option value=""><?php _e( 'Registrations for all events', 'wp-event-manager-registrations' ) ?></option>
			<?php
				$events_with_registrations = $wpdb->get_col( "SELECT DISTINCT post_parent FROM {$wpdb->posts} WHERE post_type = 'event_registration';" );
				$current                = isset( $_GET['_event_listing'] ) ? $_GET['_event_listing'] : 0;
				foreach ( $events_with_registrations as $event_id ) {
					if ( ( $title = get_the_title( $event_id ) ) && $event_id ) {
						echo '<option value="' . $event_id . '" ' . selected( $current, $event_id, false ) . '">' . $title . '</option>';
					}
				}
			?>
		</select>
		<?php
	}

 	/**
 	 * modify what registrations are shown
 	 */
	public function request( $vars ) {
		global $typenow, $wp_query;

		if ( $typenow == 'event_registration' && isset( $_GET['_event_listing'] ) && $_GET['_event_listing'] > 0 ) {
			$vars['post_parent'] = (int) $_GET['_event_listing'];
		}

		// Sorting
		if ( isset( $vars['orderby'] ) ) {
			if ( 'rating' == $vars['orderby'] ) {
				$vars = array_merge( $vars, array(
					'meta_key' => '_rating',
					'orderby'  => 'meta_value_num'
				) );
			}
		}

		return $vars;
	}

	/**
	 * Sorting
	 */
	public function sortable_columns( $columns ) {
		$custom = array(
			'registration_rating'     => 'rating',
			'attendee'              => 'post_title',
			'event_registration_posted' => 'date',
			'event'                    => 'post_parent'
		);
		unset( $columns['comments'] );

		return wp_parse_args( $custom, $columns );
	}

	/**
	 * Search custom fields as well as content.
	 * @param WP_Query $wp
	 */
	public function search_meta( $wp ) {
		global $pagenow, $wpdb;

		if ( 'edit.php' != $pagenow || empty( $wp->query_vars['s'] ) || $wp->query_vars['post_type'] != 'event_registration' ) {
			return;
		}

		$post_ids = array_unique( array_merge(
			$wpdb->get_col(
				$wpdb->prepare( "
					SELECT posts.ID
					FROM {$wpdb->posts} posts
					INNER JOIN {$wpdb->postmeta} p1 ON posts.ID = p1.post_id
					WHERE p1.meta_value LIKE '%%%s%%'
					OR posts.post_title LIKE '%%%s%%'
					OR posts.post_content LIKE '%%%s%%'
					AND posts.post_type = 'event_registration'
					",
					esc_attr( $wp->query_vars['s'] ),
					esc_attr( $wp->query_vars['s'] ),
					esc_attr( $wp->query_vars['s'] )
				)
			),
			array( 0 )
		) );

		// Adjust the query vars
		unset( $wp->query_vars['s'] );
		$wp->query_vars['event_registration_search'] = true;
		$wp->query_vars['post__in'] = $post_ids;
	}

	/**
	 * Change the label when searching meta.
	 * @param string $query
	 * @return string
	 */
	public function search_meta_label( $query ) {
		global $pagenow, $typenow;

		if ( 'edit.php' != $pagenow || $typenow != 'event_registration' || ! get_query_var( 'event_registration_search' ) ) {
			return $query;
		}

		return wp_unslash( sanitize_text_field( $_GET['s'] ) );
	}

	/**
	 * Add statuses to admin
	 */
	public function add_custom_statuses() {
		global $typenow;

		if ( 'event_registration' === $typenow ) {
			echo "<script>jQuery(document).ready( function() {";
			echo "jQuery( 'select[name=\"_status\"]' ).find('option[value!=\"-1\"]').remove();";
			foreach( get_event_registration_statuses() as $key => $value ) {
				echo "jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"" . esc_attr( $key ) . "\">" . esc_attr( $value ) . "</option>' );";
			}
			echo "});</script>";
		}
	}
}
new WP_Event_Manager_Registrations_Admin();
