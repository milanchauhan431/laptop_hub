<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="card-title pageHeader"><?=$pageHeader?></h4>
                            </div>       
                            <div class="col-md-6 float-right">  
                                <div class="input-group">
                                    <div class="input-group-append <?=($this->cm_id != 1)?"hidden":""?>" style="<?=($this->cm_id != 1)?"width:25%;":""?>">
                                        <select name="cm_id" id="cm_id" class="form-control select2">
                                            <option value="1,2,3,4">ALL Branch</option>
                                            <option value="1" <?=($this->cm_id == 1)?"selected":""?> >Branch 1</option>
                                            <option value="2" <?=($this->cm_id == 2)?"selected":""?> >Branch 2</option>
                                            <option value="3" <?=($this->cm_id == 3)?"selected":""?> >Branch 3</option>
                                            <option value="4" <?=($this->cm_id == 4)?"selected":""?> >Branch 4</option>
                                        </select>
                                    </div>
                                    <div class="input-group-append" style="<?=($this->cm_id != 1)?"width:50%;":"width:35%;"?>">
                                        <select id="item_type" class="form-control select2">
                                            <?php
                                                foreach($this->itemTypes as $type=>$typeName):
                                                    echo '<option value="'.$type.'">'.$typeName.'</option>';
                                                endforeach;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="input-group-append" style="<?=($this->cm_id != 1)?"width:30%;":"width:20%;"?>">
                                        <select id="stock_type" class="form-control select2" >
                                            <option value="0">ALL</option>
                                            <option value="1">With Stock</option>
                                            <option value="2">Without Stock</option>
                                        </select>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="button" class="btn waves-effect waves-light btn-success refreshReportData loadData" title="Load Data">
									        <i class="fas fa-sync-alt"></i> Load
								        </button>
                                    </div>
                                </div>
                                <div class="error stock_type"></div>
                            </div>                  
                        </div>                                         
                    </div>
                    <div class="card-body reportDiv" style="min-height:75vh">
                        <div class="table-responsive">
                            <table id='reportTable' class="table table-bordered">
								<thead class="thead-info" id="theadData">
                                    <tr class="text-center">
                                        <th colspan="4">Stock Register</th>
                                    </tr>
									<tr>
										<th class="text-center">#</th>
										<th class="text-left">Item Code</th>
										<th class="text-left">Item Description</th>
										<th class="text-right">Balance Qty.</th>
									</tr>
								</thead>
								<tbody id="tbodyData"></tbody>
							</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>


<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function(){
	reportTable();
    setTimeout(function(){$(".loadData").trigger('click');},500);
    
    $(document).on('click','.loadData',function(e){
		$(".error").html("");
		var valid = 1;
		var item_type = $('#item_type').val();
		var stock_type = $('#stock_type').val();
        var cm_id = $("#cm_id").val();
		if($("#item_type").val() == ""){$(".item_type").html("Item Type is required.");valid=0;}
		if($("#stock_type").val() == ""){$(".stock_type").html("Stock type is required.");valid=0;}
		if(valid){
            $.ajax({
                url: base_url + controller + '/getStockRegisterData',
                data: {item_type:item_type,stock_type:stock_type,cm_id:cm_id},
				type: "POST",
				dataType:'json',
				success:function(data){
                    $("#reportTable").DataTable().clear().destroy();
					$("#tbodyData").html(data.tbody);
					reportTable();
                }
            });
        }
    });   
});
</script>