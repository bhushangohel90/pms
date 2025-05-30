document.addEventListener("DOMContentLoaded", function () {
	const leaveFormContainer = document.getElementById("leave-form-container");
	const showLeaveFormBtn = document.getElementById("show-leave-form");
	const leaveForm = document.getElementById("leave-form");
	const leaveList = document.getElementById("leave-list");


	showLeaveFormBtn.addEventListener("click", function () {
		leaveFormContainer.style.display = leaveFormContainer.style.display === "none" ? "block" : "none";
	});


	leaveForm.addEventListener("submit", function (e) {
		e.preventDefault();

		let formData = new FormData(leaveForm);
		formData.append("action", "submit_leave");

		fetch(myAjax.ajaxurl, {
			method: "POST",
			body: formData
		})
			.then(response => response.json())
			.then(data => {
			if (data.success) {
				leaveForm.reset(); 
				loadLeaveList(); 
			} else {
				alert("Error: " + data.message);
			}
		})
			.catch(error => console.error("Error:", error));
	});


	function loadLeaveList() {
		fetch(myAjax.ajaxurl + "?action=get_leave_list")
			.then(response => response.json())
			.then(data => {
			if (data.success) {
				leaveList.innerHTML = data.data;
			} else {
				leaveList.innerHTML = "<p>No leave records found.</p>";
			}
		})
			.catch(error => {
			console.error("Error:", error);
			leaveList.innerHTML = "<p>Error loading leave list. Please try again later.</p>";
		});
	}


	loadLeaveList();
});


document.addEventListener("DOMContentLoaded", function () {
	const allSalarySlipResult = document.getElementById("all-salary-slip-result");

	function loadSalarySlips() {
		fetch(myAjax.ajaxurl + "?action=generate_all_salary_slip")
			.then(response => response.json())
			.then(data => {
			if (data.success) {
				allSalarySlipResult.innerHTML = data.data;
			} else {
				allSalarySlipResult.innerHTML = "<p>Error loading salary slips. Please try again later.</p>";
			}
		})
			.catch(error => {
			console.error("Error:", error);
			allSalarySlipResult.innerHTML = "<p>Error loading salary slips. Please try again later.</p>";
		});
	}
	loadSalarySlips();
});

function printSalarySlip(userName, salary, totalWorkingDays, presentDays, basicSalary, da, travel, conveyance, special, grossSalary, safetyDeposit, professionalTax, netSalary, month, year) {
    // Open a new window for printing
    var printWindow = window.open('', 'Salary Slip', 'height=600,width=800');

    // Write the HTML structure into the print window
    printWindow.document.write('<html><head><title>Salary Slip - ' + month + ' ' + year + '</title>');
    printWindow.document.write('<style>body { font-family: Arial, sans-serif; padding: 20px; line-height: 1.6; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 10px; }');
    printWindow.document.write('table, th, td { border: 1px solid black; padding: 8px; text-align: left; }</style>');
    printWindow.document.write('</head><body>');

    // Insert the logo with an ID to ensure it loads before printing
    printWindow.document.write('<div style="text-align:center; margin-bottom:20px;">');
    printWindow.document.write('<img id="salarySlipLogo" src="http://testbeds.space/pms/wp-content/uploads/2025/02/Screenshot-from-2025-02-05-17-27-24.png" style="max-width:1800px; height:auto;">');
    printWindow.document.write('</div>');

    // Insert the structured salary slip content
    printWindow.document.write('<h1 style="text-align:center; text-decoration: underline;">Salary Slip of ' + month + ' ' + year + '</h1>');
    printWindow.document.write('<p><strong>Name:</strong> ' + userName + '</p>');
    printWindow.document.write('<p><strong>Salary in Amount:</strong> ' + formatCurrency(salary) + '</p>');
    printWindow.document.write('<p><strong>Total Working Days:</strong> ' + totalWorkingDays + '</p>');
    printWindow.document.write('<p><strong>Present Days:</strong> ' + presentDays + '</p>');

    // Earnings Table
    printWindow.document.write('<table>');
    printWindow.document.write('<tr><td style="font-size: 18px; font-weight: bold;">Earnings</td><td style="font-size: 18px; font-weight: bold;">Amount</td></tr>');
    printWindow.document.write('<tr><td>Basic Salary</td><td>' + formatCurrency(basicSalary) + '</td></tr>');
    printWindow.document.write('<tr><td>Dearness Allowance</td><td>' + formatCurrency(da) + '</td></tr>');
    printWindow.document.write('<tr><td>Travel Allowance</td><td>' + formatCurrency(travel) + '</td></tr>');
    printWindow.document.write('<tr><td>Conveyance Allowance</td><td>' + formatCurrency(conveyance) + '</td></tr>');
    printWindow.document.write('<tr><td>Special Allowance</td><td>' + formatCurrency(special) + '</td></tr>');
    printWindow.document.write('<tr><th>Gross Salary</th><th>' + formatCurrency(grossSalary) + '</th></tr>');
    printWindow.document.write('</table>');

    // Deduction Table
    printWindow.document.write('<table>');
    printWindow.document.write('<tr><td style="font-size: 18px; font-weight: bold;">Deduction</td><td style="font-size: 18px; font-weight: bold;">Amount</td></tr>');
    printWindow.document.write('<tr><td>Safety Deposit</td><td>' + formatCurrency(safetyDeposit) + '</td></tr>');
    printWindow.document.write('<tr><td>Professional Tax</td><td>' + formatCurrency(professionalTax) + '</td></tr>');
    printWindow.document.write('</table>');

    // Net Salary
    printWindow.document.write('<table border="1" cellspacing="0" cellpadding="8" style="width: 100%; border-collapse: collapse;">');
    printWindow.document.write('<tr><th style="text-align: left;">Net Salary:</th><td style="color: ' + (netSalary < 0 ? 'red' : 'green') + '; font-weight: bold;">' + formatCurrency(netSalary) + '</td></tr>');
    printWindow.document.write('</table>');

    printWindow.document.write('</body></html>');
    printWindow.document.close();

   
    var image = printWindow.document.getElementById('salarySlipLogo');
    image.onload = function () {
        setTimeout(() => {
            printWindow.print();
        }, 1000); 
    };
}

