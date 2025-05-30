<?php

/**
 *  Define Theme URL
 */
define('THEME_ROOT_URL', get_template_directory() .'/' );

if ( ! function_exists( 'cc_setup' ) ) :     
function cc_setup() {

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'main-menu' => __( 'Main Menu' ),
		'footer-menu-1' => __( 'Footer Menu 1' ),
		'footer-menu-2' => __( 'Footer Menu 2' ),
		'footer-menu-3' => __( 'Footer Menu 3' ),
		'footer-menu-4' => __( 'Footer Menu 4' ),
		'footer-menu-5' => __( 'Footer Menu 5' ),
		'footer-menu-6' => __( 'Footer Menu 6' ),
		'legal-menu'    => __( 'Legal Menu' )
	) );


	/* image size set */
	add_image_size( 'medium_rect', 818, 435, true ); 
	add_image_size( 'agent_logo', 156, 156, true ); 

	/*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
	add_theme_support( 'custom-logo', array(
		'flex-width'  => true,
		'flex-height' => true,
	) );
}
endif;
add_action( 'after_setup_theme', 'cc_setup' );

function add_css()
{
	wp_register_style('global', get_template_directory_uri() . '/assets/css/global.css', false,'1.1','all');
	wp_enqueue_style( 'global');

	wp_register_style('style', get_template_directory_uri() . '/assets/css/style.css', false,'1.1','all');
	wp_enqueue_style( 'style');

	wp_register_style('single-task', get_template_directory_uri() . '/assets/css/single-task.css', false,'1.1','all');
	wp_enqueue_style( 'single-task');
}
add_action('wp_enqueue_scripts', 'add_css');

function add_script()
{
	wp_register_script('script', get_template_directory_uri() . '/assets/script/script.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'script');

	wp_register_script('single-task', get_template_directory_uri() . '/assets/script/single-task.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'single-task'); 
}
add_action('wp_enqueue_scripts', 'add_script');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cbd_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Main Sidebar', 'cbd' ),
		'id'            => 'main-sidebar',
		'description'   => esc_html__( 'Add widgets here.', 'cbd' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'cbd_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
require THEME_ROOT_URL . 'inc/enqueue.php';

/**
 * Register ACF Blocks
 */
require THEME_ROOT_URL . 'inc/register-blocks.php';

/**
 * Enable support for woocommerce
 */
function add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'add_woocommerce_support' );

// time allocation
function allocate_task_callback() {
	if (isset($_POST['post_id']) && isset($_POST['allocation_time'])) {
		$post_id = absint($_POST['post_id']);
		$allocation_time = sanitize_text_field($_POST['allocation_time']);

		if (current_user_can('edit_post', $post_id) && strtotime($allocation_time) > time()) {
			update_field('allocation_time', $allocation_time, $post_id);
			wp_send_json_success('Task allocated successfully.');
		} else {
			wp_send_json_error('Invalid data or insufficient permissions.');
		}
	} else {
		wp_send_json_error('Invalid request.');
	}
}

add_action('wp_ajax_allocate_task', 'allocate_task_callback');
add_action('wp_ajax_nopriv_allocate_task', 'allocate_task_callback');

// Hook to handle form submissions
add_action('init', 'process_project_submission');

function process_project_submission() {
	if (isset($_POST['submit_project'])) {
		$selected_post_id = intval($_POST['selected_post']); 

		$task_query = new WP_Query(array(
			'post_type' => 'tasks',
			'posts_per_page' => 1, 
		));

		if ($task_query->have_posts()) {
			while ($task_query->have_posts()) {
				$task_query->the_post();
				$post_id = get_the_ID();

				update_post_meta($post_id, 'project', $selected_post_id);

?><script>
	console.log('Project Assigned successfully.');

</script>

<?php
			}
			wp_reset_postdata(); 
		}
	}
}

//Custom Status 1
function custom_register_post_status_projects() {
	register_post_status('new-ticket', array(
		'label'                     => _x('New Ticket', 'projects'),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop('New Ticket (%s)', 'New Ticket (%s)'),
	));
}
add_action('init', 'custom_register_post_status_projects');

function register_leave_post_type() {
	$args = array(
		'label'             => __('Leaves', 'textdomain'),
		'public'            => false,
		'show_ui'           => true,
		'supports'          => array('title', 'editor', 'author', 'custom-fields'),
		'capability_type'   => 'post',
		'map_meta_cap'      => true,
	);
	register_post_type('leave', $args);
}
add_action('init', 'register_leave_post_type');


