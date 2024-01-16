<?php
class ExternalServicesModel extends MasterModel{
    private $serviceMaster = "service_master";
    private $serviceTrans = "service_transaction";
    private $stockTrans = "stock_transaction";

    public function getDTRows($data){
        $data['tableName'] = $this->serviceMaster;
        $data['select'] = "service_master.id,service_master.entry_type,service_master.trans_number,service_master.trans_date,service_master.party_id,party_master.party_name,service_master.item_id,item_master.item_name,service_master.qty,service_master.amount,(service_master.amount + service_master.part_amount) as total_amount,service_master.remark,service_master.trans_status,employee_master.emp_name";

        $data['leftJoin']['item_master'] = "service_master.item_id = item_master.id";
        $data['leftJoin']['party_master'] = "service_master.party_id = party_master.id";
        $data['leftJoin']['employee_master'] = "service_master.service_inspector_id = employee_master.id";
        
        $data['where']['service_master.trans_status'] = $data['trans_status'];
        $data['where']['service_master.entry_type'] = $this->data['entryData']->id;

        $data['where']['service_master.trans_date >= '] = $this->startYearDate;
        $data['where']['service_master.trans_date <= '] = $this->endYearDate;

        if(!in_array($this->userRole,[-1,1])):
            $data['where']['service_master.service_inspector_id'] = $this->loginId;
        endif;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "service_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(service_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "service_master.qty";
        $data['searchCol'][] = "(service_master.amount + service_master.part_amount)";
        $data['searchCol'][] = "service_master.remark";
        $data['searchCol'][] = "employee_master.emp_name";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if (isset($data['order'])) {
			$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];
		}

		return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if(empty($data['id'])):
                $nextNoData = ['table_name'=>$this->serviceMaster,'customWhere' => 'trans_type = '.$data['trans_type']];
                $data['trans_no'] = $this->transMainModel->getNextTransNo($nextNoData); 
                $data['trans_prefix'] = $this->data['entryData']->trans_prefix;
                $data['trans_number'] = $data['trans_prefix'].sprintf("%04d",$data['trans_no']);
                $data['entry_type'] = $this->data['entryData']->id;
            endif;

            $result = $this->store($this->serviceMaster,$data);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getExternalServices($data){
        $queryData = array();
        $queryData['tableName'] = $this->serviceMaster;
        $queryData['select'] = "service_master.*,party_master.party_name,item_master.item_name";
        $queryData['leftJoin']['item_master'] = "service_master.item_id = item_master.id";
        $queryData['leftJoin']['party_master'] = "service_master.party_id = party_master.id";
        $queryData['where']['service_master.id'] = $data['id'];
        return $this->row($queryData);
    }

    public function getExternalServicesTransaction($data){
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

            $serviceData = $this->getExternalServices(['id'=>$id]);
            $serviceTrans = $this->getExternalServicesTransaction(['service_id'=>$id]);

            if(!empty($serviceTrans)):
                return ['status'=>0,'message'=>"Service has been completed. you can not delete it."];
            endif;

            $result = $this->trash($this->serviceMaster,['id'=>$id]);
            $this->trash($this->serviceTrans,['service_id'=>$id]);
            $this->remove($this->stockTrans,['entry_type'=>$serviceData->entry_type,'main_ref_id' => $serviceData->id]);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }    

    public function saveCompleteService($data){
        try{
            $this->db->trans_begin();

            $newKitData = (!empty($data['newKitData']))?$data['newKitData']:array();
            unset($data['newKitData']);

            $result = $this->store($this->serviceMaster,$data);

            //new kit item add in product and minus stock
            foreach($newKitData as $row):
                $row['kit_status'] = 4;
                $row['service_id'] = $result['id'];
                $itemTrans = $this->store($this->serviceTrans,$row);

                if($row['kit_item_type'] != 8):
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
            endforeach;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function uncompleteService($id){
        try{
            $this->db->trans_begin();

            $serviceData = $this->getExternalServices(['id'=>$id]);
            $serviceTrans = $this->getExternalServicesTransaction(['service_id'=>$id]);         

            $result = $this->store($this->serviceMaster,['id'=>$id,'amount'=>0,'price'=>0,'trans_status'=>0]);
            $this->trash($this->serviceTrans,['service_id'=>$id]);
            $this->remove($this->stockTrans,['entry_type'=>$serviceData->entry_type,'main_ref_id' => $serviceData->id]);

            $result['message'] = "Service reversed successfully.";

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getPendingServicesItemsForInvoice(){
        $queryData = array();
        $queryData['tableName'] = $this->serviceTrans;
        $queryData['select'] = "service_master.id as trans_main_id,service_master.trans_number,service_master.trans_date,service_master.entry_type,service_transaction.id,service_transaction.kit_item_id as item_id,service_transaction.price,item_master.item_code,item_master.item_name,item_master.item_type,item_master.hsn_code,item_master.gst_per,item_master.unit_id,unit_master.unit_name,(service_transaction.qty - service_transaction.inv_qty) as pending_qty, 0 as stock_eff";
        $queryData['leftJoin']['service_master'] = "service_transaction.service_id = service_master.id";
        $queryData['leftJoin']['item_master'] = "service_transaction.kit_item_id = item_master.id";
        $queryData['leftJoin']['unit_master'] = "unit_master.id = item_master.unit_id";
        $queryData['where']['service_master.trans_status'] = 1;
        $queryData['where']['service_master.entry_type'] = $this->data['entryData']->id;
        $queryData['where']['(service_transaction.qty - service_transaction.inv_qty) >'] = 0;
        $result = $this->rows($queryData);
        return $result;
    }
}
?>