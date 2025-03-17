<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.html">

    <title>Footvibe</title>

    <!-- Bootstrap core CSS -->
    <link href="/client/flatlab-4/css/bootstrap.min.css" rel="stylesheet">
    <link href="/client/flatlab-4/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="/client/flatlab-4/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="/client/flatlab-4/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet"
        type="text/css" media="screen" />
    <link rel="stylesheet" href="/client/flatlab-4/css/owl.carousel.css" type="text/css">

    <!--right slidebar-->
    <link href="/client/flatlab-4/css/slidebars.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/client/flatlab-4/css/style.css" rel="stylesheet">
    <link href="client/flatlab-4/css/style-responsive.css" rel="stylesheet" />
    <!-- Thêm CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>

    <style>
        /* Điều chỉnh kích thước chữ cho các mục menu trong sidebar */
        #sidebar .sidebar-menu>li>a {
            font-size: 18px;
            /* Tăng kích thước chữ cho các mục menu */
        }

        /* Điều chỉnh kích thước chữ cho các menu con (sub-menu) */
        #sidebar .sub-menu>a {
            font-size: 18px;
            /* Tăng kích thước chữ cho các mục trong menu con */
        }

        /* Tăng kích thước chữ cho các tiêu đề trong sidebar (ví dụ: 'Quản lý sản phẩm') */
        #sidebar .sub-menu>a>span {
            font-size: 20px;
            /* Tăng kích thước chữ tiêu đề */
        }

        /* Tăng kích thước chữ cho các mục trong sub-menu */
        #sidebar .sub-menu .sub>li>a {
            font-size: 16px;
            /* Đặt kích thước chữ nhỏ hơn cho các mục con trong menu con */
        }

        /* Tăng kích thước chữ cho các mục không có sub-menu (ví dụ: Quản lý đơn hàng, Phiếu giảm giá, Quản lý liên hệ) */
        #sidebar .sidebar-menu>li>a {
            font-size: 18px;
            /* Kích thước chữ cho các liên kết không có submenu */
        }

        /* Sidebar (Aside) */
        #sidebar {
            font-size: 18px;
            /* Tăng kích thước chữ của sidebar */

            width: 300px;
            /* Đặt độ rộng sidebar */
            height: 100vh;
            /* Chiếm toàn bộ chiều cao */
        }

        /* Main content */
        #main-content {
            margin-left: 300px;
            /* Đẩy nội dung sang phải, tránh bị che khuất */
            width: calc(100% - 300px);
            /* Chiều rộng của content sẽ là 100% trừ đi chiều rộng sidebar */
        }
    </style>
</head>

<body class="light-sidebar-nav">

    <section id="container">
        <!-- header start -->
        @include('blocks.header')
        <!--header end-->

        <!--sidebar start-->
        @include('blocks.sidebar')
        <!--sidebar end-->

        <!--main content start-->
        <section id="main-content" id="main-content" class="p-3">
            @yield('content')

        </section>

        @yield('js-cus')
    </section>



    <!--main content end-->

    <!-- Right Slidebar start -->
    <div class="sb-slidebar sb-right sb-style-overlay">
        <!-- Slidebar content -->
    </div>
    <!-- Right Slidebar end -->
    <!-- js placed at the end of the document so the pages load faster -->
    <script src="/client/flatlab-4/js/jquery.js"></script>
    <script src="/client/flatlab-4/js/bootstrap.bundle.min.js"></script>
    <script class="include" type="text/javascript" src="/client/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="/client/flatlab-4/js/jquery.scrollTo.min.js"></script>
    <script src="/client/flatlab-4/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="/client/flatlab-4/js/jquery.sparkline.js" type="text/javascript"></script>
    <script src="/client/flatlab-4/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
    <script src="/client/flatlab-4/js/owl.carousel.js"></script>
    <script src="/client/flatlab-4/js/jquery.customSelect.min.js"></script>
    <script src="/client/flatlab-4/js/respond.min.js"></script>

    <!--right slidebar-->
    <script src="/client/flatlab-4/js/slidebars.min.js"></script>

    <!--common script for all pages-->
    <script src="/client/flatlab-4/js/common-scripts.js"></script>

    <!--script for this page-->
    <script src="/client/flatlab-4/js/sparkline-chart.js"></script>
    <script src="/client/flatlab-4/js/easy-pie-chart.js"></script>
    <script src="/client/flatlab-4/js/count.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Khi nhấp vào các menu chính có class "sub-menu"
            $(".sub-menu > a").click(function() {
                var $subMenu = $(this).next(".sub");
                if ($subMenu.is(":visible")) {
                    $subMenu.slideUp(); // Thu gọn menu
                } else {
                    $(".sub").slideUp(); // Thu gọn tất cả các menu con
                    $subMenu.slideDown(); // Mở rộng menu hiện tại
                }
            });
        });

        // owl carousel
        $(document).ready(function() {
            $("#owl-demo").owlCarousel({
                navigation: true,
                slideSpeed: 300,
                paginationSpeed: 400,
                singleItem: true,
                autoPlay: true
            });
        });

        // custom select box
        $(function() {
            $('select.styled').customSelect();
        });

        $(window).on("resize", function() {
            var owl = $("#owl-demo").data("owlCarousel");
            owl.reinit();
        });
    </script>
<!-- Mirrored from thevectorlab.net/flatlab-4/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 26 Aug 2024 14:11:19 GMT -->

</html>
