<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Account confirmation</h2>

		<div>
			<h3>Dear {{$customerName}},</h3>
			You have an appointment for introductory visit on {{date('Y-m-d', strtotime($introVisitDate))}}.<br/>
			Thanks for Joining <strong>The Little Gym</strong>
		</div>
	</body>
</html>
