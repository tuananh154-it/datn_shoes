<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">

            <!-- Dashboard -->
            @can('show-dashboard')
                <li>
                    <a class="active" href="{{route('dashboards.index')}}">
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
                        
                            <li><a href="{{ route('reviews.index') }}">Quản lý đánh giá</a></li>
                        
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

            <!-- Quản lý yêu cầu trả lại -->
            <!-- <li>
                <a href="{{ route('return_requests.index') }}">
                    <i class="fa fa-undo"></i>
                    <span>Quản lý yêu cầu trả lại</span>
                </a>
            </li> -->

        </ul>
        <!-- sidebar menu end-->
        <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<!-- <script>
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

                // thoi gian phat realtime
                setTimeout(() => {
                    notification.style.animation = 'fadeOut 0.5s ease-out';
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 4500);
            } else {
                console.error("Element #admin-notifications not found");
            }
        });
    });
</script> -->
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
</style>
</aside>
