<div class="row">
	<div class="col-12">
		<table class="table"><tr><td class="fs-18 text-center" style="letter-spacing: 2px;font-weight:bold;padding:0px !important; border-bottom:1px solid #000000;">PURCHASE ORDER</td></tr></table>
		
		<table class="table" style="margin-top:2px;">
			<tr>
				<th class="text-left" style="width:50%;vertical-align:top;">PO No. : <?=$poData->trans_number?></th>
				<th class="text-right" style="width:50%;vertical-align:top;">PO Date : <?=formatDate($poData->trans_date)?></th>
			</tr>
			<tr>
				<td style="width:50%;vertical-align:top;">
					TO,<br>
					<b>M/S. <?=$poData->party_name?></b> <br>
					<small><?=htmlspecialchars($partyData->party_address)." ".$partyData->state_name.", ".$partyData->city_name." - ".$partyData->party_pincode?></small><br><br>
				</td>
				<td style="width:50%;vertical-align:top;">
					<small>
						Contact Person : <?=$partyData->contact_person?><br>
						Contact No. : <?=$partyData->party_mobile?><br>
						Email : <?=$partyData->party_email?><br>
						GSTIN : <?=$poData->gstin?><br><br>
					</small><br><br>
				</td> 
			</tr>
			
			<tr>
				<td style="width:50%;vertical-align:top;">
					<b>Billing Address: </b><br>
					<?=$companyData->company_name?> <br>
					<small>
						<?=$companyData->company_address?><br>
						Contact No. : <?=$companyData->company_contact?><br>
						GSTIN : <?=$companyData->company_gst_no?><br><br>
					</small><br><br>
				</td>
				<td style="width:50%;vertical-align:top;">
					<b>Delivery/Booking Address: </b><br>
					<?=$companyData->company_name?> <br>
					<small>
						<?=$poData->delivery_address." ".$partyData->delivery_state_name.", ".$partyData->delivery_city_name." - ".$partyData->delivery_pincode?></br><br>
						Transport Name : <?=$poData->transport_name?><br>
						Contact Person : <?=$poData->contact_person?><br>
						Contact No. : <?=$poData->contact_no?><br>
					</small><br><br>
				</td>
			</tr>
			<tr>
				<th colspan="2" class="text-left">Sub:- Purchase order for Supply of following material</th>
			</tr>
			<tr>
				<td colspan="2">
					Dear Sir,<br>
					We are pleased to issue this Purchase Order for supply of goods as per our discussion
					Schedule of Quantities and Prices 
				</td>
			</tr>
		</table>
		
		<table class="table item-list-bb" style="margin-top:10px;">
			<tr>
				<th style="width:40px;">No.</th>
                <th>MAKE</th>
                <th>CAT No.</th>
				<th class="text-left">PRODUCT NAME</th>
				<th style="width:60px;">GST <small>%</small></th>
				<th style="width:100px;">Qty</th>
				<th style="width:50px;">UOM</th>
				<th style="width:60px;">Rate<br><small>(INR)</small></th>
				<th style="width:60px;">Disc (%)</th>
				<th style="width:110px;">Amount<br><small>(INR)</small></th>
			</tr>
			<?php
				$i=1;$totalQty = 0;$migst=0;$mcgst=0;$msgst=0;
				if(!empty($poData->itemList)):
					foreach($poData->itemList as $row):						
						echo '<tr>';
							echo '<td class="text-center">'.$i++.'</td>';
							echo '<td>'.$row->make.'</td>';
							echo '<td>'.$row->item_code.'</td>';
							echo '<td>'.$row->item_name.'</td>';
							echo '<td class="text-center">'.$row->gst_per.'</td>';
							echo '<td class="text-right">'.$row->qty.'</td>';
							echo '<td class="text-center">'.$row->unit_name.'</td>';
							echo '<td class="text-right">'.$row->price.'</td>';
							echo '<td class="text-right">'.$row->disc_per.'</td>';
							echo '<td class="text-right">'.$row->taxable_amount.'</td>';
						echo '</tr>';
						$totalQty += $row->qty;
						if($row->gst_per > $migst){$migst=$row->gst_per;$mcgst=$row->cgst_per;$msgst=$row->sgst_per;}
					endforeach;
				endif;
				
				$rwspan= 1; $srwspan = '';
                $beforExp = "";
                $afterExp = "";
                $invExpenseData = (!empty($poData->expenseData)) ? $poData->expenseData : array();
                foreach ($expenseList as $row) :
                    $expAmt = 0;
                    $amtFiledName = $row->map_code . "_amount";
                    if (!empty($invExpenseData) && $row->map_code != "roff") :
                        $expAmt = floatVal($invExpenseData->{$amtFiledName});
                    endif;

                    if(!empty($expAmt)):
                        if ($row->position == 1) :
                            $beforExp .= '<tr>
                                <th colspan="2" class="text-right">' . $row->exp_name . '</th>
                                <td class="text-right">'.sprintf('%.2f',$expAmt).'</td>
                            </tr>';
                        else:
                            $afterExp .= '<tr>
                                <th colspan="2" class="text-right">' . $row->exp_name . '</th>
                                <td class="text-right">'.sprintf('%.2f',$expAmt).'</td>
                            </tr>';
                        endif;
                        $rwspan++;
                    endif;
                endforeach;

				$taxHtml = '';
				foreach ($taxList as $taxRow) :
                    $taxAmt = 0;
                    $taxAmt = floatVal($poData->{$taxRow->map_code . '_amount'});
                    if(!empty($taxAmt)):
                        $taxHtml .= '<tr>
                            <th colspan="2" class="text-right">' . $taxRow->name . ' @'.(($poData->gst_type == 1)?floatVal($migst/2):$migst).'%</th>
                            <td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>
                        </tr>';
                        $rwspan++;
                    endif;
                endforeach;
			?>
			<tr>
				<th colspan="5" class="text-right">Total Qty.</th>
				<th class="text-right"><?=sprintf('%.3f',$totalQty)?></th>
				<th></th>
				<th colspan="2" class="text-right">Sub Total</th>
				<th class="text-right"><?=sprintf('%.2f',$poData->taxable_amount)?></th>
			</tr>
			<tr>
				<th class="text-left" colspan="7" rowspan="<?=$rwspan?>">
					Notes : <br><?=$poData->remark?>
				</th>				
			</tr>
			<?=$beforExp.$taxHtml.$afterExp?>
			<tr>
				<th class="text-left" colspan="7" rowspan="3">
					Amount In Words : <br><?=numToWordEnglish($poData->net_amount)?>
				</th>				
			</tr>
			
			<tr>
				<th colspan="2" class="text-right">Round Off</th>
				<td class="text-right"><?=sprintf('%.2f',$poData->round_off_amount)?></td>
			</tr>
			<tr>
				<th colspan="2" class="text-right">Grand Total</th>
				<th class="text-right"><?=sprintf('%.2f',$poData->net_amount)?></th>
			</tr>
		</table>
		<h4>Terms & Conditions :-</h4>
		<table class="table top-table" style="margin-top:10px;">
			<?php
				if(!empty($poData->termsConditions)):
					foreach($poData->termsConditions as $row):
						echo '<tr>';
							echo '<th class="text-left fs-11" style="width:140px;">'.$row->term_title.'</th>';
							echo '<td class=" fs-11"> : '.$row->condition.'</td>';
						echo '</tr>';
					endforeach;
				endif;
			?>
		</table>
		
		
	</div>
</div>