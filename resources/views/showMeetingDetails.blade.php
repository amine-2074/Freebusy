@extends("layout")
@section('calls')
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


@endsection
@section('content')
<div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
        <div class="wrapper wrapper--w700" style="padding: 40px;">
            <div class="card card-1">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Start Of Meeting</th>
                      <th scope="col">End Of Meeting</th>
                      <th scope="col">Participants</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                        <th scope="row">{{$startMeeting}}</th>
                        <th scope="row">{{$endmeeting}}</th>
                        <td>
                            @foreach($participants_decoded as $key => $participant)
                              {{$participant}} <br>
                            @endforeach
                        </td>
                    </tr>
                  </tbody>
                </table>
            </div>
        </div>
</div>
@endsection