// Handle task status update from frontend
function update_task_status() {

	if (!isset($_POST['post_id'], $_POST['task_status']) || !current_user_can('edit_posts')) {
		wp_send_json_error('Invalid request');
		return; 
	}

	$post_id = intval($_POST['post_id']);
	$task_status = sanitize_text_field($_POST['task_status']);

	$post_data = array(
		'ID'           => $post_id,
		'post_status'  => $task_status, 
	);

	$result = wp_update_post($post_data);

	if (is_wp_error($result)) {
		wp_send_json_error('there is some error');
	} else {

		$current_user_id = get_current_user_id();
		$current_user_email = get_the_author_meta('user_email', $current_user_id);
		$current_user_name = get_the_author_meta('display_name', $current_user_id);
		$task_title = get_the_title($post_id);
		$subject = 'Task Status Updated';
		$message = "Hello {$current_user_name},<br><br>The status of the task '{$task_title}' has been changed to '{$task_status}'.<br><br>Best regards,<br>Your Website Team";
		$headers = array('Content-Type: text/html; charset=UTF-8');


		wp_mail($current_user_email, $subject, $message, $headers);

		wp_send_json_success('Task status updated successfully.');
	}
}
add_action('wp_ajax_update_task_status', 'update_task_status');
add_action('wp_ajax_nopriv_update_task_status', 'update_task_status');

function filter_user_posts_by_candidate_name($query) {
	if (is_admin() && $query->is_main_query() && !wp_doing_ajax()) {


		if (is_user_logged_in()) {
			$current_user = wp_get_current_user();
			if (!current_user_can('administrator')) {


				if ($query->get('post_type') === 'post' || $query->get('post_type') === 'tasks')                                                                                                                    {                 
					$meta_query = array(
						array(
							'key'     => 'candidate_name',  
							'value'   => $current_user->ID, 
							'compare' => '='
						)
					);
					$query->set('meta_query', $meta_query);
				}
			}
		}
	}
}
add_action('pre_get_posts', 'filter_user_posts_by_candidate_name');

function enqueue_timer_script() {
	wp_enqueue_script('jquery');

	wp_localize_script('custom-timer', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_timer_script');




function enqueue_timer_scripts() {
	wp_enqueue_script('custom-timer', get_template_directory_uri() . '/assets/script/custom-timer.js', array('jquery'), null, true);

	$post_id = get_the_ID();
	$timers = get_field('timers', $post_id); // Fetch existing timers from the database

	$start_time = 0;
	$stop_time = 0;
	$total_time_consumed = 0;

	// Calculate total time consumed from existing timers
	if ($timers && is_array($timers)) {
		foreach ($timers as $timer) {
			$total_time_consumed += isset($timer['total_time_consumed']) ? (int) $timer['total_time_consumed'] : 0;
		}
	}

	wp_localize_script('custom-timer', 'timerVars', array(
		'start_time' => $start_time,
		'stop_time' => $stop_time,
		'allocated_time' => (int) get_field('t_allocated_time', $post_id) * 3600,
		'total_time_consumed' => $total_time_consumed, // Total time consumed across all timers
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('save_timer_nonce'),
		'post_id' => $post_id,
		'timers' => $timers, // Pass existing timers to JavaScript
	));
}
add_action('wp_enqueue_scripts', 'enqueue_timer_scripts');

function save_timer_data() {
	if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'save_timer_nonce')) {
		$post_id = intval($_POST['post_id']);
		$stop_time = intval($_POST['stop_time']);
		$consumed_time = intval($_POST['consumed_time']); // Time consumed in seconds

		if ($post_id && $stop_time) {
			$timers = get_field('timers', $post_id);			
			if (!$timers || !is_array($timers)) {
				$timers = [];
			}

			$author_id = get_post_field('post_author', $post_id);

			$last_timer_index = count($timers) - 1;
			$timers[$last_timer_index]['stop_timer'] = date('d-m-Y H:i:s', $stop_time);
			$timers[$last_timer_index]['total_time_consumed'] = $consumed_time;
			$timers[$last_timer_index]['user'] = $author_id; 
			update_field('timers', $timers, $post_id);

			wp_send_json_success();
		} else {
			wp_send_json_error('Invalid post ID or stop time');
		}
	} else {
		wp_send_json_error('Invalid nonce');
	}
}
add_action('wp_ajax_save_timer_data', 'save_timer_data');

function save_start_time() {
	if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'save_timer_nonce')) {
		$post_id = intval($_POST['post_id']);
		$start_time = intval($_POST['start_time']);

		if ($post_id && $start_time) {
			$timers = get_field('timers', $post_id);

			// If no timers yet, initialize an empty array
			if (!$timers || !is_array($timers)) {
				$timers = [];
			}

			// Add new timer entry with only the start time
			$timers[] = array(
				'start_timer' => date('d-m-Y H:i:s', $start_time),

			);
			// Update the timers field
			update_field('timers', $timers, $post_id);

			wp_send_json_success();
		} else {
			wp_send_json_error('Invalid post ID or start time');
		}
	} else {
		wp_send_json_error('Invalid nonce');
	}
}
add_action('wp_ajax_save_start_time', 'save_start_time');


