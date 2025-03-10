@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Profile</h1>
    <form action="{{ route('profiles.update', $profile->user_id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="phone" value="{{ $profile->phone }}">
        <textarea name="address">{{ $profile->address }}</textarea>
        <input type="text" name="avatar" value="{{ $profile->avatar }}">
        <button type="submit">Update Profile</button>
    </form>
</div>
@endsection