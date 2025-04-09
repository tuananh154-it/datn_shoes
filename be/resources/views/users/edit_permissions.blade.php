@extends('master')

@section('content')
    <style>
        .row {
            padding-top: 60px;
        }
    </style>
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    Chỉnh sửa quyền cho người dùng: {{ $user->name }}
                </header>
                <div class="card-body">
                    <!-- Hiển thị lỗi nếu có -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form cập nhật quyền -->
                    <form method="POST" action="{{ route('users.updatePermissions', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Chọn quyền -->
                        <div class="form-group">
                            <label for="permissions">Chọn quyền:</label>
                            <select name="permissions[]" id="permissions" multiple class="form-control form-control-lg mb-3">
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}"
                                        @if($user->hasPermissionTo($permission->name)) selected @endif>
                                        {{ $permission->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nút cập nhật quyền -->
                        <button type="submit" class="btn btn-warning ">Cập nhật quyền</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
