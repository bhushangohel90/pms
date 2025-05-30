<?php
/*
Template Name: Users Data
*/

get_header(); 

$users = get_users(array(
	'exclude' => array(1), 
	'role__not_in' => array('Administrator')
));

?>
<section class="user-data container mt-5">
	<h1 class="text-center mb-5"><?php echo get_the_title(); ?></h1>
	<div class="row">
		<?php foreach ($users as $user): 
		$user_id = $user->ID;
		$user_name = $user->display_name;
		$user_email = $user->user_email;

		?>
		<div class="col-lg-4 col-md-6 mb-4">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-body">
					<h2 class="h5 card-title"><?php echo esc_html($user_name); ?></h2>
					<p class="text-muted mb-4"><strong>Email:</strong> <?php echo esc_html($user_email); ?></p>

					<!-- Task List -->
					<ul class="list-group list-group-flush">

						<?php
						$user_tasks = new WP_Query(array(
							'post_type' => 'tasks',
							'posts_per_page' => 5,
							'author' => $user_id,
						));

						$total_tasks = 0;
						$total_time_consumed = 0; 
						$total_allocated_time = 0;

						if ($user_tasks->have_posts()):
						while ($user_tasks->have_posts()): $user_tasks->the_post();
						$allocated_time = (float) (get_field('t_allocated_time') ?? 0);
						$total_time = 0;

						if (have_rows('timers')):
						while (have_rows('timers')): the_row();
						$task_total_time = get_sub_field('total_time_consumed');
						$total_time += (float)$task_total_time;
						endwhile;
						endif;

						$total_tasks++;
						$total_time_consumed += $total_time;
						$total_allocated_time += $allocated_time * 3600; // Convert hours to seconds
						?>
						<li class="list-group-item d-flex">
							<div class="me-3">
								<strong><?php echo $total_tasks; ?></strong>
							</div>
							<div>
								<strong><?php echo esc_html(get_the_title()); ?></strong><br>
								<small class="text-muted">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
										<path d="M8 3.5a.5.5 0 0 1 .5.5v4.25H11a.5.5 0 0 1 0 1H7.5a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5z"></path>
										<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm0-1A7 7 0 1 1 8 1a7 7 0 0 1 0 14z"></path>
									</svg>
									Allocated: <?php echo esc_html($allocated_time); ?> hrs | Consumed: <?php echo $total_time; ?>
								</small>
							</div>
						</li>
						<?php endwhile; else: ?>
						<li class="list-group-item text-muted">No tasks found for this user.</li>
						<?php endif; wp_reset_postdata(); ?>
					</ul>
				</div>

				<!-- Footer Section -->
				<div class="card-footer d-flex justify-content-between align-items-center">
					<div class="d-flex justify-content-between align-items-center gap-1">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
							<path d="M8 3.5a.5.5 0 0 1 .5.5v4.25H11a.5.5 0 0 1 0 1H7.5a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5z"></path>
							<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm0-1A7 7 0 1 1 8 1a7 7 0 0 1 0 14z"></path>
						</svg>
						<span>Total Tasks: <?php echo $total_tasks; ?></span>
					</div>
					<div class="d-flex justify-content-between align-items-center gap-1">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
							<path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm4-3a4 4 0 1 1-8 0 4 4 0 0 1 8 0z"/>
							<path fill-rule="evenodd" d="M1 14s1-1.5 7-1.5S15 14 15 14H1zm8-1.465a32.27 32.27 0 0 0-2 .465h4a32.27 32.27 0 0 0-2-.465z"/>
						</svg>
						<span>
							Performance: 
							<?php 
							$performance = 0;
							if ($total_allocated_time > 0) {
								$performance = (($total_allocated_time - $total_time_consumed) / $total_allocated_time) * 100;
							}
							
							echo number_format($performance, 2, '.', '') . '%'; 
							?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</section>

<?php get_footer(); ?>