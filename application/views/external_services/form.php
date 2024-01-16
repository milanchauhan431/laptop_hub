<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="trans_type" id="trans_type" value="<?=(!empty($dataRow->trans_type))?$dataRow->trans_type:"3"?>">
            <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:$trans_prefix?>">
            <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:$trans_no?>">

            <div class="col-md-2 form-group">
                <label for="trans_number">Entry No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:$trans_number?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="trans_date">Entry Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control req" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:getFyDate()?>">
            </div>

            <div class="col-md-4 form-group">
                <label for="party_id">Party Name</label>
                <select name="party_id" id="party_id" class="form-control select2 req">
                    <option value="">Select Party</option>
                    <?=getPartyListOption($partyList,((!empty($dataRow->party_id))?$dataRow->party_id:""))?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="item_id">Item Name</label>
                <select name="item_id" id="item_id" class="form-control select2 req">
                    <option value="">Select Item</option>
                    <?=getItemListOption($itemList,( (!empty($dataRow->item_id))?$dataRow->item_id:"" ))?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="qty">Qty.</label>
                <input type="text" name="qty" id="qty" class="form-control floatOnly req" value="<?=(!empty($dataRow->qty))?$dataRow->qty:""?>">
            </div>

            <div class="col-md-2 form-group">
                <label for="warranty_status">Warranty Status</label>
                <select name="warranty_status" id="warranty_status" class="form-control">
                    <option value="0">Out of Warranty</option>
                    <option value="1">In Warranty</option>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="service_inspector_id">Service Inspector</label>
                <select name="service_inspector_id" id="service_inspector_id" class="form-control select2">
                    <option value="">Select Inspector</option>
                    <?=getEmployeeListOptions($employeeList,( (!empty($dataRow->service_inspector_id))?$dataRow->service_inspector_id:"" ))?>
                </select>
            </div>

            <div class="col-md-5 form-group">
                <label for="remark">Note</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
    </div>
</form>