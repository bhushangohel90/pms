jQuery(document).ready(function($) {
	var timerInterval;
	var startTime;
	var stopTime;
	var elapsedTime = timerVars.total_time_consumed;
	var allocatedTime = timerVars.allocated_time; 
	var remainingTime = allocatedTime - elapsedTime; // Remaining time in seconds

	// Update the display immediately based on the data
	updateTimerDisplay();

	// Retrieve and display stored timers from localStorage
	var storedTimers = JSON.parse(localStorage.getItem('timers')) || [];
	storedTimers.forEach(function(timer) {
		appendTimerData(timer.startTime, timer.stopTime, timer.consumedTime);
	});
	function calculateOverlapTime() {
		var overlapTime = Math.max(0, elapsedTime - allocatedTime); // Ensure overlap time doesn't go negative
		$('#overlap-time-value').text(formatTimeFromSeconds(overlapTime));

		// If overlap time is zero, hide the overlap time display
		if (overlapTime === 0) {
			$('#overlap-time').hide();
		} else {
			$('#overlap-time').show();
		}
	}
	function updateTimerDisplay() {
		var hours = Math.floor(elapsedTime / 3600);
		var minutes = Math.floor((elapsedTime % 3600) / 60);
		var seconds = elapsedTime % 60;
		$('#elapsed-time').text(formatTime(hours, minutes, seconds));
		var remainingHours = Math.floor(remainingTime / 3600);
		var remainingMinutes = Math.floor((remainingTime % 3600) / 60);
		var remainingSeconds = remainingTime % 60;
		$('#remaining-time').text(formatTime(remainingHours, remainingMinutes, remainingSeconds));
		calculateOverlapTime();
	}
	$('#start-timer').click(function() {
		$(this).prop('disabled', true); // Disable the start button
		$('#stop-timer').prop('disabled', false); // Enable the stop button

		// Set startTime only once, when the timer starts
		if (!startTime) {
			startTime = Date.now();
			$.ajax({
				url: timerVars.ajax_url,
				type: 'POST',
				data: {
					action: 'save_start_time',
					nonce: timerVars.nonce,
					post_id: timerVars.post_id,
					start_time: startTime
				},
				success: function(response) {
					if (!response.success) {
						alert('Error saving start time: ' + response.data.message);
					}
				},
				error: function(xhr, status, error) {
					alert('Error: ' + error);
				}
			});
		}
		timerInterval = setInterval(function() {
			elapsedTime++;
			remainingTime--;
			updateTimerDisplay();
			calculateOverlapTime();
		}, 1000);
	});
	$('#stop-timer').click(function() {
		clearInterval(timerInterval); 
		$(this).prop('disabled', true);
		$('#start-timer').prop('disabled', false);
		stopTime = Date.now();
		var consumedTime = Math.floor((stopTime - startTime) / 1000); 
		var timerData = {
			startTime: startTime,
			stopTime: stopTime,
			consumedTime: consumedTime,
			remainingTime: remainingTime
		};
		$.ajax({
			url: timerVars.ajax_url,
			type: 'POST',
			data: {
				action: 'save_timer_data',
				nonce: timerVars.nonce,
				post_id: timerVars.post_id,
				start_time: startTime,
				stop_time: stopTime,
				total_time_consumed: elapsedTime,
				consumed_time: consumedTime,
				remaining_time: remainingTime
			},
			success: function(response) {
				if (response.success) {
					appendTimerData(startTime, stopTime, consumedTime);
				} else {
					alert('Error saving time data: ' + response.data.message);
				}
			},
			error: function(xhr, status, error) {
				alert('Error: ' + error);
			}
		});
	});

	
	function appendTimerData(startTime, stopTime, consumedTime) {
		var startDate = new Date(startTime);
		var stopDate = new Date(stopTime);

		// Format the date and time (YYYY-MM-DD HH:mm:ss)
		var startFormatted = formatDateTime(startDate);
		var stopFormatted = formatDateTime(stopDate);
		var consumedFormatted = formatTimeFromSeconds(consumedTime);

		var newTimer = `
			<li>
			<strong>Start Time:</strong> ${startFormatted}<br>
			<strong>Stop Time:</strong> ${stopFormatted}<br>
			<strong>Total Time Consumed:</strong> ${consumedFormatted}<br>
			<button class="delete-timer" data-start-time="${startFormatted}" data-stop-time="${stopFormatted}" 
             data-consumed-time="${consumedFormatted}">Delete</button>
			</li>`;

		$('#timers-list').append(newTimer); // Append to the list
	}


	function formatTime(hours, minutes, seconds) {
		return (hours < 10 ? '0' : '') + hours + ':' +
			(minutes < 10 ? '0' : '') + minutes + ':' +
			(seconds < 10 ? '0' : '') + seconds;
	}

	function formatTimeFromSeconds(seconds) {
		var hours = Math.floor(seconds / 3600);
		var minutes = Math.floor((seconds % 3600) / 60);
		var remainingSeconds = seconds % 60;
		return formatTime(hours, minutes, remainingSeconds);
	}


	function formatDateTime(date) {
		var year = date.getFullYear();
		var month = ('0' + (date.getMonth() + 1)).slice(-2); 
		var day = ('0' + date.getDate()).slice(-2);
		var hours = ('0' + date.getHours()).slice(-2);
		var minutes = ('0' + date.getMinutes()).slice(-2);
		var seconds = ('0' + date.getSeconds()).slice(-2);

		return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
	}
});


jQuery(document).ready(function($) {
	$('.delete-timer').on('click', function() {
		var $button = $(this);
		var postId = $button.data('post-id');
		var timerIndex = $button.data('timer-index');
		var nonce = deleteTimerData.nonce;
		// Directly make the AJAX request without confirmation
		$.ajax({
			url: deleteTimerData.ajax_url, // Use the localized ajax_url variable
			type: 'POST',
			data: {
				action: 'delete_timer', // Custom action defined in PHP
				post_id: postId,
				timer_index: timerIndex,
				nonce: nonce
			},
			success: function(response) {
				if (response.success) {
					// On success, remove the timer from the frontend
					$('#timer-' + timerIndex).fadeOut(function() {
						$(this).remove();
					});
				} else {

				}
			},
			error: function() {
				alert('An error occurred.');
			}
		});
	});
});
