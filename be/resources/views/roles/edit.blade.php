@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Role</h1>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $role->name }}" required>
        <button type="submit">Update Role</button>
    </form>
</div>
@endsection