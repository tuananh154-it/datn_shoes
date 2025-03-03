@extends('layouts.app')

@section('content')
<style>
    /* Container styling */
.container {
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    padding: 30px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Heading style */
h1 {
    text-align: center;
    font-size: 2.5em;
    color: #333;
    margin-bottom: 20px;
    font-weight: bold;
}

/* Form input styling */
input[type="text"], input[type="email"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1em;
}

/* Placeholder text style */
input::placeholder {
    font-style: italic;
    color: #888;
}

/* Button styling */
button {
    width: 100%;
    padding: 12px;
    font-size: 1.2em;
    color: white;
    background-color: #d9db40;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #9de9ae;
}

/* Style for success message */
.alert-success {
    margin-top: 20px;
    padding: 10px;
    background-color: #d4edda;
    color: #155724;
    border-radius: 5px;
    text-align: center;
}

</style>
<div class="container">
    <h1>Edit User</h1>

    <!-- Display success message if any -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form to edit user -->
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Name field -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" value="{{ $user->name }}" required>
        </div>

        <!-- Email field -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" required>
        </div>

        <!-- Password field (optional) -->
        <div class="form-group">
            <label for="password">New Password (Leave empty if not changing)</label>
            <input type="password" name="password" placeholder="New Password">
        </div>

        <!-- Submit button -->
        <button type="submit">Update</button>
    </form>
</div>
@endsection