add_action('wp_ajax_get_timers', 'get_timers_callback');
add_action('wp_ajax_nopriv_get_timers', 'get_timers_callback');
function get_timers_callback() {

	if( have_rows('timers') ):
	$timers = array();
	while( have_rows('timers') ): the_row();
	$start_time = get_sub_field('start_timer');
	$stop_time = get_sub_field('stop_timer');
	$total_time_consumed = get_sub_field('total_time_consumed');

	$timers[] = array(
		'start_time' => $start_time,
		'stop_time' => $stop_time,
		'total_time_consumed' => $total_time_consumed
	);
	endwhile;
	wp_send_json_success($timers);
	else:
	endif;
	wp_die();
}

add_action('wp_ajax_get_user_list', 'get_user_list');
function get_user_list() {
	$users = get_users(array('fields' => array('ID', 'user_login')));
	wp_send_json_success(array('data' => $users));
}   

function enqueue_media_uploader_scripts() {
	if (is_page_template('ticket-form.php')) { // Replace with your actual template filename
		wp_enqueue_media(); // Enqueue the media uploader scripts
	}
}
add_action('wp_enqueue_scripts', 'enqueue_media_uploader_scripts');

function delete_timers_callback() {

	if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'delete_timer_nonce')) {
		wp_send_json_error(array('message' => 'Invalid nonce'));
	}


	$post_id = intval($_POST['post_id']);
	$timer_index = intval($_POST['timer_index']);

	$timers = get_field('timers', $post_id);

	if ($timers && isset($timers[$timer_index])) {

		unset($timers[$timer_index]);

		$timers = array_values($timers);


		if (update_field('timers', $timers, $post_id)) {
			wp_send_json_success();
		} else {
			wp_send_json_error(array('message' => 'Failed to delete timer from backend'));
		}
	} else {
		wp_send_json_error(array('message' => 'Timer not found'));
	}
}
add_action('wp_ajax_delete_timer', 'delete_timers_callback');

function enqueue_delete_timer_script() {
	wp_enqueue_script('custom-timer', get_template_directory_uri() . '/assets/script/custom-timer.js', array('jquery'), null, true);

	// Localize the script to pass AJAX URL and nonce to the JavaScript
	wp_localize_script('custom-timer', 'deleteTimerData', array(
		'ajax_url' => admin_url('admin-ajax.php'), // This is the URL for the AJAX request
		'nonce' => wp_create_nonce('delete_timer_nonce') // Security nonce for the AJAX request
	));
}
add_action('wp_enqueue_scripts', 'enqueue_delete_timer_script');

function enqueue_ticket_form_scripts() {
	// Enqueue the custom script for AJAX handling
	wp_enqueue_script('ticket-form-ajax', get_template_directory_uri() . '/assets/script/ticket-form-ajax.js', array('jquery'), null, true);

	// Localize the script to pass the AJAX URL to JS
	wp_localize_script('ticket-form-ajax', 'ticketFormAjax', array(
		'ajaxurl' => admin_url('admin-ajax.php')  // WordPress AJAX URL
	));
}
add_action('wp_enqueue_scripts', 'enqueue_ticket_form_scripts');

function handle_ticket_form_submission() {

	if (isset($_POST['task_nonce']) && wp_verify_nonce($_POST['task_nonce'], 'add_task_nonce')) {

		$task_name = sanitize_text_field($_POST['post_title']);
		$task_description = wp_kses_post($_POST['post_content']);
		$task_category = isset($_POST['post_taxonomy_category']) ? intval($_POST['post_taxonomy_category']) : 0;
		$task_role = isset($_POST['post_taxonomy']) ? intval($_POST['post_taxonomy']) : 0;
		$priority = isset($_POST['acf_radio_field']) ? sanitize_text_field($_POST['acf_radio_field']) : 'low';
		$candidate_name = isset($_POST['candidate_name']) ? intval($_POST['candidate_name']) : 0;
		$allocated_time = isset($_POST['allocated_time']) ? sanitize_text_field($_POST['allocated_time']) : '';
		$task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : 0;
		$due_date = isset($_POST['due_date']) ? sanitize_text_field($_POST['due_date']) : ''; 
		$post_data = array(
			'post_title'   => $task_name,
			'post_content' => $task_description,
			'post_status'  => 'new-ticket',
			'post_type'    => 'tasks',
			'post_category' => array($task_category),
		);
		if ($task_id) {
			$post_data['ID'] = $task_id;
			$post_id = wp_update_post($post_data);
		} else {
			$post_id = wp_insert_post($post_data);
		}

		if ($post_id) {
			if ($task_role) {
				wp_set_object_terms($post_id, $task_role, 'roles');
			}
			update_post_meta($post_id, 'priority', $priority);
			if ($candidate_name) {
				update_field('candidate_name', $candidate_name, $post_id);
			}

			if (!empty($allocated_time)) {
				update_field('t_allocated_time', $allocated_time, $post_id);
			}

			// Update due date field
			if (!empty($due_date)) {
				update_post_meta($post_id, 'due_date', $due_date);
			}
			$task_url = get_permalink($post_id);
			wp_send_json_success(array('task_url' => $task_url, 'due_date' => $due_date)); // Send due date in response
		} else {
			wp_send_json_error();
		}
	} else {
		wp_send_json_error();
	}
}
add_action('wp_ajax_add_task', 'handle_ticket_form_submission');
add_action('wp_ajax_nopriv_add_task', 'handle_ticket_form_submission');


