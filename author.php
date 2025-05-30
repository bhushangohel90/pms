<?php get_header(); ?>

<!-- Global Date Filter -->
<?php
$current_user = get_userdata(get_the_author_meta('ID'));

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';


$new_ticket_args = array(
	'post_type' => 'tasks',
	'posts_per_page' => -1, 
	'post_status' => 'new-ticket', 
);


if ($start_date && $end_date) {
	$new_ticket_args['date_query'] = array(
		'after' => $start_date,
		'before' => $end_date,
		'inclusive' => true,
	);
}

$new_ticket_tasks = new WP_Query($new_ticket_args);


$task_status = isset($_GET['task_status']) ? $_GET['task_status'] : 'all';
$user_filter = isset($_GET['user_filter']) ? $_GET['user_filter'] : 'all';


$is_admin = current_user_can('administrator');


$args = array(
	'post_type' => 'tasks',
	'posts_per_page' => -1,
	'orderby' => 'date',
	'order' => 'ASC'
);


if ($is_admin && $user_filter !== 'all') {
	$args['author'] = $user_filter;
} elseif (!$is_admin) {
	$args['author'] = $current_user->ID;
}


if ($task_status != 'all') {
	$args['post_status'] = $task_status;
}


if ($start_date && $end_date) {
	$args['date_query'] = array(
		'after' => $start_date,
		'before' => $end_date,
		'inclusive' => true,
	);
}

$assigned_tasks = new WP_Query($args);


$project_args = array(
	'post_type' => 'projects',
	'posts_per_page' => -1,
	'author' => $current_user->ID,
	'orderby' => 'date',
	'order' => 'ASC'
);

if ($start_date && $end_date) {
	$project_args['date_query'] = array(
		'after' => $start_date,
		'before' => $end_date,
		'inclusive' => true,
	);
}

$assigned_projects = new WP_Query($project_args);

?>

