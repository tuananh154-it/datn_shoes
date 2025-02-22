import React from "react";
const Dashboard: React.FC = () => {
  return (
     <>
    <section id="main-content">
    <section className="wrapper">
        <div className="row state-overview">
            <div className="col-lg-3 col-sm-6">
                <section className="card">
                    <div className="symbol terques">
                        <i className="fa fa-user"></i>
                    </div>
                    <div className="value">
                        <h1 className="count">
                            0
                        </h1>
                        <p>New Users</p>
                    </div>
                </section>
            </div>
            <div className="col-lg-3 col-sm-6">
                <section className="card">
                    <div className="symbol red">
                        <i className="fa fa-tags"></i>
                    </div>
                    <div className="value">
                        <h1 className=" count2">
                            0
                        </h1>
                        <p>Sales</p>
                    </div>
                </section>
            </div>
            <div className="col-lg-3 col-sm-6">
                <section className="card">
                    <div className="symbol yellow">
                        <i className="fa fa-shopping-cart"></i>
                    </div>
                    <div className="value">
                        <h1 className=" count3">
                            0
                        </h1>
                        <p>New Order</p>
                    </div>
                </section>
            </div>
            <div className="col-lg-3 col-sm-6">
                <section className="card">
                    <div className="symbol blue">
                        <i className="fa fa-bar-chart-o"></i>
                    </div>
                    <div className="value">
                        <h1 className=" count4">
                            0
                        </h1>
                        <p>Total Profit</p>
                    </div>
                </section>
            </div>
        </div>

        <div className="row">
            <div className="col-lg-8">
                <div className="border-head">
                    <h3>Earning Graph</h3>
                </div>
                <div className="custom-bar-chart">
                    <ul className="y-axis">
                        <li><span>100</span></li>
                        <li><span>80</span></li>
                        <li><span>60</span></li>
                        <li><span>40</span></li>
                        <li><span>20</span></li>
                        <li><span>0</span></li>
                    </ul>
                    <div className="bar">
                        <div className="title">JAN</div>
                        <div className="value tooltips" data-original-title="80%" data-toggle="tooltip" data-placement="top">80%</div>
                    </div>
                    <div className="bar ">
                        <div className="title">FEB</div>
                        <div className="value tooltips" data-original-title="50%" data-toggle="tooltip" data-placement="top">50%</div>
                    </div>
                    <div className="bar ">
                        <div className="title">MAR</div>
                        <div className="value tooltips" data-original-title="40%" data-toggle="tooltip" data-placement="top">40%</div>
                    </div>
                    <div className="bar ">
                        <div className="title">APR</div>
                        <div className="value tooltips" data-original-title="55%" data-toggle="tooltip" data-placement="top">55%</div>
                    </div>
                    <div className="bar">
                        <div className="title">MAY</div>
                        <div className="value tooltips" data-original-title="20%" data-toggle="tooltip" data-placement="top">20%</div>
                    </div>
                    <div className="bar ">
                        <div className="title">JUN</div>
                        <div className="value tooltips" data-original-title="39%" data-toggle="tooltip" data-placement="top">39%</div>
                    </div>
                    <div className="bar">
                        <div className="title">JUL</div>
                        <div className="value tooltips" data-original-title="75%" data-toggle="tooltip" data-placement="top">75%</div>
                    </div>
                    <div className="bar ">
                        <div className="title">AUG</div>
                        <div className="value tooltips" data-original-title="45%" data-toggle="tooltip" data-placement="top">45%</div>
                    </div>
                    <div className="bar ">
                        <div className="title">SEP</div>
                        <div className="value tooltips" data-original-title="50%" data-toggle="tooltip" data-placement="top">50%</div>
                    </div>
                    <div className="bar ">
                        <div className="title">OCT</div>
                        <div className="value tooltips" data-original-title="42%" data-toggle="tooltip" data-placement="top">42%</div>
                    </div>
                    <div className="bar ">
                        <div className="title">NOV</div>
                        <div className="value tooltips" data-original-title="60%" data-toggle="tooltip" data-placement="top">60%</div>
                    </div>
                    <div className="bar ">
                        <div className="title">DEC</div>
                        <div className="value tooltips" data-original-title="90%" data-toggle="tooltip" data-placement="top">90%</div>
                    </div>
                </div>
            </div>
            <div className="col-lg-4">
                <div className="card terques-chart">
                    <div className="card-body chart-texture">
                        <div className="chart">
                            <div className="heading">
                                <span>Friday</span>
                                <strong>$ 57,00 | 15%</strong>
                            </div>
                            <div className="sparkline" data-type="line" data-resize="true" data-height="75" data-width="90%" data-line-width="1" data-line-color="#fff" data-spot-color="#fff" data-fill-color="" data-highlight-line-color="#fff" data-spot-radius="4" data-data="[200,135,667,333,526,996,564,123,890,564,455]"></div>
                        </div>
                    </div>
                    <div className="chart-tittle">
                        <span className="title">New Earning</span>
                        <span className="value">
                            <a href="#" className="active">Market</a>
                            |
                            <a href="#">Referal</a>
                            |
                            <a href="#">Online</a>
                        </span>
                    </div>
                </div>

                <div className="card green-chart">
                    <div className="card-body">
                        <div className="chart">
                            <div className="heading">
                                <span>June</span>
                                <strong>23 Days | 65%</strong>
                            </div>
                            <div id="barchart"></div>
                        </div>
                    </div>
                    <div className="chart-tittle">
                        <span className="title">Total Earning</span>
                        <span className="value">$, 76,54,678</span>
                    </div>
                </div>
            </div>
        </div>
        <div className="row">
            <div className="col-lg-4">
                <section className="card">
                    <div className="card-body">
                        <a href="#" className="task-thumb">
                            <img src="img/avatar1.jpg" alt=""/>
                        </a>
                        <div className="task-thumb-details">
                            <h1><a href="#">Anjelina Joli</a></h1>
                            <p>Senior Architect</p>
                        </div>
                    </div>
                    <table className="table table-hover personal-task">
                        <tbody>
                          <tr>
                              <td>
                                  <i className=" fa fa-tasks"></i>
                              </td>
                              <td>New Task Issued</td>
                              <td> 02</td>
                          </tr>
                          <tr>
                              <td>
                                  <i className="fa fa-exclamation-triangle"></i>
                              </td>
                              <td>Task Pending</td>
                              <td> 14</td>
                          </tr>
                          <tr>
                              <td>
                                  <i className="fa fa-envelope"></i>
                              </td>
                              <td>Inbox</td>
                              <td> 45</td>
                          </tr>
                          <tr>
                              <td>
                                  <i className=" fa fa-bell-o"></i>
                              </td>
                              <td>New Notification</td>
                              <td> 09</td>
                          </tr>
                        </tbody>
                    </table>
                </section>
            </div>
            <div className="col-lg-8">
                <section className="card">
                    <div className="card-body progress-card">
                        <div className="task-progress">
                            <h1>Work Progress</h1>
                            <p>Anjelina Joli</p>
                        </div>
                        <div className="task-option">
                            <select className="styled">
                                <option>Anjelina Joli</option>
                                <option>Tom Crouse</option>
                                <option>Jhon Due</option>
                            </select>
                        </div>
                    </div>
                    <table className="table table-hover personal-task">
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                Target Sell
                            </td>
                            <td>
                                <span className="badge badge-pill badge-danger">75%</span>
                            </td>
                            <td>
                              <div id="work-progress1"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                Product Delivery
                            </td>
                            <td>
                                <span className="badge badge-pill badge-success">43%</span>
                            </td>
                            <td>
                                <div id="work-progress2"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                Payment Collection
                            </td>
                            <td>
                                <span className="badge badge-pill badge-info">67%</span>
                            </td>
                            <td>
                                <div id="work-progress3"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>
                                Work Progress
                            </td>
                            <td>
                                <span className="badge badge-pill badge-warning">30%</span>
                            </td>
                            <td>
                                <div id="work-progress4"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>
                                Delivery Pending
                            </td>
                            <td>
                                <span className="badge badge-pill badge-primary">15%</span>
                            </td>
                            <td>
                                <div id="work-progress5"></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </section>
</section>
     </>
);
}

export default Dashboard