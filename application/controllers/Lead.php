<?php
class Lead extends MY_Controller{
	private $indexPage = "lead/index";
	private $leadForm = "lead/lead_form";
    private $followupFrom = "lead/followup_form";

    public function __construct()
	{
		parent::__construct();
		$this->data['headData']->pageTitle = "CRM DASHBOARD";
		$this->data['headData']->controller = "lead";
		$this->data['headData']->pageUrl = "lead";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'lead']);
	}

    public function index(){
		$this->data['tableHeader'] = getSalesDtHeader("lead");
		$this->load->view($this->indexPage, $this->data);
	}
	
    public function getDTRows($lead_status = 0)	{
		$data = $this->input->post();
		$data['lead_status'] = $lead_status;
		$result = $this->leads->getDTRows($data);
		$sendData = array();
		$i = ($data['start'] + 1);
		foreach ($result['data'] as $row):
			$row->sr_no = $i++;
			$row->controller = $this->data['headData']->controller;
			$row->appointments = '';
			$row->followupDate = '';
			$row->followupNote = '';
			$followupData = $this->leads->getFollowupData(['entry_type' => 1, 'lead_id' => $row->id]);
			if(!empty($followupData)){
				$row->followupDate = formatDate($followupData->appointment_date) ;
				$row->followupNote =$followupData->notes;
			}
			if ($row->lead_status == 0 || $row->lead_status == 1) {
				$appointsData = $this->leads->getAppointments(['entry_type' => 2, 'lead_id' => $row->id, 'status' => 0]);
				if (!empty($appointsData)) {
					$apArray = [];
					foreach ($appointsData as $ap) {
						$style = "";
						if (date('Y-m-d H:i:s') >= date("Y-m-d H:i:s", strtotime($ap->appointment_date . ' ' . $ap->appointment_time . ' -24 Hours'))) {
							$style = 'text-danger';
						}

						$apArray[] = '<a href="javascript:void(0)" class="closeApplointment ' . $style . '" data-modal_id="modal-md" data-form_title="Appointment"  data-fnsave="saveAppointmentStatus" data-function="closeApplointment" data-id="' . $ap->id . '">' . formatDate($ap->appointment_date, 'd-m-Y ') . formatDate($ap->appointment_time, 'H:i A') . '</a>';
					}
					$row->appointments = implode("<hr style='margin-top:0px;margin-bottom:0px'>", $apArray);
				}
			}
			$sendData[] = getLeadData($row);
		endforeach;
		$result['data'] = $sendData;
		$this->printJson($result);
	}

    public function addLead(){
        $this->data['entry_type'] = $this->data['entryData']->id;
		$this->data['countryList'] = $this->party->getCountries();
		$this->data['currencyList'] = $this->party->getCurrencyList();
		$this->data['customerList'] = $this->party->getPartyList(['party_type'=>0,'party_category'=>1]);
		$this->data['categoryList'] = $this->itemCategory->getCategoryList(['ref_id'=>0,'final_category'=>1]);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
		$this->load->view($this->leadForm, $this->data);
	}
	
    public function save(){
        $data = $this->input->post();
		$errorMessage = array();
		if (empty($data['lead_date'])) {
			$errorMessage['lead_date'] = "Date is required.";
		}
		if (empty($data['mode'])) {
			$errorMessage['mode'] = "Mode is required.";
		}
		if (empty($data['party_id'])) {
			$errorMessage['party_id'] = "Customer is required.";
		}
		if (empty($data['sales_executive'])) {
			$errorMessage['sales_executive'] = "Sales Executive is required.";
		}

		if (!empty($errorMessage)):
			$this->printJson(['status' => 0, 'message' => $errorMessage]);
		else:
			$result = $this->leads->saveLead($data);
			$this->printJson($result);
		endif;
    }

    public function edit(){
		$id = $this->input->post('id');
		$leadData = $this->leads->getLead($id);

		$this->data['countryList'] = $this->party->getCountries();
		$this->data['currencyList'] = $this->party->getCurrencyList();
		$this->data['customerList'] = $this->party->getPartyList(['party_type'=>0,'party_category'=>1]);
		$this->data['categoryList'] = $this->itemCategory->getCategoryList(['ref_id'=>0,'final_category'=>1]);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
		
		$this->data['dataRow'] = $leadData;
		$this->load->view($this->leadForm, $this->data);
	}

    public function addFollowup(){
        $data = $this->input->post();
		$this->data['lead_id'] = $data['id'];
		$this->data['entry_type'] = $data['entry_type'];
	
		$stage = $this->followupStage;
		
		if($data['entry_type'] == 1){
			$this->data['leadData'] = $leadData = $this->leads->getLead($data['id']);
			if($leadData->lead_status == 0 || $leadData->lead_status == 4){ $stage = array(0 => 'Open', 4 => "Lost");  }
			elseif($leadData->lead_status == 1){ $stage = array(2 => "Hold", 5 => "Enquiry"); }
			elseif($leadData->lead_status == 2){ $stage = array(0 => 'Open', 1 => "Confirmed", 2 => "Hold", 5 => "Enquiry"); }
			elseif($leadData->lead_status == 5 && !empty($leadData->enq_id)){ $stage = array(  3 => "Won", 4 => "Lost"); }
		}else{
			$this->data['leadData'] = $leadData = $this->salesEnquiry->getTransChildDetail($data['id']);
			$stage = array(4 => "Won", 5 => "Lost");
		}

        $this->data['followupStage'] = $stage;
		$this->data['salesExecutives'] = $this->employee->getEmployeeList();
		
		$this->load->view($this->followupFrom, $this->data);
    }

    public function followupListHtml($data=array())	{
        $data = $this->input->post();
		$stage = $this->followupStage;
		if($data['entry_type'] == 3){
			$stage = array(0 => 'Open', 3 => "Hold", 4 => "Won", 5 => "Lost");
		}

		$appintmentData = $this->leads->getAppointments($data);
		$html = '';

		if (!empty($appintmentData)) {
			$i = 1;
			foreach ($appintmentData as $row) {
				$deleteParam = $row->id . ",'deleteAppointment','Appointment'";
				$deleteBtn = '';
				//$deleteBtn = '<button type="button" onclick="trashAppointment(' . $deleteParam . ');"  class="btn btn-outline-danger waves-effect waves-light" style="padding:2px 8px;"><i class="ti-trash"></i></button>';
				$html.='<tr>
							<td clas="text-center">'.$i++.'</td>
							<td clas="text-center">'.formatDate($row->appointment_date,'d-m-Y ').'</td>
							<td>'.$this->appointmentMode[$row->mode].'</td>
							<td>'.$row->executive_name.'</td>
							<td>'.$stage[$row->status].'</td>
							<td>'.$row->notes.'</td>
							<td >'.$deleteBtn.'</td>
						</tr>';
			}
		} else {
			$html = '<tr><th colspan="7" class="text-center">No data available.</th></tr>';
		}

		$this->printJson(['status'=>1,"tbodyData"=>$html]);
	}

    public function saveFollowup()
	{
		$data = $this->input->post();
		$errorMessage = array();
		if (empty($data['appointment_date'])) {
			$errorMessage['appointment_date'] = "Date is required.";
		}
		if (empty($data['mode'])) {
			$errorMessage['mode'] = "Mode is required.";
		}
		if (empty($data['sales_executive'])) {
			$errorMessage['sales_executive'] = "Sales Executive is required.";
		}
		if (!empty($errorMessage)):
			$this->printJson(['status' => 0, 'message' => $errorMessage]);
		else:
			$result = $this->leads->saveFollowup($data);
			$this->printJson($result);
		endif;
	}

    public function addAppointment(){
        $data = $this->input->post();
		$data['entry_type'] = 2;
		$this->data['lead_id'] = $data['id'];
		$this->data['appointmentMode'] = $this->appointmentMode;
		$this->load->view('lead/appointment_form', $this->data);
    }

    public function saveAppointment()	{
		$data = $this->input->post();
		$errorMessage = array();
		if (empty($data['appointment_date']))
			$errorMessage['appointment_date'] = "Date is required.";
		if (empty($data['appointment_time']))
			$errorMessage['appointment_time'] = "Time is required.";
		if (empty($data['contact_person']))
			$errorMessage['contact_person'] = "Contact Person is required.";
		if (empty($data['mode']))
			$errorMessage['mode'] = "Mode is required.";

		if (!empty($errorMessage)):
			$this->printJson(['status' => 0, 'message' => $errorMessage]);
		else:
			$leadData = $this->leads->getLead($data['lead_id']); 
			$data['sales_executive'] = $leadData->sales_executive;
			$data['contact_person'] = ucwords($data['contact_person']);
			$data['appointment_date'] = formatDate($data['appointment_date'], 'Y-m-d');
			$data['appointment_time'] = formatDate($data['appointment_time'], 'H:i:s');
			$result = $this->leads->setAppointment($data);
			$this->printJson($result);
		endif;
	}


    public function appointmentListHtml($data=array())	{
		$data = $this->input->post();
        $this->leads->getAppointments($data);
		$appintmentData = $this->leads->getAppointments($data);
		$html = '';
		if (!empty($appintmentData)) {
			$i = 1;
			foreach ($appintmentData as $row) {
				$deleteParam = $row->id . ",'deleteAppointment','Appointment'";
				$deleteBtn = '';
				/* if (empty($row->status)) {
					$deleteBtn = '<button type="button" onclick="trashAppointment(' . $deleteParam . ');"  class="btn btn-outline-danger waves-effect waves-light" style="padding:2px 8px;"><i class="ti-trash"></i></button>';
				} */
				$html .= '<tr>
							<td clas="text-center">' . $i++ . '</td>
							<td clas="text-center">' . formatDate($row->appointment_date, 'd-m-Y ') . formatDate($row->appointment_time, 'H:i A') . '</td>
							<td>' . $this->appointmentMode[$row->mode] . '</td>
							<td>' . $row->contact_person . '</td>
							<td>' . $row->purpose . '</td>
							<td clas="text-center">' . $deleteBtn . '</td>
						</tr>';
			}
		} else {
			$html = '<tr><th colspan="6" class="text-center">No data available.</th></tr>';
		}
		$this->printJson(['status'=>1,"tbodyData"=>$html]);
	}

}
?>