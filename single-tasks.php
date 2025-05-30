<?php
/*
	Template Name: Single Task
*/

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

		<?php while (have_posts()): the_post(); ?>
		<?php
		$current_user_id = get_current_user_id(); // Or the ID of the user you want to display
		$birthdate = get_user_meta($current_user_id, 'birthdate', true);

		if ($birthdate) {
			echo '<p>Birthdate: ' . esc_html($birthdate) . '</p>';
		} else {
			echo '<p>Birthdate not set.</p>';
		}

		$task_author_id = get_post_field('post_author');
		$task_author_name = get_the_author_meta('display_name', $task_author_id);
		$task_status = get_post_status();
		$candidate_name = get_post_field('candidate_name');

		// Handle status change and reassign task to logged-in user
		if(isset($_POST['submit_allocated_time'])) {
			$allocated_time = sanitize_text_field($_POST['allocated_time']);
			update_field('t_allocated_time', $allocated_time, get_the_ID());
		}

		if (isset($_POST['task_status'])) {
			$new_status = sanitize_text_field($_POST['task_status']);
			$post_id = get_the_ID();
			$author_id = get_current_user_id();

			// Update the task status and reassign the post to the logged-in user
			if ($new_status && $author_id) {
				// Update post status
				wp_update_post(array(
					'ID' => $post_id,
					'post_status' => $new_status,
					'post_author' => $author_id, // Reassign post to logged-in user
				));

				// Update task author name
				$task_author_name = get_the_author_meta('display_name', $author_id);

				// Check if candidate_name is a user field
				$user_data = array(
					'ID' => $author_id,
					'user_login' => get_the_author_meta('user_login', $author_id),
					'user_email' => get_the_author_meta('user_email', $author_id),
					'display_name' => $task_author_name,  // Optional: Include display name
				);

				// Update the candidate_name user field with the user data array
				update_field('candidate_name', $user_data, $post_id);
			}
		}
		?>
		<section class="hero">
			<div class="flex justify-start">

				<div class="task-info <?php post_class(); ?>" id="post-<?php the_ID(); ?>">
					<div class="flex align-start justify-between">
						<div class="entry-content">
							<div class="task-assignment">
								<div class="user">
									Assign to: <?php echo esc_html($task_author_name); ?>
								</div>

								<ul class="breadcrumb">
									<?php
									$post_author_id = get_the_author_meta('ID');
									$post_author_nicename = get_the_author_meta('user_nicename');
									$post_title = get_the_title();
									$project = get_field('project');
									$post_id = get_the_ID();
									$project_field = get_post_meta($post_id, 'project', true);

									if ($project_field) {
										$project_link = get_permalink($project_field);
									} else {
										$alert_message = 'No project assigned to you.';
									}

									if (!empty($post_author_id)): ?>
									<li class="breadcrumb-item">
										<a href="<?php echo esc_url("https://testbeds.space/pms/author/{$post_author_nicename}/"); ?>">All Tasks</a></li>
									<li class="breadcrumb-item">
										<a href="<?php echo esc_url($project_link); ?>"
										   onclick="<?php echo !empty($alert_message) ? 'alert(\'' . addslashes($alert_message) . '\'); return false;' : ''; ?>">
											All Projects
										</a>
									</li>
									<?php endif; ?>
								</ul>
							</div>

							<h2><?php the_title(); ?></h2>

							<?php

							$due_date = get_post_meta(get_the_ID(), 'due_date', true);

							if (!empty($due_date)) {

								echo '<h4 class="due-date">Due Date: ' . esc_html(date('F j, Y', strtotime($due_date))) . '</h4>';
							}
							?>

							<div class="status flex align-center justify-start">
								<form id="task-status-form" method="post">
									Status:
									<input type="hidden" name="action" value="update_task_status">
									<select name="task_status" id="task_status" onchange="this.form.submit()">
										<option value="new-ticket" <?php selected($task_status, 'new-ticket'); ?>>New Ticket</option>
										<option value="take-on-board" <?php selected($task_status, 'take-on-board'); ?>>Take On Board</option>
										<option value="in-progress" <?php selected($task_status, 'in-progress'); ?>>In Progress</option>
										<option value="pending" <?php selected($task_status, 'pending'); ?>>Pending Review<t></t></option>
										<option value="done" <?php selected($task_status, 'done'); ?>>Done</option>
									</select>
									<input type="hidden" name="post_id" value="<?php echo esc_attr(get_the_ID()); ?>">
									<input type="hidden" name="author_id" id="author_id" value="<?php echo esc_attr(get_current_user_id()); ?>">
								</form>
							</div>
							<?php if (!empty(get_field('priority'))): ?>
							<div class="priority flex align-center justify-start">
								Priority : <div class="<?php echo get_field('priority'); ?>">
								<?php echo get_field('priority'); ?></div>
							</div>
							<?php endif; ?>

							<form method="post" style="display:none;">
								<?php $selected_post_id = get_field('project'); ?>
								<select id="selected-post" name="selected_post">
									<option value="">Select a project</option>
									<?php
									$query = new WP_Query(array(
										'post_type' => 'projects',
										'posts_per_page' => -1,
									));
									while ($query->have_posts()):
									$query->the_post();
									$post_id = get_the_ID();
									$selected = ($post_id == $selected_post_id) ? 'selected="selected"' : '';
									?>
									<option value="<?php echo $post_id; ?>" <?php echo $selected; ?>>
										<?php the_title(); ?></option>
									<?php endwhile; ?>
									<?php wp_reset_postdata(); ?>
								</select>
								<input type="submit" name="submit_project" value="Submit">
							</form>
							<blockquote>
								<?php the_content(); ?>
							</blockquote>
						</div>
						<div id="timer-container">
							<p id="remaining-time"><?php echo get_field('t_allocated_time'); ?></p>
							<button id="start-timer">Start</button>
							<button id="stop-timer" disabled>Stop</button>
							<p id="time-consumed">Time Consumed: <span id="elapsed-time"><?php echo                                                                        get_post_meta(get_the_ID(),'time_consumed', true);?></span></p>
							<p id="overlap-time">Overlap Time: <span id="overlap-time-value">0:00:00</span>                               </p>
							<ul id="timers-list">
								<?php
								$timers = get_field('timers');
								if ($timers) {
									foreach ($timers as $index => $timer) {
										$start_time = isset($timer['start_timer']) ? $timer['start_timer'] : '';  
										$stop_time = isset($timer['stop_timer']) ? $timer['stop_timer'] : ''; 
										$total_time_consumed = isset($timer['total_time_consumed']) ? $timer['total_time_consumed'] : '';  
										$start_time_formatted = $start_time ? date('d-m-Y H:i:s', strtotime($start_time)) : 'N/A';
										$stop_time_formatted = $stop_time ? date('d-m-Y H:i:s', strtotime($stop_time)) : 'N/A';
										$total_time_consumed = $total_time_consumed ? $total_time_consumed : 'N/A';
										$post_id = get_the_ID();
										echo '<li id="timer-' . $index . '">';
										echo '<strong>Start Time:</strong> ' . esc_html($start_time_formatted) . '<br>';
										echo '<strong>Stop Time:</strong> ' . esc_html($stop_time_formatted) . '<br>';
										echo '<strong>Total Time Consumed:</strong> ' . gmdate('H:i:s', $total_time_consumed) . '<br>';
										echo '<button class="delete-timer" data-post-id="' . $post_id . '" data-timer-index="' . $index . '">Delete</button>'; echo '</li>';
									}  
								} 
								?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
  <?php endwhile; ?>
		 
	<section class="task-comments">
			<h3>Comments</h3>

			<?php
			// Display existing comments
			$comments = get_comments(array(
				'post_id' => get_the_ID(), 
				'status' => 'approve'   
			));

			if ($comments) :
			echo '<ul class="comment-list">';
			foreach ($comments as $comment) :
			?>
			<li class="comment-item">
				<div class="comment-author">
					<strong><?php echo esc_html($comment->comment_author); ?></strong> 
					<span class="comment-meta">on <?php echo esc_html(get_comment_date('F j, Y', $comment)); ?></span>
				</div>
				<div class="comment-content">
					<?php echo esc_html($comment->comment_content); ?>
				</div>
			</li>
			<?php
			endforeach;
			echo '</ul>';
			else :
			echo '<p>No comments yet. Be the first to comment!</p>';
			endif;
			?>
		</section>
		
		<section class="bug-report-form">
			<div class="bugs">
				<?php
				$post_id = get_the_ID();
				$bug_list = get_field( 'bug_list', $post_id );
				if ( $bug_list ) :
				$bug_list = array_reverse( $bug_list );
				foreach ( $bug_list as $bug ) :
				$page_url = $bug['page_url'];
				$screenshot = $bug['screenshot'];
				$bug_description = $bug['bug_description'];
				$status = $bug['status'];
				?>
				<div class="bug-item">
					<h3>Bug Report</h3>
					<p><strong>Page URL:</strong><a href="<?php echo esc_url( $page_url ); ?>"><?php echo esc_url($page_url);?></a></p>
					<p><strong>Screenshot:</strong><a href="<?php echo esc_url( $screenshot ); ?>" target="_blank"><?php echo esc_url($screenshot);?></a></p>
					<p><strong>Bug Description:</strong> <?php echo esc_html( $bug_description ); ?></p>
					<p><strong>Status:</strong> <?php echo esc_html( ucfirst( $status ) ); ?> 
						<button class="edit-bug" 
								data-bug-id="<?php echo esc_attr($bug['bug_id']); ?>" 
								data-page-url="<?php echo esc_url($page_url); ?>"
								data-screenshot="<?php echo esc_url($screenshot); ?>"
								data-bug-description="<?php echo esc_attr($bug_description); ?>"
								data-status="<?php echo esc_attr($status); ?>">
							Edit
						</button>
						<input type="hidden" name="bug_id" id="bug_id">
					</p>
					<?php
					endforeach;
					endif;
					?>
				</div>
				<h2>Bug Report Form</h2>
				<form id="bug-report-form">
					<input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ); ?>">

					<div class="form-field">
						<label for="page_url">Page URL:</label>
						<input type="url" name="page_url" id="page_url" required placeholder="Enter the URL of the page">
					</div>

					<div class="form-field">
						<label for="screenshot">Screenshot:</label>
						<input type="text" name="screenshot" id="screenshot">
					</div>

					<div class="form-field">
						<label for="bug_description">Bug Description:</label>
						<textarea name="bug_description" id="bug_description" rows="5" required placeholder="Describe the bug here..."></textarea>
					</div>
					<div class="form-field">
						<label for="status">Status:</label>
						<select name="status" id="status" required>
							<option value="">Select Status</option>
							<option value="Ready for Review">Ready for Review</option>
							<option value="Not a Bug">Not a Bug</option>
							<option value="complete">Complete</option>
							<option value="Pending">Pending</option>
						</select>
					</div>
					<div class="form-field">
						<button type="submit">Submit Bug Report</button>
					</div>
				</form>
				<div id="bug-report-message"></div>
			</div>
		</section>
		<?php $custom_field = get_field('user_birthdate', 'user_' . get_current_user_id());
		if ($custom_field) {
			echo 'Custom Field: ' . esc_html($custom_field);
		}
		?>
	</main>
</div>



<?php get_footer(); ?>
