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

  </head>

  <body class="light-sidebar-nav">

    <section id="container">
        <!-- header start -->
        @include('blocks.header')
        <!--header end-->
        <!--sidebar start-->
        @include('blocks.sidebar')
        <!--sidebar end-->
        <section class="wrapper" >
            <!--state overview start-->
            <div class="row state-overview">
                @yield('content')
            </div>
            <!--state overview end-->

  
        </section>
       

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
