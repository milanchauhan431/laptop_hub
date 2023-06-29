<?php
class Lead extends MY_Controller{
	private $indexPage = "lead/index";
	private $leadForm = "lead/lead_form";

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
			if(!empty($followupData))
			{
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
}
?>