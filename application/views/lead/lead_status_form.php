<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=$lead_id?>">

            <div class="col-md-12 form-group">
                <label for="lead_status">Status</label>
                <select name="lead_status" id="lead_status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="3">Won</option>
                    <option value="4">Lost</option>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="reason">Notes</label>
                <textarea name="reason" id="reason" class="form-control"></textarea>
            </div>
        </div>
    </div>
</form>