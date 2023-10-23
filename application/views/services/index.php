<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-5">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('serviceTable','2/26','getStoreDtHeader','serviceGI');" class="nav-tab btn waves-effect waves-light btn-outline-danger active" id="pending_ser" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
                                    <li class="nav-item">
                                        <button onclick="statusTab('serviceTable','1/58','getStoreDtHeader','service');" class="nav-tab btn waves-effect waves-light btn-outline-success" id="completed_ser" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-2 text-center">
                                <h4 class="card-title">Service</h4>
                            </div>
                            <div class="col-md-5">
                                <!-- <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right permission-write addNew press-add-btn" data-button="both" data-modal_id="modal-xl" data-function="addCustomize" data-form_title="Customize"><i class="fa fa-plus"></i> Customize</button> -->
                            </div>
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='serviceTable' class="table table-bordered ssTable" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
