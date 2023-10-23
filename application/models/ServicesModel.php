<?php
class ServicesModel extends MasterModel{
    private $mir = "mir";
    private $mirTrans = "mir_transaction";
    private $stockTrans = "stock_transaction";
    private $itemKit = "item_kit";
    private $serviceMaster = "service_master";
    private $serviceTrans = "service_transaction";

    public function getDTRows($data){
        if($data['entry_type'] == 26):
            $data['tableName'] = $this->mirTrans;

            $data['select'] = "mir.id,mir.entry_type,mir.trans_number,DATE_FORMAT(mir.trans_date,'%d-%m-%Y') as trans_date,item_master.item_name,mir.inv_no,mir_transaction.trans_status,mir.trans_type,mir_transaction.qty,mir_transaction.short_qty,mir_transaction.repaired_qty,mir_transaction.id as mir_trans_id,mir_transaction.item_id,(mir_transaction.short_qty - mir_transaction.repaired_qty) as pending_qty,mir_transaction.id as mir_trans_id";

            $data['leftJoin']['mir'] = "mir.id = mir_transaction.mir_id";
            $data['leftJoin']['item_master'] = "item_master.id = mir_transaction.item_id";
            $data['leftJoin']['trans_main'] = "trans_main.id = mir_transaction.po_id";
            $data['leftJoin']['party_master'] = "party_master.id = mir.party_id";

            //$data['where']['mir_transaction.trans_status'] = $data['trans_status'];
            $data['where']['mir_transaction.entry_type'] = $data['entry_type'];        
            $data['where']['mir.trans_type'] = $data['trans_type'];
            $data['where']['(mir_transaction.short_qty - mir_transaction.repaired_qty) > '] = 0;
                
            $data['order_by']['mir.id'] = "DESC";

            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "mir.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(mir.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "item_master.item_name";
            $data['searchCol'][] = "mir_transaction.short_qty";
            $data['searchCol'][] = "mir_transaction.repaired_qty";
            $data['searchCol'][] = "(mir_transaction.short_qty - mir_transaction.repaired_qty)";
        else:
            $data['tableName'] = $this->serviceMaster;
            $data['select'] = "service_master.id,service_master.entry_type,service_master.trans_number,service_master.trans_date,service_master.item_id,item_master.item_name,service_master.qty,service_master.amount,(service_master.amount + service_master.part_amount) as total_amount,service_master.remark";

            $data['leftJoin']['item_master'] = "service_master.item_id = item_master.id";
            
            //$data['where']['service_master.trans_type'] = $data['trans_type'];
            $data['where']['service_master.entry_type'] = $data['entry_type'];

            $data['where']['service_master.trans_date >= '] = $this->startYearDate;
            $data['where']['service_master.trans_date <= '] = $this->endYearDate;

            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "service_master.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(service_master.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "item_master.item_name";
            $data['searchCol'][] = "service_master.qty";
            $data['searchCol'][] = "(service_master.amount + service_master.part_amount)";
            $data['searchCol'][] = "service_master.remark";
        endif;

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if (isset($data['order'])) {
			$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];
		}

