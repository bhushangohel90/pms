const modeToggle = document.getElementById("mode-toggle");
const body = document.body;
function applyTheme(theme) {
	if (theme === "dark") {
		document.documentElement.classList.add("dark");
		modeToggle.classList.remove("light");
		modeToggle.classList.add("dark");
		localStorage.theme = "dark";
	} else {
		document.documentElement.classList.remove("dark");
		modeToggle.classList.remove("dark");
		modeToggle.classList.add("light");
		localStorage.theme = "light";
	}
}
if (
	localStorage.theme === "dark" ||
	(!("theme" in localStorage) &&
	 window.matchMedia("(prefers-color-scheme: dark)").matches)
) {
	applyTheme("dark");
} else {
	applyTheme("light");
}
modeToggle.addEventListener("click", () => {
	if (localStorage.theme === "dark") {
		applyTheme("light");
	} else {
		applyTheme("dark");
	}
});

document.addEventListener("DOMContentLoaded", function () {
	function updateTime() {
		var currentDate = new Date();
		var hours = currentDate.getHours().toString().padStart(2, "0");
		var minutes = currentDate.getMinutes().toString().padStart(2, "0");
		var seconds = currentDate.getSeconds().toString().padStart(2, "0");
		var currentTime = hours + ":" + minutes + ":" + seconds;
		var months = [
			"Jan",
			"Feb",
			"Mar",
			"Apr",
			"May",
			"Jun",
			"Jul",
			"Aug",
			"Sep",
			"Oct",
			"Nov",
			"Dec",
		];
		var monthAbbreviation = months[currentDate.getMonth()];
		var day = currentDate.getDate().toString().padStart(2, "0");
		var year = currentDate.getFullYear();
		var currentDateFormatted = day + " " + monthAbbreviation + " " + year;
		document.getElementById("current-time").textContent = currentTime;
		document.getElementById("current-date").textContent = currentDateFormatted;
	}
	updateTime();
	setInterval(updateTime, 1000);
});
