<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <!-- Thống kê -->
            @can('show-dashboards')
                <li>
                    <a class="{{ request()->routeIs('dashboards.*') ? 'active' : '' }}" href="{{ route('dashboards.index') }}">
                        <i class="fa fa-dashboard"></i>
                        <span>Thống kê</span>
                    </a>
                </li>
            @endcan

            <!-- Quản lý sản phẩm -->
            @canany(['show-products', 'create-product', 'show-sizes', 'show-colors', 'show-categories', 'show-brands'])
                @php
                    $productActive = request()->routeIs('products.*') || request()->routeIs('sizes.*') || 
                                    request()->routeIs('colors.*') || request()->routeIs('categories.*') || 
                                    request()->routeIs('brands.*');
                @endphp
                <li class="sub-menu {{ $productActive ? 'menu-open' : '' }}">
                    <a href="javascript:void(0);" class="{{ $productActive ? 'active' : '' }}">
                        <i class="fa fa-cogs"></i>
                        <span>Quản lý sản phẩm</span>
                        <span class="fa fa-chevron-down"></span>
                    </a>
                    <ul class="sub" style="{{ $productActive ? 'display: block;' : '' }}">
                        @can('show-products')
                            <li><a class="{{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Danh sách sản phẩm</a></li>
                        @endcan
                        @can('show-sizes')
                            <li><a class="{{ request()->routeIs('sizes.*') ? 'active' : '' }}" href="{{ route('sizes.index') }}">Quản lý kích thước</a></li>
                        @endcan
                        @can('show-colors')
                            <li><a class="{{ request()->routeIs('colors.*') ? 'active' : '' }}" href="{{ route('colors.index') }}">Quản lý màu sắc</a></li>
                        @endcan
                        @can('show-categories')
                            <li><a class="{{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Quản lý danh mục</a></li>
                        @endcan
                        @can('show-brands')
                            <li><a class="{{ request()->routeIs('brands.*') ? 'active' : '' }}" href="{{ route('brands.index') }}">Quản lý Thương hiệu</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            <!-- Quản lý tài khoản -->
            @canany(['show-users', 'show-roles'])
                @php
                    $userActive = request()->routeIs('users.*') || request()->routeIs('roles.*');
                @endphp
                <li class="sub-menu {{ $userActive ? 'menu-open' : '' }}">
                    <a href="javascript:void(0);" class="{{ $userActive ? 'active' : '' }}">
                        <i class="fa fa-users"></i>
                        <span>Quản lý tài khoản</span>
                        <span class="fa fa-chevron-down"></span>
                    </a>
                    <ul class="sub" style="{{ $userActive ? 'display: block;' : '' }}">
                        @can('show-users')
                            <li><a class="{{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Danh sách người dùng</a></li>
                        @endcan
                        @can('show-roles')
                            <li><a class="{{ request()->routeIs('roles.*') ? 'active' : '' }}" href="{{ route('roles.index') }}">Phân quyền Admin</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            <!-- Quản lý bài viết -->
            @canany(['show-articles', 'show-banners', 'show-comments'])
                @php
                    $postActive = request()->routeIs('articles.*') || request()->routeIs('banners.*') || 
                                  request()->routeIs('comments.*') || request()->routeIs('reviews.*');
                @endphp
                <li class="sub-menu {{ $postActive ? 'menu-open' : '' }}">
                    <a href="javascript:void(0);" class="{{ $postActive ? 'active' : '' }}">
                        <i class="fa fa-pencil-square"></i>
                        <span>Quản lý bài viết</span>
                        <span class="fa fa-chevron-down"></span>
                    </a>
                    <ul class="sub" style="{{ $postActive ? 'display: block;' : '' }}">
                        @can('show-articles')
                            <li><a class="{{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">Danh sách bài viết</a></li>
                        @endcan
                        @can('show-banners')
                            <li><a class="{{ request()->routeIs('banners.*') ? 'active' : '' }}" href="{{ route('banners.index') }}">Quản lý Banner</a></li>
                        @endcan
                        @can('show-comments')
                            <li><a class="{{ request()->routeIs('comments.*') ? 'active' : '' }}" href="{{ route('comments.index') }}">Quản lý bình luận</a></li>
                        @endcan
                        <li><a class="{{ request()->routeIs('reviews.*') ? 'active' : '' }}" href="{{ route('reviews.index') }}">Quản lý đánh giá</a></li>
                    </ul>
                </li>
            @endcanany

            <!-- Quản lý đơn hàng -->
            @can('show-orders')
                <li>
                    <a class="{{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Quản lý đơn hàng</span>
                    </a>
                </li>
            @endcan

            <!-- Phiếu giảm giá -->
            @can('show-vouchers')
                <li>
                    <a class="{{ request()->routeIs('vouchers.*') ? 'active' : '' }}" href="{{ route('vouchers.index') }}">
                        <i class="fa fa-ticket"></i>
                        <span>Quản lý mã giảm giá</span>
                    </a>
                </li>
            @endcan

            <!-- Quản lý liên hệ -->
            @can('show-contacts')
                <li>
                    <a class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}" href="{{ route('contacts.index') }}">
                        <i class="fa fa-phone"></i>
                        <span>Quản lý liên hệ</span>
                    </a>
                </li>
            @endcan

            <!-- Quản lý yêu cầu trả lại (giữ nguyên comment như mã gốc) -->
            <!-- <li>
                <a class="{{ request()->routeIs('return_requests.*') ? 'active' : '' }}" href="{{ route('return_requests.index') }}">
                    <i class="fa fa-undo"></i>
                    <span>Quản lý yêu cầu trả lại</span>
                </a>
            </li> -->
        </ul>
        <!-- sidebar menu end-->

        <!-- Pusher Realtime Notifications -->
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var pusher = new Pusher("ee494af10a7f4a6e48b6", {
                    cluster: "mt1",
                });
                console.log("Pusher initialized:", pusher);

                var channel = pusher.subscribe('orders');
                console.log("Subscribed to channel:", channel);

                channel.bind('pusher:subscription_succeeded', function() {
                    console.log("Successfully subscribed to orders");
                });

                channel.bind('pusher:subscription_error', function(status) {
                    console.error("Subscription error:", status);
                });

                channel.bind("order.placed", function(data) {
                    console.log("Received order.placed event:", data);
                    // Chỉ hiển thị thông báo nếu status là pending (đơn hàng mới)
                    if (data && data.status === "pending") {
                        const notificationsDiv = document.getElementById("admin-notifications");
                        if (notificationsDiv) {
                            const notification = document.createElement('div');
                            notification.className = 'admin-notification';
                            notification.innerHTML = `
                                <span class="emoji">📦</span>
                                <strong>${data.username}</strong><br>
                                <span>
                                    Đơn #FV-HN-${data.id} vừa được đặt<br>
                                    Tổng: ${parseFloat(data.total_price).toLocaleString()} VNĐ<br>
                                </span>
                            `;
                            notificationsDiv.appendChild(notification);

                            // Thời gian hiển thị thông báo
                            setTimeout(() => {
                                notification.style.animation = 'fadeOut 0.5s ease-out';
                                setTimeout(() => {
                                    notification.remove();
                                }, 500);
                            }, 4500);
                        } else {
                            console.error("Element #admin-notifications not found");
                        }
                    }
                });
            });
        </script>

        <!-- Notification Styles -->
        <div id="admin-notifications"></div>
        <style>
            #admin-notifications {
                position: fixed;
                bottom: 20px;
                left: 20px;
                z-index: 1000;
            }

            .admin-notification {
                background-color: #86ca30;
                color: white;
                padding: 10px 16px;
                border-radius: 6px;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
                min-width: 220px;
                font-size: 13px;
                line-height: 1.4;
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 10px;
                animation: fadeIn 0.5s ease-out;
            }

            .admin-notification .emoji {
                font-size: 16px;
                margin-right: 4px;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes fadeOut {
                from { opacity: 1; transform: translateY(0); }
                to { opacity: 0; transform: translateY(10px); }
            }
        </style>
    </div>
</aside>