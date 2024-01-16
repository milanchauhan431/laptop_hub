<form>
    <div class="col-md-12">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-info">
                        <tr>
                            <th>#</th>
                            <th>Entry No.</th>
                            <th>Entry Date</th>
                            <th>Item Name</th>
                            <th>Pending Qty.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;
                            foreach($orderItems as $row):
                                $row->from_entry_type = $row->entry_type;
                                $row->ref_id = $row->id;
                                unset($row->id,$row->entry_type);
                                
                                $row->org_price = $row->price;

                                $row->taxable_amount = $row->amount = round(($row->pending_qty * $row->price),2);

                                $row->igst_per = $row->gst_per;
                                $row->gst_amount = $row->igst_amount = (!empty($row->gst_per))?round((($row->taxable_amount * $row->gst_per) / 100),2):0;

                                $row->cgst_per = $row->sgst_per = (!empty($row->gst_per))?round(($row->gst_per / 2),2):0;
                                $row->cgst_amount = $row->sgst_amount = (!empty($row->gst_per))?round(($row->gst_amount / 2),2):0;

                                $row->net_amount = $row->taxable_amount + $row->gst_amount;

                                $row->disc_per = $row->disc_amount = 0;

                                $row->item_remark = "";

                                $row->row_index = "";
                                $row->entry_type = "";
                                echo "<tr>
                                    <td class='text-center'>
                                        <input type='checkbox' id='md_checkbox_" . $i . "' class='filled-in chk-col-success orderItem' data-row='".json_encode($row)."' ><label for='md_checkbox_" . $i . "' class='mr-3 check" . $row->ref_id . "'></label>
                                    </td>
                                    <td>".$row->trans_number."</td>
                                    <td>".formatDate($row->trans_date)."</td>
                                    <td>".$row->item_name."</td>
                                    <td>".floatval($row->pending_qty)."</td>
                                </tr>";
                                $i++;
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>