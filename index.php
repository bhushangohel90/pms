<?php get_header(); ?>

<div id="main-div" class="main-div container mt-5">
	<h1 class="text-center mb-4">Task Masters Dashboard</h1>

	<div class="row">
		<!-- Add Task -->
		<div class="col-lg-4 col-md-6 mb-4">
    <div class="card text-center shadow-sm">
        <div class="card-body">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-calendar-check mb-3" viewBox="0 0 16 16">
                <path d="M3.5 0a.5.5 0 0 1 .5.5V2h8V.5a.5.5 0 0 1 1 0V2h1a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zm-2 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                <path d="M10.854 7.646a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7 10.293l2.646-2.647a.5.5 0 0 1 .708 0z"/>
            </svg>
            <h5 class="card-title">Add Leave</h5>
            <a href="<?php echo get_site_url(); ?>/leave-form/" class="btn btn-primary">Go to Add Leave</a>
        </div>
    </div>
</div>

		<div class="col-lg-4 col-md-6 mb-4">
			<div class="card text-center shadow-sm">
				<div class="card-body">
					<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-plus-circle mb-3" viewBox="0 0 16 16">
						<path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
						<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm0-1A7 7 0 1 1 8 1a7 7 0 0 1 0 14z" />
					</svg>
					<h5 class="card-title">Add Task</h5>
					<a href="<?php echo get_site_url(); ?>/add/" class="btn btn-primary">Go to Add Task</a>
				</div>
			</div>
		</div>

		<!-- PMS Status -->
		<div class="col-lg-4 col-md-6 mb-4">
			<div class="card text-center shadow-sm">
				<div class="card-body">
					<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-list-check mb-3" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M10.854 7.854a.5.5 0 0 0 0-.708l-2-2a.5.5 0 1 0-.708.708L9.5 7.793 8.854 8.5a.5.5 0 0 0 .708.708l1-1zm0 4.146a.5.5 0 0 0 0-.708l-2-2a.5.5 0 1 0-.708.708L9.5 11.793l-.646.707a.5.5 0 0 0 .708.708l1-1z" />
						<path d="M1 3h13a1 1 0 1 1 0 2H1a1 1 0 1 1 0-2zm0 4h13a1 1 0 1 1 0 2H1a1 1 0 1 1 0-2zm0 4h13a1 1 0 1 1 0-2H1a1 1 0 1 1 0-2z" />
					</svg>
					<h5 class="card-title">Projects</h5>
					<a href="<?php echo get_site_url(); ?>/pms-status/" class="btn btn-primary">View Status</a>
				</div>
			</div>
		</div>

		<!-- Champions -->
		<div class="col-lg-4 col-md-6 mb-4">
			<div class="card text-center shadow-sm">
				<div class="card-body">
					<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-people mb-3" viewBox="0 0 16 16">
						<path d="M5.5 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm5 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
						<path fill-rule="evenodd" d="M3 10s1 0 1 2v2s0 1 1 1h8s1 0 1-1v-2s0-2 1-2c.548 0 1 .448 1 1v5c0 .553-.447 1-1 1H2c-.553 0-1-.447-1-1V11c0-.552.447-1 1-1z" />
					</svg>
					<h5 class="card-title">Champion Status</h5>
					<a href="<?php echo get_site_url(); ?>/champions/" class="btn btn-primary">View Champion Status</a>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-6 mb-4">
    <div class="card text-center shadow-sm">
        <div class="card-body">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-file-text mb-3" viewBox="0 0 16 16">
                <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5L10.5 0H4z"/>
                <path d="M9.5 0v3.5H13M5 7h6M5 10h6M5 13h3"/>
            </svg>
            <h5 class="card-title">Salary Slip</h5>
            <a href="<?php echo get_site_url(); ?>/salary-slip/" class="btn btn-primary">View Salary Slip</a>
        </div>
    </div>
</div>

	</div>

	<div class="row">
		<!-- Performance Chart -->
		<div class="col-lg-12">
			<div class="card text-center shadow-sm">
				<div class="card-body">
					<h5 class="card-title">Employee Performance Chart</h5>
					<canvas id="densityChart"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<?php

$args = array(
	'post_type' => 'tasks',
	'posts_per_page' => -1, // Get all tasks
	'post_status' => 'done', // Only completed tasks
);

