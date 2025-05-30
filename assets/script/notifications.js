document.addEventListener("DOMContentLoaded", function () {
	var dropdown = document.getElementById("notifications-dropdown");

	if (!dropdown) return;
	dropdown.addEventListener("change", function () {
		var selectedOption = this.options[this.selectedIndex];
		var selectedValue = selectedOption.value;
		var notificationId = selectedOption.getAttribute("data-id");

		if (selectedValue) {

			fetch(notifications_ajax.ajax_url, { 
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded"
				},
				body: "action=mark_notification_as_seen&notification_id=" + encodeURIComponent(notificationId)
			})
				.then(response => response.json())
				.then(data => {
				if (data.success) {

					selectedOption.textContent = '✅ ' + selectedOption.textContent.slice(2);
				} else {
					console.error("Failed to mark notification as seen.");
				}
				window.location.href = selectedValue;
			})
				.catch(error => console.error("Error:", error));
		}
	});
});
