<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="ltr">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="profile" href="https://gmpg.org/xfn/11">	
		<?php wp_head(); ?>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	</head>

	<body <?php body_class(); ?>>

		<header>
			<div class="container flex align-center justify-between">
				<a href="<?php echo get_site_url(); ?>" class="site-logo">
					<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/CodeCaste-Logo.png'); ?>" alt="PMS" class="dark-logo">
					<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/CodeCaste-Dark.png'); ?>" alt="PMS" class="light-logo" style="display:none;">
				</a>

				<div class="right-info flex align-center justify-end">
					<a href="javascriptvoid:(0);" id="mode-toggle" class="mode-toggle">
						<svg width="16" height="16" viewBox="0 0 16 16" class="dark" fill="none"
							 xmlns="http://www.w3.org/2000/svg">
							<path
								  d="M8.00232 15.585C5.77968 15.585 3.89013 14.8068 2.33368 13.2503C0.777892 11.6945 0 9.80531 0 7.58266C0 5.93552 0.471137 4.42308 1.41341 3.04535C2.35568 1.66761 3.69274 0.676659 5.42457 0.0724832C5.62863 0.00112919 5.80735 -0.0175431 5.96073 0.0164667C6.11477 0.0498098 6.24181 0.116163 6.34184 0.215525C6.44187 0.314887 6.50555 0.44159 6.53289 0.595635C6.5609 0.751013 6.54023 0.914061 6.47088 1.08478C6.34151 1.40287 6.24748 1.72496 6.18879 2.05106C6.13011 2.37715 6.1011 2.72059 6.10177 3.08136C6.10177 4.85987 6.72395 6.37131 7.96831 7.61567C9.21334 8.8607 10.7251 9.48321 12.5036 9.48321C12.9691 9.48321 13.3952 9.43387 13.782 9.33517C14.1688 9.23648 14.4979 9.15645 14.7693 9.0951C14.914 9.06843 15.0474 9.07176 15.1694 9.1051C15.2894 9.13911 15.3851 9.1978 15.4565 9.28116C15.5358 9.36451 15.5892 9.46721 15.6165 9.58924C15.6445 9.71061 15.6289 9.84965 15.5695 10.0064C15.0927 11.6402 14.1581 12.9786 12.7657 14.0215C11.374 15.0638 9.78617 15.585 8.00232 15.585ZM8.00232 14.5847C9.46941 14.5847 10.7865 14.1802 11.9535 13.3713C13.1205 12.5624 13.9707 11.5081 14.5042 10.2084C14.1708 10.2918 13.8373 10.3585 13.5039 10.4085C13.1705 10.4585 12.8371 10.4835 12.5036 10.4835C10.453 10.4835 8.70653 9.76263 7.26411 8.32088C5.82169 6.87913 5.10081 5.13262 5.10148 3.08136C5.10148 2.74793 5.12649 2.4145 5.1765 2.08107C5.22652 1.74764 5.2932 1.41421 5.37656 1.08078C4.07618 1.61426 3.02154 2.46451 2.21264 3.63152C1.40374 4.79852 0.999623 6.11557 1.00029 7.58266C1.00029 9.51656 1.68382 11.167 3.05088 12.5341C4.41795 13.9012 6.06843 14.5847 8.00232 14.5847Z"
								  fill="currentcolor" />      
						</svg>
						<svg class="light" width="22" height="22" viewBox="0 0 22 22" fill="none"
							 xmlns="http://www.w3.org/2000/svg">
							<path
								  d="M10.9991 15.4436C13.4537 15.4436 15.4436 13.4537 15.4436 10.9991C15.4436 8.54453 13.4537 6.55469 10.9991 6.55469C8.54453 6.55469 6.55469 8.54453 6.55469 10.9991C6.55469 13.4537 8.54453 15.4436 10.9991 15.4436Z"
								  stroke="currentcolor" stroke-linejoin="round" />
							<path
								  d="M19.8889 11H21M1 11H2.11111M11 19.8889V21M11 1V2.11111M17.2856 17.2856L18.0711 18.0711M3.92889 3.92889L4.71444 4.71444M4.71444 17.2856L3.92889 18.0711M18.0711 3.92889L17.2856 4.71444"
								  stroke="currentcolor" stroke-linecap="round" />
						</svg>
					</a>
					<div class="time-date flex align-center justify-start">
						<svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								  d="M13.5 26C20.4036 26 26 20.4036 26 13.5C26 6.59644 20.4036 1 13.5 1C6.59644 1 1 6.59644 1 13.5C1 20.4036 6.59644 26 13.5 26Z"
								  stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M13.5 5.99609V13.4961L18.5 15.9961" stroke="currentcolor" stroke-width="2"
								  stroke-linecap="round" stroke-linejoin="round" />
						</svg>
						<div id="current-time"></div>
					</div>
					<div class="time-date flex align-center justify-end">
						<svg width="27" height="27" viewBox="0 0 27 27" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M21 3.5H6C3.23858 3.5 1 5.73858 1 8.5V21C1 23.7614 3.23858 26 6 26H21C23.7614 26 26 23.7614 26 21V8.5C26 5.73858 23.7614 3.5 21 3.5Z"
								  stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M8.5 1V6M18.5 1V6M1 11H26" stroke="currentcolor" stroke-width="2"
								  stroke-linecap="round" stroke-linejoin="round" />
						</svg>
						<div id="current-date"></div>
					</div>
				</div>
			</div>

