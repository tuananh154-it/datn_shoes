@extends('master')

@section('content')

    <style>
        .row {
            padding-top: 60px;
        }

        .text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .custom-select-small {
            font-size: 0.70rem;
            height: 20px;
            padding: 2px 6px;
            width: auto;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header">
                    Quản lý liên hệ
                </header>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($contacts->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">Không có liên hệ phù hợp</td>
                                    </tr>
                                @else
                                    @foreach ($contacts as $contact)
                                        <tr class="text-center">
                                            <td>{{ $contact->id }}</td>
                                            <td class="text-truncate">{{ $contact->name }}</td>
                                            <td class="text-truncate">{{ $contact->email }}</td>
                                            <td class="text-truncate">{{ $contact->phone_number }}</td>
                                            <td>
                                                <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="dataTables_info" id="hidden-table-info_info">
                                Hiển thị từ {{ $contacts->firstItem() }} đến {{ $contacts->lastItem() }} của tổng
                                cộng {{ $contacts->total() }} mục
                            </div>
                        </div>
                        <div class="span6">
                            <div class="dataTables_paginate paging_bootstrap pagination">
                                <ul class="pagination">
                                    <li class="prev">
                                        <a href="{{ $contacts->previousPageUrl() }}" aria-label="Previous">←
                                            Trước</a>
                                    </li>
                                    @foreach ($contacts->getUrlRange(1, $contacts->lastPage()) as $page => $url)
                                        <li class="{{ $page == $contacts->currentPage() ? 'active' : '' }}">
                                            <a href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endforeach
                                    <li class="next">
                                        <a href="{{ $contacts->nextPageUrl() }}" aria-label="Next">Sau →</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('assets') }}/admin/js/dynamic_table_init.js"></script>
@endsection