function my_custom_post_type_supports() {
	add_post_type_support('tasks', 'comments');
}
add_action('init', 'my_custom_post_type_supports');
function enqueue_bug_report_scripts() {
	wp_enqueue_script(
		'bug-report-ajax',
		get_template_directory_uri() . '/assets/script/bug-report.js',
		array('jquery'),
		null,
		true
	);
	wp_localize_script(
		'bug-report-ajax',
		'bugReportAjax',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'bug_report_nonce' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'enqueue_bug_report_scripts' );
function enqueue_bug_report_script() {
	wp_enqueue_script('bug-report-script', get_template_directory_uri() . '/assets/script/bug-report.js', array('jquery'), null, true);
	wp_localize_script('bug-report-script', 'bugReportAjax', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce'    => wp_create_nonce('bug_report_nonce'),
	));
}
add_action('wp_enqueue_scripts', 'enqueue_bug_report_script');

function handle_bug_report_ajax() {
	check_ajax_referer('bug_report_nonce', 'nonce');

	$page_url = sanitize_text_field($_POST['page_url']);
	$screenshot = sanitize_text_field($_POST['screenshot']);
	$bug_description = sanitize_textarea_field($_POST['bug_description']);
	$status = sanitize_text_field($_POST['status']);
	$post_id = intval($_POST['post_id']);

	$bug_id = uniqid('bug_');
	$row = array(
		'bug_id'         => $bug_id,  
		'page_url'       => $page_url,
		'screenshot'     => $screenshot,
		'bug_description' => $bug_description,
		'status'         => $status,
	);

	// Add the row to the repeater field
	$success = add_row('bug_list', $row, $post_id);

	if ($success) {
		wp_send_json_success([
			'message' => 'Bug report added successfully!',
			'new_bug' => $row
		]);
	} else {
		wp_send_json_error(['message' => 'Failed to add bug report. Try again!']);
	}
	wp_die();
}
add_action('wp_ajax_handle_bug_report_ajax', 'handle_bug_report_ajax');
add_action('wp_ajax_nopriv_handle_bug_report_ajax', 'handle_bug_report_ajax');

add_action('wp_ajax_edit_bug_report', 'handle_edit_bug_report_ajax');
add_action('wp_ajax_nopriv_edit_bug_report', 'handle_edit_bug_report_ajax');

function handle_edit_bug_report_ajax() {
	check_ajax_referer('bug_report_nonce', 'nonce');

	$post_id = intval($_POST['post_id']);
	$bug_id = sanitize_text_field($_POST['bug_id']); // Ensure bug_id is properly retrieved
	$page_url = sanitize_text_field($_POST['page_url']);
	$screenshot = sanitize_text_field($_POST['screenshot']);
	$bug_description = sanitize_textarea_field($_POST['bug_description']);
	$status = sanitize_text_field($_POST['status']);

	if (!$bug_id) {
		wp_send_json_error(['message' => 'Bug ID is missing.']);
	}

	$bug_list = get_field('bug_list', $post_id);

	if ($bug_list) {
		foreach ($bug_list as $index => $bug) {
			if ($bug['bug_id'] === $bug_id) { // Match against stored bug_id
				$bug_list[$index]['page_url'] = $page_url;
				$bug_list[$index]['screenshot'] = $screenshot;
				$bug_list[$index]['bug_description'] = $bug_description;
				$bug_list[$index]['status'] = $status;

				update_field('bug_list', $bug_list, $post_id);

				wp_send_json_success(['message' => 'Bug report updated successfully!']);
			}
		}
	}

	wp_send_json_error(['message' => 'Failed to update bug report.']);
}
function send_birthday_emails() {
	// Check if the task has already run today to avoid multiple emails
	if (get_transient('birthday_email_sent')) {
		return;
	}

	// Get all users
	$users = get_users(array(
		'meta_key' => 'birthdate', // Ensure this matches the ACF field name
	));

	// Get today's date
	$today = date('m-d');

	foreach ($users as $user) {
		// Get the user's birthday from the ACF field
		$birthday = get_field('birthdate', 'user_' . $user->ID); // Ensure this matches the field name in ACF

		if ($birthday) {
			// Format the birthday to match today's date format
			$birthday_date = DateTime::createFromFormat('Ymd', $birthday);
			$birthday_formatted = $birthday_date->format('m-d');

			// Check if today is the user's birthday
			if ($today === $birthday_formatted) {
				// Send birthday email
				$to = $user->user_email;
				$subject = 'Happy Birthday!';
				$message = 'Dear ' . $user->display_name . ",\n\nHappy Birthday! We hope you have a fantastic day!\n\nBest regards,\nYour Site Team";
				$headers = array('Content-Type: text/plain; charset=UTF-8');

				wp_mail($to, $subject, $message, $headers);
			}
		}
	}
	set_transient('birthday_email_sent', true, DAY_IN_SECONDS);
}