<?php
			if (!is_user_logged_in()) {
				return;
			}

			$user_id = get_current_user_id();
			$visited_notifications = get_user_meta($user_id, 'visited_notifications', true);
			if (!$visited_notifications) {
				$visited_notifications = [];
			}

			$args = array(
				'post_type'      => 'tasks',
				'posts_per_page' => 5,
				'orderby'        => 'date',
				'order'          => 'DESC',
			);
			$tasks_query = new WP_Query($args);

			$args2 = array(
				'post_type'      => 'leave',
				'posts_per_page' => 5,
				'orderby'        => 'date',
				'order'          => 'DESC',
			);
			$leave_query = new WP_Query($args2);

			$all_tasks = [];
			$all_leaves = [];
			$birthday_notifications = [];
			$new_user_notifications = [];

			// Fetch Task Notifications
			if ($tasks_query->have_posts()) {
				while ($tasks_query->have_posts()) {
					$tasks_query->the_post();
					$task_id = get_the_ID();
					$task_date = get_the_date('Y-m-d H:i:s'); // Date & Time
					$task_author = get_the_author(); // Author Name
					$all_tasks[] = [
						'id'     => $task_id,
						'title'  => get_the_title(),
						'link'   => get_permalink(),
						'date'   => $task_date,
						'author' => $task_author,
						'seen'   => in_array($task_id, $visited_notifications) 
					];
				}
			}
			wp_reset_postdata();

			// Fetch Leave Notifications
			if ($leave_query->have_posts()) {
				while ($leave_query->have_posts()) {
					$leave_query->the_post();
					$leave_id = get_the_ID();
					$leave_date = get_the_date('Y-m-d H:i:s'); // Date & Time
					$leave_author = get_the_author(); // Author Name
					$all_leaves[] = [
						'id'     => $leave_id,
						'title'  => get_the_title(),
						'link'   => 'https://testbeds.space/pms/leave-form/', 
						'date'   => $leave_date,
						'author' => $leave_author,
						'seen'   => in_array($leave_id, $visited_notifications) 
					];
				}
			}
			wp_reset_postdata();

			$today_date = date('d/m'); 
			$users = get_users(array(
				'fields' => array('ID', 'display_name', 'user_registered'),
			));

			$birthday_found = false;
			foreach ($users as $user) {
				$birthdate = get_field('user_birthdate', 'user_' . $user->ID); // Get birthdate from ACF
				$user_registered = strtotime($user->user_registered);
				$current_time = time();
				$new_user_threshold = 86400; 

				if ($birthdate) {
					$birth_day_month = date('d/m', strtotime($birthdate));
					if ($birth_day_month === $today_date) {
						$birthday_found = true;
						$birthday_notifications[] = [
							'id'     => 'birthday_' . $user->ID,
							'title'  => "🎂 Happy Birthday, " . esc_html($user->display_name) . "!",
							'link'   => '#',
							'date'   => date('d/m/Y', strtotime($birthdate)), 
							'author' => $user->display_name,
							'seen'   => false 
						];
					}
				}

				// Check if user was added in the last 24 hours
				if (($current_time - $user_registered) < $new_user_threshold) {
					$new_user_notifications[] = [
						'id'     => 'new_user_' . $user->ID,
						'title'  => "👤 New User Added: " . esc_html($user->display_name),
						'link'   => '#',
						'date'   => date('d/m/Y', $user_registered), 
						'author' => $user->display_name,
						'seen'   => false 
					];
				}
			}

			if (!$birthday_found) {
				$birthday_notifications[] = [
					'id'     => 'no_birthday',
					'title'  => "No one's birthday today",
					'link'   => '#',
					'date'   => '',
					'author' => '',
					'seen'   => false 
				];
			}

			$total_notifications = count($all_tasks) + count($all_leaves) + count($birthday_notifications) + count($new_user_notifications);
			if ($total_notifications === 0) {
				return; 
			}
			?>

			<div class="notifications-wrapper">
				<select id="notifications-dropdown">
					<option value="" disabled selected>📢 Notifications (<?php echo $total_notifications; ?>)</option>

					<?php foreach ($all_tasks as $task) : ?>
					<option class="notification-item" data-id="<?php echo esc_attr($task['id']); ?>" value="<?php echo esc_url($task['link']); ?>">
						<?php echo $task['seen'] ? '✅' : '🔴'; ?> <?php echo esc_html($task['title']); ?> 
						(By: <?php echo esc_html($task['author']); ?> | <?php echo esc_html($task['date']); ?>)
					</option>
					<?php endforeach; ?>

					<?php foreach ($all_leaves as $leave) : ?>
					<option class="notification-item" data-id="<?php echo esc_attr($leave['id']); ?>" value="<?php echo esc_url($leave['link']); ?>">
						<?php echo $leave['seen'] ? '✅' : '🟠'; ?> <?php echo esc_html($leave['title']); ?> 
						(By: <?php echo esc_html($leave['author']); ?> | <?php echo esc_html($leave['date']); ?>)
					</option>
					<?php endforeach; ?>

					<?php foreach ($birthday_notifications as $birthday) : ?>
					<option class="notification-item birthday-notification" data-id="<?php echo esc_attr($birthday['id']); ?>" value="<?php echo esc_url($birthday['link']); ?>">
						🎉 <?php echo esc_html($birthday['title']); ?><?php if (!empty($birthday['date'])) : ?> (<?php echo esc_html($birthday['date']); ?>)<?php endif; ?>
					</option>
					<?php endforeach; ?>

					<?php foreach ($new_user_notifications as $new_user) : ?>
					<option class="notification-item new-user-notification" data-id="<?php echo esc_attr($new_user['id']); ?>" value="<?php echo esc_url($new_user['link']); ?>">
						🚀 <?php echo esc_html($new_user['title']); ?>
					</option>
					<?php endforeach; ?>
				</select>
			</div>
		</header>
