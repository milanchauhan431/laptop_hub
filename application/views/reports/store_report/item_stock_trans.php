<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-7">
                                <h4 class="card-title pageHeader"><?=$pageHeader?></h4>
                            </div>       
                            <div class="col-md-5 float-right">  
                                <input type="hidden" id="cm_id" value="<?=$cm_id?>">
                                <div class="input-group">
                                    <div class="input-group-append" style="width:80%;">
                                        <select id="item_id" class="form-control select2" >
                                            <option value="">Select Item</option>
                                            <?=getItemListOption($itemList,((!empty($item_id))?$item_id:""));?>
                                        </select>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="button" class="btn waves-effect waves-light btn-success refreshReportData loadData" title="Load Data">
									        <i class="fas fa-sync-alt"></i> Load
								        </button>
                                    </div>
                                </div>
                                <div class="error item_name"></div>
                            </div>                  
                        </div>                                         
                    </div>
                    <div class="card-body reportDiv" style="min-height:75vh">
                        <div class="table-responsive">
                            <table id='reportTable' class="table table-bordered">
								<thead class="thead-info" id="theadData">
									<tr>
										<th class="text-center">#</th>
										<th class="text-left">Location</th>
										<th class="text-left">Batch No.</th>
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
	<?php if(!empty($item_id)): ?>
    setTimeout(function(){$(".loadData").trigger('click');},500);
    <?php endif; ?>
    
    $(document).on('click','.loadData',function(e){
		$(".error").html("");
		var valid = 1;
		var item_id = $('#item_id').val();
        var cm_id = $("#cm_id").val();
		if($("#item_id").val() == ""){$(".item_name").html("Item Name is required.");valid=0;}
		if(valid){
            $.ajax({
                url: base_url + controller + '/getStockTransaction',
                data: {item_id:item_id,cm_id:cm_id},
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