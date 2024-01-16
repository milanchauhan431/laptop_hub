<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="item_type" id="item_type" value="<?=(!empty($dataRow->item_type))?$dataRow->item_type:$item_type?>">  
            
            <?php
                $itemType = (!empty($dataRow->item_type))?$dataRow->item_type:$item_type;
            ?>

            <div class="col-md-3 form-group">
                <label for="item_code">Item Code</label>
                <input type="text" name="item_code" class="form-control" value="<?= (!empty($dataRow->item_code)) ? $dataRow->item_code : ""; ?>" />
            </div>

            <div class="col-md-6 form-group">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" class="form-control req" value="<?=htmlentities((!empty($dataRow->item_name)) ? $dataRow->item_name : "")?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control select2 req">
                    <option value="0">Select</option>
                    <?php
                        foreach ($categoryList as $row) :
                            $selected = (!empty($dataRow->category_id) && $dataRow->category_id == $row->id) ? "selected" : "";
                            echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->category_name . '</option>';
                        endforeach;
                    ?>
                </select>
            </div>       

            <div class="col-md-2 form-group">
                <label for="unit_id">Unit</label>
                <select name="unit_id" id="unit_id" class="form-control select2 req">
                    <option value="0">--</option>
                    <?=getItemUnitListOption($unitData,((!empty($dataRow->unit_id))?$dataRow->unit_id:""))?>
                </select>
            </div>

            <div class="col-md-2 form-group <?=($itemType == 8)?"hidden":""?>">
                <label for="defualt_disc">Defual Disc. (%)</label>
                <input type="text" name="defualt_disc" class="form-control floatOnly req" value="<?=(!empty($dataRow->defualt_disc)) ? $dataRow->defualt_disc : ""?>" />
            </div>

            <div class="col-md-2 form-group <?=($itemType == 8)?"hidden":""?>">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control floatOnly" value="<?=(!empty($dataRow->price))?$dataRow->price:""?>">
            </div>            

            <div class="col-md-2 form-group">
                <label for="hsn_code">HSN Code</label>
                <select name="hsn_code" id="hsn_code" class="form-control select2">
                    <option value="">Select HSN Code</option>
                    <?=getHsnCodeListOption($hsnData,((!empty($dataRow->hsn_code))?$dataRow->hsn_code:""))?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="gst_per">GST (%)</label>
                <select name="gst_per" id="gst_per" class="form-control select2">
                    <?php
                        foreach($this->gstPer as $per=>$text):
                            $selected = (!empty($dataRow->gst_per) && floatVal($dataRow->gst_per) == $per)?"selected":"";
                            echo '<option value="'.$per.'" '.$selected.'>'.$text.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="active">Active</label>
                <select name="active" id="active" class="form-control">
                    <option value="1" <?=(!empty($dataRow->active) && $dataRow->active == 1)?"selected":""?>>Active</option>
                    <option value="0" <?=(!empty($dataRow->active) && $dataRow->active == 0)?"selected":""?>>De-active</option>
                    <option value="2" <?=(!empty($dataRow->active) && $dataRow->active ==2)?"selected":((!empty($active) && $active == 2)?'selected':'')?>>Enquiry</option>
                </select>
            </div>

            <div class="col-md-2 form-group <?=($itemType == 8)?"hidden":""?>">
                <label for="wh_min_qty">Min. Stock Qty (WH)</label>
                <input type="text" name="wh_min_qty" class="form-control floatOnly" value="<?= (!empty($dataRow->wh_min_qty)) ? $dataRow->wh_min_qty : "" ?>" />
            </div>
            
            <div class="col-md-2 form-group <?=($itemType == 8)?"hidden":""?>">
                <label for="wkg">Weight/Nos <small>(In Kg.)</small> </label>
                <input type="text" name="wkg" class="form-control floatOnly" value="<?= (!empty($dataRow->wkg)) ? $dataRow->wkg : "" ?>" />
            </div>

            <div class="col-md-2 form-group hidden">
                <label for="packing_standard">Packing Standard</label>
                <input type="text" name="packing_standard" id="packing_standard" class="form-control numericOnly req" value="<?=(!empty($dataRow->packing_standard))?$dataRow->packing_standard:""?>">
            </div>

            <div class="col-md-2 form-group">
                <label for="cm_id">For All Branch</label>
                <select name="cm_id" id="cm_id" class="form-control">
                    <option value="<?=$this->cm_id?>" <?=(!empty($dataRow->cm_id) && $dataRow->cm_id == $this->cm_id)?"selected":""?> >No</option>
                    <option value="0" <?=(!empty($dataRow->id) && $dataRow->cm_id == 0)?"selected":""?> >Yes</option>
                </select>
            </div>

            <div class="col-md-6 form-group <?=($itemType == 8)?"hidden":""?>">
                <label for="description">Product Description</label>
                <textarea name="description" id="description" class="form-control" rows="1"><?=(!empty($dataRow->description))?$dataRow->description:""?></textarea>
            </div>

            <div class="col-md-12 form-group">
                <label for="note">Remark</label>
                <textarea name="note" id="note" class="form-control" rows="1"><?=(!empty($dataRow->note))?$dataRow->note:""?></textarea>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    $(document).on('change','#hsn_code',function(){
        $("#gst_per").val(($(this).find(':selected').data('gst_per') || 0));
        $("#gst_per").select2();
    });
});
</script>
