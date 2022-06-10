<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add News</title>



  <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

  <!-- Fontawesome Cdn -->
  <script src="https://kit.fontawesome.com/abc728f441.js" crossorigin="anonymous"></script>

  <!-- custome style -->
  <link rel="stylesheet" href="/css/main.css"> 
</head>

<body>
  <div class="container h-100 d-flex justify-content-center align-items-center">
    <div class="row w-100">
      <div class="col-lg-12 col-md-12 col-sm-12">
       
        <div class="d-flex justify-content-between align-items-center mb-3">
          <a  href="{{route('news')}}" class="back-btn text-danger"><i class="fas fa-arrow-left"></i></a>
          <h1 class="text-success font-weight-bold">Create News</h1>
        </div>
        @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
          {{Session::get('success')}}
        </div>
        @endif
        @if (Session::has('error'))
        <div class="alert alert-danger" role="alert">
          {{Session::get('error')}}
        </div>
        @endif
        <form id="form1" method="POST" action="{{route('storeNews')}}" enctype="multipart/form-data">
          @csrf                

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Upload</span>
            </div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="choose-picture" name="photo" required>
              <label class="custom-file-label" for="choose-picture">Choose a picture</label>
              @error('photo')
              <small class="form-text text-danger"> {{$message}}</small>
              @enderror
            </div>
          </div>

          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text">Title</span>
            </div>
            <input type="text" class="form-control" name="title" rows="5" aria-label="please fill title" required></textarea>
            @error('title')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
          </div>

          <p class="mb-2">Please fill subject</p>
          <div id="subjectid">

          </div>
          <div>
            @error('subject')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
          </div>
          <div class="d-flex justify-content-end gap-2 mt-3"> 
            <button id="sub" type="submit" class="btn btn-success">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

      <script>
        function addAttr() {

          $("#sub").click(function() {
            if ($('form')[0].checkValidity()) {
              var markupStr = $('#subjectid').summernote('code');
        
              $('<input>').attr({
                type: 'hidden',
                id: 'foo',
                name: 'subject',
                value: markupStr
              }).appendTo('form');
              
              form.submit();
            }
          });
        }
        $('#subjectid').summernote({
          placeholder: '',
          tabsize: 2,
          height: 120,
          toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
          ]

        });


        addAttr();
      </script>

</body>

</html>