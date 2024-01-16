<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="trans_type" id="trans_type" value="2">
            <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">

            <div class="col-md-2 form-group">
                <label for="to_cm_id">Req. From</label>
                <input type="text" class="form-control" value="<?=(!empty($dataRow->company_code))?$dataRow->company_code:""?>" readonly>

                <input type="hidden" name="to_cm_id" id="to_cm_id" value="<?=(!empty($dataRow->cm_id))?$dataRow->cm_id:""?>"> 
            </div>

            <div class="col-md-4 form-group">
                <label for="item_id">Product Name</label>
                <input type="text" class="form-control" value="<?=(!empty($dataRow->item_name))?$dataRow->item_name:""?>" readonly>
                
                <input type="hidden" name="item_id" id="item_id" value="<?=(!empty($dataRow->item_id))?$dataRow->item_id:""?>"> 
            </div>

            <div class="col-md-2 form-group">
                <label for="qty">Req. Qty.</label>
                <input type="text" name="qty" id="qty" class="form-control" value="<?=(!empty($dataRow->qty))?floatval($dataRow->qty):""?>" readonly> 
            </div>

            <div class="col-md-2 form-group">
                <label for="issue_qty">Issue. Qty.</label>
                <input type="text" name="issue_qty" id="issue_qty" class="form-control" value="<?=(!empty($dataRow->issue_qty))?floatval($dataRow->issue_qty):""?>" readonly> 
            </div>

            <div class="col-md-2 form-group">
                <label for="pending_qty">Pending Qty.</label>
                <input type="text" id="pending_qty" class="form-control" value="<?=(!empty($dataRow->pending_qty))?floatval($dataRow->pending_qty):""?>" readonly> 
            </div>

            <div class="col-md-2 form-group">
                <label for="remark">Req. Remark</label>
                <input type="text" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?floatval($dataRow->remark):""?>" readonly> 
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="error batchError"></div>
            <div class="table table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th>Location</th>
                            <th>Batch No.</th>
                            <th>Stock Qty.</th>
                            <th style="width:15%;">Issue Qty.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=0;
                            foreach($itemBatchList as $row):
                                echo '<tr>
                                    <td>
                                        [ '.$row->store_name.' ] '.$row->location.'
                                        <input type="hidden" name="batchData['.$i.'][unique_id]" value="'.$row->unique_id.'">
                                        <input type="hidden" name="batchData['.$i.'][location_id]" value="'.$row->location_id.'">
                                    </td>
                                    <td>
                                        '.$row->batch_no.'
                                        <input type="hidden" name="batchData['.$i.'][batch_no]" value="'.$row->batch_no.'">
                                    </td>
                                    <td>
                                        '.floatval($row->qty).'
                                    </td>
                                    <td>
                                        <input type="text" name="batchData['.$i.'][batch_qty]" class="form-control floatOnly" value="">
                                    </td>
                                </tr>';
                                $i++;
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>