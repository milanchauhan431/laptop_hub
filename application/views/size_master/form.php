<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id"  value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <div class="error size"></div>
            
            <div class="col-md-6 form-group">
                <label for="width">Width</label>
                <input type="text" name="width" id="width" class="form-control floatOnly req" value="<?=(!empty($dataRow->width))?$dataRow->width:""?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="height">Hight</label>
                <input type="text" name="height" id="height" class="form-control floatOnly req" value="<?=(!empty($dataRow->height))?$dataRow->height:""?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
            </div>
        </div>
    </div>
</form>