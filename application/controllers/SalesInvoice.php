<?php
class SalesInvoice extends MY_Controller{
    private $indexPage = "sales_invoice/index";
    private $form = "sales_invoice/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Sales Invoice";
		$this->data['headData']->controller = "salesInvoice";        
        $this->data['headData']->pageUrl = "salesInvoice";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'salesInvoice']);
	}

    public function index(){
        $this->data['tableHeader'] = getAccountingDtHeader("salesInvoice");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->salesInvoice->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getSalesInvoiceData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addInvoice(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
		$this->data['ledgerList'] = $this->party->getPartyList(["'DT'","'ED'","'EI'","'ID'","'II'"]);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['itemData'])):
            $errorMessage['itemData'] = "Item Details is required.";
        else:
            $bQty = array();
            foreach($data['itemData'] as $key => $row):
                if($row['stock_eff'] == 1):
                    $postData = ['location_id' => $this->RTD_STORE->id,'batch_no' => "GB",'item_id' => $row['item_id'],'stock_required'=>1,'single_row'=>1];
                    if(!empty($row['id'])):
                        $postData['customWhere'] = "stock_transaction.child_ref_id != ".$row['id']." AND stock_transaction.main_ref_id != ".$data['id']." AND stock_transaction.entry_type != ".$data['entry_type'];
                    endif;
                    $stockData = $this->itemStock->getItemStockBatchWise($postData);
                    
                    if(!isset($bQty[$row['item_id']])):
                        $bQty[$row['item_id']] = $row['qty'] ;
                    else:
                        $bQty[$row['item_id']] += $row['qty'];
                    endif;

                    if(empty($stockData)):
                        $errorMessage['qty'.$key] = "Stock not available.";
                    else:
                        if($bQty[$row['item_id']] > $stockData->qty):
                            $errorMessage['qty'.$key] = "Stock not available.";
                        endif;
                    endif;
                endif;
            endforeach;
        endif;
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;
            $this->printJson($this->salesInvoice->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->salesInvoice->getSalesInvoice(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => 1]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['ledgerList'] = $this->party->getPartyList(["'DT'","'ED'","'EI'","'ID'","'II'"]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesInvoice->delete($id));
        endif;
    }
}
?>