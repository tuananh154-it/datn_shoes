<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <li>
                <a class="active" href="index.html">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>

          
            <li class="sub-menu">
                <a href="{{ route('sizes.index') }}" >
                    <i class="fa fa-user"></i>
                    <span>Quản lý kích thước</span>
                </a>
            </li>
            
            <li class="sub-menu">
                <a href="{{ route('colors.index') }}" >
                    <i class="fa fa-user"></i>
                    <span>Quản lý màu sắc</span>
                </a>
            </li>

            <li class="sub-menu">
                <a href="{{ route('products.index') }}" >
                    <i class="fa fa-user"></i>
                    <span>Quản lý sản phẩm</span>
                </a>
            </li>

            
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-user"></i>
                    <span>Phân quyền Admin</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-user"></i>
                    <span>Quản lý User</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="{{route('categories.index')}}" >
                    <i class="fa fa-folder"></i>
                    <span>Quản lý danh mục</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="{{route('brands.index')}}" >
                    <i class="fa fa-star"></i>
                    <span>Quản lý Thương hiệu</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="{{route('articles.index')}}" >
                    <i class="fa fa-file-text"></i>
                    <span>Quản lý bài viết</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="javascript:;" >
                    <i class="fa fa-tag"></i>
                    <span>Quản lý Banner</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="{{route('comments.index')}}" >
                    <i class="fa fa-comments-o"></i>
                    <span>Quản lý bình luận</span>
                </a>
            </li>
            <li class="sub-menu">
                <a href="{{ route('vouchers.index') }}" >
                    <i class="fa fa-ticket"></i>
                    <span>Quản lý Voucher</span>
                </a>
            </li>

            <li class="sub-menu">
                <a href="javascript:;">
                    <i class="fa fa-phone"></i>
                    <span>Quản lý liên hệ</span>
                </a>
            </li>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
