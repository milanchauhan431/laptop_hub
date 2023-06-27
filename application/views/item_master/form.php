<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="item_type" id="item_type" value="<?=(!empty($dataRow->item_type))?$dataRow->item_type:$item_type?>">

            <div class="col-md-3 form-group">
                <label for="item_code">Item Code</label>
                <input type="text" name="item_code" class="form-control" value="<?= (!empty($dataRow->item_code)) ? $dataRow->item_code : ""; ?>" />
            </div>

            <?php
                if(!empty($dataRow->item_type) && $dataRow->item_type == 1 || $item_type == 1):
            ?>
                <div class="col-md-6 form-group">
                    <label for="item_name">Item Name</label>
                    <div class="input-group">
                        <select name="size_id" id="size_id" class="form-control single-select" style="width:25%;">
                            <option value="">Select Size</option>
                            <?php
                                foreach($sizeList as $row):
                                    $selected = (!empty($dataRow->size_id) && $dataRow->size_id == $row->id)?"selected":"";
                                    echo '<option value="'.$row->id.'" '.$selected.'>'.$row->size.'</option>';
                                endforeach;
                            ?>
                        </select>
                        <!-- <div class="input-group-append"> -->
                            <select name="capacity" id="capacity" class="form-control single-select" style="width:25%;">
                                <?php
                                    foreach($this->fgCapacity as $row):
                                        $selected = (!empty($dataRow->capacity) && $dataRow->capacity == $row)?"selected":"";
                                        echo '<option value="'.$row.'" '.$selected.'>'.$row.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        <!-- </div>
                        <div class="input-group-append"> -->
                            <select name="color" id="color" class="form-control single-select" style="width:25%;">
                                <?php
                                    foreach($this->fgColorCode as $row):
                                        $selected = (!empty($dataRow->color) && $dataRow->color == $row)?"selected":"";
                                        echo '<option value="'.$row.'" '.$selected.'>'.$row.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        <!-- </div>
                        <div class="input-group-append"> -->
                            <select name="brand_id" id="brand_id" class="form-control single-select" style="width:25%;">
                                <option value="">Select Brand</option>
                                <?php
                                    foreach($brandList as $row):
                                        $selected = (!empty($dataRow->brand_id) && $dataRow->brand_id == $row->id)?"selected":"";
                                        echo '<option value="'.$row->id.'" '.$selected.'>'.$row->brand_name.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        <!-- </div> -->
                    </div>
                    <div class="error item_name"></div>
                </div>
            <?php else: ?>

            <div class="col-md-6 form-group">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" class="form-control req" value="<?=htmlentities((!empty($dataRow->item_name)) ? $dataRow->item_name : "")?>" />
            </div>

            <?php endif; ?>

            <div class="col-md-3 form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control single-select req">
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
                <select name="unit_id" id="unit_id" class="form-control single-select req">
                    <option value="0">--</option>
                    <?=getItemUnitListOption($unitData,((!empty($dataRow->unit_id))?$dataRow->unit_id:""))?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="defualt_disc">Defual Disc. (%)</label>
                <input type="text" name="defualt_disc" class="form-control floatOnly req" value="<?=(!empty($dataRow->defualt_disc)) ? $dataRow->defualt_disc : ""?>" />
            </div>

            <div class="col-md-2 form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control floatOnly" value="<?=(!empty($dataRow->price))?$dataRow->price:""?>">
            </div>

            

            <div class="col-md-2 form-group">
                <label for="hsn_code">HSN Code</label>
                <select name="hsn_code" id="hsn_code" class="form-control single-select">
                    <option value="">Select HSN Code</option>
                    <?=getHsnCodeListOption($hsnData,((!empty($dataRow->hsn_code))?$dataRow->hsn_code:""))?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="gst_per">GST (%)</label>
                <select name="gst_per" id="gst_per" class="form-control single-select">
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

            <div class="col-md-2 form-group">
                <label for="wh_min_qty">Min. Stock Qty (WH)</label>
                <input type="text" name="wh_min_qty" class="form-control floatOnly" value="<?= (!empty($dataRow->wh_min_qty)) ? $dataRow->wh_min_qty : "" ?>" />
            </div>
            
            <div class="col-md-2 form-group">
                <label for="wkg">Weight/Nos <small>(In Kg.)</small> </label>
                <input type="text" name="wkg" class="form-control floatOnly" value="<?= (!empty($dataRow->wkg)) ? $dataRow->wkg : "" ?>" />
            </div>

            <div class="col-md-8 form-group">
                <label for="description">Product Description</label>
                <textarea name="note" id="note" class="form-control" rows="1"><?=(!empty($dataRow->note))?$dataRow->note:""?></textarea>
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
        $("#gst_per").comboSelect();
    });
});
</script>
