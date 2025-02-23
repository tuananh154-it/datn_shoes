
const Header = () => {
  return (
    <header className="header white-bg">
      <div className="sidebar-toggle-box">
        <i className="fa fa-bars"></i>
      </div>
      {/* Logo */}
      <a href="#" className="logo">
        Foot<span>Vibe</span>
      </a>

      <div className="nav notify-row" id="top_menu">
        {/* Notifications */}
        <ul className="nav top-menu">
          {/* Tasks */}
          <li className="dropdown">
            <a data-toggle="dropdown" className="dropdown-toggle" href="#">
              <i className="fa fa-tasks"></i>
              <span className="badge badge-success">6</span>
            </a>
            <ul className="dropdown-menu extended tasks-bar">
              <div className="notify-arrow notify-arrow-green"></div>
              <li>
                <p className="green">You have 6 pending tasks</p>
              </li>
              <li>
                <a href="#">
                  <div className="task-info">
                    <div className="desc">Dashboard v1.3</div>
                    <div className="percent">40%</div>
                  </div>
                  <div className="progress">
                    <div
                      className="progress-bar progress-bar-striped bg-success"
                      role="progressbar"
                      aria-valuenow={40}
                      aria-valuemin={0}
                      aria-valuemax={100}
                      style={{ width: '40%' }}
                    >
                      <span className="sr-only">40% Complete (success)</span>
                    </div>
                  </div>
                </a>
              </li>
            </ul>
          </li>

          {/* Inbox */}
          <li id="header_inbox_bar" className="dropdown">
            <a data-toggle="dropdown" className="dropdown-toggle" href="#">
              <i className="fa fa-envelope-o"></i>
              <span className="badge badge-danger">5</span>
            </a>
            <ul className="dropdown-menu extended inbox">
              <div className="notify-arrow notify-arrow-red"></div>
              <li>
                <p className="red">You have 5 new messages</p>
              </li>
              <li>
                <a href="#">
                  <span className="photo">
                    <img alt="avatar" src="img/avatar-mini.jpg" />
                  </span>
                  <span className="subject">
                    <span className="from">Jonathan Smith</span>
                    <span className="time">Just now</span>
                  </span>
                  <span className="message">Hello, this is an example msg.</span>
                </a>
              </li>
              {/* More messages */}
            </ul>
          </li>

          {/* Notifications */}
          <li id="header_notification_bar" className="dropdown">
            <a data-toggle="dropdown" className="dropdown-toggle" href="#">
              <i className="fa fa-bell-o"></i>
              <span className="badge badge-warning">7</span>
            </a>
            <ul className="dropdown-menu extended notification">
              <div className="notify-arrow notify-arrow-yellow"></div>
              <li>
                <p className="yellow">You have 7 new notifications</p>
              </li>
              {/* Notifications list */}
              <li>
                <a href="#">
                  <span className="label label-danger">
                    <i className="fa fa-bolt"></i>
                  </span>
                  Server #3 overloaded.
                  <span className="small italic">34 mins</span>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>

      {/* Top Nav */}
      <div className="top-nav">
        {/* Search & User Info */}
        <ul className="nav pull-right top-menu">
          <li>
            <input type="text" className="form-control search" placeholder="Search" />
          </li>
          {/* User login dropdown */}
          <li className="dropdown">
            <a data-toggle="dropdown" className="dropdown-toggle" href="#">
              <img alt="" src="img/avatar1_small.jpg" />
              <span className="username">Jhon Doue</span>
              <b className="caret"></b>
            </a>
            <ul className="dropdown-menu extended logout dropdown-menu-right">
              <div className="log-arrow-up"></div>
              <li><a href="#"><i className="fa fa-suitcase"></i> Profile</a></li>
              <li><a href="#"><i className="fa fa-cog"></i> Settings</a></li>
              <li><a href="#"><i className="fa fa-bell-o"></i> Notification</a></li>
              <li><a href="login.html"><i className="fa fa-key"></i> Log Out</a></li>
            </ul>
          </li>
          <li className="sb-toggle-right">
            <i className="fa fa-align-right"></i>
          </li>
        </ul>
      </div>
    </header>
  )
}

export default Header