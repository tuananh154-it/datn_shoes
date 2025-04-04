@extends('master')

@section('content')

    <style>
        .row {
            padding-top: 60px;
        }

    </style>

    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header">
                    Danh sách quyền hạn
                </header>

                <div class="mb-3">
                    <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Thêm quyền hạn
                    </a>
                </div> 
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Role Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Edit</a>
            
                                <!-- Delete form -->
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </div>
@endsection