// Schedule a daily event
if (!wp_next_scheduled('daily_birthday_check')) {
	wp_schedule_event(time(), 'daily', 'daily_birthday_check');
}

// Hook the function to the scheduled event
add_action('daily_birthday_check', 'send_birthday_emails');

function handle_task_status_update() {
	// Check if the form is submitted and the correct action is set
	if (isset($_POST['action']) && $_POST['action'] == 'update_task_status') {
		// Sanitize and retrieve the form data
		$task_status = sanitize_text_field($_POST['task_status']);
		$post_id = intval($_POST['post_id']);
		$author_id = intval($_POST['author_id']);

		// Update the task status (you can store this in post meta or wherever you need)
		update_post_meta($post_id, '_task_status', $task_status);

		// Get the email address of the logged-in user
		$user_info = get_userdata($author_id);
		$user_email = $user_info->user_email;

		// Prepare the email content
		$subject = "Task Status Updated";
		$message = "Hello, \n\nThe status of your task has been updated to: $task_status.\n\nRegards,\nYour Team";

		// Send the email
		wp_mail($user_email, $subject, $message);
	}
}
add_action('init', 'handle_task_status_update');



function handle_leave_submission() {
	if (!is_user_logged_in() || !isset($_POST['leave_nonce']) || !wp_verify_nonce($_POST['leave_nonce'], 'leave_nonce')) {
		wp_send_json_error(['message' => 'Unauthorized request']);
	}

	$user_id = get_current_user_id();
	$user_info = get_userdata($user_id);
	$user_email = $user_info->user_email;
	$user_name = $user_info->display_name;

	$leave_title = sanitize_text_field($_POST['leave_title']);
	$leave_reason = sanitize_textarea_field($_POST['leave_reason']);
	$leave_start = sanitize_text_field($_POST['leave_start']);
	$leave_end = sanitize_text_field($_POST['leave_end']);
	$leave_day = sanitize_text_field($_POST['leave_day']);
	
	$args =[
		'post_type'   => 'leave',
		'post_status' => 'publish',
		'author'      => $user_id,
		'meta_query'  => [
			'relation' => 'OR',
			[
				'key'     => 'start_date',
				'value'   => [$leave_start, $leave_end],
				'compare' => 'BETWEEN',
				'type'    => 'DATE'
			],
			[
				'key'     => 'end_date',
				'value'   => [$leave_start, $leave_end],
				'compare' => 'BETWEEN',
				'type'    => 'DATE'
			],
			[
				'relation' => 'AND',
				[
					'key'     => 'start_date',
					'value'   => $leave_start,
					'compare' => '<=',
					'type'    => 'DATE'
				],
				[
					'key'     => 'end_date',
					'value'   => $leave_end,
					'compare' => '>=',
					'type'    => 'DATE'
				]
			]
		]
	];

	$existing_leave = new WP_Query($args);

	if ($existing_leave->have_posts()) {
		wp_send_json_error(['message' => 'You cannot choose overlapping leave dates.']);
	}

	// Insert leave request
	$leave_id = wp_insert_post([
		'post_type'    => 'leave',
		'post_title'   => $leave_title,
		'post_status'  => 'publish',
		'post_author'  => $user_id,
	]);

	if ($leave_id) {
		// Save ACF fields
		update_field('title', $leave_title, $leave_id);
		update_field('start_date', $leave_start, $leave_id);
		update_field('end_date', $leave_end, $leave_id);
		update_field('day', $leave_day, $leave_id);
		update_field('reason', $leave_reason, $leave_id);

		// Admin Email Notification
		$admin_email = get_option('admin_email'); // Dynamic Admin Email
		$subject = "New Leave Request Submitted by $user_name";
		$message = "
			<html>
			<head>
				<title>New Leave Request</title>
			</head>
			<body>
				<p>Hello Admin,</p>
				<p>A new leave request has been submitted by <strong>$user_name</strong>.</p>
				<table border='1' cellpadding='5' cellspacing='0'>
					<tr><th>Leave Title</th><td>$leave_title</td></tr>
					<tr><th>Start Date</th><td>$leave_start</td></tr>
					<tr><th>End Date</th><td>$leave_end</td></tr>
					<tr><th>Day Type</th><td>$leave_day</td></tr>
					<tr><th>Reason</th><td>$leave_reason</td></tr>
				</table>
				<p>Please review the request.</p>
				<p>Thank you!</p>
			</body>
			</html>
		";

		$headers = [
			'Content-Type: text/html; charset=UTF-8',
			"From: $user_name <$user_email>"
		];

		// Send Email
		$mail_sent = wp_mail($admin_email, $subject, $message, $headers);

		// Log email (optional, for debugging)
		if (!$mail_sent) {
			error_log("Leave request email failed to send to $admin_email.");
		}

		wp_send_json_success(['message' => 'Leave submitted successfully and email sent to admin']);
	} else {
		wp_send_json_error(['message' => 'Error submitting leave request']);
	}
}

