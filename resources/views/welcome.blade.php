<!DOCTYPE html>
<html>

<head>
	<title>Store Data from file</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
	<style>
		.d-flex {
			height: 100vh;
		}

		.align-items-center {
			min-height: 100vh;
		}
	</style>
</head>

<body>
	<div class="container-fluid d-flex align-items-center justify-content-center">
		<a href="{{ route('meeting.getData') }}">
			<button class="btn btn-primary">Store Data</button>
		</a>
	</div>
</body>

</html>