<?php
class StockTransferModel extends MasterModel{
    private $stockRequest = "stock_request";
    private $stockTrans = "stock_transaction";

    public function getDTRows($data){
        $data['tableName'] = $this->stockRequest;
        $data['select'] = "stock_request.*,(stock_request.qty - stock_request.issue_qty) as pending_qty,company_info.company_code,item_master.item_code,item_master.item_name";

        
        $data['leftJoin']['item_master'] = "item_master.id = stock_request.item_id";
        
        $data['where']['stock_request.trans_date >='] = $this->startYearDate;
        $data['where']['stock_request.trans_date <='] = $this->endYearDate;

        $data['where']['stock_request.trans_status'] = $data['trans_status'];

        if($data['trans_status'] == 1):
            $data['leftJoin']['company_info'] = "company_info.id = stock_request.to_cm_id";
            $data['where']['stock_request.trans_type'] = 2;   
            $data['cm_id'] = $this->cm_id;
        else:
            $data['leftJoin']['company_info'] = "company_info.id = stock_request.cm_id";
            $data['where']['stock_request.trans_type'] = 1;
            $data['cm_id'] = array_diff([1,2,3,4],[$this->cm_id]);
        endif;
        
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['serachCol'][] = "DATE_FORMAT(stock_request.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "company_info.company_code";
        $data['serachCol'][] = "item_master.item_name";
        $data['serachCol'][] = "stock_request.qty";
        $data['serachCol'][] = "stock_request.issue_qty";
        $data['serachCol'][] = "(stock_request.qty - stock_request.issue_qty)";
        $data['serachCol'][] = "stock_request.remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data); //$this->printQuery();
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $batchData = $data['batchData']; unset($data['batchData']);
            $data['trans_date'] = date("Y-m-d");
            $data['trans_status'] = 1;
            $data['issue_qty'] = array_sum(array_column($batchData,'batch_qty'));
            $result = $this->store($this->stockRequest,$data,'Stock Transfer');

            foreach($batchData as $row):
                $row['id'] = "";
                $row['entry_type'] = $this->data['entryData']->id;
                $row['ref_date'] = $data['trans_date'];
                $row['main_ref_id'] = $result['id'];
                $row['item_id'] = $data['item_id'];
                $row['p_or_m'] = -1;
                $row['qty'] = $row['batch_qty'];unset($row['batch_qty']);
                $row['cm_id'] = $this->cm_id;

                $this->store($this->stockTrans,$row);

                $locationData = $this->db->where('store_type',1)->where('cm_id',$data['to_cm_id'])->get('location_master')->row();
                $row['location_id'] = $locationData->id;
                $row['unique_id'] = $this->transMainModel->getStockUniqueId(['location_id' => $row['location_id'],'batch_no' => $row['batch_no'],'item_id' => $data['item_id']]);
                $row['p_or_m'] = 1;
                $row['cm_id'] = $data['to_cm_id'];
                $this->store($this->stockTrans,$row);
            endforeach;

            $setData = array();
            $setData['tableName'] = $this->stockRequest;
            $setData['where']['id'] = $data['ref_id'];
            $setData['cm_id'] = $data['to_cm_id'];
            $setData['set']['issue_qty'] = 'issue_qty, + '.$data['issue_qty'];
            $setData['update']['trans_status'] = "(CASE WHEN issue_qty >= qty THEN 1 ELSE 0 END)";
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

    public function rejectStockTransfer($data){
        try{
            $this->db->trans_begin();
            
            $result = $this->edit($this->stockRequest,['id'=>$data['id']],['trans_status'=>2,'cm_id'=>$data['cm_id']],'Stock Request');

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