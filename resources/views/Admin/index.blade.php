@extends('layouts.app')
@section('content')

<div class="row justify-content-center w-75 mx-auto gap-5">
    <a class="dash-card border border-primary font-weight-bold text-primary col-lg-3 text-decoration-none" href="{{route('users')}}">
        <i class="bg-primary fas fa-user"></i>
        Users
    </a>
    <a class="dash-card border border-success col-lg-3 font-weight-bold text-success text-decoration-none"  href="{{route('news')}}">
        <i class="bg-success fas fa-bell"></i>
        News
    </a>
    <a class="dash-card border border-warning col-lg-3 font-weight-bold text-warning text-decoration-none" href="{{route('nots')}}">
        <i class="bg-warning fas fa-comment-alt"></i>
        Notifications
    </a>
</div>

@stop