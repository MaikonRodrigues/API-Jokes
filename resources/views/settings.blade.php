@extends('adminlte::page')

@section('title', 'Home')

@section('content_header')
    <h1>Comunidade das Piadas</h1> 
@stop

@section('content')
    <div class="content">
        <div class="col-md-10 col-md-offset-1">
        <img src="uploads/avatars/{{ $avatar }}" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px;">
        <h2> {{ $name }}'s Profile</h2>
        <form enctype="multipart/form-data" action='/ApiLaravelForAndroidTeste/public/settings' method="POST">
            <label>Update profile Image {{ $avatar }}</label>
            <input type="file" name="avatar">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="pull-right btn btn-sm btn-primary">
        </form>
        </div>
    </div>
@endsection