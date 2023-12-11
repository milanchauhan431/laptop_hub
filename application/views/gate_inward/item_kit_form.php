<form>
    <div class="col-md-12">
        <input type="hidden" name="mir_trans_id" value="<?=$mir_trans_id?>">
        <input type="hidden" name="item_id" value="<?=$item_id?>">
        <input type="hidden" name="unique_id" value="<?=$unique_id?>">

        <div class="row" id="NewkitForm">
            <div class="col-md-4 form-group">
                <label for="item_id">Item Name</label>
                <select id="item_id" class="form-control select2 req">
                    <option value="">Select Item</option>
                    <?=getItemListOption($itemList)?>
                </select>
            </div>
            <div class="col-md-2 form-group">
                <label for="qty">Qty.</label>
                <input type="text" id="qty" class="form-control numericOnly req" value="">
            </div>
            <div class="col-md-6 form-group">
                <label for="remark">Note</label>
                <div class="input-group">
                    <input type="text" id="remark" class="form-control" value="">
                    <button type="button" id="addKit" class="btn btn-outline-info waves-effect waves-light"><i class="fa fa-plus"></i></button>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="error item_error"></div>
                <div class="table table-responsive">
                    <table id="itemKitTable" class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Qty.</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="itemKitData">
                            <tr id="noData">
                                <td class="text-center" colspan="5">No data available in table</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>         
        </div>
    </div>
</form>


<script>
var kitItemCount = 0;
$(document).ready(function(){
    $(document).on('click','#addKit',function(){
        $("#NewkitForm .error").html("");
        var kit_item_id = $("#itemKitForm #item_id").val();
        var kit_item_name = $("#itemKitForm #item_id :selected").text();
        var qty = $("#itemKitForm #qty").val();
        var remark = $("#itemKitForm #remark").val();

        if(kit_item_id == ""){
            $(".item_id").html("Item Name is required.");
        }

        if(qty == "" || parseFloat(qty) == 0){
            $(".qty").html("Qty is required.");
        }

        var errorCount = $('#NewkitForm  .error:not(:empty)').length;

		if(errorCount == 0){
            formData = {};
            formData.id = "";
            formData.kit_item_id = kit_item_id;
            formData.kit_item_name = kit_item_name;
            formData.qty = qty;
            formData.remark = remark;

            addKitItem(formData);

            $("#itemKitForm #item_id").val("");$("#itemKitForm #item_id").select2();
            $("#itemKitForm #qty").val("");
            $("#itemKitForm #remark").val("");
        }
    });
});

function addKitItem(data){
    $('table#itemKitTable tr#noData').remove();
    //Get the reference of the Table's TBODY element.
    var tblName = "itemKitTable";
    
    var tBody = $("#"+tblName+" > TBODY")[0];
    
    //Add Row.
    row = tBody.insertRow(-1);
    //Add index cell
    var countRow = $('#'+tblName+' tbody tr:last').index() + 1;
    var cell = $(row.insertCell(-1));
    cell.html(countRow);
    cell.attr("style","width:5%;");	

    var idInput = $("<input/>",{type:"hidden",name:"kitData["+kitItemCount+"][id]",value:data.id});
    var itemIdInput = $("<input/>",{type:"hidden",name:"kitData["+kitItemCount+"][kit_item_id]",value:data.kit_item_id});
    cell = $(row.insertCell(-1));
    cell.html(data.kit_item_name);	
    cell.append(idInput);
    cell.append(itemIdInput);

    var qtyInput = $("<input/>",{type:"hidden",name:"kitData["+kitItemCount+"][qty]",value:data.qty});
    cell = $(row.insertCell(-1));
    cell.html(data.qty);
    cell.append(qtyInput);

    var remarkInput = $("<input/>",{type:"hidden",name:"kitData["+kitItemCount+"][remark]",value:data.remark});
    cell = $(row.insertCell(-1));
    cell.html(data.remark);
    cell.append(remarkInput);

    //Add Button cell.	
    var btnRemove = $('<button><i class="ti-trash"></i></button>');
    btnRemove.attr("type", "button");
    btnRemove.attr("onclick", "RemoveKitItem(this);");
    btnRemove.attr("style","margin-left:4px;");
    btnRemove.attr("class", "btn btn-sm btn-outline-danger waves-effect waves-light");
    cell = $(row.insertCell(-1));
    cell.append(btnRemove);
    cell.attr("class","text-center");
    cell.attr("style","width:10%;");

    kitItemCount++;
}

function RemoveKitItem(button){
    var row = $(button).closest("TR");
	var table = $("#itemKitTable")[0];
	table.deleteRow(row[0].rowIndex);

	$('#itemKitTable tbody tr td:nth-child(1)').each(function(idx, ele) {
        ele.textContent = idx + 1;
    });
	var countTR = $('#itemKitTable tbody tr:last').index() + 1;

    if (countTR == 0) {
        $("#itemKitTable tbody").html('<tr id="noData"><td colspan="5" align="center">No data available in table</td></tr>');
    }
}
</script>

<?php
    if(!empty($kitList)):
        foreach($kitList as $row):
            $row->kit_item_name = ((!empty($row->item_code))?"[".$row->item_code."] ":"").$row->item_name;
            echo "<script>addKitItem(".json_encode($row).");</script>";
        endforeach;
    endif;
?>