<!-- ============================================================== -->
<!-- Header -->
<!-- ============================================================== -->
    <?php $this->load->view('includes/header'); ?>
    <link href="<?=base_url()?>assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/js/pages/chartist/chartist-init.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/extra-libs/c3/c3.min.css">
<!-- ============================================================== -->
<!-- End Header  -->
<!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Sales Summery -->
                <!-- ============================================================== -->
                <div class="row">
					<div class="col-lg-3">
						<div class="card bg-orange text-white">
							<div class="card-body">
								<div id="cc1" class="carousel slide" data-ride="carousel">
									<div class="carousel-inner">
										<div class="carousel-item flex-column active">
											<div class="d-flex no-block align-items-center">
												<a href="JavaScript: void(0);"><i class="display-6 fas fa-user text-white" title="Present"></i></a>
												<div class="m-l-15 m-t-10">
													<h4 class="font-medium m-b-0">Present</h4>
													<h5>20</h5>
												</div>
											</div>
										</div>
										<div class="carousel-item flex-column">
											<div class="d-flex no-block align-items-center">
												<a href="JavaScript: void(0);"><i class="display-6 fas fa-user text-white" title="Absent"></i></a>
												<div class="m-l-15 m-t-10">
													<h4 class="font-medium m-b-0">Absent</h4>
													<h5>4</h5>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="card bg-success text-white">
							<div class="card-body">
								<div id="myCarousel22" class="carousel slide" data-ride="carousel">
									<div class="carousel-inner">
										<div class="carousel-item flex-column active">
											<div class="d-flex no-block align-items-center">
												<a href="JavaScript: void(0);"><i class="display-6 icon-Receipt-3 text-white" title="BTC"></i></a>
												<div class="m-l-15 m-t-10">
													<h4 class="font-medium m-b-0">Pending Sales</h4>
													<h5>5</h5>
												</div>
											</div>
										</div>
										<div class="carousel-item flex-column">
											<div class="d-flex no-block align-items-center">
												<a href="JavaScript: void(0);"><i class="display-6 icon-Receipt-3 text-white" title="BTC"></i></a>
												<div class="m-l-15 m-t-10">
													<h4 class="font-medium m-b-0">Today's Sales</h4>
													<h5><i class="fas fa-inr"></i> 1,50,000</h5>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="card bg-cyan text-white">
							<div class="card-body">
								<div id="myCarousel45" class="carousel slide" data-ride="carousel">
									<div class="carousel-inner">
										<div class="carousel-item flex-column active">
											<div class="d-flex no-block align-items-center">
												<a href="JavaScript: void(0);"><i class="display-6 icon-Shopping-Basket text-white" title="BTC"></i></a>
												<div class="m-l-15 m-t-10">
													<h4 class="font-medium m-b-0">Pending Purchase</h4>
													<h5>12</h5>
												</div>
											</div>
										</div>
										<div class="carousel-item flex-column">
											<div class="d-flex no-block align-items-center">
												<a href="JavaScript: void(0);"><i class="display-6 icon-Shopping-Basket text-white" title="BTC"></i></a>
												<div class="m-l-15 m-t-10">
													<h4 class="font-medium m-b-0">Today's Purchase</h4>
													<h5><i class="fas fa-inr"></i> 50,000</h5>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="card bg-dark text-white">
							<div class="card-body">
								<div id="myCarousel33" class="carousel slide" data-ride="carousel">
									<div class="carousel-inner">
										<div class="carousel-item flex-column active">
											<div class="d-flex no-block align-items-center">
												<a href="JavaScript: void(0);"><i class="display-6 fas fa-arrow-left text-white" title="BTC"></i></a>
												<div class="m-l-15 m-t-10">
													<h4 class="font-medium m-b-0">Receivables</h4>
													<h5><i class="fas fa-inr"></i> 50,000</h5>
												</div>
											</div>
										</div>
										<div class="carousel-item flex-column">
											<div class="d-flex no-block align-items-center">
												<a href="JavaScript: void(0);"><i class="display-6 fas fa-arrow-left text-white" title="BTC"></i></a>
												<div class="m-l-15 m-t-10">
													<h4 class="font-medium m-b-0">Payables</h4>
													<h5><i class="fas fa-inr"></i> 50,000</h5>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>				
				
				<!-- ============================================================== -->
                <!-- Sales Order, Sales and Stock / Exchange -->
                <!-- ============================================================== -->
				<div class="row">
					<div class="col-lg-12">
					    <?php
					        
					    ?>
					</div>
			    </div>
				<div class="row">
					<div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="row card-title">
                                    <div class="col-lg-6">
                                        <select class="custom-select ml-auto">
                                            <option selected value="">Today</option>
                                            <option value="1">Last Week</option>
                                            <option value="1">Last Month</option>
                                            <option value="1">Last Year</option>
                                        </select>
                                        <!--<symbol class="fs-20">&#8853;</symbol><symbol class="fs-20">&#8853;</symbol>
                                        <symbol class="fs-20">&#9744;</symbol>
                                        <symbol class="fs-20">&#9711;</symbol>
                                        <symbol class="fs-20">&#9661;</symbol>
                                        <symbol class="fs-20" style="text-orientation: sideways-right;writing-mode: vertical-rl;">&#9674;</symbol>
                                        <symbol class="fs-20">&#10960;</symbol>
                                        <symbol class="fs-20">&#9680;</symbol>
                                        <symbol class="fs-15" style="border:1px solid #000000;padding:0px 2px;">&#9711;</symbol>
                                        <symbol class="fs-20">&#10153;</symbol>
                                        <symbol class="fs-20">&#128167;</symbol>-->
                                    </div>
                                    <div id="legendDiv" class="col-lg-6"></div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="ct-animation-chart" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
						<div class="card">
							<div class="card-header">
                                <div class="row card-title">
                                    <div class="col-lg-12 text-center"><h4 class="text-primary">List of today's birthday</h4></div>
                                </div>
                            </div>
                            <div class="card-body" style="padding:0.4rem;background: #171618;">
                                <div class="sales_track scrollable" style="height:300px;">
                                    <?php
                                        if(!empty($todayBirthdayList)):
                                            foreach($todayBirthdayList as $row):
                                    ?>
                                                <div class="col-md-12 row m-b-2 bd_div" style="margin:0.25rem 0.05rem;padding:0.5rem;width: 99%;">
                                                    <span class="border-span"> </span>
                                                	<span class="border-span"> </span>
                                                	<span class="border-span"> </span>
                                                	<span class="border-span"> </span>
                                                    <div class="col-md-3 text-center">
                                                        <img src="<?=base_url()?>assets/images/bday.png" style="width:80%;">
                                                    </div>
                                                    <div class="col-md-9">
                                                        <h5 class="fs-15 lightning_text"><?=$row->emp_name?></h5>
                                                        <small><?=$row->emp_dsg?> (<?=$row->dept_name?>)</small>
                                                    </div>
                                                </div>
                                    <?php
                                            endforeach;
                                        else:
                                    ?>
                                        <div class="text-center">
                                            <img src="<?=base_url()?>assets/images/sad_emoji.png" style="width:60%;">
                                            <!--<img src="<?=base_url()?>assets/images/sad.png" style="width:60%;">-->
                                            <h5 class="m-t-5 text-white">Today is no one's birthday</h5>
                                        </div>
                                    <?php    
                                        endif;
                                    ?>
								</div>
                            </div>
						</div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Task, Feeds -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card earning-widget">
                            <div class="card-body">
                                <h4 class="m-b-0">Sellers</h4>
                            </div>
                            <div class="border-top scrollable" style="height:365px;">
                                <table class="table v-middle no-border">
                                    <tbody>
                                        <tr>
                                            <td style="width:50px;">
                                                <img src="assets/images/users/user_default.png" width="30" class="rounded-circle" alt="logo">
                                            </td>
                                            <td>Andrew Simon</td>
                                            <td align="right" class="font-medium fs-15">$2300</td>
                                        </tr>
                                        <tr>
                                            <td style="width:50px;">
                                                <img src="assets/images/users/user_default.png" width="30" class="rounded-circle" alt="logo">
                                            </td>
                                            <td>Andrew Simon</td>
                                            <td align="right" class="font-medium fs-15">$2300</td>
                                        </tr>
                                        <tr>
                                            <td style="width:50px;">
                                                <img src="assets/images/users/user_default.png" width="30" class="rounded-circle" alt="logo">
                                            </td>
                                            <td>Andrew Simon</td>
                                            <td align="right" class="font-medium fs-15">$2300</td>
                                        </tr>
                                        <tr>
                                            <td style="width:50px;">
                                                <img src="assets/images/users/user_default.png" width="30" class="rounded-circle" alt="logo">
                                            </td>
                                            <td>Andrew Simon</td>
                                            <td align="right" class="font-medium fs-15">$2300</td>
                                        </tr>
                                        <tr>
                                            <td style="width:50px;">
                                                <img src="assets/images/users/user_default.png" width="30" class="rounded-circle" alt="logo">
                                            </td>
                                            <td>Andrew Simon</td>
                                            <td align="right" class="font-medium fs-15">$2300</td>
                                        </tr>
                                        <tr>
                                            <td style="width:50px;">
                                                <img src="assets/images/users/user_default.png" width="30" class="rounded-circle" alt="logo">
                                            </td>
                                            <td>Andrew Simon</td>
                                            <td align="right" class="font-medium fs-15">$2300</td>
                                        </tr>
                                        <tr>
                                            <td style="width:50px;">
                                                <img src="assets/images/users/user_default.png" width="30" class="rounded-circle" alt="logo">
                                            </td>
                                            <td>Andrew Simon</td>
                                            <td align="right" class="font-medium fs-15">$2300</td>
                                        </tr>
                                        <tr>
                                            <td style="width:50px;">
                                                <img src="assets/images/users/user_default.png" width="30" class="rounded-circle" alt="logo">
                                            </td>
                                            <td>Andrew Simon</td>
                                            <td align="right" class="font-medium fs-15">$2300</td>
                                        </tr>
                                        <tr>
                                            <td style="width:50px;">
                                                <img src="assets/images/users/user_default.png" width="30" class="rounded-circle" alt="logo">
                                            </td>
                                            <td>Andrew Simon</td>
                                            <td align="right" class="font-medium fs-15">$2300</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Credit vs Debit</h4>
                                <div id="stacked-column"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- Trade history / Exchange -->
            <!-- ============================================================== -->
        </div>
        

        <!-- ============================================================== -->
            <?php $this->load->view('includes/footer'); ?>

            <!--<script src="<?=base_url()?>assets/libs/chartist/dist/chartist.min.js"></script>
            <script src="<?=base_url()?>assets/libs/chartist/dist/chartist-plugin-legend.js"></script>
            <script src="<?=base_url()?>assets/js/pages/chartist/chartist-plugin-tooltip.js"></script>
            <script src="<?=base_url()?>assets/js/pages/chartist/chartist-init.js"></script>-->
            <script src="<?=base_url()?>assets/js/pages/c3-chart/bar-pie/c3-stacked-column.js"></script>
            <script src="<?=base_url()?>assets/js/pages/dashboards/dashboard3.js"></script>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->