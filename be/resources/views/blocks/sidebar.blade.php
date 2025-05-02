<aside>
    <div id="sidebar" class="nav-collapse">
        <ul class="sidebar-menu" id="nav-accordion">

            {{-- Thống kê --}}
            @can('show-dashboard')
            <li>
                <a class="{{ request()->routeIs('dashboards.index') ? 'active' : '' }}" href="{{ route('dashboards.index') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Thống kê</span>
                </a>
            </li>
            @endcan

            {{-- Quản lý sản phẩm --}}
            @canany(['show-products', 'create-product', 'show-sizes', 'show-colors', 'show-categories', 'show-brands'])
            @php
                $productActive = request()->is('admin/products*') || request()->is('admin/sizes*') || request()->is('admin/colors*') || request()->is('admin/categories*') || request()->is('admin/brands*');
            @endphp
            <li class="sub-menu {{ $productActive ? 'menu-open' : '' }}">
                <a href="javascript:void(0);" class="{{ $productActive ? 'active' : '' }}">
                    <i class="fa fa-cogs"></i>
                    <span>Quản lý sản phẩm</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="sub" style="{{ $productActive ? 'display: block;' : '' }}">
                    @can('show-products')
                    <li><a class="{{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">Danh sách sản phẩm</a></li>
                    @endcan
                    @can('show-sizes')
                    <li><a class="{{ request()->routeIs('sizes.index') ? 'active' : '' }}" href="{{ route('sizes.index') }}">Quản lý kích thước</a></li>
                    @endcan
                    @can('show-colors')
                    <li><a class="{{ request()->routeIs('colors.index') ? 'active' : '' }}" href="{{ route('colors.index') }}">Quản lý màu sắc</a></li>
                    @endcan
                    @can('show-categories')
                    <li><a class="{{ request()->routeIs('categories.index') ? 'active' : '' }}" href="{{ route('categories.index') }}">Quản lý danh mục</a></li>
                    @endcan
                    @can('show-brands')
                    <li><a class="{{ request()->routeIs('brands.index') ? 'active' : '' }}" href="{{ route('brands.index') }}">Quản lý Thương hiệu</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany

            {{-- Quản lý tài khoản --}}
            @canany(['show-users', 'show-roles'])
            @php
                $userActive = request()->is('admin/users*') || request()->is('admin/roles*');
            @endphp
            <li class="sub-menu {{ $userActive ? 'menu-open' : '' }}">
                <a href="javascript:void(0);" class="{{ $userActive ? 'active' : '' }}">
                    <i class="fa fa-users"></i>
                    <span>Quản lý tài khoản</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="sub" style="{{ $userActive ? 'display: block;' : '' }}">
                    @can('show-users')
                    <li><a class="{{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">Danh sách người dùng</a></li>
                    @endcan
                    @can('show-roles')
                    <li><a class="{{ request()->routeIs('roles.index') ? 'active' : '' }}" href="{{ route('roles.index') }}">Phân quyền Admin</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany

            {{-- Quản lý bài viết --}}
            @canany(['show-articles', 'show-banners', 'show-comments'])
            @php
                $postActive = request()->is('admin/articles*') || request()->is('admin/banners*') || request()->is('admin/comments*');
            @endphp
            <li class="sub-menu {{ $postActive ? 'menu-open' : '' }}">
                <a href="javascript:void(0);" class="{{ $postActive ? 'active' : '' }}">
                    <i class="fa fa-pencil-square"></i>
                    <span>Quản lý bài viết</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="sub" style="{{ $postActive ? 'display: block;' : '' }}">
                    @can('show-articles')
                    <li><a class="{{ request()->routeIs('articles.index') ? 'active' : '' }}" href="{{ route('articles.index') }}">Danh sách bài viết</a></li>
                    @endcan
                    @can('show-banners')
                    <li><a class="{{ request()->routeIs('banners.index') ? 'active' : '' }}" href="{{ route('banners.index') }}">Quản lý Banner</a></li>
                    @endcan
                    @can('show-comments')
                    <li><a class="{{ request()->routeIs('comments.index') ? 'active' : '' }}" href="{{ route('comments.index') }}">Quản lý bình luận</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany

            {{-- Quản lý đơn hàng --}}
            @can('show-orders')
            <li>
                <a class="{{ request()->routeIs('orders.index') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Quản lý đơn hàng</span>
                </a>
            </li>
            @endcan

            {{-- Mã giảm giá --}}
            @can('show-vouchers')
            <li>
                <a class="{{ request()->routeIs('vouchers.index') ? 'active' : '' }}" href="{{ route('vouchers.index') }}">
                    <i class="fa fa-ticket"></i>
                    <span>Quản lý mã giảm giá</span>
                </a>
            </li>
            @endcan

            {{-- Liên hệ --}}
            @can('show-contacts')
            <li>
                <a class="{{ request()->routeIs('contacts.index') ? 'active' : '' }}" href="{{ route('contacts.index') }}">
                    <i class="fa fa-phone"></i>
                    <span>Quản lý liên hệ</span>
                </a>
            </li>
            @endcan

        </ul>
    </div>

    {{-- CSS --}}
    <style>
     /* ====== Cơ bản cho tất cả menu ====== */
.sidebar-menu li a {
    font-size: 18px !important;
    font-weight: 500 !important;
    color: #444 !important;
    display: flex;
    align-items: center;
    padding: 14px 20px;
    text-decoration: none;
    border-left: 4px solid transparent;
    transition: all 0.2s ease;
}

.sidebar-menu li a i {
    margin-right: 10px;
    font-size: 18px !important;
}

.sidebar-menu li a span {
    font-size: 18px !important;
    font-weight: 500 !important;
}

/* ====== Menu cha: có nền đỏ nhạt, gạch trái khi active ====== */
.sidebar-menu > li > a.active,
.sidebar-menu .sub-menu > a.active {
    background-color: #ffecec !important;
    color: #d9534f !important;
    font-weight: 600 !important;
    border-left: 4px solid #d9534f !important;
}

/* ====== Menu con: chỉ đậm chữ và đổi màu, KHÔNG có nền ====== */
.sidebar-menu .sub-menu ul.sub li a.active {
    background-color: transparent !important;
    color: #d9534f !important;
    font-weight: 600 !important;
    border-left: 0 !important;
}

/* ====== Hover ====== */
.sidebar-menu li a:hover {
    background-color: #f8f8f8 !important;
    color: #d9534f !important;
    font-weight: 600 !important;
}

/* ====== Hover riêng cho menu con ====== */
.sidebar-menu .sub-menu ul.sub li a:hover {
    background-color: #f9f9f9 !important;
    color: #d9534f !important;
}

/* ====== Hiện menu con khi có class menu-open ====== */
.sidebar-menu .menu-open > ul.sub {
    display: block !important;
}


    </style>
</aside>
