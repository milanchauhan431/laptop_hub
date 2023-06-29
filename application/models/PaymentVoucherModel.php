<?php 
class PaymentVoucherModel extends MasterModel{
	private $transMain = "trans_main";

	public function getDtRows($data){
		$data['tableName'] = $this->transMain;
		$data['select'] = "trans_main.*,opp_acc.party_name as opp_acc_name,vou_acc.party_name as vou_acc_name";

        $data['leftJoin']['party_master as opp_acc'] = "opp_acc.id = trans_main.opp_acc_id";
        $data['leftJoin']['party_master as vou_acc'] = "vou_acc.id = trans_main.vou_acc_id";

        if(!empty($data['vou_name_s'])):
            $data['where']['vou_name_s'] = $data['vou_name_s'];
        endif;
		
        $data['where']['trans_main.trans_date >='] = $this->startYearDate;
        $data['where']['trans_main.trans_date <='] = $this->endYearDate;

		
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_main.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "opp_acc.party_name";
        $data['searchCol'][] = "vou_acc.party_name";
        $data['searchCol'][] = "trans_main.net_amount";
        $data['searchCol'][] = "trans_main.doc_no";
        $data['searchCol'][] = "trans_main.doc_date";
        $data['searchCol'][] = "trans_main.remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
	}

    public function getPartyInvoiceList($data){
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "id,trans_number,(net_amount - rop_amount) as due_amount";
        $queryData['where']['party_id'] = $data['party_id'];
        $queryData['where']['vou_name_s'] = $data['vou_name_s'];
        if(empty($data['ref_id'])):
            $queryData['where']['(net_amount - rop_amount) >'] = 0;
        else:
            $queryData['cusomWhere'][] = "((net_amount - rop_amount) > 0 OR id = ".$data['ref_id'].")";
        endif;
        return $this->rows($queryData);
    }

	public function save($data){
		try{
			$this->db->trans_begin();

            if(!empty($data['id'])):
                $vouData = $this->getVoucher($data['id']);
                if(!empty($vouData->ref_id)):
                    $setData = array();
                    $setData['tableName'] = $this->transMain;
                    $setData['where']['id'] = $vouData->ref_id;
                    $setData['set']['rop_amount'] = 'rop_amount, - '.$vouData->net_amount;
                    $this->setValue($setData);
                endif;
            endif;

			$data['doc_date'] = (!empty($data['doc_date']))?$data['doc_date']:null;

			$result = $this->store($this->transMain,$data,'Voucher');
			$data['id'] = $result['id'];	

            if(!empty($data['ref_id'])):
                $setData = array();
                $setData['tableName'] = $this->transMain;
                $setData['where']['id'] = $data['ref_id'];
                $setData['set']['rop_amount'] = 'rop_amount, + '.$data['net_amount'];
                $this->setValue($setData);
            endif;

			$this->transMainModel->ledgerEffects($data);

			if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
		}catch(\Exception $e){
            $this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
	}

	public function getVoucher($id){
        $data['tableName'] = $this->transMain;
		$data['select'] = "trans_main.*,party_master.party_name, reference.trans_no as inv_no, reference.trans_prefix as inv_prefix, reference.trans_date as inv_date";
		$data['join']['party_master'] = "party_master.id = trans_main.party_id";
		$data['leftJoin']['trans_main as reference'] = "reference.id = trans_main.ref_id";
        $data['where']['trans_main.id'] = $id;
        return $this->row($data);
    }  

	public function delete($id){
		try{
			$this->db->trans_begin();

            $vouData = $this->getVoucher($id);
            if(!empty($vouData->ref_id)):
                $setData = array();
                $setData['tableName'] = $this->transMain;
                $setData['where']['id'] = $vouData->ref_id;
                $setData['set']['rop_amount'] = 'rop_amount, - '.$vouData->net_amount;
                $this->setValue($setData);
            endif;
			
			$result= $this->trash($this->transMain,['id'=>$id],'PaymentVoucher');
			$this->transMainModel->deleteLedgerTrans($id);

			if($this->db->trans_status() !== FALSE):
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