@extends('layouts.app')
@section('content')
<div class="alert alert-success" id="success_msg" style="display: none;">
  successfully deleted  
</div>
<div class="container"> 
      <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{route('dashboard')}}" class="back-btn text-danger"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-primary font-weight-bold">Manage Users</h1>
      </div>
      <div class="table-responsive-xl ">
        <table class="table table-hover">
          @if ($users->count()>0)
          <thead class="bg-primary text-white">
            <tr>
              <th>Id</th>
              <th>Name</th>
              <th>Email</th>
              <th>Gender</th>
              <th>Type_plan</th>
              <th>firebaseToken</th>
            
            </tr>
          </thead>
          @endif
          <tbody>
            @foreach ($users as $user )
            <tr class="users{{$user->id}}" >
              <td data-label="Id">{{$user->id}}</td>
              <td data-label="Name">{{$user->name}}</td>
              <td data-label="Email">{{$user->email}}</td>
            
              <td data-label="Gender">
              {{$user->gender}}
              </td>

              <td data-label="Type_plan">
                @if ($user->type_plane==1)
                WERTHEIM
                @elseif ($user->type_plane==2)
                FREUDENBERG
                @endif
              </td>

              <td data-label="firebaseToken">
                {{$user->firebaseToken}}
              </td>
            </tr>
            @endforeach
            </tbody>
        </table>
      </div>


  
  <div class="d-flex justify-content-center">
    {{ $users->links() }}
  </div>
</div>

<!--  -->


@stop

