<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('stockReqTable',0);" id="pending_sr" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('stockReqTable',1);" id="complete_sr" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button> 
                                    </li>
                                    <li class="nav-item"> 
                                        <button onclick="statusTab('stockReqTable',2);" id="reject_sr" class="nav-tab btn waves-effect waves-light btn-outline-dark" style="outline:0px" data-toggle="tab" aria-expanded="false">Rejected</button> 
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4 text-center">
                                <h4 class="card-title">Stock Request</h4>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right addNew permission-write press-add-btn" data-button="both" data-modal_id="modal-md" data-function="addRequest" data-form_title="Add Request" ><i class="fa fa-plus"></i> Add Request</button>
                            </div>
                        </div>                                         
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='stockReqTable' class="table table-bordered ssTable" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>