<?php
class LoginModel extends CI_Model{

	private $employeeMaster = "employee_master";
    private $empRole = ["-1"=>"Super Admin","1"=>"Admin","2"=>"Production Manager","3"=>"Accountant","4"=>"Sales Manager","5"=>"Purchase Manager","6"=>"Employee"];

	public function checkAuth($data){
		$result = $this->db->where('emp_code',$data['user_name'])->where('emp_password',md5($data['password']))->where('is_delete',0)->get($this->employeeMaster);
		
		if($result->num_rows() == 1):
			$resData = $result->row();
			if($resData->is_block == 1):
				return ['status'=>0,'message'=>'Your Account is Blocked. Please Contact Your Admin.'];
			else:
				if($resData->is_active == 0):
					return ['status'=>0,'message'=>'Your Account is Inactive. Please Contact Your Admin.'];
				else:		
					//update fcm notification token
					if(isset($data['web_push_token'])):
						$this->db->where('id',$resData->id);
						$this->db->update($this->employeeMaster,['web_push_token'=>$data['web_push_token']]);
					endif;

					//Employe Data
					$this->session->set_userdata('LoginOk','login success');
					$this->session->set_userdata('loginId',$resData->id);
					$this->session->set_userdata('role',$resData->emp_role);
					$this->session->set_userdata('roleName',$this->empRole[$empRole]);
					$this->session->set_userdata('emp_name',$resData->emp_name);
					$this->session->set_userdata('cm_id',$resData->cm_id);

					//Defualt Store
					//Ready To Dispatch Store
					$RTD_STORE = $this->db->where('store_type',1)->where('cm_id',$resData->cm_id)->get('location_master')->row();
					//Repairing Store
					$REP_STORE = $this->db->where('store_type',2)->where('cm_id',$resData->cm_id)->get('location_master')->row();
					//Rejection Or Scrap Store
					$REJ_STORE = $this->db->where('store_type',3)->where('cm_id',$resData->cm_id)->get('location_master')->row();
					//Customized System Store
					$CUSTSYS_STORE = $this->db->where('store_type',4)->where('cm_id',$resData->cm_id)->get('location_master')->row();
					
					$this->session->set_userdata("RTD_STORE",$RTD_STORE);
					$this->session->set_userdata("REP_STORE",$REP_STORE);
					$this->session->set_userdata("REJ_STORE",$REJ_STORE);
					$this->session->set_userdata("CUSTSYS_STORE",$CUSTSYS_STORE);
					
					//FY Data
					$fyData=$this->db->where('is_active',1)->get('financial_year')->row();
					$startDate = $fyData->start_date;
					$endDate = $fyData->end_date;
					$cyear  = date("Y-m-d H:i:s",strtotime("01-04-".date("Y")." 00:00:00")).' AND '.date("Y-m-d H:i:s",strtotime("31-03-".((int)date("Y") + 1)." 23:59:59"));
					$this->session->set_userdata('currentYear',$cyear);
					$this->session->set_userdata('financialYear',$fyData->financial_year);
					$this->session->set_userdata('isActiveYear',$fyData->close_status);
					$this->session->set_userdata('shortYear',$fyData->year);
					$this->session->set_userdata('startYear',$fyData->start_year);
					$this->session->set_userdata('endYear',$fyData->end_year);
					$this->session->set_userdata('startDate',$startDate);
					$this->session->set_userdata('endDate',$endDate);
					$this->session->set_userdata('currentFormDate',date('d-m-Y'));
					
					if($data['fyear'] != $cyear):
						$this->session->set_userdata('currentFormDate',date('d-m-Y',strtotime($endDate)));
					endif;
					
					return ['status'=>1,'message'=>'Login Success.'];
				endif;
			endif;
		else:
			return ['status'=>0,'message'=>"Invalid Username or Password."];
		endif;
	}

	public function setFinancialYear($year){
		$fyData=$this->db->where('financial_year',$year)->get('financial_year')->row();
		$startDate = $fyData->start_date;
		$endDate = $fyData->end_date;
		$cyear  = date("Y-m-d H:i:s",strtotime("01-04-".date("Y")." 00:00:00")).' AND '.date("Y-m-d H:i:s",strtotime("31-03-".((int)date("Y") + 1)." 23:59:59"));
		$this->session->set_userdata('currentYear',$cyear);
		$this->session->set_userdata('financialYear',$fyData->financial_year);
		$this->session->set_userdata('isActiveYear',$fyData->close_status);
		
		$this->session->set_userdata('shortYear',$fyData->year);
		$this->session->set_userdata('startYear',$fyData->start_year);
		$this->session->set_userdata('endYear',$fyData->end_year);
		$this->session->set_userdata('startDate',$startDate);
		$this->session->set_userdata('endDate',$endDate);
		$this->session->set_userdata('currentFormDate',date('d-m-Y'));
		return true;
	}

}
?>