<?php
class CustomizationModel extends MasterModel{
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $stockTrans = "stock_transaction";
    private $itemKit = "item_kit";
    private $serviceMaster = "service_master";
    private $serviceTrans = "service_transaction";

    public function getDTRows($data){
        if($data['entry_type'] == 20):
            $data['tableName'] = $this->transChild;
            $data['select'] = "trans_child.id as trans_child_id,trans_child.entry_type,trans_child.item_id,trans_child.item_name,trans_child.qty,trans_child.item_remark,trans_main.id,trans_main.trans_number,DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y') as trans_date";

            $data['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
            $data['leftJoin']['employee_master'] = "employee_master.id = trans_child.initiate_by";

            $data['where']['trans_child.entry_type'] = $data['entry_type'];

            $data['where']['trans_child.trans_status'] = 0;
            $data['where']['trans_child.initiate_by >'] = 0;
            $data['where']['trans_child.initiate_at'] = NULL;

            if(!in_array($this->userRole,[-1,1])):
                $data['where']['trans_child.initiate_by'] = $this->loginId;
            endif;

            $data['order_by']['trans_main.trans_date'] = "DESC";
            $data['order_by']['trans_main.id'] = "DESC";

            $data['group_by'][] = "trans_child.id";

            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "trans_main.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "trans_child.item_name";
            $data['searchCol'][] = "trans_child.qty";
            $data['searchCol'][] = "trans_child.item_remark";
        else:
            $data['tableName'] = $this->serviceMaster;
            $data['select'] = "service_master.id,service_master.entry_type,service_master.trans_number,service_master.trans_date,service_master.item_id,item_master.item_name,service_master.qty,service_master.amount,(CASE WHEN service_master.trans_type = 1 THEN (service_master.amount + service_master.part_amount) ELSE service_master.amount END) as total_amount,service_master.remark,trans_main.trans_number as ref_no";

            $data['leftJoin']['item_master'] = "service_master.item_id = item_master.id";
            $data['leftJoin']['trans_child'] = "trans_child.id = service_master.ref_id";
            $data['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
            
            $data['where']['service_master.trans_type'] = $data['trans_type'];
            $data['where']['service_master.entry_type'] = $data['entry_type'];

            $data['where']['service_master.trans_date >= '] = $this->startYearDate;
            $data['where']['service_master.trans_date <= '] = $this->endYearDate;

            if(!in_array($this->userRole,[-1,1])):
                $data['where']['service_master.created_by'] = $this->loginId;
            endif;

            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "service_master.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(service_master.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "trans_main.trans_number";
            $data['searchCol'][] = "item_master.item_name";
            $data['searchCol'][] = "service_master.qty";
            $data['searchCol'][] = "(CASE WHEN service_master.trans_type = 1 THEN (service_master.amount + service_master.part_amount) ELSE service_master.amount END)";
            $data['searchCol'][] = "service_master.remark";
        endif;

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if (isset($data['order'])) {
			$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];
		}

		return $this->pagingRows($data);
    }

    public function saveCustomize($data){
        try{
            if(empty($data['id'])):
                $nextNoData = ['table_name'=>$this->serviceMaster,'customWhere' => 'trans_type = '.$data['trans_type']];
                $data['trans_no'] = $this->transMainModel->getNextTransNo($nextNoData);
                //$data['trans_prefix'] = $this->transMainModel->getPrefix("CSM/");
                $data['trans_prefix'] = $this->data['entryData']->trans_prefix;
                $data['trans_number'] = $data['trans_prefix'].sprintf("%04d",$data['trans_no']);
            endif;

            $kitData = $data['kitData']; 
            $newKitData = (!empty($data['newKitData']))?$data['newKitData']:array();
            unset($data['kitData'],$data['newKitData']);

            $uniqueId = $this->transMainModel->getStockUniqueId(['location_id' => $this->CUSTSYS_STORE->id,'batch_no' => $data['batch_no'],'item_id' => $data['item_id']]);
            $data['new_unique_id'] = $uniqueId;
            $result = $this->store($this->serviceMaster,$data);

            //minus from ready to dispatch store
            $itmStcData = $this->itemStock->getStockTrans(['batch_no'=>$data['batch_no'],'location_id'=>$this->RTD_STORE->id,'item_id'=>$data['item_id']]);
            $stockTransData = [
                'id' => "",
                'entry_type' => $data['entry_type'],
                'unique_id' => $data['unique_id'],
                'ref_date' => $data['trans_date'],
                'ref_no' => $data['trans_number'],
                'main_ref_id' => $result['id'],
                'child_ref_id' => 0,
                'location_id' => $this->RTD_STORE->id,
                'batch_no' => $data['batch_no'],
                'party_id' => 0,
                'item_id' => $data['item_id'],
                'p_or_m' => -1,
                'qty' => $data['qty'],
                'price' => $data['purchase_price']
            ];
            $this->store($this->stockTrans,$stockTransData);

            //plus in customized store            
            $stockTransData = [
                'id' => "",
                'entry_type' => $data['entry_type'],
                'unique_id' => $uniqueId,
                'ref_date' => $data['trans_date'],
                'ref_no' => $data['trans_number'],
                'main_ref_id' => $result['id'],
                'child_ref_id' => 0,
                'location_id' => $this->CUSTSYS_STORE->id,
                'batch_no' => $data['batch_no'],
                'party_id' => 0,
                'item_id' => $data['item_id'],
                'p_or_m' => 1,
                'qty' => $data['qty'],
                'price' => $data['price']
            ];
            $this->store($this->stockTrans,$stockTransData);

            //remove or replace kit item in product and stock effect
            foreach($kitData as $row):
                $row['service_id'] = $result['id'];
                $itemTrans = $this->store($this->serviceTrans,$row);

                //if part replace
                if($row['kit_status'] == 1):
                    $stockData = [
                        'id' => "",
                        'entry_type' => $data['entry_type'],
                        'unique_id' => $this->transMainModel->getStockUniqueId(['location_id' => $this->REJ_STORE->id,'batch_no' => $data['batch_no'],'item_id' => $row['kit_item_id']]),
                        'ref_date' => $data['trans_date'],
                        'ref_no' => $data['trans_number'],
                        'main_ref_id' => $result['id'],
                        'child_ref_id' => $itemTrans['id'],
                        'location_id' => $this->REJ_STORE->id,
                        'batch_no' => $data['batch_no'],
                        'party_id' => 0,
                        'item_id' => $row['kit_item_id'],
                        'p_or_m' => 1,
                        'qty' => $row['qty'],
                        'price' => 0,//$row['price']
                    ];
                    $this->store($this->stockTrans,$stockData);

                    $stockData = [
                        'id' => "",
                        'entry_type' => $data['entry_type'],
                        'unique_id' => $row['unique_id'],
                        'ref_date' => $data['trans_date'],
                        'ref_no' => $data['trans_number'],
                        'main_ref_id' => $result['id'],
                        'child_ref_id' => $itemTrans['id'],
                        'location_id' => $row['location_id'],
                        'batch_no' => $row['batch_no'],
                        'party_id' => 0,
                        'item_id' => $row['kit_item_id'],
                        'p_or_m' => -1,
                        'qty' => $row['qty'],
                        'price' => $row['price']
                    ];
                    $this->store($this->stockTrans,$stockData);                    
                endif;

                if($row['kit_status'] == 3):
                    $stockData = [
                        'id' => "",
                        'entry_type' => $data['entry_type'],
                        'unique_id' => $this->transMainModel->getStockUniqueId(['location_id' => $this->RTD_STORE->id,'batch_no' => $data['batch_no'],'item_id' => $row['kit_item_id']]),
                        'ref_date' => $data['trans_date'],
                        'ref_no' => $data['trans_number'],
                        'main_ref_id' => $result['id'],
                        'child_ref_id' => $itemTrans['id'],
                        'location_id' => $this->RTD_STORE->id,
                        'batch_no' => $data['batch_no'],
                        'party_id' => 0,
                        'item_id' => $row['kit_item_id'],
                        'p_or_m' => 1,
                        'qty' => $row['qty'],
                        'price' => $row['price']
                    ];
                    $this->store($this->stockTrans,$stockData);
                endif;
                
                //generate new item bom for customized system
                $kitItems = [
                    'id' => "",
                    'unique_id' => $uniqueId,
                    'kit_type' => 1,
                    'item_id' => $data['item_id'],
                    'kit_item_id' => $row['kit_item_id'],
                    'qty' => $row['qty'],
                    'kit_status' => $row['kit_status'],
                    'service_id' => $row['service_id'],
                    'is_delete' => 0
                ];
                $this->store($this->itemKit,$kitItems);
            endforeach;

            //new kit item add in product and minus stock
            foreach($newKitData as $row):
                $row['kit_status'] = 4;
                $row['service_id'] = $result['id'];
                $itemTrans = $this->store($this->serviceTrans,$row);

                $stockData = [
                    'id' => "",
                    'entry_type' => $data['entry_type'],
                    'unique_id' => $row['unique_id'],
                    'ref_date' => $data['trans_date'],
                    'ref_no' => $data['trans_number'],
                    'main_ref_id' => $result['id'],
                    'child_ref_id' => $itemTrans['id'],
                    'location_id' => $row['location_id'],
                    'batch_no' => $row['batch_no'],
                    'party_id' => 0,
                    'item_id' => $row['kit_item_id'],
                    'p_or_m' => -1,
                    'qty' => $row['qty'],
                    'price' => $row['price']
                ];
                $this->store($this->stockTrans,$stockData);

                //generate new item bom for customized system
                $kitItems = [
                    'id' => "",
                    'unique_id' => $uniqueId,
                    'kit_type' => 1,
                    'item_id' => $data['item_id'],
                    'kit_item_id' => $row['kit_item_id'],
                    'qty' => $row['qty'],
                    'kit_status' => $row['kit_status'],
                    'service_id' => $row['service_id'],
                    'is_delete' => 0
                ];
                $this->store($this->itemKit,$kitItems);
            endforeach;

            $this->edit($this->transChild,['id'=>$data['ref_id']],['initiate_at'=>date("Y-m-d H:i:s")]);

            $queryData = [];
            $queryData['tableName'] = $this->transChild;
            $queryData['select'] = "trans_child.item_name,trans_main.trans_number";
            $queryData['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
            $queryData['where']['trans_child.id'] = $data['ref_id'];
            $orderData = $this->row($queryData);

            /* Send Notification */            
            $notifyData['notificationTitle'] = "Customization Completed.";
            $notifyData['notificationMsg'] = "Order No. : ".$orderData->trans_number."\nItem Name : ".$orderData->item_name;
            $notifyData['callBack'] = base_url("salesOrders");
            $this->notify($notifyData);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getCustomize($data){
        $queryData = array();
        $queryData['tableName'] = $this->serviceMaster;
        $queryData['where']['id'] = $data['id'];
        $result = $this->row($queryData);
        return $result;
    }

    public function getCustomizeTransaction($data){
        $queryData = array();
        $queryData['tableName'] = $this->serviceTrans;
        $queryData['select'] = "service_transaction.*,item_master.item_code,item_master.item_name";
        $queryData['leftJoin']['item_master'] = "service_transaction.kit_item_id = item_master.id";
        $queryData['where']['service_transaction.service_id'] = $data['service_id'];
        $result = $this->rows($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $serviceData = $this->getCustomize(['id'=>$id]);
            $serviceTrans = $this->getCustomizeTransaction(['service_id'=>$id]);

            //check customized stock
            //if($serviceData->trans_type == 2):
                $postData = ['location_id' => $this->CUSTSYS_STORE->id,'batch_no' => $serviceData->batch_no,'item_id' => $serviceData->item_id,'stock_required'=>1,'single_row'=>1];                    
                $stockData = $this->itemStock->getItemStockBatchWise($postData);
                $stockQty = (!empty($stockData->qty))?$stockData->qty:0;

                if(empty($stockQty)):
                    return ['status'=>0,'message'=>"Product Stock not availabel. you can not delete it."];
                else:
                    if($serviceData->qty > $stockQty):
                        return ['status'=>0,'message'=>"Product Stock not availabel. you can not delete it."];
                    endif;
                endif;

                $stockQty = 0;
                foreach($serviceTrans as $row):
                    if($row->kit_status == 1):
                        $postData = ['location_id' => $this->REJ_STORE->id,'batch_no' => $serviceData->batch_no,'item_id' => $row->kit_item_id,'stock_required'=>1,'single_row'=>1];                    
                        $stockData = $this->itemStock->getItemStockBatchWise($postData);
                        $stockQty = (!empty($stockData->qty))?$stockData->qty:0;

                        if(empty($stockQty)):
                            return ['status'=>0,'message'=>"Part Stock not availabel. you can not delete it."];
                        else:
                            if($row->qty > $stockQty):
                                return ['status'=>0,'message'=>"Part Stock not availabel. you can not delete it."];
                            endif;
                        endif;
                    endif;

                    if($row->kit_status == 3):
                        $postData = ['location_id' => $this->RTD_STORE->id,'batch_no' => $row->batch_no,'item_id' => $row->kit_item_id,'stock_required'=>1,'single_row'=>1];                    
                        $stockData = $this->itemStock->getItemStockBatchWise($postData);
                        $stockQty = (!empty($stockData->qty))?$stockData->qty:0;

                        if(empty($stockQty)):
                            return ['status'=>0,'message'=>"Part Stock not availabel. you can not delete it."];
                        else:
                            if($row->qty > $stockQty):
                                return ['status'=>0,'message'=>"Part Stock not availabel. you can not delete it."];
                            endif;
                        endif;
                    endif;
                endforeach;
            //endif;

            $result = $this->trash($this->serviceMaster,['id'=>$id]);
            $this->trash($this->serviceTrans,['service_id'=>$id]);
            $this->remove($this->stockTrans,['entry_type'=>$serviceData->entry_type,'main_ref_id' => $serviceData->id]);
            $this->remove($this->itemKit,['service_id'=>$id,'unique_id'=>$serviceData->new_unique_id]);
            $this->edit($this->transChild,['id'=>$serviceData->ref_id],['initiate_at'=>NULL]);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }    
}
?>