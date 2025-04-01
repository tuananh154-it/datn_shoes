
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">

            <!-- Dashboard -->
            @can('show-dashboard')
            <li>
                <a  class="active" href="{{route('dashboards.index')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Thống kê </span>
                </a>
            </li>
            @endcan

            <!-- Quản lý sản phẩm -->
            @canany(['show-products', 'create-product', 'show-sizes', 'show-colors', 'show-categories', 'show-brands'])
            <li class="sub-menu">
                <a>
                    <i class="fa fa-cogs"></i>
                    <span>Quản lý sản phẩm</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="sub">
                    @can('show-products')
                    <li><a href="{{ route('products.index') }}">Danh sách sản phẩm</a></li>
                    @endcan
                    @can('create-product')
                    <li><a href="{{ route('products.create') }}">Thêm sản phẩm</a></li>
                    @endcan
                    @can('show-sizes')
                    <li><a href="{{ route('sizes.index') }}">Quản lý kích thước</a></li>
                    @endcan
                    @can('show-colors')
                    <li><a href="{{ route('colors.index') }}">Quản lý màu sắc</a></li>
                    @endcan
                    @can('show-categories')
                    <li><a href="{{ route('categories.index') }}">Quản lý danh mục</a></li>
                    @endcan
                    @can('show-brands')
                    <li><a href="{{ route('brands.index') }}">Quản lý Thương hiệu</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany

            <!-- Quản lý tài khoản -->
            @canany(['show-users', 'show-roles'])
            <li class="sub-menu">
                <a>
                    <i class="fa fa-users"></i>
                    <span>Quản lý tài khoản</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="sub">
                    @can('show-users')
                    <li><a href="{{ route('users.index') }}">Danh sách người dùng</a></li>
                    @endcan
                    @can('show-roles')
                    <li><a href="{{ route('roles.index') }}">Phân quyền Admin</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany

            <!-- Quản lý bài viết -->
            @canany(['show-articles', 'show-banners', 'show-comments'])
            <li class="sub-menu">
                <a>
                    <i class="fa fa-pencil-square"></i>
                    <span>Quản lý bài viết</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="sub">
                    @can('show-articles')
                    <li><a href="{{ route('articles.index') }}">Danh sách bài viết</a></li>
                    @endcan
                    @can('show-banners')
                    <li><a href="{{ route('banners.index') }}">Quản lý Banner</a></li>
                    @endcan
                    @can('show-comments')
                    <li><a href="{{ route('comments.index') }}">Quản lý bình luận</a></li>
                    @endcan
                </ul>
            </li>
            @endcanany

            <!-- Quản lý đơn hàng -->
            @can('show-orders')
            <li>
                <a href="{{ route('orders.index') }}">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Quản lý đơn hàng</span>
                </a>
            </li>
            @endcan

            <!-- Phiếu giảm giá -->
            @can('show-vouchers')
            <li>
                <a href="{{ route('vouchers.index') }}">
                    <i class="fa fa-ticket"></i>
                    <span>Quản lý mã giảm giá </span>
                </a>
            </li>
            @endcan

            <!-- Quản lý liên hệ -->
            @can('show-contacts')
            <li>
                <a href="{{ route('contacts.index') }}">
                    <i class="fa fa-phone"></i>
                    <span>Quản lý liên hệ</span>
                </a>
            </li>
            @endcan

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
