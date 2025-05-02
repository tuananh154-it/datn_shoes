@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    @if(session('success'))
        <h2 class="text-success">🎉 {{ session('success') }}</h2>
    @elseif(session('error'))
        <h2 class="text-danger">⚠️ {{ session('error') }}</h2>
    @else
        <h2>🎉 Cảm ơn bạn!</h2>
    @endif

    <p class="mt-3">Bạn có thể tiếp tục mua sắm tại cửa hàng của chúng tôi:</p>

    <a href="http://localhost:5173" class="btn btn-primary mt-3">
        🛒 Tiếp tục mua sắm
    </a>
    
</div>
@endsection
