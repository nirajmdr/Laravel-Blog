@extends('layouts.app')

@section('content')
    <h1>Edit POST</h1>
    {{-- Form using Laravel Collective --}}
    {!! Form::open(['action' => ['PostsController@update', $post->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
      <div class="form-group">
        {{Form::label('title', 'Title')}}
        {{Form::text('title', $post->title,['class' =>'form-control', 'placeholder' => 'Title'])}}
      </div>
      <div class="form-group">
        {{Form::label('body', 'Body')}}
        {{Form::textarea('body',$post->body,['class' =>'form-control', 'placeholder' => 'Write something'])}}
      </div>
      <div class="form-group">
        {{Form::file('cover_image')}}
      </div>
      {{Form::hidden('_method', 'PUT')}} {{--Pass PUT method in Post medthod Form--}}
      {{Form::submit('Submit', ['class'=> 'btn btn-primary'])}}
    {!! Form::close() !!}
    @endsection
