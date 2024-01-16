<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="trans_type" id="trans_type" value="<?=(!empty($dataRow->trans_type))?$dataRow->trans_type:"1"?>">
            <input type="date" name="trans_date" id="trans_date" class="hidden" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:getFyDate()?>">

            <div class="col-md-4 form-group">
                <label for="to_cm_id">Branch</label>
                <select name="to_cm_id" id="to_cm_id" class="form-control select2 req">
                    <option value="">Select</option>
                    <?=getCompanyListOption($companyList,( (!empty($dataRow->to_cm_id))?$dataRow->to_cm_id:"" ))?>
                </select>
            </div>

            <div class="col-md-8 form-group">
                <label for="item_id">Product Name</label>
                <select name="item_id" id="item_id" class="form-control select2 req">
                    <option value="">Select Product</option>
                    <?=getItemListOption($itemList,( (!empty($dataRow->item_id))?$dataRow->item_id:"" ))?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="qty">Req. Qty.</label>
                <input type="text" name="qty" id="qty" class="form-control floatOnly req" value="<?=(!empty($dataRow->qty))?floatval($dataRow->qty):""?>"> 
            </div>

            <div class="col-md-9 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
    </div>
</form>