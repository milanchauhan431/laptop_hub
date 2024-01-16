<?php
class StockTransfer extends MY_Controller{
    private $indexPage = "stock_transfer/index";
    private $form = "stock_transfer/form"; 

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Stock Transfer";
		$this->data['headData']->controller = "stockTransfer";        
        $this->data['headData']->pageUrl = "stockTransfer";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'stockTransfer']);
	}

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("stockTransfer");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();
        $data['trans_status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->stockTransfer->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getStockTransferData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function stockTransfer(){
        $data = $this->input->post();
        $data['other'] = 1;
        $this->data['dataRow'] = $dataRow = $this->stockRequest->getStockRequest($data);
        $this->data['itemBatchList'] = $this->itemStock->getItemStockBatchWise(['item_id'=>$dataRow->item_id,'location_ids'=>[$this->RTD_STORE->id,$this->CUSTSYS_STORE->id],'stock_required'=>1]);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['batchData'])):
            $errorMessage['batchError'] = "Batch Detail is required.";
        else:
            foreach($data['batchData'] as $row):
                if($row['batch_qty'] > 0):
                    $postData = ['unique_id' => $row['unique_id'],'item_id' => $data['item_id'],'stock_required'=>1,'single_row'=>1];
                        
                    $stockData = $this->itemStock->getItemStockBatchWise($postData);  
                    
                    $stockQty = (!empty($stockData->qty))?$stockData->qty:0;

                    if(empty($stockQty)):
                        $errorMessage['batch_qty'.$key] = "Stock not available.";
                    else:
                        if($row['batch_qty'] > $stockQty):
                            $errorMessage['batch_qty'.$key] = "Stock not available.";
                        endif;
                    endif;
                endif;
            endforeach;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->stockTransfer->save($data));
        endif;
    }


    public function rejectStockTransfer(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->stockTransfer->rejectStockTransfer($data));
        endif;
    }
}
?>