<?php
/*
Template Name: PMS Status
*/
get_header(); ?>

<section class="pms-status ticket">
	<div class="container">
		<div class="row">
			<?php
			$project_query = new WP_Query(array(
				'post_type' => 'projects',
				'posts_per_page' => -1,
			));
			if ($project_query->have_posts()) :
			while ($project_query->have_posts()) : $project_query->the_post();
			$project_id = get_the_ID();
			$project_title = get_the_title();
			$task_count = 0;
			$total_time_consumed = 0;
			$allocated_time = get_field('p_allocated_time') ? get_field('p_allocated_time') : '00:00:00';
			?>
			<div class="col-lg-4 col-md-6 mb-4">
				<div class="card project-card">
					<div class="card-body">
						<a class="pms" href="<?php the_permalink(); ?>">
							<h2 class="project-title"><?php echo $project_title; ?></h2>
						</a>
						<hr>
						<?php if (!empty($allocated_time) && $allocated_time > 0) : ?>
						<p><strong>Allocated Time:</strong> <?php echo $allocated_time; ?></p>
						<?php endif; ?>

						<!-- Fetch associated tasks -->
						<?php
						$associated_tasks = new WP_Query(array(
							'post_type' => 'tasks',
							'posts_per_page' => -1,
							'meta_query' => array(
								array(
									'key' => 'project',
									'value' => $project_id,
									'compare' => '=',
								),
							),
						));
						if ($associated_tasks->have_posts()) :
						echo '<p><strong>Associated Tasks:</strong></p>';
						echo '<ul class="list-group">';
						while ($associated_tasks->have_posts()) : $associated_tasks->the_post();
						$author_name = get_the_author_meta('display_name', get_post_field('post_author'));
						$task_status = get_post_status();
						$task_count++;

						// Calculate the time consumed for each task
						$timers = get_field('timers', get_the_ID());
						if ($timers) {
							foreach ($timers as $timer) {
								$start_time = new DateTime($timer['start_timer']);
								$stop_time = new DateTime($timer['stop_timer']);
								if (!empty($timer['stop_timer'])) 
								{
									$time_consumed = $start_time->diff($stop_time)->format('%H:%I:%S');
									$total_time_consumed += strtotime($time_consumed) - strtotime('00:00:00');
								}
							}
						}
						?>
						<li class="list-group-item">
							<a href="<?php the_permalink(); ?>">
								<?php echo $task_count .'. '. esc_html(get_the_title()) . ' - ' . ucfirst($task_status) . ' (User: ' . esc_html($author_name) . ')'; ?>
							</a>
						</li>
						<?php
						endwhile;
						echo '</ul>';
						else :
						echo '<p>No tasks found for this project.</p>';
						endif;
						wp_reset_postdata();
						?>
					</div>
					<div class="card-footer d-flex justify-content-between align-items-center gap-2">
						<div class="d-flex justify-content-between align-items-center gap-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
								<path d="M8 3.5a.5.5 0 0 1 .5.5v4.25H11a.5.5 0 0 1 0 1H7.5a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5z"></path>
								<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm0-1A7 7 0 1 1 8 1a7 7 0 0 1 0 14z"></path>
							</svg>
							<span>Total Time: <?php echo gmdate('H:i:s', $total_time_consumed); ?></span>
						</div>
						<div class="d-flex justify-content-between align-items-center gap-1">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
								<path d="M8 3.5a.5.5 0 0 1 .5.5v4.25H11a.5.5 0 0 1 0 1H7.5a.5.5 0 0 1-.5-.5V4a.5.5 0 0 1 .5-.5z"></path>
								<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm0-1A7 7 0 1 1 8 1a7 7 0 0 1 0 14z"></path>
							</svg>
							<span>Remaining: 
								<?php 
								$allocated_time_seconds = strtotime($allocated_time) - strtotime('00:00:00');
								$remaining_time = max(0, $allocated_time_seconds - $total_time_consumed);
								echo gmdate('H:i:s', $remaining_time); 
								?>
							</span>
						</div>
					</div>
				</div>
			</div>
			<?php
			endwhile;
			wp_reset_postdata();
			else :
			echo '<div class="col-12"><p>No projects found.</p></div>';
			endif;
			?>
		</div>
	</div>
</section>

<?php get_footer(); ?>