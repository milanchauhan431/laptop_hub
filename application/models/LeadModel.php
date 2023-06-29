<?php
class LeadModel extends MasterModel{
    private $appointmentTable = "crm_appointments";
    private $partyMaster = "party_master";
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $itemMaster = "item_master";
    private $countries = "countries";
    private $states = "states";
    private $cities = "cities";
    private $lead_managment='lead_managment';

    public function getDTRows($data){
		$data['tableName'] = $this->lead_managment;
        $data['select'] ="lead_managment.*,party_master.party_name,employee_master.emp_name,party_master.party_phone";
        $data['join']['party_master'] = "party_master.id = lead_managment.party_id AND party_master.is_delete = 0";
        $data['leftJoin']['employee_master'] = "employee_master.id = lead_managment.sales_executive";

        $data['where']['lead_managment.lead_status'] = $data['lead_status'];
		
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "lead_managment.lead_date";
        $data['searchCol'][] = "lead_managment.lead_no";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "party_master.party_phone";
        $data['searchCol'][] = "employee_master.emp_name";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        
        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
	}

    public function getNextLeadNo(){
        $data['tableName'] = $this->lead_managment;
        $data['select'] = "MAX(lead_no) as lead_no";
        $maxNo = $this->row($data)->lead_no;
		$nextNo = (!empty($maxNo))?($maxNo + 1):1;
		return $nextNo;
    }
    
    public function saveLead($data){
        try{
            $this->db->trans_begin();
            $leadNo = $this->getNextLeadNo();
            $leadData = [
                'id'=>$data['id'],
                'party_id'=>$data['party_id'],
                'lead_date'=>$data['lead_date'],
                'sales_executive'=>$data['sales_executive'],
                'mode'=>$data['mode'],
                'lead_no'=>$leadNo,
                'lead_status'=>$data['status'],
                'lead_from'=>$data['lead_from'],
            ];
            $result = $this->store($this->lead_managment,$leadData);
            
            $data['lead_id'] = $result['id'];
            $data['entry_type'] = 1;
            $data['appointment_date'] = $data['lead_date'];
            unset($data['lead_date'],$data['lead_from']);
            $this->saveFollowup($data);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function saveFollowup($data){ 
        try{
            $this->db->trans_begin();

            $result = $this->store($this->appointmentTable,$data,'Followup');

            if($data['entry_type'] == 1){
                $this->store($this->lead_managment,['id'=>$data['lead_id'],'lead_status'=>$data['status']]);
            }else{
                $this->store($this->transChild,['id'=>$data['lead_id'],'trans_status'=>$data['status']]);
            }
          
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
        return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function setAppointment($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->appointmentTable,$data,'Appointment');
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
        return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
		
    }


    public function getLead($id){
        $data['select'] = "lead_managment.*,party_master.party_name,employee_master.emp_name as sales_executive_name";
        $data['leftJoin']['party_master'] = "party_master.id = lead_managment.party_id";
        $data['leftJoin']['employee_master'] = "employee_master.id = lead_managment.sales_executive";
        $data['where']['lead_managment.id'] = $id;
        $data['tableName'] = $this->lead_managment;
        return $this->row($data);
    }    

    public function getFollowupData($postData){
        $data['tableName'] = $this->appointmentTable;
        $data['where']['crm_appointments.entry_type'] = $postData['entry_type'];
        $data['where']['crm_appointments.lead_id'] = $postData['lead_id'];
        $data['limit'] = 1;
        $data['order_by']['crm_appointments.appointment_date'] = "DESC";
        return $this->row($data);
    }

    public function getAppointments($postData){
        $data['tableName'] = $this->appointmentTable;
        $data['select']='crm_appointments.*,employee_master.emp_name as executive_name,lead_managment.lead_no,party_master.party_name';
        $data['leftJoin']['employee_master'] = 'employee_master.id = crm_appointments.sales_executive';
        $data['leftJoin']['lead_managment'] = "lead_managment.id = crm_appointments.lead_id";
        $data['leftJoin']['party_master'] = "party_master.id = crm_appointments.party_id";
        $data['where']['lead_id'] = $postData['lead_id'];
        $data['where']['entry_type'] = $postData['entry_type'];
        if(isset($postData['status'])){$data['where']['crm_appointments.status'] = $postData['status'];}
        return $this->rows($data);
    }

}
?>