// Register AJAX Actions
add_action('wp_ajax_submit_leave', 'handle_leave_submission');
add_action('wp_ajax_nopriv_submit_leave', 'handle_leave_submission');

function get_leave_list() {
	if (!is_user_logged_in()) {
		wp_send_json_error(['message' => 'Unauthorized request']);
	}

	$user_id = get_current_user_id();
	$leaves = get_posts([
		'post_type'      => 'leave',
		'post_status'    => 'publish',
		'author'         => $user_id,
		'numberposts'    => -1,
	]);

	if ($leaves) {
		$monthly_leave = [];

		ob_start();
?>
<table border="1" cellspacing="0" cellpadding="8" style="width: 100%; border-collapse: collapse;">
	<thead>
		<tr style="background-color: #f2f2f2; text-align: left;">
			<th>Title</th>
			<th>Leave Type</th>
			<th>Start Date</th>
			<th>End Date</th>
			<th>Reason</th>
			<th>Leaves</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($leaves as $leave): 
		$title = get_field('title', $leave->ID);
		$start = get_field('start_date', $leave->ID);
		$end = get_field('end_date', $leave->ID);
		$day = get_field('day', $leave->ID);
		$reason = get_field('reason', $leave->ID);
		$leave_id = $leave->ID; 


		$start_date = new DateTime($start);
		$end_date = new DateTime($end);
		$diff = $start_date->diff($end_date);
		$total_days = $diff->days + 1; 


		$total_leave = ($day === "Full Day") ? $total_days : $total_days * 0.5;


		$month_key = $start_date->format('Y-m');


		if (!isset($monthly_leave[$month_key])) {
			$monthly_leave[$month_key] = 0;
		}
		$monthly_leave[$month_key] += $total_leave;
		?>
		<tr>
			<td><?php echo esc_html($title); ?></td>
			<td><?php echo esc_html($day); ?></td>
			<td><?php echo esc_html($start); ?></td>
			<td><?php echo esc_html($end); ?></td>
			<td><?php echo esc_html($reason); ?></td>
			<td><?php echo esc_html($total_leave); ?></td>
			<td><button class="delete-leave-btn" data-leave-id="<?php echo esc_attr($leave_id); ?>">Delete</button></td>
		</tr>
		<?php endforeach; ?>
	</tbody>

</table>
<?php
		wp_send_json_success(ob_get_clean());
	} else {
		wp_send_json_success("<p style='color: red;'>No leave records found.</p>");
	}
}
add_action('wp_ajax_get_leave_list', 'get_leave_list');
add_action('wp_ajax_nopriv_get_leave_list', 'get_leave_list');


add_action('wp_ajax_filter_leave_by_month', 'filter_leave_by_month');
add_action('wp_ajax_nopriv_filter_leave_by_month', 'filter_leave_by_month');

function filter_leave_by_month() {
	if (is_user_logged_in()) {

		$user_id = get_current_user_id();
		$selected_month = isset($_POST['month']) ? $_POST['month'] : date('m');       
		$leaves = get_posts([
			'post_type'     => 'leave',
			'post_status'   => 'publish',
			'author'        => $user_id,
			'numberposts'   => -1,
		]);

		$leave_summary = [];

		if ($leaves) {
			foreach ($leaves as $leave) {
				$start = get_field('start_date', $leave->ID);
				$end = get_field('end_date', $leave->ID);
				$day = get_field('day', $leave->ID);
				$start_date = new DateTime($start);
				$end_date = new DateTime($end);
				$diff = $start_date->diff($end_date);
				$total_days = $diff->days + 1;      
				$total_leave = ($day === "Full Day") ? $total_days : $total_days * 0.5;              
				$leave_month = $start_date->format('m');              
				if ($selected_month === "all" || $leave_month == $selected_month) {
					if (!isset($leave_summary[$leave_month])) {
						$leave_summary[$leave_month] = 0;
					}
					$leave_summary[$leave_month] += $total_leave;
				}
			}            
			$output = '';
			if (!empty($leave_summary)) {
				foreach ($leave_summary as $month => $total_leave_for_month) {
					$output .= "<tr><td>" . esc_html(date("F", strtotime("$month/01"))) . "</td><td>" . esc_html($total_leave_for_month) . "</td></tr>";
				}
			} else {
				$output .= "<tr><td colspan='2' style='text-align: center;'>No leave records found.</td></tr>";
			}            
			echo json_encode(['leave_summary' => $output]);
		} else {
			echo json_encode(['leave_summary' => "<p style='color: red;'>No leave records found.</p>"]);
		}
	} else {
		echo json_encode(['leave_summary' => "<p>You need to be logged in to view your leave records.</p>"]);
	}
	die(); 
}
function delete_leave_post() {

	if (!is_user_logged_in()) {
		wp_send_json_error(['message' => 'Unauthorized request']);
	}

	if (isset($_POST['leave_id']) && is_numeric($_POST['leave_id'])) {
		$leave_id = intval($_POST['leave_id']);


		$leave_post = get_post($leave_id);


		if ($leave_post && $leave_post->post_author == get_current_user_id()) {

			wp_delete_post($leave_id, true);
			wp_send_json_success(['message' => 'Leave deleted successfully']);
		} else {
			wp_send_json_error(['message' => 'You do not have permission to delete this leave']);
		}
	} else {
		wp_send_json_error(['message' => 'Invalid leave ID']);
	}
}
add_action('wp_ajax_delete_leave', 'delete_leave_post');

