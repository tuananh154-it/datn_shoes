@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Profile of {{ $user->name }}</h1>
    <p>Phone: {{ $profile->phone }}</p>
    <p>Address: {{ $profile->address }}</p>
    <p>Avatar: <img src="{{ $profile->avatar }}" alt="Avatar" width="100"></p>
    <a href="{{ route('profiles.edit', $profile->user_id) }}" class="btn btn-warning">Edit Profile</a>
</div>
@endsection