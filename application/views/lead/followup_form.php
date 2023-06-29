<form data-res_function="resFollowup">
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="lead_id" id="lead_id" value="<?=$lead_id?>">
            <input type="hidden" name="party_id" id="party_id" value="<?=$leadData->party_id?>">
            <input type="hidden" name="entry_type" id="entry_type" value="<?=!empty($entry_type)?$entry_type:'1'?>">

            <div class="col-md-6 form-group">
                <label for="appointment_date">Followup Date</label>
                <input type="date" name="appointment_date" id="appointment_date" max="<?=date("Y-m-d")?>" class="form-control req" value="<?=(!empty($dataRow->appointment_date))?$dataRow->appointment_date:date("Y-m-d")?>" />
            </div>
            <div class="col-md-6 form-group">
                <label for="mode">Mode</label>
                <select name="mode" id="mode" class="form-control req single-select">
                    <?php
                        foreach($this->appointmentMode as $key=>$row):
							$selected = (!empty($dataRow->mode) and $dataRow->mode == $row)?"selected":"";
                            echo '<option value="'.$key.'" '.$selected .'>'.$row.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
			<div class="col-md-6 form-group">
                <label for="sales_executive">Sales Executives</label>
                <select class="form-control single-select" name="sales_executive" id="sales_executive">
                    <option value="">Select Sales Executive</option>
                    <?php
                    if(!empty($salesExecutives)){
                        foreach($salesExecutives as $row){
                            $selected = (!empty($leadData->sales_executive) && $leadData->sales_executive == $row->id)?'selected':'';
                        ?>
                            <option value="<?=$row->id?>" <?=$selected?>><?=$row->emp_name?></option>
                        <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6 form-group">
                <label for="status">Stage</label>
                <select class="form-control " name="status" id="status">
                    
                    <?php
                        foreach($followupStage as $key=>$row):
							$selected = (!empty($dataRow->status) and $dataRow->status == $row)?"selected":"";
                            echo '<option value="'.$key.'" '.$selected .'>'.$row.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <!-- <div class="col-md-4 form-group">
                <label for="category_id">Category</label>
                <select class="form-control single-select" name="category_id" id="category_id">
                    <option value="">Select Category</option>
                    <?php
                    if(!empty($categoryList)){
                        foreach($categoryList as $row){
                            $selected = (!empty($leadData->category_id) && $leadData->category_id == $row->id)?'selected':'';

                        ?>
                            <option value="<?=$row->id?>" <?=$selected?>><?=$row->category_name?></option>
                        <?php
                        }
                    }
                    ?>
                </select>
            </div> -->
            <div class="col-md-12 form-group">
                <label for="notes">Notes</label>
                <div class="input-group">
                    <textarea name="notes" id="notes" class="form-control"><?=(!empty($dataRow->notes))?$dataRow->notes:""?></textarea>
                </div>
            </div>
            <div class="col-md-12 form-group float-right">
                <button type="button" class="btn waves-effect waves-light btn-outline-success btn-save float-right mt-30" onclick="customStore({'formId':'followUp','fnsave':'saveFollowup'},);"> <i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>    
</form>
<hr>
<style>#followupTable td,#followupTable th{font-size:0.8rem;}</style>
<div class="col-md-12">
    <div class="row">
        <label for="">Followups : </label>
        <div class="table-responsive">
            <table id='followupTable' class="table table-bordered">
                <thead class="thead-info">
                    <tr>
                        <th style="width:5%;">#</th>
                        <th>Date</th>
                        <th>Mode</th>
                        <th>Sales Executives</th>
                        <th>Stage</th>
                        <th>Notes</th>
                        <th style="width:10%;">Action</th>
                    </tr>                            
                </thead>
                <tbody id="followupData">
					
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    var followupTrans = {'postData':{'lead_id':$("#lead_id").val(),'entry_type':$("#entry_type").val()},'table_id':"followupTable",'tbody_id':'followupData','tfoot_id':'','fnget':'followupListHtml'};
    getTransHtml(followupTrans);
});

function resFollowup(data){
    if(data.status==1){
        toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

        var followupTrans = {'postData':{'lead_id':$("#lead_id").val(),'entry_type':$("#entry_type").val()},'table_id':"followupTable",'tbody_id':'followupData','tfoot_id':'','fnget':'followupListHtml'};
        getTransHtml(followupTrans);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });
        }			
    }
}
</script>
