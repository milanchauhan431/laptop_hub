<form>
    <div class="col-md-12">
        <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
        <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($dataRow->ref_id))?$dataRow->ref_id:$ref_id?>">
        <input type="hidden" name="trans_type" id="trans_type" value="<?=(!empty($dataRow->trans_type))?$dataRow->trans_type:$trans_type?>">
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="item_id">Item Name</label>
                <input type="text" id="item_name" class="form-control" value="<?=(!empty($dataRow->item_name))?$dataRow->item_name:$item_name?>" readonly>
                <input type="hidden" name="item_id" id="item_id" class="form-control" value="<?=(!empty($dataRow->item_id))?$dataRow->item_id:$item_id?>">
                <input type="hidden" name="batch_no" id="batch_no" class="form-control" value="<?=(!empty($dataRow->batch_no))?$dataRow->batch_no:$batch_no?>">
            </div>

            <div class="col-md-2 form-group">
                <label for="qty">Repair Qty</label>
                <input type="text" name="qty" id="qty" class="form-control numericOnly" value="<?=(!empty($dataRow->qty))?$dataRow->qty:''?>">
            </div>

            <div class="col-md-3 form-group hidden">
                <label for="amount">Repairing Amount</label>
                <input type="text" name="amount" id="amount" class="form-control floatOnly" value="<?=(!empty($dataRow->amount))?$dataRow->amount:""?>">
            </div>

            <div class="col-md-3 form-group hidden">
                <label for="part_amount">New Part Amount</label>
                <input type="text" name="part_amount" id="part_amount" class="form-control floatOnly" value="<?=(!empty($dataRow->part_amount))?$dataRow->part_amount:""?>" readonly>
            </div>

            <div class="col-md-6 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="error kit_error"></div>
                <div class="table table-responsive">
                    <table id="itemKitTable" class="table table-bordered">
                        <thead class="thead-info">
                            <tr>
                                <th>#</th>
                                <th>Item Name</th>
                                <th>Qty.</th>
                                <th style="width:15%;">Action</th>
                                <th style="width:20%;">Batch No.</th>
                                <th style="width:15%;">Price</th>
                            </tr>
                        </thead>
                        <tbody id="itemKitData">
                            <?php
                                if(!empty($kitList)):
                                    $i=1;
                                    foreach($kitList as $row):
                                        $row->id = "";
                                        $row->kit_item_name = ((!empty($row->item_code))?"[".$row->item_code."] ":"").$row->item_name;
                                        echo '<tr>
                                            <td>
                                                '.$i.'
                                            </td>
                                            <td>
                                                '.$row->kit_item_name.'
                                                <input type="hidden" name="kitData['.$i.'][id]" id="id_'.$i.'" value="'.$row->id.'">
                                                <input type="hidden" name="kitData['.$i.'][kit_item_id]" id="kit_item_id_'.$i.'" value="'.$row->kit_item_id.'">
                                            </td>
                                            <td>
                                                '.floatVal($row->qty).'
                                                <input type="hidden" name="kitData['.$i.'][qty]" id="qty_'.$i.'" value="'.$row->qty.'">
                                            </td>
                                            <td>
                                                <select name="kitData['.$i.'][kit_status]" id="kit_status_'.$i.'" class="form-control kitStatus" data-row_id="'.$i.'">
                                                    <option value="">Select</option>
                                                    <option value="1">Replace</option>
                                                    <option value="2">Repair</option>
                                                </select>
                                                <div class="error kit_status_'.$i.'"></div>
                                            </td>
                                            <td>
                                                <select name="kitData['.$i.'][batch_no]" id="batch_no_'.$i.'" class="form-control select2 batchNo"  data-row_id="'.$i.'">
                                                    <option value="">Select</option>
                                                </select>
                                                <input type="hidden" name="kitData['.$i.'][unique_id]" id="unique_id_'.$i.'" value="">
                                                <div class="error batch_no_'.$i.'"></div>
                                            </td>
                                            <td>
                                                <input type="text" name="kitData['.$i.'][price]" id="price_'.$i.'" class="form-control floatOnly" value="'.((!empty($row->price))?$row->price:"").'">
                                                <div class="error price_'.$i.'"></div>
                                            </td>
                                        </tr>';
                                        $i++;
                                    endforeach;
                                else:
                                    echo '<tr>
                                        <td class="text-center" colspan="6">No data available in table</td>
                                    </tr>';
                                endif;
                            ?>                            
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
    $(document).on('change',"#itemKitTable .kitStatus",function(){
        var id = $(this).data('row_id');
        var status = $(this).val();
        var item_id = $("#kit_item_id_"+id).val();

        if(status == 1){
            $.ajax({
                url : base_url + controller + '/getItemBtachList',
                type : 'post',
                data : {item_id : item_id},
                dataType:'json',
                success:function(res){
                    $("#batch_no_"+id).html(res.batchOption);
                    $("#batch_no_"+id).select2();
                }
            });
        }else{
            $("#batch_no_"+id).html('<option value="">Select</option>');
            $("#batch_no_"+id).select2();
        }
    });

    $(document).on('change','#itemKitTable .batchNo',function(){
        var id = $(this).data('row_id');
        var batch_no = $(this).find(":selected").val();

        $("#price_"+id).val("");
        $("#unique_id_"+id).val("");
        if(batch_no){
            $("#price_"+id).val($(this).find(":selected").data('price'));
            $("#unique_id_"+id).val($(this).find(":selected").data('unique_id'));
        }
    });
});
</script>