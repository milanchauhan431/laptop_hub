<?php
class PaymentVoucher extends MY_Controller{
    private $index = "payment_voucher/index";
    private $form = "payment_voucher/form";	
	
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "PaymentVoucher";
		$this->data['headData']->controller = "paymentVoucher";
        $this->data['headData']->pageUrl = "paymentVoucher";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'paymentVoucher']);
	}

	public function index(){
		$this->data['tableHeader'] = getAccountingDtHeader("paymentVoucher");
		$this->load->view($this->index,$this->data);
	}

    public function getDtRows($status="BCRct"){
        $data = $this->input->post();$data['vou_name_s'] = $status;
		$result = $this->paymentVoucher->getDtRows($data); 
		$sendData = array(); $i=($data['start'] + 1);
		foreach($result['data'] as $row):
			$row->sr_no = $i++;
			$sendData[] = getPaymentVoucher($row);
		endforeach;
		$result['data'] = $sendData;
		$this->printJson($result);
	}

    public function addPaymentVoucher(){
		$this->data['partyList'] = $this->party->getPartyList();
		$this->data['ledgerList'] = $this->party->getPartyList(['group_code'=>['"BA"','"BOL"','"BOA"','"CS"']]);		
		$this->data['trans_prefix'] =  $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] =  $this->data['entryData']->trans_no;	
        $this->data['trans_number']	= $this->data['trans_prefix'].$this->data['trans_no'];
		$this->load->view($this->form,$this->data);
	}

    public function getTransNo(){
		$data = $this->input->post();
        if($data['vou_name_s'] == "BCRct"):
            $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        else:
            $this->data['trans_prefix'] = str_replace("R","P",$this->data['entryData']->trans_prefix);
        endif;
		
        $this->data['trans_no'] = $this->transMainModel->nextTransNo($this->data['entryData']->id,0,$data['vou_name_s']);
        $this->data['trans_number']	= $this->data['trans_prefix'].$this->data['trans_no'];

		$this->printJson(['status'=>1,'data'=>$this->data]);
	}

    public function getReference($data=array()){
		$postData = (!empty($data))?$data:$this->input->post();

        $postData['vou_name_s'] = (($postData['vou_name_s'] == "BCRct")?"Sale":"Purc");
		$referenceData = $this->paymentVoucher->getPartyInvoiceList($postData);		
		
		$optionsHtml = '<option value="">Select Reference</option>';
		foreach($referenceData as $row):
            $selected = (!empty($postData['ref_id']) && $row->id == $postData['ref_id'])?"selected":"";
			$optionsHtml .= '<option value="'.$row->id.'" '.$selected.'>'.$row->trans_number.'</option>';
		endforeach;
		
        if(!empty($data)):
            return $optionsHtml;
        else:
		    $this->printJson(['status'=>1,'referenceData'=>$optionsHtml]);
        endif;
	}

    public function save(){
		$data = $this->input->post();
		$errorMessage = array();

		if(empty($data['trans_date']))
			$errorMessage['trans_date'] = "Voucher Date is required.";
		if(empty($data['vou_name_s']))
			$errorMessage['vou_name_s'] = "Entry Type is required.";
		if(empty($data['opp_acc_id']))
			$errorMessage['opp_acc_id'] = "Party Name is required.";
		if(empty($data['vou_acc_id']))
			$errorMessage['vou_acc_id'] = "Ledger Name is required.";
		if(empty($data['payment_mode']))
			$errorMessage['payment_mode'] = "Payment Mode is required.";
		if(empty($data['net_amount']))
			$errorMessage['net_amount'] = "Amount is required.";

		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
            $data['vou_name_l'] = (($data['vou_name_s'] == "BCRct")?"Bank/Cash Received":"Bank/Cash Paid");
			$data['party_id'] = $data['opp_acc_id'];
            $data['entry_type'] = $this->data['entryData']->id;
			$this->printJson($this->paymentVoucher->save($data));
		endif;
	}

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $dataRow = $this->paymentVoucher->getVoucher($data['id']);
		$this->data['partyList'] = $this->party->getPartyList();
		$this->data['ledgerList'] = $this->party->getPartyList(['group_code'=>['"BA"','"BOL"','"BOA"','"CS"']]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->paymentVoucher->delete($id));
        endif;
    }
}
?>