function enqueue_leave_form_scripts() {
	wp_enqueue_script(
		'leave-form-js',
		get_template_directory_uri() . '/assets/script/leave-form.js',
		['jquery'],
		null,
		true
	);


	wp_localize_script(
		'leave-form-js',
		'myAjax',
		['ajaxurl' => admin_url('admin-ajax.php')]
	);
}
add_action('wp_enqueue_scripts', 'enqueue_leave_form_scripts');


function generate_all_users_salary_slip() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error(['message' => 'Unauthorized request']);
	}

	$current_user = wp_get_current_user();
	$is_admin = in_array( 'administrator', (array) $current_user->roles );


	$current_date = new DateTime();
	$current_date->modify('-1 month');
	$current_month_name = $current_date->format('F');
	$current_year = $current_date->format('Y');


	$months = [
		'January'   => 31,
		'February'  => date('L', strtotime($current_year . '-02-01')) ? 29 : 28, 
		'March'     => 31,
		'April'     => 30,
		'May'       => 31,
		'June'      => 30,
		'July'      => 31,
		'August'    => 31,
		'September' => 30,
		'October'   => 31,
		'November'  => 30,
		'December'  => 31,
	];
	$total_working_days = $months[$current_month_name];
	if ( $is_admin ) {
		$users = get_users([	'role__not_in' => ['Administrator'], 
		]);
	} else {
		$users = [$current_user]; 
	}
	if ( ! $users ) {
		wp_send_json_error(['message' => 'No users found']);
	}
	ob_start();
	foreach ( $users as $user ) {
		$salary = get_field('user_salary', 'user_' . $user->ID);
		if ( ! $salary ) {
			$salary = 0;
		}
		$leaves = get_posts([
			'post_type'   => 'leave',
			'post_status' => 'publish',
			'author'      => $user->ID,
			'numberposts' => -1,
		]);
		$total_leave = 0;
		foreach ( $leaves as $leave ) {
			$start = get_field('start_date', $leave->ID);
			$end   = get_field('end_date', $leave->ID);
			$day   = get_field('day', $leave->ID);

			if ( ! $start || ! $end ) {
				continue;
			}

			$start_date = new DateTime($start);
			$end_date   = new DateTime($end);

			if ( $start_date->format('Y-m') === $current_date->format('Y-m') ) {
				$diff       = $start_date->diff($end_date);
				$total_days = $diff->days + 1; 
				$leave_days = ( $day === "Full Day" ) ? $total_days : $total_days * 0.5;
				$total_leave += $leave_days;
			}
		}
		$present_days = $total_working_days - $total_leave;
		if ( $present_days < 0 ) {
			$present_days = 0;
		}
		$earning_multiplier = $present_days / $total_working_days;
		$basic_percentage       = 0.50;
		$da_percentage          = 0.06;
		$travel_percentage      = 0.224;
		$conveyance_percentage  = 0.182;
		$special_percentage     = 0.034; 
		$basic_salary   = $salary * $earning_multiplier * $basic_percentage;
		$da             = $salary * $earning_multiplier * $da_percentage;
		$travel         = $salary * $earning_multiplier * $travel_percentage;
		$conveyance     = $salary * $earning_multiplier * $conveyance_percentage;
		$special        = $salary * $earning_multiplier * $special_percentage;
		$gross_salary   = $basic_salary + $da + $travel + $conveyance + $special;
		$safety_deposit    = 0;
		$professional_tax  = 0;
		$net_salary        = $gross_salary - ($safety_deposit + $professional_tax);
		$salary_slip_content = "Salary Slip for " . esc_html($current_month_name . ' ' . $current_year) . "\n\n";
		$salary_slip_content .= "Name: " . esc_html($user->display_name) . "\n";
		$salary_slip_content .= "Salary in Amount: " . esc_html(number_format($salary, 2)) . "\n";
		$salary_slip_content .= "Total Working Days: " . esc_html($total_working_days) . "\n";
		$salary_slip_content .= "Present Days: " . esc_html(number_format($present_days, 2)) . "\n\n";
		$salary_slip_content .= "Earnings Amount:\n";
		$salary_slip_content .= "Basic Salary: " . esc_html(number_format($basic_salary, 2)) . "\n";
		$salary_slip_content .= "Dearness Allowance: " . esc_html(number_format($da, 2)) . "\n";
		$salary_slip_content .= "Travel Allowance: " . esc_html(number_format($travel, 2)) . "\n";
		$salary_slip_content .= "Conveyance Allowance: " . esc_html(number_format($conveyance, 2)) . "\n";
		$salary_slip_content .= "Special Allowance: " . esc_html(number_format($special, 2)) . "\n";
		$salary_slip_content .= "Gross Salary: " . esc_html(number_format($gross_salary, 2)) . "\n\n";
		$salary_slip_content .= "Deduction Amount:\n";
		$salary_slip_content .= "Safety Deposit: " . esc_html(number_format($safety_deposit, 2)) . "\n";
		$salary_slip_content .= "Professional Tax: " . esc_html(number_format($professional_tax, 2)) . "\n\n";
		$salary_slip_content .= "Net Salary: " . esc_html(number_format($net_salary, 2)) . "\n";
		if ( ! $is_admin ) {
			echo '<div style="border:1px solid #ccc; padding:15px; margin-bottom:20px;">';
			echo '<div style="display: flex; align-items: center; justify-content: space-between;">';
			echo '<h4 style="margin: 0;">Salary Slip of ' . esc_html($current_month_name . ' ' . $current_year) . '</h4>';

			echo '<button style="padding: 10px 20px; background-color: ##0d6efd; color: white; border: none; cursor: pointer;" onclick="printSalarySlip(\'' . esc_js($user->display_name) . '\', ' . esc_js($salary) . ', ' . esc_js($total_working_days) . ', ' . esc_js($present_days) . ', ' . esc_js($basic_salary) . ', ' . esc_js($da) . ', ' . esc_js($travel) . ', ' . esc_js($conveyance) . ', ' . esc_js($special) . ', ' . esc_js($gross_salary) . ', ' . esc_js($safety_deposit) . ', ' . esc_js($professional_tax) . ', ' . esc_js($net_salary) . ', \'' . esc_js($current_month_name) . '\', \'' . esc_js($current_year) . '\')">Download Salary Slip (' . esc_html($current_month_name . ' ' . $current_year) . ')</button>';
		}
		echo '</div>';
	}
	$content = ob_get_clean();
	wp_send_json_success($content);
}
add_action('wp_ajax_generate_all_salary_slip', 'generate_all_users_salary_slip');
add_action('wp_ajax_nopriv_generate_all_salary_slip', 'generate_all_users_salary_slip');


