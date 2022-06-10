@extends('layouts.app')
@section('content')


<div class="container"> 

      <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{route('dashboard')}}" class="back-btn text-danger"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-success font-weight-bold">Manage News</h1>
      </div>
      <div class="alert alert-success" id="success_msg" style="display: none;">
        successfully deleted  
      </div>
      <div class="table-responsive">
        <table class="table">
        @if ($news->count()>0)
          <thead class="bg-success text-white">
            <tr>
              <th scope="col">Id</th>
              <th scope="col">Title</th>
              <th scope="col">Subject</th>
              <th scope="col">Image</th>

              <th scope="col">action</th>
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
            @foreach ($news as $new )
              <tr class="news{{$new->id}}" >
                <td data-label="Id">{{$new->id}}</td>
                <td data-label="Title">{{$new->title}}</td>
                <td data-label="Subject" class="p-0">
                  <div class="content">
                    <p> 
                      {!! html_entity_decode( $new->subject)!!}

                    </p>
                  </div>
                </td>
                <td data-label="Image">
                  @if ($new->image!='')
                  <img src="/images/news/{{$new->image}}" height="100px" width="100px" />
                  @endif
                </td>
                <td data-label="action" class="">
                  <a href="{{route('editNews',$new->id)}}" class="btn btn-primary" ><i class="fas fa-edit"></i></a>
                  <a href="#" news_id="{{$new->id}}" class="delete_btn btn btn-danger" ><i class="fas fa-trash"></i></a>
                </td>
              </tr>
            @endforeach
            </tbody>
        </table>
      </div>
      <a href="{{route('createNews')}}" class="btn btn-success"><i class="fas fa-plus mr-2"></i>Add News</a>
      <div class="d-flex justify-content-center">
      {{ $news->links() }}
    </div>
</div>
@stop

@section('scripts')
<script>     
  $(document).on('click', '.delete_btn', function(e) {
    e.preventDefault();
    var news_id = $(this).attr('news_id');
    $.ajax({
      type: "POST",
      //enctype: 'multipart/form-data',
      url: "{{route('deleteNews')}}",
      data: {
        '_token': "{{csrf_token()}}",
        'id': news_id
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
        $('.news' + data.id).remove();

      },
      error: function(e) {
        //alert("dee");

       /* $("#result").text(e.responseText);
        console.log("ERROR : ", e);
        $("#btnSubmit").prop("disabled", false);
*/
      }
    });
  });
</script>
@stop