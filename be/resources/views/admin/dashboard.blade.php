<!-- Nếu đây là trang dashboard của người dùng -->
<h1>Welcome to your Dashboard!</h1>

<!-- Form logout -->
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form>