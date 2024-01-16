<form>
    <div class="col-md-12">
        <div class="error general_error"></div>
        <div class="row">
            <input type="hidden" name="id" id="service_id" value="<?=$service_id?>">

            <div class="col-md-2 form-group">
                <label for="trans_number">Entry No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:$trans_number?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="trans_date">Entry Date</label>
                <input type="text" name="trans_date" id="trans_date" class="form-control req" value="<?=(!empty($dataRow->trans_date))?getFyDate($dataRow->trans_date,"d-m-Y"):getFyDate()?>" readonly>
            </div>

            <div class="col-md-4 form-group">
                <label for="party_id">Customer Name</label>
                <input type="text" id="party_id" class="form-control req" value="<?=(!empty($dataRow->party_name))?$dataRow->party_name:""?>" readonly>
            </div>

            <div class="col-md-4 form-group">
                <label for="item_id">Product Name</label>
                <input type="text" id="item_id" class="form-control req" value="<?=(!empty($dataRow->item_name))?$dataRow->item_name:""?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="qty">Qty.</label>
                <input type="text" name="qty" id="qty" class="form-control floatOnly req" value="<?=(!empty($dataRow->qty))?floatval($dataRow->qty):""?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="warranty_status">Warranty Status</label>
                <input type="text" id="warranty_status" class="form-control floatOnly" value="<?=(!empty($dataRow->warranty_status))?"In Warranty":"Out of Warranty"?>" readonly>
            </div>

            <div class="col-md-8 form-group">
                <label for="remark">Note</label>
                <input type="text" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>" readonly>
            </div>
        </div>

        <hr>

        <div class="row" id="NewkitForm">
            <div class="col-md-12">
                <h4>Service Details :</h4>
            </div>

            <input type="hidden" id="kit_item_type" value="">
            <div class="col-md-4 form-group">
                <label for="kit_item_id">Part Name</label>
                <select id="kit_item_id" class="form-control select2 req">
                    <option value="">Select Item</option>
                    <?=getItemListOption($itemList)?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="kit_batch_no">Batch No.</label>
                <select id="kit_batch_no" class="form-control select2 req">
                    <option value="">Select Batch No.</option>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="kit_qty">Qty.</label>
                <input type="text" id="kit_qty" class="form-control numericOnly req" value="">
            </div>

            <div class="col-md-3 form-group">
                <label for="kit_price">Price</label>
                <input type="text" id="kit_price" class="form-control floatOnly req" value="">
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Note</label>
                <div class="input-group">
                    <input type="text" id="kit_remark" class="form-control" value="">
                    <button type="button" id="addKit" class="btn btn-outline-info waves-effect waves-light"><i class="fa fa-plus"></i> ADD</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="error item_error"></div>
                <div class="table table-responsive">
                    <table id="newItemKitTable" class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>#</th>
                                <th>Part Name</th>
                                <th>Batch No.</th>
                                <th>Qty.</th>
                                <th>Price</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="newItemKitData">
                            <tr id="noData">
                                <td class="text-center" colspan="7">No data available in table</td>
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
    $(document).on('change','#kit_item_id',function(){
        var item_id = $(this).val();
        if(item_id){
            $.ajax({
                url : base_url + controller + '/getItemBtachList',
                type : 'post',
                data : {item_id : item_id},
                dataType:'json',
                success:function(res){
                    $("#kit_batch_no").html(res.batchOption);
                    $("#kit_batch_no").select2();

                    $("#kit_item_type").val(res.itemDetail.item_type);
                }
            });
        }else{
            $("#kit_batch_no").html('<option value="">Select</option>');
            $("#kit_batch_no").select2();
            $("#kit_item_type").val("");
        }
    });

    $(document).on('change','#kit_batch_no',function(){
        var batch_no = $(this).find(":selected").val();
        $("#kit_price").val("");
        if(batch_no){
            $("#kit_price").val($(this).find(":selected").data('price'));
        }
    });

    $(document).on('click','#NewkitForm #addKit',function(e){
        e.stopImmediatePropagation();
        e.preventDefault();

        $("#NewkitForm .error").html("");
        var kit_item_id = $("#NewkitForm #kit_item_id").val();
        var kit_item_type = $("#NewkitForm #kit_item_type").val();
        var kit_item_name = $("#NewkitForm #kit_item_id :selected").text();
        var batch_no = $("#NewkitForm #kit_batch_no :selected").val();
        var unique_id = $("#NewkitForm #kit_batch_no :selected").data("unique_id");
        var qty = $("#NewkitForm #kit_qty").val();
        var price = $("#NewkitForm #kit_price").val();
        var remark = $("#NewkitForm #kit_remark").val();

        if(kit_item_id == ""){
            $(".kit_item_id").html("Item Name is required.");
        }

        if(batch_no == "" && kit_item_type != 8){
            $(".kit_batch_no").html("Batch No. is required.");
        }

        if(qty == "" || parseFloat(qty) == 0){
            $(".kit_qty").html("Qty is required.");
        }

        if(price == "" || parseFloat(price) == 0){
            $(".kit_price").html("Price is required.");
        }

        var errorCount = $('#NewkitForm  .error:not(:empty)').length;

		if(errorCount == 0){
            formData = {};
            formData.id = "";
            formData.kit_item_id = kit_item_id;
            formData.kit_item_type = kit_item_type;
            formData.kit_item_name = kit_item_name;
            formData.qty = qty;
            formData.batch_no = batch_no;
            formData.unique_id = unique_id;
            formData.price = price;
            formData.amount = parseFloat(parseFloat(qty) * parseFloat(price)).toFixed(2);
            formData.remark = remark;

            addKitItem(formData);

            $("#NewkitForm #kit_item_id").val("");$("#NewkitForm #kit_item_id").select2();
            $("#NewkitForm #kit_item_type").val("");
            $("#NewkitForm #kit_batch_no").val("");$("#NewkitForm #kit_batch_no").select2();
            $("#NewkitForm #kit_qty").val("");
            $("#NewkitForm #kit_price").val("");
            $("#NewkitForm #kit_remark").val("");
        }
    });
});

