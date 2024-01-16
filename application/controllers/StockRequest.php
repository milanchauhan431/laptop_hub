<?php
class StockRequest extends MY_Controller{
    private $indexPage = "stock_request/index";
    private $form = "stock_request/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Stock Request";
		$this->data['headData']->controller = "stockRequest";        
        $this->data['headData']->pageUrl = "stockRequest";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'stockRequest']);
	}

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("stockRequest");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();
        $data['trans_status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->stockRequest->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getStockRequestData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addRequest(){
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>[1,2,3],'cm_id'=>0]);
        $this->data['companyList'] = $this->masterModel->getCompanyList(['other'=>1]);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['trans_date']))
            $errorMessage['trans_date'] = "Requst Date is required.";
        if(empty($data['item_id']))
            $errorMessage['item_id'] = "Item Name is required.";
        if(empty($data['to_cm_id']))
            $errorMessage['to_cm_id'] = "To Branch is required.";
        if(empty($data['qty']))
            $errorMessage['qty'] = "Qty. is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->stockRequest->save($data));
        endif;  
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->stockRequest->getStockRequest($data);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>[1,2,3],'cm_id'=>0]);
        $this->data['companyList'] = $this->masterModel->getCompanyList(['other'=>1]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->stockRequest->delete($id));
        endif;
    }
}
?>