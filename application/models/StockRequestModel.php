<?php
class StockRequestModel extends MasterModel{
    private $stockRequest = "stock_request";
    private $stockTransaction = "stock_transaction";

    public function getDTRows($data){
        $data['tableName'] = $this->stockRequest;
        $data['select'] = "stock_request.*,(stock_request.qty - stock_request.issue_qty) as pending_qty,company_info.company_code,item_master.item_code,item_master.item_name";

        $data['leftJoin']['company_info'] = "company_info.id = stock_request.to_cm_id";
        $data['leftJoin']['item_master'] = "item_master.id = stock_request.item_id";
        
        $data['where']['stock_request.trans_date >='] = $this->startYearDate;
        $data['where']['stock_request.trans_date <='] = $this->endYearDate;

        $data['where']['stock_request.trans_status'] = $data['trans_status'];
        $data['where']['stock_request.trans_type'] = 1;
        
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
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if(!empty($data['id'])):
                $stockReq = $this->getStockRequest(['id'=>$data['id']]);
                if($stockReq->trans_status > 0):
                    return ['status'=>2,'message'=>"You cannot edit this request. because your request is processed or rejected."];
                endif;
            endif;

            $result = $this->store($this->stockRequest,$data,'Stock Request');
        
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getStockRequest($data){
        $queryData = array();
        $queryData['tableName'] = $this->stockRequest;
        $queryData['select'] = "stock_request.*,(stock_request.qty - stock_request.issue_qty) as pending_qty,company_info.company_code,item_master.item_code,item_master.item_name";
        $queryData['leftJoin']['company_info'] = "company_info.id = stock_request.to_cm_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = stock_request.item_id";
        $queryData['where']['stock_request.id'] = $data['id'];
        if(!empty($data['other'])):
            $queryData['cm_id'] = array_diff([1,2,3,4],[$this->cm_id]);
        endif;
        $result = $this->row($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            if(!empty($data['id'])):
                $stockReq = $this->getStockRequest(['id'=>$data['id']]);
                if($stockReq->trans_status > 0):
                    return ['status'=>2,'message'=>"You cannot delete this request. because your request is processed or rejected."];
                endif;
            endif;

            $result = $this->trash($this->stockRequest,['id'=>$id],'Stock Request');

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