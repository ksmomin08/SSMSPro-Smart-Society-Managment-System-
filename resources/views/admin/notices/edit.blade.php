@extends('layouts.admin')

@section('content')

<h2>Edit Notice</h2>

<form method="POST"
      action="{{ route('notices.update', $notice->id) }}">

    @csrf

    @method('PUT')

    <input type="text"
           name="title"
           value="{{ $notice->title }}">

    <br><br>

    <textarea name="description" id="" cols="30" rows="10">{{ $notice->description }}</textarea>

    <br><br>

    <input type="date" 
    name="notice_date" 
    value="{{ $notice->notice_date }}">
    
    <br><br>

    <button type="submit" class="btn">

        Update Notice

    </button>

</form>

@endsection