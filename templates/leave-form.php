<?php 
/*
     Template Name: Leave Form                                  
*/
?>
<?php get_header(); ?>
<?php
if (!is_user_logged_in()) {
	echo '<h1 style="text-align:center;color:red;margin-top:500px;" onmouseover="this.style.color=\'white\'" onmouseout="this.style.color=\'red\'">!!! You must be logged in to submit a leave request !!!!</h1>';

	return;
}
?>
<button id="show-leave-form">Add Leave</button><br>
<div id="leave-form-container" style="display: none;">
	<form id="leave-form">
		<label for="leave_title">Leave Title:</label>
		<input type="text" name="leave_title" required><br><br>

		<label for="leave_reason">Reason:</label>
		<textarea name="leave_reason" required></textarea><br><br>

		<label for="leave_start">Start Date:</label>
		<input type="date" name="leave_start" id="leave_start" required><br><br>

		<label for="leave_end">End Date:</label>
		<input type="date" name="leave_end" id="leave_end" required><br><br>

		<label for="leave_day">Leave Type:</label>
		<select name="leave_day" required>
			<option value="Full Day">Full Day</option>
			<option value="Half Day">Half Day</option>
		</select><br><br>

		<input type="hidden" name="leave_nonce" value="<?php echo wp_create_nonce('leave_nonce'); ?>">
		<input type="submit" value="Submit Leave">
	</form>
</div>

<h3 style="margin-top: 20px;">Users Salary</h3>
<table border="1" cellspacing="0" cellpadding="8" style="width: 100%; border-collapse: collapse;">
	<thead>
		<tr style="background-color: #f2f2f2; text-align: left;">
			<th>User</th>
			<th>Salary</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$users = get_users();
		if ($users) :
		foreach ($users as $user) :
		$user_meta = get_userdata($user->ID);
		$user_roles = $user_meta->roles;
		if (in_array('administrator', $user_roles)) {
			continue;
		}
		$salary = get_field('user_salary', 'user_' . $user->ID);
		?>
		<tr>
			<td><?php echo esc_html($user->display_name); ?></td>
			<td><?php echo esc_html($salary ? $salary : 'N/A'); ?></td>
		</tr>
		<?php
		endforeach;
		else :
		?>
		<tr>
			<td colspan="2">No users found.</td>
		</tr>
		<?php endif; ?>
	</tbody>
</table><br><br>

<form method="get" style="margin-bottom: 20px;">
	<h3> Monthly Leaves 	</h3>
	<label for="month">Select Month:</label>
	<select name="month" id="month">
		<option value="all">All Months</option> 
		<?php
		for ($m = 1; $m <= 12; $m++) {
			$month_num = str_pad($m, 2, '0', STR_PAD_LEFT);
			$selected = ($month_num == $selected_month) ? 'selected' : '';
			echo "<option value='$month_num' $selected>" . date("F", strtotime("$month_num/01")) . "</option>";
		}
		?>
	</select>
</form>
<div id="leave-summary">
	<p>Loading...</p>
</div><br><br>
<div id="leave-list"></div>


<?php get_footer(); ?>


