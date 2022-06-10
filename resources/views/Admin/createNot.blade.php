<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Notification</title>

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
    <div class="container d-flex justify-content-center align-items-center">
        <div class="row w-100">
            <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="d-flex justify-content-between align-items-center my-3">
                <a href="{{route('nots')}}" class="back-btn text-danger"><i class="fas fa-arrow-left"></i></a>
                <h1 class="text-warning font-weight-bold">Create Notification</h1>
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
            <form method="POST" action="{{route('storeNots')}}" enctype="multipart/form-data">
                @csrf
                
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text bg-warning border-warning text-white" for="inputGroupSelect01">Type</label>
                    </div>
                    <select class="custom-select" name="type" id="inputGroupSelect01">
                        <option selected value="Notification">Notification</option>
                        <option value="Receipt">Receipt</option>
                        <option value="Appointment">Appointment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="emailSearch" class="form-label" >Please select an email</label>
                    <input class="form-control" list="datalistOptions" name="useremail" id="emailSearch" placeholder="Type to search of email" required>
                    <datalist id="datalistOptions">
                        @foreach($users as $category)
                        <option value="{{ $category->email }}">{{ $category->email }}</option>
                        @endforeach
                    </datalist>
                    @error('useremail')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Upload</span>
                    </div>
                    <div class="custom-file">
                    <input type="file" class="custom-file-input" id="choose-picture" name="photo">
                    <label class="custom-file-label" for="choose-picture">Choose a Picture</label>
                    @error('photo')
                    <small class="form-text text-danger"> {{$message}}</small>
                    @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Title</label>
                    <input type="text" class="form-control" name="title" aria-describedby="emailHelp" placeholder="Please fill title" required>
                    @error('title')
                    <small class="form-text text-danger"> {{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label id="sublabel" for="subtitleid">Please fill subtitle</label>
                    <div id="subtitleid">
                    </div>
                </div>
                <div>
                    @error('subtitle')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label id="subjectlabel" for="subjectid">Please fill subject</label>
                    <div id="subjectid">
                    </div>
                </div>
                <div>
                    @error('subject')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group mt-2">
                    <label for="exampleInputPassword1">Date</label>
                    <input type="datetime-local" required class="form-control" name="appointment" placeholder="please fill appointment" min="{{ Carbon\Carbon::now()->format('Y-m-d\Th:i') }}">
                    @error('appointment')
                    <small class="form-text text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3"> 
                    <button id="sub" type="submit" class="btn btn-warning">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
            <script>
              function hideelement() {
                    $('#subjectid').summernote('destroy');
                    $('#subtitleid').summernote('destroy');
                    $('#subtitleid').hide();
                    $('#subjectid').hide();
                    $('#inphoto').hide();
                    

                    $('#subjectlabel').hide();
                    $('#sublabel').hide();
                    return;
                }

                function showelement() {
                    addsummer();
                    $('#inphoto').show();
                    $('#subjectlabel').show();
                    $('#sublabel').show();
                    return;
                }

                function hideonchange() {
                    $('#inputGroupSelect01').on('change', function() {

                        if (this.value == 'Appointment') {
                            hideelement();
                            return;



                        } else {
                            showelement();
                            return;
                        }
                    });
                }

                function addsummer() {
                    $(['#subjectid', '#subtitleid']).summernote({
                        placeholder: '',
                        tabsize: 2,
                        height: 120,
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'clear']],
                            ['fontname', ['fontname']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link']],
                            ['view', ['fullscreen', 'codeview', 'help']]
                        ]

                    });
                }

                function addAttr() {

                    $("#sub").click(function() {

                        if ($('form')[0].checkValidity()) {
                            var markupStr = $('#subjectid').summernote('code');
                            var subtitle = $('#subtitleid').summernote('code');

                            $('<input>').attr({
                                type: 'hidden',
                                id: 'foo',
                                name: 'subject',
                                value: markupStr
                            }).appendTo('form');


                            $('<input>').attr({
                                type: 'hidden',
                                id: 'foo',
                                name: 'subtitle',
                                value: subtitle
                            }).appendTo('form');

                            form.submit();
                        }





                    });
                }





                addAttr();
                addsummer();
                hideonchange();
            </script>

</body>

</html>