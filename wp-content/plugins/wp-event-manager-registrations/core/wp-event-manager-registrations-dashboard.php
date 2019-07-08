<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP_Event_Manager_Registrations_Dashboard class.
 */
class WP_Event_Manager_Registrations_Dashboard {

	/**
	 * __construct function.
	 */
	public function __construct() {
		add_filter( 'the_title', array( $this, 'add_breadcrumb_to_the_title' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'wp_loaded', array( $this, 'delete_handler' ) );
		add_action( 'wp_loaded', array( $this, 'edit_handler' ) );
		add_action( 'wp_loaded', array( $this, 'csv_handler' ) );
		add_filter( '_columns', array( $this, 'add_registrations_columns' ) );
		add_filter( 'event_manager_event_dashboard_columns', array( $this, 'add_registrations_columns' ) );
		add_action( 'event_manager_event_dashboard_column_registrations', array( $this, 'registrations_column' ) );
		add_action( 'event_manager_event_dashboard_content_show_registrations', array( $this, 'show_registrations' ) );

		// Ajax
		add_action( 'wp_ajax_add_event_registration_note', array( $this, 'add_event_registration_note' ) );
		add_action( 'wp_ajax_delete_event_registration_note', array( $this, 'delete_event_registration_note' ) );

		// Secure order notes
		add_filter( 'comments_clauses', array( __CLASS__, 'exclude_registration_comments' ), 10, 1 );
		add_action( 'comment_feed_join', array( $this, 'exclude_registration_comments_from_feed_join' ) );
		add_action( 'comment_feed_where', array( $this, 'exclude_registration_comments_from_feed_where' ) );
	}

	/**
	 * Change page titles
	 */
	public function add_breadcrumb_to_the_title( $post_title ) {
		global $post;

		if ( is_main_query() && is_page() && strstr( $post->post_content, '[event_dashboard' ) && in_the_loop() ) {
			remove_filter( 'the_title', array( $this, 'add_breadcrumb_to_the_title' ) );
			if ( ! empty( $_GET['action'] ) && 'show_registrations' === $_GET['action'] ) {
				$event_id = absint( $_GET['event_id'] );
				if ( 'event_listing' === get_post_type( $event_id ) ) {
						//$post_title = __( 'Event Registrations', 'wp-event-manager-registrations' ) . ' &laquo; <a href="' . get_permalink( $post->ID ) . '">' . $post_title . '</a>';
					$post_title = __( 'Event Registrations', 'wp-event-manager-registrations' ) ;
				}
			}
		}
		return $post_title;
	}

	/**
	 * frontend_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public function frontend_scripts() {
		wp_register_script( 'wp-event-manager-registrations-dashboard', EVENT_MANAGER_REGISTRATIONS_PLUGIN_URL . '/assets/js/registration-dashboard.min.js', array( 'jquery' ), EVENT_MANAGER_REGISTRATIONS_VERSION, true );

		wp_localize_script( 'wp-event-manager-registrations-dashboard', 'event_manager_registration', array(
			'i18n_confirm_delete'         => __( 'Are you sure you want to delete this? There is no undo.', 'wp-event-manager-registrations' ),
			'i18n_toggle_content'         => __( 'Details', 'wp-event-manager-registrations' ),
			'i18n_toggle_notes'           => __( 'Notes', 'wp-event-manager-registrations' ),
			'i18n_hide'                   => __( 'Hide', 'wp-event-manager-registrations' ),
			'ajax_url'                    => admin_url('admin-ajax.php'),
			'event_registration_notes_nonce' => wp_create_nonce( "event-registration-notes" ),
		) );

		wp_enqueue_style( 'wp-event-manager-registrations-frontend', EVENT_MANAGER_REGISTRATIONS_PLUGIN_URL . '/assets/css/frontend.min.css' );
		
		$ajax_url         = WP_Event_Manager_Ajax::get_endpoint();
		wp_register_script( 'wp-event-manager-registration-checkin', EVENT_MANAGER_REGISTRATIONS_PLUGIN_URL . '/assets/js/registration-checkin.min.js', array('jquery','wp-event-manager-common'), EVENT_MANAGER_REGISTRATIONS_VERSION, true);
		wp_localize_script( 'wp-event-manager-registration-checkin', 'event_manager_registrations_registration_checkin', array( 
							'ajaxUrl' 	 => $ajax_url)
						  );
		wp_enqueue_script( 'wp-event-manager-registration-checkin');
	}

	/**
	 * See if user can edit the registration
	 * @return bool
	 */
	public function can_edit_registration( $registration_id ) {
		$registration = get_post( $registration_id );

		if ( ! $registration ) {
			return false;
		}

		$event = get_post( $registration->post_parent );

		// Permissions
		if ( ! $event || ! $registration || $registration->post_type !== 'event_registration' || $event->post_type !== 'event_listing' || !event_manager_user_can_edit_event( $event->ID ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Edit an registration
	 */
	public function edit_handler() {
		if ( ! empty( $_POST['wp_event_manager_edit_registration'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'edit_event_registration' ) ) {
			global $wp_post_statuses;

			$registration_id = absint( $_POST['registration_id'] );

			if ( ! $this->can_edit_registration( $registration_id ) ) {
				return;
			}

			$registration_status = sanitize_text_field( $_POST['registration_status'] );
            /*
			$registration_rating = floatval( $_POST['registration_rating'] );
			$registration_rating = $registration_rating < 0 ? 0 : $registration_rating;
			$registration_rating = $registration_rating > 5 ? 5 : $registration_rating;

			update_post_meta( $registration_id, '_rating', $registration_rating );
            */
			if ( array_key_exists( $registration_status, $wp_post_statuses ) ) {
				wp_update_post( array(
					'ID'          => $registration_id,
					'post_status' => $registration_status
				) );
			}
		}
	}

	/**
	 * Delete an registration
	 */
	public function delete_handler() {
		if ( ! empty( $_GET['delete_event_registration'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'delete_event_registration' ) ) {
			$registration_id = absint( $_GET['delete_event_registration'] );

			if ( ! $this->can_edit_registration( $registration_id ) ) {
				return;
			}

			wp_delete_post( $registration_id, true );
		}
	}

	/**
	 * Download a CSV
	 */
	public function csv_handler() {
		if ( ! empty( $_GET['download-csv'] ) ) {
			$event_id = absint( $_REQUEST['event_id'] );
			$event    = get_post( $event_id );

			// Permissions
			if ( ! event_manager_user_can_edit_event( $event )  ) {
				return;
			}

			$args = apply_filters( 'event_manager_event_registrations_args', array(
				'post_type'           => 'event_registration',
				'post_status'         => array_merge( array_keys( get_event_registration_statuses() ), array( 'publish' ) ),
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => -1,
				'post_parent'         => $event_id
			) );

			// Filters
			$registration_status  = ! empty( $_GET['registration_status'] ) ? sanitize_text_field( $_GET['registration_status'] ) : '';
			$registration_orderby = ! empty( $_GET['registration_orderby'] ) ? sanitize_text_field( $_GET['registration_orderby'] ) : '';

			if ( $registration_status ) {
				$args['post_status'] = $registration_status;
			}

			switch ( $registration_orderby ) {
				case 'name' :
					$args['order']   = 'ASC';
					$args['orderby'] = 'post_title';
				break;
				case 'rating' :
					$args['order']    = 'DESC';
					$args['orderby']  = 'meta_value';
					$args['meta_key'] = '_rating';
				break;
				default :
					$args['order']   = 'DESC';
					$args['orderby'] = 'date';
				break;
			}

			$registrations = get_posts( $args );

			@set_time_limit(0);
			if ( function_exists( 'apache_setenv' ) ) {
				@apache_setenv( 'no-gzip', 1 );
			}
			@ini_set('zlib.output_compression', 0);
			@ob_clean();

			header( 'Content-Type: text/csv; charset=UTF-8' );
			header( 'Content-Disposition: attachment; filename=' . __( 'registrations', 'wp-event-manager-registrations' ) . '.csv' );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );

			$fp  = fopen('php://output', 'w');
			$row = array_map( __CLASS__ . '::wrap_column', array(
				__( 'Registration date', 'wp-event-manager-registrations' ),
				__( 'Registration status', 'wp-event-manager-registrations' )	   			
	   		) );

	   		// Other custom fields
	   		$custom_fields = array();

	   		//Attendee information plugin is active then it is call this function it will give organizer selected fields only.
	   		//Default give all the fields
	   		if (function_exists('get_event_organizer_attendee_fields')) {
	   		    $registration_fields = get_event_organizer_attendee_fields($event_id);
	   		}
	   		else{
	   			$registration_fields =  get_event_registration_form_fields($suppress_filters = false);
	   		}
	   		
	   		$custom_fields = array_keys ($registration_fields );
	   		$custom_fields = array_unique( $custom_fields );
	   		$custom_fields = array_diff( $custom_fields, array(
	   			'_edit_lock',
	   			'_attachment',
	   			'_attachment_file',
	   			'_event_registered_for',
	   			'_attendee_email',
	   			'_attendee_user_id',
	   			'_rating',
				'_registration_source',
				'_secret_dir'
	   		) );
	   		foreach ( $custom_fields as $custom_field ) {
	   		   $custom_field  = str_replace(array('-','_'), ' ', $custom_field);
	   			$row[] = $custom_field;
	   		}
            $row = apply_filters('event_registration_dashboard_csv_header',$row);
	   		fwrite( $fp, implode( ',', $row ) . "\n" );

	   		foreach ( $registrations as $registration ) {
	   			$row   = array();
	   			$row[] = date_i18n( get_option( 'date_format' ), strtotime( $registration->post_date ) );
	   			$row[] = $registration->post_status;	   			
	   			

	   			foreach ( $custom_fields as $custom_field ) {
					$row[] = get_post_meta( $registration->ID, $custom_field, true );
	   			}
                $row = apply_filters('event_registration_dashboard_csv_row_value',$row,$registration->ID );
	   			$row   = array_map( __CLASS__ . '::wrap_column', $row );

	   			fwrite( $fp, implode( ',', $row ) . "\n" );
	   		}

			fclose( $fp );
			exit;
		}
	}

	/**
	 * Add a new column to the event dashboard
	 * @param $columns array
	 * @return array
	 */
	public function add_registrations_columns( $columns ) {
		$columns['registrations'] = __( 'Registrations', 'wp-event-manager-registrations' );
		return $columns;
	}

	/**
	 * Show the count of registrations in the event dashboard
	 * @param  WP_Post Event
	 */
	public function registrations_column( $event ) {
		global $post;

		echo ( $count = get_event_registration_count( $event->ID ) ) ? '<a href="' . add_query_arg( array( 'action' => 'show_registrations', 'event_id' => $event->ID ), get_permalink( $post->ID ) ) . '">' . $count . '</a>' : '&ndash;';
	}

	/**
	 * Show registrations on the event dashboard
	 */
	public function show_registrations( $atts ) {
		$event_id = absint( $_REQUEST['event_id'] );
		$event    = get_post( $event_id );

		extract( shortcode_atts( array(
			'posts_per_page' => '20',
		), $atts ) );

		remove_filter( 'the_title', array( $this, 'add_breadcrumb_to_the_title' ) );

		// Permissions
		if ( ! event_manager_user_can_edit_event( $event_id)  ) {
			_e( 'You do not have permission to view this event.', 'wp-event-manager-registrations' );
			return;
		}

		wp_enqueue_script( 'wp-event-manager-registrations-dashboard' );

		$args = apply_filters( 'event_manager_event_registrations_args', array(
			'post_type'           => 'event_registration',
			'post_status'         => array_diff( array_merge( array_keys( get_event_registration_statuses() ), array( 'publish' ) ), array( 'archived' ) ),
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $posts_per_page,
			'offset'              => ( max( 1, get_query_var('paged') ) - 1 ) * $posts_per_page,
			'post_parent'         => $event_id
		) );

		// Filters
		$registration_status  = ! empty( $_GET['registration_status'] ) ? sanitize_text_field( $_GET['registration_status'] ) : '';
		$registration_orderby = ! empty( $_GET['registration_orderby'] ) ? sanitize_text_field( $_GET['registration_orderby'] ) : '';
		$registration_byname = ! empty( $_GET['registration_byname'] ) ? sanitize_text_field( $_GET['registration_byname'] ) : '';

		if ( $registration_status ) {
			$args['post_status'] = $registration_status;
		}
		if( $registration_byname ){
			$args['s'] = $registration_byname;			
		}

		switch ( $registration_orderby ) {
			case 'name' :
				$args['order']   = 'ASC';
				$args['orderby'] = 'post_title';
			break;
			case 'rating' :
				$args['order']    = 'DESC';
				$args['orderby']  = 'meta_value';
				$args['meta_key'] = '_rating';
			break;
			default :
				$args['order']   = 'DESC';
				$args['orderby'] = 'date';
			break;
		}

		$registrations = new WP_Query;

		$columns = apply_filters( 'event_manager_event_registrations_columns', array(
			'name'  => __( 'Name', 'wp-event-manager-registrations' ),
			'email' => __( 'Email', 'wp-event-manager-registrations' ),
			'date'  => __( 'Date Received', 'wp-event-manager-registrations' ),
		) );

		get_event_manager_template( 'event-registrations.php', array( 'registrations' => $registrations->query( $args ), 'event_id' => $event_id, 'max_num_pages' => $registrations->max_num_pages, 'columns' => $columns, 'registration_status' => $registration_status, 'registration_orderby' => $registration_orderby,'registration_byname'=>$registration_byname ), 'wp-event-manager-registrations', EVENT_MANAGER_REGISTRATIONS_PLUGIN_DIR . '/templates/' );
	}

	/**
	 * Wrap a column in quotes for the CSV
	 * @param  string data to wrap
	 * @return string wrapped data
	 */
	public static function wrap_column( $data ) {
		$data = is_array( $data ) ? json_encode( $data ) : $data;
		return '"' . str_replace( '"', '""', $data ) . '"';
	}

	/**
	 * Add note via ajax
	 */
	public function add_event_registration_note() {
		check_ajax_referer( 'event-registration-notes', 'security' );

		$registration_id = absint( $_POST['registration_id'] );
		$registration    = get_post( $registration_id );
		$note           = wp_kses_post( trim( stripslashes( $_POST['note'] ) ) );

		if ( $registration_id > 0 && $this->can_edit_registration( $registration_id ) ) {

			$user                 = get_user_by( 'id', get_current_user_id() );
			$comment_author       = $user->display_name;
			$comment_author_email = $user->user_email;
			$comment_post_ID      = $registration_id;
			$comment_author_url   = '';
			$comment_content      = $note;
			$comment_agent        = 'WP Event Manager';
			$comment_type         = 'event_registration';
			$comment_parent       = 0;
			$comment_approved     = 1;
			$commentdata          = apply_filters( 'event_registration_note_data', compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' ), $registration_id );
			$comment_id           = wp_insert_comment($commentdata);
			
			echo '<li rel="' . esc_attr( $comment_id ) . '" class="event-registration-note"><div class="event-registration-note-content">';
			echo wpautop( wptexturize( $note ) );
			echo '</div><p class="event-registration-note-meta"><a href="#" class="delete_note">' . __( 'Delete note', 'wp-event-manager-registrations' ) . '</a></p>';
			echo '</li>';
		}else{
		    echo '<span class="required">Please, Add Valid Event Listing ID. </span>';
		}
		die();
	}

	/**
	 * Delete note via ajax
	 */
	public function delete_event_registration_note() {
		check_ajax_referer( 'event-registration-notes', 'security' );

		if ( $note_id = absint( $_POST['note_id'] ) ) {
			$note           = get_comment( $note_id );
			$registration_id = absint( $note->comment_post_ID );
			$registration    = get_post( $registration_id );
			if ( $registration_id > 0 && $this->can_edit_registration($registration_id) ) {
				wp_delete_comment( $note_id );
			}
		}
		die();
	}

	/**
	 * Exclude registration comments from queries and RSS
	 *
	 * This code should exclude comments from queries. Some queries (like the recent comments widget on the dashboard) are hardcoded
	 * and are not filtered, however, the code current_user_can( 'read_post', $comment->comment_post_ID ) should keep them safe.
	 *
	 * The frontend view order pages get around this filter by using remove_filter.
	 *
	 * @param array $clauses
	 * @return array
	 */
	public static function exclude_registration_comments( $clauses ) {
		global $wpdb, $typenow, $pagenow;

		if ( is_admin() && $typenow == 'event_registration' ) {
			return $clauses;
		}

		if ( ! $clauses['join'] ) {
			$clauses['join'] = '';
		}

		if ( ! strstr( $clauses['join'], "JOIN $wpdb->posts" ) ) {
			$clauses['join'] .= " LEFT JOIN $wpdb->posts ON comment_post_ID = $wpdb->posts.ID ";
		}

		if ( $clauses['where'] ) {
			$clauses['where'] .= ' AND ';
		}

		$clauses['where'] .= " $wpdb->posts.post_type NOT IN ('event_registration') ";

		return $clauses;
	}

	/**
	 * Exclude comments from queries and RSS
	 *
	 * @param string $join
	 * @return string
	 */
	public function exclude_registration_comments_from_feed_join( $join ) {
		global $wpdb;

	    if ( ! strstr( $join, $wpdb->posts ) ) {
	    	$join = " LEFT JOIN $wpdb->posts ON $wpdb->comments.comment_post_ID = $wpdb->posts.ID ";
	    }

	    return $join;
	}

	/**
	 * Exclude order comments from queries and RSS
	 *
	 * @param string $where
	 * @return string
	 */
	public function exclude_registration_comments_from_feed_where( $where ) {
		global $wpdb;

	    if ( $where ) {
	    	$where .= ' AND ';
	    }

		$where .= " $wpdb->posts.post_type NOT IN ('event_registration') ";

	    return $where;
	}
}
new WP_Event_Manager_Registrations_Dashboard();