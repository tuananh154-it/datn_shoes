        <!--header start-->
        <header class="header white-bg">
            <div class="sidebar-toggle-box">
                <i class="fa fa-bars"></i>
            </div>
          <!--logo start-->
          <a href="{{route('dashboards.index')}}" class="logo">Foot<span>Vibe</span></a>
          <!--logo end-->
          <div class="nav notify-row" id="top_menu">
              <!--  notification start -->
              <ul class="nav top-menu">
                  <!-- settings start -->
                  <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <i class="fa fa-tasks"></i>
                          <span class="badge badge-success">6</span>
                      </a>
                      <ul class="dropdown-menu extended tasks-bar">
                          <div class="notify-arrow notify-arrow-green"></div>
                          <li>
                              <p class="green">You have 6 pending tasks</p>
                          </li>
                          <li>
                              <a href="#">
                                  <div class="task-info">
                                      <div class="desc">Dashboard v1.3</div>
                                      <div class="percent">40%</div>
                                  </div>
                                  <div class="progress">
                                      <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                          <span class="sr-only">40% Complete (success)</span>
                                      </div>
                                  </div>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <!-- settings end -->
                  <!-- inbox dropdown start-->
                  <li id="header_inbox_bar" class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <i class="fa fa-envelope-o"></i>
                          <span class="badge badge-danger">5</span>
                      </a>
                      <ul class="dropdown-menu extended inbox">
                          <div class="notify-arrow notify-arrow-red"></div>
                          <li>
                              <p class="red">You have 5 new messages</p>
                          </li>
                          <li>
                              <a href="#">
                                  <span class="photo"><img alt="avatar" src="img/avatar-mini.jpg"></span>
                                  <span class="subject">
                                  <span class="from">Jonathan Smith</span>
                                  <span class="time">Just now</span>
                                  </span>
                                  <span class="message">
                                      Hello, this is an example msg.
                                  </span>
                              </a>
                          </li>
                          <li>
                              <a href="#">
                                  <span class="photo"><img alt="avatar" src="img/avatar-mini2.jpg"></span>
                                  <span class="subject">
                                  <span class="from">Jhon Doe</span>
                                  <span class="time">10 mins</span>
                                  </span>
                                  <span class="message">
                                   Hi, Jhon Doe Bhai how are you ?
                                  </span>
                              </a>
                          </li>
                          <li>
                              <a href="#">
                                  <span class="photo"><img alt="avatar" src="img/avatar-mini3.jpg"></span>
                                  <span class="subject">
                                  <span class="from">Jason Stathum</span>
                                  <span class="time">3 hrs</span>
                                  </span>
                                  <span class="message">
                                      This is awesome dashboard.
                                  </span>
                              </a>
                          </li>
                          <li>
                              <a href="#">
                                  <span class="photo"><img alt="avatar" src="img/avatar-mini4.jpg"></span>
                                  <span class="subject">
                                  <span class="from">Jondi Rose</span>
                                  <span class="time">Just now</span>
                                  </span>
                                  <span class="message">
                                      Hello, this is metrolab
                                  </span>
                              </a>
                          </li>
                          <li>
                              <a href="#">See all messages</a>
                          </li>
                      </ul>
                  </li>
                  <!-- inbox dropdown end -->
                  <!-- notification dropdown start-->
                  <li id="header_notification_bar" class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <i class="fa fa-bell-o"></i>
                          <span class="badge badge-warning">7</span>
                      </a>
                      <ul class="dropdown-menu extended notification">
                          <div class="notify-arrow notify-arrow-yellow"></div>
                          <li>
                              <p class="yellow">You have 7 new notifications</p>
                          </li>
                          <li>
                              <a href="#">
                                  <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                  Server #3 overloaded.
                                  <span class="small italic">34 mins</span>
                              </a>
                          </li>
                          <li>
                              <a href="#">
                                  <span class="label label-warning"><i class="fa fa-bell"></i></span>
                                  Server #10 not respoding.
                                  <span class="small italic">1 Hours</span>
                              </a>
                          </li>
                      </ul>
                  </li>
                  <!-- notification dropdown end -->
              </ul>
              <!--  notification end -->
          </div>
          <div class="top-nav ">
              <!--search & user info start-->
              <ul class="nav pull-right top-menu">
                  <li>
                      <input type="text" class="form-control search" placeholder="Search">
                  </li>
                  <!-- user login dropdown start-->
                  <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <img alt="" src="img/avatar1_small.jpg">
                          <span class="username">{{ Auth::user()->name }}</span>
                          <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu extended logout dropdown-menu-right">
                        <div class="log-arrow-up"></div>
                        <li><a href="{{ route('profiles.index', Auth::user()->id) }}">Profile</a></li>
                        <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                        <li><a href="#"><i class="fa fa-bell-o"></i> Notification</a></li>

                        <!-- Form đăng xuất -->
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; color: inherit; padding: 0;">
                                    <i class="fa fa-key"></i> Log Out
                                </button>
                            </form>
                        </li>
                    </ul>

                  </li>
                  <li class="sb-toggle-right">
                      <i class="fa  fa-align-right"></i>
                  </li>
                  <!-- user login dropdown end -->
              </ul>
              <!--search & user info end-->
          </div>
      </header>
