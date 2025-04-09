@extends('master')

@section('content')
<style>
    /* Container chứa danh sách */
    .user-list-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: 50px auto;
    }

    /* Tiêu đề */
    .user-list-container h2 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
        font-size: 1.75rem;
    }

    /* Danh sách người dùng */
    .user-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    /* Mỗi dòng thông tin user */
    .user-list li {
        border-bottom: 1px solid #eee;
        padding: 15px 10px;
        transition: background-color 0.3s ease;
    }

    /* Bỏ đường viền cho phần tử cuối cùng */
    .user-list li:last-child {
        border-bottom: none;
    }

    /* Link hiển thị tên user */
    .user-list li a {
        text-decoration: none;
        font-size: 1.1rem;
        color: #007bff;
    }

    /* Hiệu ứng hover cho link */
    .user-list li a:hover {
        color: #0056b3;
    }
</style>

<div class="user-list-container">
    <h2>Thông tin người dùng</h2>
    <ul class="user-list">
        <!-- Hiển thị người dùng hiện tại -->
        <li>
            <a href="{{ route('profiles.show') }}">
                {{ Auth::user()->name }} - {{ Auth::user()->email }}
            </a>
        </li>
    </ul>
</div>
@endsection