function show_admin_login_notification() {
    if (is_user_logged_in() && !get_transient('login_notification_shown')) 
	{
        echo '<div class="notice notice-success is-dismissible">
                 <h1>Welcome back, ' . wp_get_current_user()->display_name . '!</h1>
              </div>';
        set_transient('login_notification_shown', true, 600);
    }
}
add_action('admin_notices', 'show_admin_login_notification');

function enqueue_notifications_assets() {
    wp_enqueue_script('notifications-js', get_template_directory_uri() . '/assets/script/notifications.js', array('jquery'), null, true);

    // Localize script to pass the AJAX URL to JavaScript
    wp_localize_script('notifications-js', 'notifications_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_notifications_assets');

function mark_notification_as_seen() {
    if (!is_user_logged_in() || !isset($_POST['notification_id'])) {
        wp_send_json_error(['message' => 'Unauthorized request']);
    }

    $user_id = get_current_user_id();
    $notification_id = intval($_POST['notification_id']);

    $visited_notifications = get_user_meta($user_id, 'visited_notifications', true);
    if (!$visited_notifications) {
        $visited_notifications = [];
    }

    // Add the notification to visited list
    if (!in_array($notification_id, $visited_notifications)) {
        $visited_notifications[] = $notification_id;
        update_user_meta($user_id, 'visited_notifications', $visited_notifications);
    }

    wp_send_json_success(['message' => 'Notification marked as seen']);
}

add_action('wp_ajax_mark_notification_as_seen', 'mark_notification_as_seen');


function send_birthday_email() {
    $recipient = "parth@codecaste.in"; // Change this to the recipient's email
    $subject = "Happy Birthday!";
    $message = "Wishing you a fantastic birthday filled with joy and happiness!";
    $headers = array('Content-Type: text/html; charset=UTF-8');

    wp_mail($recipient, $subject, $message, $headers);
}

// Schedule the event if not already scheduled
if (!wp_next_scheduled('birthday_email_event')) {
    wp_schedule_event(strtotime('00:00:00'), 'daily', 'birthday_email_event');
}

// Hook the function to the scheduled event
add_action('birthday_email_event', 'send_birthday_email');