function formatCurrency(amount) {
	return amount.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

jQuery(document).ready(function($) {
	$('#leave-form').submit(function(e) {
		e.preventDefault();  

		var leaveData = {
			action: 'submit_leave',
			leave_title: $('#leave_title').val(),
			leave_reason: $('#leave_reason').val(),
			leave_start: $('#leave_start').val(),
			leave_end: $('#leave_end').val(),
			leave_day: $('#leave_day').val(),

		};

		$.ajax({
			url: ajaxurl,  
			method: 'POST',
			data: leaveData,
			success: function(response) {
				if (response.success) {
					alert(response.data.message); 
				} else {
					alert(response.data.message); 
				}
			},
			error: function() {
				alert('An error occurred while submitting the leave request.');
			}
		});
	});
});
jQuery(document).ready(function($) {
	$(document).on('click', '.delete-leave-btn', function() {
		var leaveId = $(this).data('leave-id');
		var row = $(this).closest('tr');


		if (confirm('Are you sure you want to delete this leave record?')) {
			$.ajax({
				url: myAjax.ajaxurl, 
				type: 'POST',
				data: {
					action: 'delete_leave', 
					leave_id: leaveId,
				},
				success: function(response) {
					if (response.success) {
						row.remove();
						alert('Leave deleted successfully.');
					} else {
						alert('Failed to delete leave: ' + response.data.message);
					}
				},
				error: function() {
					alert('There was an error processing your request.');
				}
			});
		}
	});
});


jQuery(document).ready(function($) {
  
    $('#month').change(function() {
        var selectedMonth = $(this).val();

       
        $('#leave-summary').html('<p>Loading...</p>');

       
        $.ajax({
            url: myAjax.ajaxurl, 
            method: 'POST',
            data: {
                action: 'filter_leave_by_month', 
                month: selectedMonth,
            },
            success: function(response) {
                var data = JSON.parse(response);
              
                $('#leave-summary').html('<table border="1" cellspacing="0" cellpadding="8" style="width: 100%; border-collapse: collapse;">' +
                    '<thead>' +
                        '<tr style="background-color: #f2f2f2; text-align: left;">' +
                            '<th>Month</th>' +
                            '<th>Total Leave</th>' +
                        '</tr>' +
                    '</thead>' +
                    '<tbody>' +
                        data.leave_summary +
                    '</tbody>' +
                '</table>');
            },
            error: function() {
                $('#leave-summary').html('<p style="color: red;">An error occurred while fetching the leave records.</p>');
            }
        });
    });
    
    $('#month').trigger('change');
});


document.addEventListener("DOMContentLoaded", function() {
		const startDateField = document.getElementById("leave_start");
		const endDateField = document.getElementById("leave_end");
		endDateField.addEventListener("change", function() {
			const startDate = new Date(startDateField.value);
			const endDate = new Date(endDateField.value);

			if (endDate < startDate) {
				alert("End date cannot be before start date!");
				endDateField.value = ""; 
			}
		});
	});