<div class="container py-4">
	<div class="row">
		<div class="col-12">
			<!-- User Header -->
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h1 class="h3 mb-0"><?php echo esc_html($current_user->display_name); ?></h1>
			</div>

			<div class="card mb-4 card-body">
				<div class="d-flex gap-3">

					<!-- Date Filter -->
					<form id="date-range-filter" method="GET" class="d-flex gap-2 align-items-center">
						<div class="input-group">
							<span class="input-group-text">From</span>
							<input type="date" class="form-control" id="start_date" name="start_date" 
								   value="<?php echo isset($_GET['start_date']) ? esc_attr($_GET['start_date']) : ''; ?>">
						</div>
						<div class="input-group">
							<span class="input-group-text">To</span>
							<input type="date" class="form-control" id="end_date" name="end_date" 
								   value="<?php echo isset($_GET['end_date']) ? esc_attr($_GET['end_date']) : ''; ?>">
						</div>
						<button type="submit" class="btn btn-primary">Filter</button>
					</form>

					<!-- Task Status Filter -->
					<div class="card-body">
						<form id="task-status-form" method="GET">
							<div class="d-flex gap-3 align-items-center">
								<label for="task_status" class="form-label">Status</label>
								<select name="task_status" id="task_status" class="form-select" onchange="this.form.submit()">
									<option value="all" <?php selected(isset($_GET['task_status']) ? $_GET['task_status'] : '', 'all'); ?>>All</option>
									<option value="new-ticket" <?php selected(isset($_GET['task_status']) ? $_GET['task_status'] : '', 'new-ticket'); ?>>New Ticket</option>
									<option value="take-on-board" <?php selected(isset($_GET['task_status']) ? $_GET['task_status'] : '', 'take-on-board'); ?>>Take On Board</option>
									<option value="in-progress" <?php selected(isset($_GET['task_status']) ? $_GET['task_status'] : '', 'in-progress'); ?>>In Progress</option>
									<option value="pending" <?php selected(isset($_GET['task_status']) ? $_GET['task_status'] : '', 'pending'); ?>>Pending Review</option>
									<option value="done" <?php selected(isset($_GET['task_status']) ? $_GET['task_status'] : '', 'done'); ?>>Done</option>
								</select>
							</div>

							<?php if (current_user_can('administrator')) : ?>
							<div class="col-md-3">
								<label for="user_filter" class="form-label">User</label>
								<select name="user_filter" id="user_filter" class="form-select" onchange="this.form.submit()">
									<option value="all" <?php selected(isset($_GET['user_filter']) ? $_GET['user_filter'] : '', 'all'); ?>>All Users</option>
									<?php $users = get_users();
									foreach ($users as $user) {
										$selected = isset($_GET['user_filter']) && $_GET['user_filter'] == $user->ID ? 'selected' : '';
										echo '<option value="' . esc_attr($user->ID) . '" ' . $selected . '>' . esc_html($user->display_name) . '</option>';
									} ?>
								</select>
							</div>
							<?php endif; ?>
						</form>
					</div>
				</div>
			</div>

			<!-- Tasks Grid -->
			<?php if ($new_ticket_tasks->have_posts()) { ?>
			<div class="row">
				<div class="col-md-12 mb-4">
					<div class="card h-100">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="card-title mb-0">New Ticket Tasks</h5>

						</div>
						<div class="card-body">
							<div class="list-group list-group-flush">
								<?php while ($new_ticket_tasks->have_posts()) {
	$new_ticket_tasks->the_post();
	$task_status = get_post_status(); ?>
								<a href="<?php the_permalink(); ?>" class="list-group-item list-group-item-action">
									<div class="d-flex w-100 justify-content-between">
										<h6 class="mb-1"><?php echo esc_html(get_the_title()); ?></h6>
										<small class="badge bg-primary"><?php echo ucfirst($task_status); ?></small>
									</div>
									<small class="text-muted"><?php echo get_the_date('F j, Y \a\t g:i a'); ?></small>
								</a>
								<?php } wp_reset_postdata(); ?>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>

				<!-- Assigned Tasks -->
				<div class="col-md-12 mb-4">
					<div class="card h-100">
						<div class="card-header d-flex justify-content-between align-items-center">
							<h5 class="card-title mb-0">Assigned Tasks</h5>
							<div class="text-end">
								<span class="badge bg-secondary"><?php echo $assigned_tasks->found_posts;?>Tasks</span>
							</div>
						</div>
						<div class="card-body">
							<div class="list-group list-group-flush">
								<?php if ($assigned_tasks->have_posts()) {
	while ($assigned_tasks->have_posts()) {
		$assigned_tasks->the_post();
		$task_status = get_post_status();

		$allocated_time = get_field('t_allocated_time');
		$timers = get_field('timers', get_the_ID());

		$totalTimeConsumed = 0;
		$total_time_consumed = 0;

		// Ensure timers is an array before proceeding with the loop
		if (is_array($timers)) {
			foreach ($timers as $index => $timer) {
				$time_consumed = isset($timer['total_time_consumed']) ? $timer['total_time_consumed'] : 0;
				$totalTimeConsumed += $time_consumed;
			}


			$allocated_time_seconds = 0;
			if ($allocated_time) {
				// Explode the allocated time string (H:i:s format)
				list($hours, $minutes, $seconds) = explode(":", $allocated_time);
				$allocated_time_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;
			}

			// Calculate and display overlap time (difference between allocated time in seconds and total time consumed)
			if ($allocated_time_seconds > 0) {
				$overlap_time = $totalTimeConsumed - $allocated_time_seconds;
				if ($overlap_time > 0) {
					echo '<h5>Overlap Time: <strong>' . gmdate('H:i:s', $overlap_time) . '</strong></h5>';
				} 
			} else {
				echo '<h5>Overlap Time: Not Available (Allocated time not set or invalid)</h5>';
			}
			// Add to the total_time_consumed for all tasks
			$total_time_consumed += $totalTimeConsumed;
		} else {
			echo '<h5>No timers found for this task.</h5>';
		}

		$average_time_consumed = $total_time_consumed / $allocated_time;                                                                              
								?>
								<a href="<?php the_permalink(); ?>" class="list-group-item list-group-item-action">
									<div class="d-flex w-100 justify-content-between mb-1">
										<h6 class="mb-1"><?php echo esc_html(get_the_title()); ?></h6>
										<small class="badge bg-primary"><?php echo ucfirst($task_status); ?></small>
									</div>
									<div class="d-flex justify-content-between align-items-center">
										<small class="text-muted"><?php echo get_the_date('F j, Y'); ?></small>
										<small class="text-muted">Allocated: <?php echo $allocated_time ? esc_html($allocated_time) : 'Not Set'; ?></small>
									</div>
									<?php if (isset($totalTimeConsumed)): ?>
									<div class="progress mt-2" style="height: 5px;">
										<div class="progress-bar" role="progressbar"                                        style="width: <?php echo ($allocated_time_seconds > 0) ? min(($totalTimeConsumed / $allocated_time_seconds) * 100, 100) : 100; ?>%" 
											 aria-valuenow="<?php echo $totalTimeConsumed; ?>" 
											 aria-valuemin="0" 
											 aria-valuemax="<?php echo $allocated_time_seconds; ?>">
										</div>
									</div>
									<?php endif; ?>  
									<div class="text-end">
										<div class="small text-muted">Average Time: <?php echo gmdate('H:i:s', $average_time_consumed); ?></div>									</div>
								</a>
								<?php  } wp_reset_postdata();
} else {
	echo '<p class="text-muted">No tasks found for the selected criteria.</p>';
} ?>
							</div>
						</div>
					</div>
				</div>
				<!-- Projects Section -->
				<div class="col-md-12 mb-4">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title mb-0">Assigned Projects</h5>
						</div>
						<div class="card-body">
							<div class="list-group list-group-flush">
								<?php
								if ($assigned_projects->have_posts()) {
									while ($assigned_projects->have_posts()) {
										$assigned_projects->the_post();
								?>
								<a href="<?php the_permalink(); ?>" class="list-group-item list-group-item-action">
									<h6 class="mb-1"><?php echo esc_html(get_the_title()); ?></h6>
								</a>
								<?php
									}
									wp_reset_postdata();
								} else {
									echo '<p class="text-muted">No projects found for the current user.</p>';
								}
								?>
							</div>
						</div>
					</div>
				</div>            
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>