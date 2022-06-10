@extends('layouts.app')
@section('content')

<div class="container"> 
      <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{route('dashboard')}}" class="back-btn text-danger"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-warning font-weight-bold">Manage Notifications</h1>
      </div>
      <div class="alert alert-success" id="success_msg" style="display: none;">
        successfully deleted  
      </div>
      <div class="table-responsive">
        <table class="table">
          @if ($nots->count()>0)
          <thead class="bg-warning text-white">
              <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Subtitle</th>
                <th>Subject</th>
          
                <th>Date</th>
                <th>Firebase_token</th>
                <th>IsRead</th>

                <th>User-Email</th>
                <th>Deleted by the user</th>
                <th>Image</th>
                <th>Type</th>
                <th>action</th>
              </tr>
            </thead>
            @else
          
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Sorry!!</strong> There are no data found please add some from button below <b> :( </b>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif
          <tbody>
            @foreach ($nots as $not )
            <tr class="nots{{$not->id}}" >
                <td data-label="Id">{{$not->id}}</td>
                <td data-label="Title">{{$not->title}}</td>

                <td data-label="Subtitle">
                  <div class="content">
                    {!!html_entity_decode($not->subtitle)!!}
                  </div>
                </td>
                <td data-label="Subject">
                  <div class="content">  
                    {!!html_entity_decode($not->subject)!!}
                  </div>
                </td>
                <td data-label="Date">{{Carbon\Carbon::parse($not->appointment)->format('Y-m-d h:i')  }}</td>
                <td data-label="Firebase_token">{{$not->getUser->firebaseToken}}</td>
                <td data-label="IsRead">{{$not->read?'yes':'no'}}</td>

                <td data-label="User-Email">{{$not->getUser->email}}</td>
                @if ($not->hide)
                <td data-label="Deleted by the user" ><span style="color:#ff0000; font-weight:900">YES</span></td>

                @else
                <td data-label="Deleted by the user" ><span style="color:#00ff00;font-weight:900;">NO</span></td>
                @endif   
                <td data-label="Image">
                  @if ($not->image!='')
                  <img src="/images/nots/{{$not->image}}"/>
                  @endif
                </td>

                <td data-label="Type">{{$not->type}}</td>
                <td data-label="action" class="">
                  <a href="{{route('editNots',$not->id)}}" class="btn btn-primary" ><i class="fas fa-edit"></i></a>
                  <a href="#" not_id="{{$not->id}}" class="delete_btn btn btn-danger" ><i class="fas fa-trash"></i></a>
                </td>
              </tr>
            @endforeach
            </tbody>
        </table>
      </div>
      <a href="{{route('createNots')}}" class="btn btn-warning mt-4"><i class="fas fa-plus mr-2"></i>Add Notification</a>
      <div class="d-flex justify-content-center">
          {{ $nots->links() }}
      </div>
</div>



@stop

@section('scripts')
<script>     
  $(document).on('click', '.delete_btn', function(e) {
    e.preventDefault();

    var not_id = $(this).attr('not_id');
    $.ajax({
      type: "POST",
      url: "{{route('deleteNots')}}",
      data: {
        '_token': "{{csrf_token()}}",
        'id': not_id
      },
      
      timeout: 600000,
      success: function(data) {
            //alert(data);
        if (data.status == true) {
        //  $('#success_msg').show();
          var x = document.getElementById("success_msg");
          if (x.style.display === "none") {
            x.style.display = "block";
          }

        }
        $('.nots' + data.id).remove();

      },
      error: function(e) {
      }
    });
  });
</script>
@stop