<?php $this->load->view('includes/header'); ?>
<div class="page-wrapper">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h4><u>Sales Invoice</u></h4>
                    </div>
                    <div class="card-body">
                        <form autocomplete="off" id="saveSalesInvoice" data-res_function="resSaveInvoice" enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="hiddenInput">
                                        <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
                                        <input type="hidden" name="entry_type" id="entry_type" value="<?=(!empty($dataRow->entry_type))?$dataRow->entry_type:$entry_type?>">
                                        <input type="hidden" name="from_entry_type" id="from_entry_type" value="<?=(!empty($dataRow->from_entry_type))?$dataRow->from_entry_type:((!empty($from_entry_type))?$from_entry_type:"")?>">
                                        <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($dataRow->ref_id))?$dataRow->ref_id:((!empty($ref_id))?$ref_id:"")?>">
										<input type="hidden" name="party_name" id="party_name" value="<?=(!empty($dataRow->party_name))?$dataRow->party_name:""?>">
                                        <input type="hidden" name="gst_type" id="gst_type" value="<?=(!empty($dataRow->gst_type))?$dataRow->gst_type:""?>">
                                        <input type="hidden" name="party_state_code" id="party_state_code" value="<?=(!empty($dataRow->party_state_code))?$dataRow->party_state_code:""?>">
										<input type="hidden" name="tax_class" id="tax_class" value="<?=(!empty($dataRow->tax_class))?$dataRow->tax_class:""?>">
										<!-- <input type="hidden" name="memo_type" id="memo_type" value="<?=(!empty($dataRow->memo_type))?$dataRow->memo_type:"DEBIT"?>">
										<input type="hidden" name="challan_no" id="challan_no" value="<?=(!empty($dataRow->challan_no))?$dataRow->challan_no:""?>">
										<input type="hidden" name="doc_no" id="doc_no" value="<?=(!empty($dataRow->doc_no))?$dataRow->doc_no:""?>">
										<input type="hidden" name="doc_date" id="doc_date" value="<?=(!empty($dataRow->doc_date))?$dataRow->doc_date:""?>">
										<input type="hidden" name="masterDetails[t_col_1]" id="master_t_col_1" value="<?=(!empty($dataRow->contact_person))?$dataRow->contact_person:""?>">
										<input type="hidden" name="masterDetails[t_col_2]" id="master_t_col_2" value="<?=(!empty($dataRow->contact_no))?$dataRow->contact_no:""?>"> -->
									</div>

                                    <div class="col-md-2 form-group">
                                        <label for="trans_number">Inv. No.</label>
                                        <div class="input-group">
                                            <input type="text" name="trans_prefix" id="trans_prefix" class="form-control" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:((!empty($trans_prefix))?$trans_prefix:"")?>" readonly>
                                            <input type="text" name="trans_no" id="trans_no" class="form-control numericOnly" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:((!empty($trans_no))?$trans_no:"")?>">
                                        </div>
                                        <input type="hidden" name="trans_number" id="trans_number" class="form-control" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:((!empty($trans_number))?$trans_number:"")?>" readonly>
                                        <div class="error trans_number"></div>
                                    </div>

                                    <div class="col-md-2 form-group">
                                        <label for="trans_date">Inv. Date</label>
                                        <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:getFyDate()?>">
                                    </div>

                                    <div class="col-md-5 form-group">
                                        <label for="party_id">Customer Name</label>
                                        <div class="float-right">	
                                            
											<span class="dropdown float-right m-r-5">
												<a class="text-primary font-bold waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" datatip="Progress" flow="down">+ Add New</a>

												<div class="dropdown-menu dropdown-menu-left user-dd animated flipInY" x-placement="start-left">
													<div class="d-flex no-block align-items-center p-10 bg-primary text-white">ACTION</div>
													
													<a class="dropdown-item addNew" href="javascript:void(0)" data-button="both" data-modal_id="modal-xl" data-function="addParty" data-controller="parties" data-postdata='{"party_category" : 1 }' data-res_function="resPartyMaster" data-js_store_fn="customStore" data-form_title="Add Customer">+ Customer</a>
													
												</div>
											</span>

                                            <span class="float-right m-r-10">
                                                <a class="text-primary font-bold waves-effect waves-dark getPendingOrders" href="javascript:void(0)">+ Sales Order</a>
                                            </span>
										</div>
                                        <select name="party_id" id="party_id" class="form-control select2 partyDetails partyOptions req" data-res_function="resPartyDetail" data-party_category="1">
											<option value="">Select Party</option>
											<?=getPartyListOption($partyList,((!empty($dataRow->party_id))?$dataRow->party_id:0))?>
										</select>

                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="gstin">GST NO.</label>
                                        <select name="gstin" id="gstin" class="form-control select2">
                                            <option value="">Select GST No.</option>
                                            <?php
                                                if(!empty($dataRow->party_id)):
                                                    foreach($gstinList as $row):
                                                        $selected = ($dataRow->gstin == $row->gstin)?"selected":"";
                                                        echo '<option value="'.$row->gstin.'" '.$selected.'>'.$row->gstin.'</option>';
                                                    endforeach;
                                                endif;
                                            ?>
                                        </select>
                                    </div>

                                    <div class="col-md-2 form-group hidden">
										<label for="memo_type">Memo Type</label>
										<select name="memo_type" id="memo_type" class="form-control">
											<option value="DEBIT" <?=(!empty($dataRow->memo_type) && $dataRow->memo_type == "DEBIT")?"selected":""?> >Debit</option>
											<option value="CASH" <?=(!empty($dataRow->memo_type) && $dataRow->memo_type == "CASH")?"selected":""?> >Cash</option>
										</select>
									</div>

                                    <div class="col-md-4 form-group">
										<label for="sp_acc_id">GST Type </label>
                                        <select name="sp_acc_id" id="sp_acc_id" class="form-control select2 req">
											<?=getSpAccListOption($salesAccounts,((!empty($dataRow->sp_acc_id))?$dataRow->sp_acc_id:0))?>
										</select>
                                        <input type="hidden" id="inv_type" value="SALES">
									</div>

                                    <div class="col-md-2 form-group hidden">
										<label for="challan_no">Challan No.</label>
										<input type="text" name="challan_no" class="form-control" placeholder="Enter Challan No." value="<?= (!empty($dataRow->challan_no)) ? $dataRow->challan_no : "" ?>" />
									</div>

                                    <div class="col-md-2 form-group hidden">
                                        <label for="doc_no">PO. No.</label>
                                        <input type="text" name="doc_no" id="doc_no" class="form-control" value="<?=(!empty($dataRow->doc_no))?$dataRow->doc_no:""?>">
                                    </div>

                                    <div class="col-md-3 form-group hidden">
                                        <label for="doc_date">PO. Date</label>
                                        <input type="date" name="doc_date" id="doc_date" class="form-control" value="<?=(!empty($dataRow->doc_date))?$dataRow->doc_date:getFyDate()?>">
                                    </div>

                                    <div class="col-md-2 form-group hidden">
										<label for="apply_round">Apply Round Off</label>
                                        <select name="apply_round" id="apply_round" class="form-control">
											<option value="1" <?= (!empty($dataRow) && $dataRow->apply_round == 1) ? "selected" : "" ?>>Yes</option>
											<option value="0" <?= (!empty($dataRow) && $dataRow->apply_round == 0) ? "selected" : "" ?>>No</option>
										</select>
                                    </div>
                                    
                                    <div class="col-md-2 form-group hidden">
                                        <label for="master_t_col_1">Contact Person</label>
                                        <input type="text" name="masterDetails[t_col_1]" id="master_t_col_1" class="form-control" value="<?=(!empty($dataRow->contact_person))?$dataRow->contact_person:""?>">
                                    </div>

                                    <div class="col-md-2 form-group hidden">
                                        <label for="master_t_col_2">Contact No.</label>
                                        <input type="text" name="masterDetails[t_col_2]" id="master_t_col_2" class="form-control numericOnly" value="<?=(!empty($dataRow->contact_no))?$dataRow->contact_no:""?>">
                                    </div>

                                    <div class="col-md-5 form-group">
                                        <label for="master_t_col_3">Ship To</label>
                                        <input type="text" name="masterDetails[t_col_3]" id="master_t_col_3" class="form-control" value="<?=(!empty($dataRow->ship_address))?$dataRow->ship_address:""?>">
                                    </div>

                                    <div class="col-md-2 form-group exportData <?=(empty($dataRow))?"hidden":((!empty($dataRow->tax_class) && !in_array($dataRow->tax_class,["EXPORTGSTACC","EXPORTTFACC"]))?"hidden":"")?>">
                                        <label for="port_code">Port Code</label>
                                        <input type="text" name="port_code" id="port_code" class="form-control" value="<?=(!empty($dataRow->port_code))?$dataRow->port_code:""?>">
                                    </div>

                                    <div class="col-md-2 form-group exportData <?=(empty($dataRow))?"hidden":((!empty($dataRow->tax_class) && !in_array($dataRow->tax_class,["EXPORTGSTACC","EXPORTTFACC"]))?"hidden":"")?>">
                                        <label for="ship_bill_no">Shipping Bill No.</label>
                                        <input type="text" name="ship_bill_no" id="ship_bill_no" class="form-control" value="<?=(!empty($dataRow->ship_bill_no))?$dataRow->ship_bill_no:""?>">
                                    </div>

                                    <div class="col-md-2 form-group exportData <?=(empty($dataRow))?"hidden":((!empty($dataRow->tax_class) && !in_array($dataRow->tax_class,["EXPORTGSTACC","EXPORTTFACC"]))?"hidden":"")?>">
                                        <label for="ship_bill_date">Shipping Bill Date</label>
                                        <input type="date" name="ship_bill_date" id="ship_bill_date" class="form-control" value="<?=(!empty($dataRow->ship_bill_date))?$dataRow->ship_bill_date:""?>">
                                    </div>

                                    <div class="col-md-3 form-group">
                                        <label for="bill_per">Bill (%)</label>
                                        <input type="text" name="masterDetails[i_col_1]" id="master_i_col_1" class="form-control numericOnly" value="<?=(!empty($dataRow->bill_per))?$dataRow->bill_per:"100"?>">
                                    </div>
                                </div>

                                <hr>

								<div class="row">
									<div class="col-md-12 itemForm" id="itemForm">
										<div class="row form-group">
											<div id="itemInputs">
												<input type="hidden" id="trans_id" class="itemFormInput" value="" />
												<input type="hidden" id="trans_from_entry_type" class="itemFormInput" value="" />
												<input type="hidden" id="trans_ref_id" class="itemFormInput" value="" />
												<input type="hidden" id="row_index" class="itemFormInput" value="">
												<input type="hidden" id="item_code" class="itemFormInput" value="" />
												<input type="hidden" id="item_type" class="itemFormInput" value="1" />
												<input type="hidden" id="stock_eff" class="itemFormInput" value="1" />
												<input type="hidden" id="org_price" class="itemFormInput" class="org_price" value="" />
											</div>
											<div class="col-md-4 form-group">
												<label for="item_id">Product Name</label>
												<div class="float-right">	
                                                    <span class="dropdown float-right">
                                                        <a class="text-primary font-bold waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" datatip="Progress" flow="down">+ Add New</a>
                
                                                        <div class="dropdown-menu dropdown-menu-left user-dd animated flipInY" x-placement="start-left">
                                                            <div class="d-flex no-block align-items-center p-10 bg-primary text-white">ACTION</div>
                                                            
                                                            <a class="dropdown-item addNew" href="javascript:void(0)" data-button="both" data-modal_id="modal-xl" data-function="addItem" data-controller="items" data-postdata='{"item_type" : 1 }' data-res_function="resItemMaster" data-js_store_fn="customStore" data-form_title="Add Finish Goods">+ Finish Good</a>
                                                            
                                                        </div>
                                                    </span>
                                                </div>
												<input type="hidden" id="item_name"  class="itemFormInput" class="form-control" value="" />
												<select id="item_id" class="form-control select2 itemDetails itemOptions itemFormInput" data-res_function="resItemDetail" data-item_type="1">
													<option value="">Select Product Name</option>
													<?=getItemListOption($itemList)?>
												</select>
											</div>
											<div class="col-md-2 form-group">
												<label for="qty">Quantity</label>
												<input type="text" id="qty" class="form-control floatOnly itemFormInput req" value="0">
											</div>
											<div class="col-md-2 form-group">
												<label for="packing_qty">Packing Standard</label>
												<input type="text" id="packing_qty" class="form-control itemFormInput" value="" readonly>
											</div>
											<div class="col-md-2 form-group">
												<label for="disc_per">Disc. (%)</label>
												<input type="text" id="disc_per" class="form-control itemFormInput floatOnly" value="0">
											</div>
											<div class="col-md-2 form-group">
												<label for="price">Price</label>
												<input type="text" id="price" class="form-control itemFormInput floatOnly req" value="0" />
											</div>
											<div class="col-md-2 form-group">
												<label for="unit_id">Unit</label>        
												<select id="unit_id" class="form-control itemFormInput select2">
													<option value="">Select Unit</option>
													<?=getItemUnitListOption($unitList)?>
												</select> 
												<input type="hidden" id="unit_name" class="form-control itemFormInput" value="" />                       
											</div>
											<div class="col-md-2 form-group">
												<label for="hsn_code">HSN Code</label>
												<input type="text" id="hsn_code" class="form-control itemFormInput numericOnly req" value="" />
												<!--<select name="hsn_code" id="hsn_code" class="form-control select2">
													<option value="">Select HSN Code</option>
													<?=getHsnCodeListOption($hsnList)?>
												</select>-->
											</div>
											<div class="col-md-2 form-group">
												<label for="gst_per">GST Per.(%)</label>
												<select id="gst_per" class="form-control itemFormInput select2">
													<?php
														foreach($this->gstPer as $per=>$text):
															echo '<option value="'.$per.'">'.$text.'</option>';
														endforeach;
													?>
												</select>
											</div>
											<div class="col-md-4 form-group">
												<label for="item_remark">Remark</label>
												<input type="text" id="item_remark" class="form-control itemFormInput" value="" />
											</div>
											<div class="col-md-2">
												<button type="button" class="btn btn-success waves-effect float-right btn-block mt-30 saveItem"><i class="fa fa-plus"></i> Add</button>
											</div>
										</div>
									</div>
								</div>
								<hr>

                                <div class="col-md-12 row">
                                    <div class="col-md-6"><h4>Item Details : </h4></div>
                                    <div class="col-md-6">
                                        <!--<button type="button" class="btn btn-outline-success waves-effect float-right add-item"><i class="fa fa-plus"></i> Add Item</button>-->
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="error itemData"></div>
                                    <div class="row form-group">
                                        <div class="table-responsive">
                                            <table id="salesInvoiceItems" class="table table-striped table-borderless">
                                                <thead class="thead-info">
                                                    <tr>
                                                        <th style="width:5%;">#</th>
                                                        <th>Item Name</th>
                                                        <th>HSN Code</th>
                                                        <th>Qty.</th>
                                                        <th>Unit</th>
                                                        <th>Price</th>
                                                        <th>Disc.</th>
                                                        <th class="igstCol">IGST</th>
                                                        <th class="cgstCol">CGST</th>
                                                        <th class="sgstCol">SGST</th>
                                                        <th class="amountCol">Amount</th>
                                                        <th class="netAmtCol">Amount</th>
                                                        <th>Remark</th>
                                                        <th class="text-center" style="width:10%;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tempItem" class="temp_item">
                                                    <tr id="noData">
                                                        <td colspan="15" class="text-center">No data available in table</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>

                                <?php $this->load->view('includes/tax_summary',['expenseList'=>$expenseList,'taxList'=>$taxList,'ledgerList'=>$ledgerList,'dataRow'=>((!empty($dataRow))?$dataRow:array())])?>

                                <hr>

                                <div class="row">
                                    <div class="col-md-9 form-group">
                                        <label for="remark">Remark</label>
                                        <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label for="">&nbsp;</label>
                                        <button type="button" class="btn btn-outline-success waves-effect btn-block" data-toggle="modal" data-target="#termModel">Terms & Conditions (<span id="termsCounter">0</span>)</button>
                                        <div class="error term_id"></div>
                                    </div>
                                    <?php $this->load->view('includes/terms_form',['termsList'=>$termsList,'termsConditions'=>(!empty($dataRow->termsConditions)) ? $dataRow->termsConditions : array()])?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div class="col-md-12">
                            <button type="button" class="btn waves-effect waves-light btn-outline-success float-right save-form" onclick="customStore({'formId':'saveSalesInvoice'});" ><i class="fa fa-check"></i> Save</button>
                            <a href="javascript:void(0)" onclick="window.location.href='<?=base_url($headData->controller)?>'" class="btn waves-effect waves-light btn-outline-secondary float-right btn-close press-close-btn save-form" style="margin-right:10px;"><i class="fa fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script src="<?php echo base_url(); ?>assets/js/custom/sales-invoice-form.js?v=<?= time() ?>"></script>
<!-- <script src="<?php echo base_url(); ?>assets/js/custom/row-attachment.js?v=<?= time() ?>"></script> -->
<script src="<?php echo base_url(); ?>assets/js/custom/calculate.js?v=<?= time() ?>"></script>

<?php
if(!empty($dataRow->itemList)):
    foreach($dataRow->itemList as $row):
        $row->row_index = "";
        $row->gst_per = floatVal($row->gst_per);
        echo '<script>AddRow('.json_encode($row).');</script>';
    endforeach;
endif;
?>