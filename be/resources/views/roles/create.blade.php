@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Role</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Role Name" required>
        <button type="submit">Create Role</button>
    </form>
</div>
@endsection