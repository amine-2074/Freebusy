@extends("layout")
@section('title')
Book meeting
@endsection
@section('calls')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style>
	.alas {
		word-break: break-all;
	}
</style>
@endsection
@section('content')
<div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
	<div class="wrapper wrapper--w700" style="padding: 40px;">
		<div class="card card-1">
			<table class="table">
				<thead>
					<tr>
						<th scope="col">Date</th>
						<th scope="col">available times</th>
					</tr>
				</thead>
				<tbody>
					@foreach($suggested_meeting_date_times as $date => $datetimes)
					<tr>
						<th scope="row">{{$date}}</th>

						<td>
							<p class="alas">
								@foreach($datetimes as $key => $datetime)
								<a href="{{ route('meeting.book', [$datetime, json_encode($participants), $length]) }}">{{$datetime}}
								</a><br>
								@endforeach
							<p>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection