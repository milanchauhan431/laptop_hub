<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title"><?=$this->itemTypes[$item_type]?></h4>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn waves-effect waves-light btn-outline-primary float-right addNew permission-write press-add-btn" data-button="both" data-modal_id="modal-xl" data-function="addItem" data-form_title="Add <?=$this->itemTypes[$item_type]?>" data-postdata='{"item_type" : <?=$item_type?> }' ><i class="fa fa-plus"></i> Add <?=$this->itemTypes[$item_type]?></button>                                
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='itemTable' class="table table-bordered ssTable" data-url='/getDTRows/<?=$item_type?>'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>