$query = new WP_Query($args);

// Initialize totals
$total_allocated_time = 0;
$total_time = 0;
$total_tasks = 0; // To count total number of tasks

// To track performance by employee
$employees_performance = array();

// Loop through tasks and calculate total allocated and consumed times
foreach ($query->posts as $post) {
	$allocated = get_field('t_allocated_time', $post->ID); // Get allocated time
	$total_allocated_time += $allocated;
	$total_tasks++; // Increment task count

	// Get timers for time consumed
	$timers = get_field('timers', $post->ID);
	if ($timers) {
		foreach ($timers as $timer) { 
			$total_time_consumed = $timer['total_time_consumed'];
			$user = isset($timer['user']) ? $timer['user']['nickname'] : '';

			if ($total_time_consumed) {
				$total_time += $total_time_consumed; // Add consumed time

				// Track performance for each employee
				if (!isset($employees_performance[$user])) {
					$employees_performance[$user] = [
						'allocated' => 0,
						'consumed' => 0
					];
				}

				// Add allocated time and consumed time for the employee
				$employees_performance[$user]['allocated'] += $allocated;
				$employees_performance[$user]['consumed'] += $total_time_consumed;
			}
		}
	}
}

// Calculate overall performance ratio
$performance = ($total_allocated_time > 0) ? ($total_time / $total_allocated_time) : 0;
$performance_percentage = ($performance * 100); // Convert to percentage

// Prepare data for the chart
$total_allocated_time_formatted = number_format($total_allocated_time, 2);
$total_time_formatted = number_format($total_time, 2);
$performance_percentage_formatted = number_format($performance_percentage, 2);

// Prepare performance by employee data
$employee_labels = [];
$employee_performance_data = [];
foreach ($employees_performance as $user => $data) {
	$employee_labels[] = $user;
	$employee_performance = ($data['allocated'] > 0) ? ($data['consumed'] / $data['allocated']) * 100 : 0;
	$employee_performance_data[] = number_format($employee_performance, 2);
}


$total_tasks_formatted = $total_tasks;
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	// Chart.js code for employee performance visualization
	var densityCanvas = document.getElementById("densityChart");

	// Initialize datasets
	var datasets = [];
	var allocatedTimeData = [];
	var consumedTimeData = [];
	var performanceData = [];

	// PHP data for employee performance
	<?php
	// Generate the employee dataset for Chart.js
	foreach ($employees_performance as $user => $data) { ?>
	// Add individual employee data
	datasets.push({
		label: '<?php echo $user; ?>', // Employee Name
		data: [
			<?php echo number_format($data['allocated'], 2); ?>, // Allocated Time
			<?php echo number_format($data['consumed'], 2); ?>, // Consumed Time
			<?php 
														// Calculate performance for the employee
														$employee_performance = ($data['allocated'] > 0) ? ($data['consumed'] / $data['allocated']) * 100 : 0;
														echo number_format($employee_performance, 2);
			?> // Performance Percentage
		],
		backgroundColor: 'rgba(54, 162, 235, 0.6)', // Color for Allocated Time
		borderColor: 'rgba(54, 162, 235, 1)', // Border color for Allocated Time
		borderWidth: 1
	});
	allocatedTimeData.push(<?php echo number_format($data['allocated'], 2); ?>);
	consumedTimeData.push(<?php echo number_format($data['consumed'], 2); ?>);
	performanceData.push(<?php 
														$employee_performance = ($data['allocated'] > 0) ? ($data['consumed'] / $data['allocated']) * 100 : 0;
														echo number_format($employee_performance, 2);
		?>);
	<?php } ?>

	var planetData = {
		labels: <?php echo json_encode(array_keys($employees_performance)); ?>, // Employee Names
		datasets: datasets // Employee performance datasets
	};

	var chartOptions = {
		scales: {
			xAxes: [{
				barPercentage: 1,
				categoryPercentage: 0.6
			}],
			yAxes: [{
				id: "y-axis-time",
				ticks: {
					beginAtZero: true
				}
			}]
		}
	};
	var barChart = new Chart(densityCanvas, {
		type: 'bar',
		data: planetData,
		options: chartOptions
	});
</script>



<?php get_footer(); ?>
