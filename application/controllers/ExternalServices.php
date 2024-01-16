<?php
class ExternalServices extends MY_Controller{
    private $index = "external_services/index";
    private $form = "external_services/form";
    private $completeService = "external_services/complete_service";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "External Services";
		$this->data['headData']->controller = "externalServices";
        $this->data['headData']->pageUrl = "externalServices";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'externalServices']);
    }

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("externalServices");
		$this->load->view($this->index,$this->data);
    }

    public function getDTRows($status=0){
        $data = $this->input->post();
        $data['trans_status'] = $status;

        $result = $this->externalServices->getDTRows($data);
        $sendData = array();$i=($data['start']+1);

        foreach($result['data'] as $row):
            $row->sr_no = $i++;        
            $row->controller = $this->data['headData']->controller;
            $sendData[] = getExternalServicesData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }  

    public function addExternalService(){
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['entryData']->trans_prefix.$this->data['entryData']->trans_no;
        $this->data['partyList'] = $this->party->getPartyList();
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['employeeList'] = $this->employee->getEmployeeList();
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['trans_date']))
            $errorMessage['trans_date'] = "Entry Date is required.";
        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['item_id']))
            $errorMessage['item_id'] = "Item Name is required.";
        if(empty($data['qty']))
            $errorMessage['qty'] = "Qty is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->externalServices->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->externalServices->getExternalServices($data);
        $this->data['partyList'] = $this->party->getPartyList();
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['employeeList'] = $this->employee->getEmployeeList();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->externalServices->delete($id));
        endif;
    }

    public function completeService(){
        $data = $this->input->post();
        $this->data['service_id'] = $data['id'];
        $this->data['dataRow'] = $this->externalServices->getExternalServices($data);
        $this->data['partyList'] = $this->party->getPartyList();
        $this->data['itemList'] = $this->item->getItemList();
        $this->load->view($this->completeService,$this->data);
    }

    public function saveCompleteService(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['id']))
            $errorMessage['general_error'] = "Somthing is wrong.";
        if(empty($data['newKitData']))
            $errorMessage['item_error'] = "Please add service Details.";

        $partAmount = 0;    
        $newKitData = $bQty = array();
        if(!empty($data['newKitData'])):
            foreach($data['newKitData'] as $key => $row):                
                if(empty($row['price'])):
                    $errorMessage['kit_price_'.$key] = "Price is required.";
                else:
                    $row['location_id'] = $this->RTD_STORE->id;
                    $row['amount'] = round(($row['qty'] * $row['price']),2);
                    $partAmount += $row['amount'];
                endif;

                if($row['kit_item_type'] != 8):
                    if(empty($row['batch_no'])):
                        $errorMessage['kit_batch_no_'.$key] = "Batch No. is required.";
                    else:
                        $postData = ['location_id' => $this->RTD_STORE->id,'batch_no' => $row['batch_no'],'item_id' => $row['kit_item_id'],'stock_required'=>1,'single_row'=>1];                    
                        $stockData = $this->itemStock->getItemStockBatchWise($postData);
                        $stockQty = (!empty($stockData->qty))?$stockData->qty:0;
                        
                        if(empty($stockQty)):
                            $errorMessage['kit_qty_'.$key] = "Stock not available.";
                        else:
                            if(!isset($bQty[$stockData->unique_id])):
                                $bQty[$stockData->unique_id] = $row['qty'] ;
                            else:
                                $bQty[$stockData->unique_id] += $row['qty'];
                            endif;

                            if($bQty[$stockData->unique_id] > $stockQty):
                                $errorMessage['kit_qty_'.$key] = "Stock not available.";
                            endif;
                        endif;
                    endif;    
                endif;    
                
                $newKitData[] = $row;
            endforeach;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['entry_type'] = $this->data['entryData']->id;
            $data['newKitData'] = $newKitData;
            $data['amount'] = $partAmount;
            $data['price'] = round(($data['amount'] / $data['qty']),2);
            $data['trans_status'] = 1;
            $data['trans_date'] = formatDate($data['trans_date'],'Y-m-d');

            $this->printJson($this->externalServices->saveCompleteService($data));
        endif;
    }

    public function uncompleteService(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->externalServices->uncompleteService($id));
        endif;
    }

    public function getPartyServices(){
        $data = $this->input->post();
        $this->data['orderItems'] = $this->externalServices->getPendingServicesItemsForInvoice($data);
        $this->load->view('sales_invoice/create_service_invoice',$this->data);
    }
}
?>