		return $this->pagingRows($data);
    }

    public function saveService($data){
        try{
            if(empty($data['id'])):
                $data['trans_no'] = $this->transMainModel->nextTransNo($data['entry_type']);
                $data['trans_prefix'] = $this->data['entryData']->trans_prefix;
                $data['trans_number'] = $data['trans_prefix'].sprintf("%04d",$data['trans_no']);
            endif;

            $kitData = $data['kitData']; unset($data['kitData']);
            $result = $this->store($this->serviceMaster,$data);

            //minus from repairing store
            $reparingData = $this->itemStock->getStockTrans(['child_ref_id'=>$data['ref_id'],'batch_no'=>$data['batch_no'],'location_id'=>$this->REP_STORE->id,'item_id'=>$data['item_id']]);
            $stockTransData = [
                'id' => "",
                'entry_type' => $data['entry_type'],
                'unique_id' => $reparingData->unique_id,
                'ref_date' => $data['trans_date'],
                'ref_no' => $data['trans_number'],
                'main_ref_id' => $result['id'],
                'child_ref_id' => 0,
                'location_id' => $this->REP_STORE->id,
                'batch_no' => $data['batch_no'],
                'party_id' => 0,
                'item_id' => $data['item_id'],
                'p_or_m' => -1,
                'qty' => $data['qty'],
                'price' => $reparingData->price
            ];
            $this->store($this->stockTrans,$stockTransData);

            //plus in ready to dispatch store
            $stockTransData = [
                'id' => "",
                'entry_type' => $this->data['entryData']->id,
                'unique_id' => $this->transMainModel->getStockUniqueId(['location_id' => $this->RTD_STORE->id,'batch_no' => $data['batch_no'],'item_id' => $data['item_id']]),
                'ref_date' => $data['trans_date'],
                'ref_no' => $data['trans_number'],
                'main_ref_id' => $result['id'],
                'child_ref_id' => 0,
                'location_id' => $this->RTD_STORE->id,
                'batch_no' => $data['batch_no'],
                'party_id' => 0,
                'item_id' => $data['item_id'],
                'p_or_m' => 1,
                'qty' => $data['qty'],
                'price' => $reparingData->price
            ];

            $this->store($this->stockTrans,$stockTransData);

            foreach($kitData as $row):
                $row['service_id'] = $result['id'];
                $itemTrans = $this->store($this->serviceTrans,$row);

                //if part replace
                if($row['kit_status'] == 1):
                    $stockData = [
                        'id' => "",
                        'entry_type' => $this->data['entryData']->id,
                        'unique_id' => $this->transMainModel->getStockUniqueId(['location_id' => $this->REJ_STORE->id,'batch_no' => $row['batch_no'],'item_id' => $row['kit_item_id']]),
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
                        'entry_type' => $this->data['entryData']->id,
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
            endforeach;

            //set reparing price in mir transaction 
            $setData = array();
            $setData['tableName'] = $this->mirTrans;
            $setData['where']['id'] = $data['ref_id'];
            $setData['set']['repaired_qty'] = 'repaired_qty, + '.$data['qty'];
            $setData['set']['repairing_amount'] = 'repairing_amount, + '.($data['amount'] + $data['part_amount']);
            $setData['update']['repairing_price'] = "(repairing_amount/repaired_qty)";
            $this->setValue($setData);

            // update lot expense in price
            $setData = array();
            $setData['tableName'] = $this->stockTrans;
            $setData['where']['item_id'] = $data['item_id'];
            $setData['where']['batch_no'] = $data['batch_no'];
            $setData['where']['location_id'] = $this->RTD_STORE->id;
            $setData['update']['price'] = '(SELECT (price + repairing_price) as price FROM mir_transaction WHERE id='.$data['ref_id'].')';
            $this->setValue($setData);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getService($data){
        $queryData = array();
        $queryData['tableName'] = $this->serviceMaster;
        $queryData['where']['id'] = $data['id'];
        $result = $this->row($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $serviceData = $this->getService(['id'=>$id]);
            //print_r($serviceData);exit;

            $result = $this->trash($this->serviceMaster,['id'=>$id]);
            $this->trash($this->serviceTrans,['service_id'=>$id]);
            $this->remove($this->stockTrans,['entry_type'=>$serviceData->entry_type,'main_ref_id' => $serviceData->id]);

            $setData = array();
            $setData['tableName'] = $this->mirTrans;
            $setData['where']['id'] = $serviceData->ref_id;
            $setData['set']['repaired_qty'] = 'repaired_qty, - '.$serviceData->qty;
            $setData['set']['repairing_amount'] = 'repairing_amount, - '.($serviceData->amount + $serviceData->part_amount);
            $setData['update']['repairing_price'] = "(repairing_amount/repaired_qty)";
            $this->setValue($setData);

            // update lot expense in price
            $setData = array();
            $setData['tableName'] = $this->stockTrans;
            $setData['where']['item_id'] = $serviceData->item_id;
            $setData['where']['batch_no'] = $serviceData->batch_no;
            $setData['where']['location_id'] = $this->RTD_STORE->id;
            $setData['update']['price'] = '(SELECT (price + repairing_price) as price FROM mir_transaction WHERE id='.$serviceData->ref_id.')';
            $this->setValue($setData);

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