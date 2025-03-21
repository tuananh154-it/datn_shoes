
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">

            <!-- Dashboard -->
            <li>
                <a class="active" href="{{route('dashboards.index')}}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Quản lý sản phẩm -->
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-cogs"></i>
                    <span>Quản lý sản phẩm</span>
                    <span class="fa fa-chevron-down"></span> <!-- Icon toggle -->
                </a>
                <ul class="sub">
                    <li><a href="{{ route('products.index') }}">Danh sách sản phẩm</a></li>
                    <li><a href="{{ route('sizes.index') }}">Quản lý kích thước</a></li>
                    <li><a href="{{ route('colors.index') }}">Quản lý màu sắc</a></li>
                    <li><a href="{{ route('categories.index') }}">Quản lý danh mục</a></li>
                    <li><a href="{{ route('brands.index') }}">Quản lý Thương hiệu</a></li>
                </ul>
            </li>

            <!-- Quản lý tài khoản -->
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-users"></i>
                    <span>Quản lý tài khoản</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="sub">
                    <li><a href="javascript:;">Danh sách người dùng</a></li>
                    <li><a href="javascript:;">Phân quyền Admin</a></li>
                </ul>
            </li>

            <!-- Quản lý bài viết -->
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-pencil-square"></i>
                    <span>Quản lý bài viết</span>
                    <span class="fa fa-chevron-down"></span>
                </a>
                <ul class="sub">
                    <li><a href="{{ route('articles.index') }}">Danh sách bài viết</a></li>
                    <li><a href="{{ route('banners.index') }}">Quản lý Banner</a></li>
                    <li><a href="{{ route('comments.index') }}">Quản lý bình luận</a></li>
                </ul>
            </li>

            <!-- Quản lý đơn hàng -->
            <li>
                <a href="{{ route('orders.index') }}">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Quản lý đơn hàng</span>
                </a>
            </li>

            <!-- Phiếu giảm giá -->
            <li>
                <a href="{{ route('vouchers.index') }}">
                    <i class="fa fa-ticket"></i>
                    <span>Quản lý Voucher</span>
                </a>
            </li>

            <!-- Quản lý liên hệ -->
            <li>
                <a href="{{ route('contacts.index') }}">
                    <i class="fa fa-phone"></i>
                    <span>Quản lý liên hệ</span>
                </a>
            </li>

        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
