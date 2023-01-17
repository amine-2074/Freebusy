@extends("layout")
@section('title')
Request meeting
@endsection
@section('content')
<div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
	<div class="wrapper wrapper--w680">
		<div class="card card-1">
			<div class="card-heading"></div>
			<div class="card-body">
				<h2 class="title">Request Meeting</h2>
				<form action="{{ route('meeting.request') }}" method="POST" enctype="multipart/form-data">
					@csrf
					@method('POST')
					<div class="row row-space">
						<div class="col-2">
							<div class="input-group">
								<input class="input--style-1" type="datetime-local" step="1800"
									id="earliest_requested_date" placeholder="EARLIEST REQUESTED DATE"
									name="earliest_requested_date">
							</div>
						</div>
						<div class="col-2">
							<div class="input-group">
								<input class="input--style-1" type="datetime-local" step="1800"
									id="earliest_requested_date" placeholder="LATEST REQUESTED DATE"
									name="latest_requested_date">
							</div>
						</div>
					</div>
					<div class="input-group">
						<label for="Participants">Participants</label>
						<div class="rs-select2 js-select-simple select--no-search">
							<select class="form-control" name="participants[]" multiple="" id="Participants">
								@foreach($employees as $employee)
								<option value="{{$employee->id}}">{{$employee->name}}</option>
								@endforeach
							</select>
							<div class="select-dropdown"></div>
						</div>
					</div>
					<div class="input-group">
						<div class="rs-select2 js-select-simple select--no-search">
							<input class="input--style-1" type="number" step="30" min="0"
								placeholder="MEETING LENGTH IN MINUTES" name="meeting_length">
						</div>
					</div>
					<div class="row row-space">
						<div class="col-2">
							<div class="input-group">
								<label for="office_hours_start">OFFICE HOURS</label>
								<div class="d-flex justify-content-start">
									<input class="input--style-1" type="number" min="8" max="16"
										placeholder="OFFICE HOURS START" id="office_hours_start"
										name="office_hours_start">
								</div>
								<div class="d-flex justify-content-end">
									<input class="input--style-1" type="number" min="9" max="17"
										placeholder="OFFICE HOURS END" id="office_hours_end" name="office_hours_end">
								</div>
							</div>
						</div>
					</div>
					<div class="p-t-20">
						<button class="btn btn--radius btn--green" type="submit">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection