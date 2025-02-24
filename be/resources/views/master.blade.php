<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from thevectorlab.net/flatlab-4/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 26 Aug 2024 14:10:45 GMT -->
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
    <link href="/client/flatlab-4/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="/client/flatlab-4/css/owl.carousel.css" type="text/css">

    <!--right slidebar-->
    <link href="/client/flatlab-4/css/slidebars.css" rel="stylesheet">

    <!-- Custom styles for this template -->

    <link href="/client/flatlab-4/css/style.css" rel="stylesheet">
    <link href="client/flatlab-4/css/style-responsive.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

        <section id="main-content">
            @yield('content')

        </section>
        <!--main content end-->

        <!-- Right Slidebar start -->
        <div class="sb-slidebar sb-right sb-style-overlay">
            <h5 class="side-title">Online Customers</h5>
            <ul class="quick-chat-list">
                <li class="online">
                    <div class="media">
                        <a href="#" class="">
                            <img alt="" src="img/chat-avatar2.jpg" class="mr-3 rounded-circle">
                        </a>
                        <div class="media-body">
                            <strong>John Doe</strong>
                            <small>Dream Land, AU</small>
                        </div>
                    </div><!-- media -->
                </li>
                <li class="online">
                    <div class="media">
                        <a href="#" class="">
                            <img alt="" src="img/chat-avatar.jpg" class="mr-3 rounded-circle">
                        </a>
                        <div class="media-body">
                            <div class="media-status">
                                <span class=" badge bg-important">3</span>
                            </div>
                            <strong>Jonathan Smith</strong>
                            <small>United States</small>
                        </div>
                    </div><!-- media -->
                </li>

                <li class="online">
                    <div class="media">
                        <a href="#" class="">
                            <img alt="" src="img/pro-ac-1.png" class="mr-3 rounded-circle">
                        </a>
                        <div class="media-body">
                            <div class="media-status">
                                <span class=" badge badge-success">5</span>
                            </div>
                            <strong>Jane Doe</strong>
                            <small>ABC, USA</small>
                        </div>
                    </div><!-- media -->
                </li>
                <li class="online">
                    <div class="media">
                        <a href="#" class="">
                            <img alt="" src="img/avatar1.jpg" class="mr-3 rounded-circle">
                        </a>
                        <div class="media-body">
                            <strong>Anjelina Joli</strong>
                            <small>Fockland, UK</small>
                        </div>
                    </div><!-- media -->
                </li>
                <li class="online">
                    <div class="media">
                        <a href="#" class="">
                            <img alt="" src="img/mail-avatar.jpg" class="mr-3 rounded-circle">
                        </a>
                        <div class="media-body">
                            <div class="media-status">
                                <span class=" badge bg-warning">7</span>
                            </div>
                            <strong>Mr Tasi</strong>
                            <small>Dream Land, USA</small>
                        </div>
                    </div><!-- media -->
                </li>
            </ul>
            <h5 class="side-title"> pending Task</h5>
            <ul class="p-task tasks-bar">
                <li>
                    <a href="#">
                        <div class="task-info">
                            <div class="desc">Dashboard v1.3</div>
                            <div class="percent">40%</div>
                        </div>
                        <div class="progress">
                            <div style="width: 40%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="40" role="progressbar" class="progress-bar progress-bar-striped bg-success">
                                <span class="sr-only">40% Complete (success)</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="task-info">
                            <div class="desc">Database Update</div>
                            <div class="percent">60%</div>
                        </div>
                        <div class="progress">
                            <div style="width: 60%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="60" role="progressbar" class="progress-bar progress-bar-striped bg-warning">
                                <span class="sr-only">60% Complete (warning)</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="task-info">
                            <div class="desc">Iphone Development</div>
                            <div class="percent">87%</div>
                        </div>
                        <div class="progress">
                            <div style="width: 87%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="20" role="progressbar" class="progress-bar progress-bar-striped bg-info">
                                <span class="sr-only">87% Complete</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="task-info">
                            <div class="desc">Mobile App</div>
                            <div class="percent">33%</div>
                        </div>
                        <div class="progress">
                            <div style="width: 33%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="80" role="progressbar" class="progress-bar progress-bar-striped bg-danger">
                                <span class="sr-only">33% Complete (danger)</span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="task-info">
                            <div class="desc">Dashboard v1.3</div>
                            <div class="percent">45%</div>
                        </div>
                        <div class="progress">
                            <div style="width: 45%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="45" role="progressbar" class="progress-bar progress-bar-striped">
                                <span class="sr-only">45% Complete</span>
                            </div>
                        </div>

                    </a>
                </li>
                <li class="external">
                    <a href="#">See All Tasks</a>
                </li>
            </ul>
        </div>
        <!-- Right Slidebar end -->

        <!--footer start-->
        @include('blocks.footer')
        <!--footer end-->
    </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="/client/flatlab-4/js/jquery.js"></script>
    <script src="/client/flatlab-4/js/bootstrap.bundle.min.js"></script>
    <script class="include" type="text/javascript" src="/client/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="/client/flatlab-4/js/jquery.scrollTo.min.js"></script>
    <script src="/client/flatlab-4/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="/client/flatlab-4/js/jquery.sparkline.js" type="text/javascript"></script>
    <script src="/client/flatlab-4/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
    <script src="/client/flatlab-4/js/owl.carousel.js" ></script>
    <script src="/client/flatlab-4/js/jquery.customSelect.min.js" ></script>
    <script src="/client/flatlab-4/js/respond.min.js" ></script>

    <!--right slidebar-->
    <script src="/client/flatlab-4/js/slidebars.min.js"></script>

    <!--common script for all pages-->
    <script src="/client/flatlab-4/js/common-scripts.js"></script>

    <!--script for this page-->
    <script src="/client/flatlab-4/js/sparkline-chart.js"></script>
    <script src="/client/flatlab-4/js/easy-pie-chart.js"></script>
    <script src="/client/flatlab-4/js/count.js"></script>

  <script>

      //owl carousel

      $(document).ready(function() {
          $("#owl-demo").owlCarousel({
              navigation : true,
              slideSpeed : 300,
              paginationSpeed : 400,
              singleItem : true,
			  autoPlay:true

          });
      });

      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });

      $(window).on("resize",function(){
          var owl = $("#owl-demo").data("owlCarousel");
          owl.reinit();
      });

  </script>

  </body>

<!-- Mirrored from thevectorlab.net/flatlab-4/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 26 Aug 2024 14:11:19 GMT -->
</html>