function addKitItem(data){
    $('table#newItemKitTable tr#noData').remove();
    //Get the reference of the Table's TBODY element.
    var tblName = "newItemKitTable";
    
    var tBody = $("#"+tblName+" > TBODY")[0];
    
    //Add Row.
    row = tBody.insertRow(-1);
    //Add index cell
    var countRow = $('#'+tblName+' tbody tr:last').index() + 1;
    var cell = $(row.insertCell(-1));
    cell.html(countRow);
    cell.attr("style","width:5%;");	

    var idInput = $("<input/>",{type:"hidden",name:"newKitData["+kitItemCount+"][id]",value:data.id});
    var itemIdInput = $("<input/>",{type:"hidden",name:"newKitData["+kitItemCount+"][kit_item_id]",value:data.kit_item_id});
    var itemTypeInput = $("<input/>",{type:"hidden",name:"newKitData["+kitItemCount+"][kit_item_type]",value:data.kit_item_type});
    cell = $(row.insertCell(-1));
    cell.html(data.kit_item_name);	
    cell.append(idInput);
    cell.append(itemIdInput);
    cell.append(itemTypeInput);
    cell.append('<div class="error kit_item_id_'+kitItemCount+'"></div>');

    var batchNoInput = $("<input/>",{type:"hidden",name:"newKitData["+kitItemCount+"][batch_no]",value:data.batch_no});
    var uniqueIdInput = $("<input/>",{type:"hidden",name:"newKitData["+kitItemCount+"][unique_id]",value:data.unique_id});
    cell = $(row.insertCell(-1));
    cell.html(data.batch_no);
    cell.append(batchNoInput);
    cell.append(uniqueIdInput);
    cell.append('<div class="error kit_batch_no_'+kitItemCount+'"></div>');

    var qtyInput = $("<input/>",{type:"hidden",name:"newKitData["+kitItemCount+"][qty]",value:data.qty});
    cell = $(row.insertCell(-1));
    cell.html(data.qty);
    cell.append(qtyInput);
    cell.append('<div class="error kit_qty_'+kitItemCount+'"></div>');

    var priceInput = $("<input/>",{type:"hidden",name:"newKitData["+kitItemCount+"][price]",value:data.price});
    var amountInput = $("<input/>",{type:"hidden",name:"newKitData["+kitItemCount+"][amount]",class:'amount',value:data.amount});
    cell = $(row.insertCell(-1));
    cell.html(data.price);
    cell.append(priceInput);
    cell.append(amountInput);
    cell.append('<div class="error kit_price_'+kitItemCount+'"></div>');

    var remarkInput = $("<input/>",{type:"hidden",name:"newKitData["+kitItemCount+"][remark]",value:data.remark});
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
	var table = $("#newItemKitTable")[0];
	table.deleteRow(row[0].rowIndex);

	$('#newItemKitTable tbody tr td:nth-child(1)').each(function(idx, ele) {
        ele.textContent = idx + 1;
    });
	var countTR = $('#newItemKitTable tbody tr:last').index() + 1;

    if (countTR == 0) {
        $("#newItemKitTable tbody").html('<tr id="noData"><td colspan="7" align="center">No data available in table</td></tr>');
    }
}
</script>