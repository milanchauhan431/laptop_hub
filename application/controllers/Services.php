<?php
class Services extends MY_Controller{
    private $index = "services/index";
    private $serviceForm = "services/form";
    private $customizeForm = "services/customize_form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Service";
		$this->data['headData']->controller = "services";
        $this->data['headData']->pageUrl = "services";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'services']);
    }

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("serviceGI");
		$this->load->view($this->index,$this->data);
    }

    public function getDTRows($type=2,$entry_type = 26){
        $data = $this->input->post();
        $data['trans_type'] = $type;
        $data['entry_type'] = $entry_type;

        $result = $this->services->getDTRows($data);
        $sendData = array();$i=($data['start']+1);

        foreach($result['data'] as $row):
            $row->sr_no = $i++;        
            $row->controller = $this->data['headData']->controller;
            $sendData[] = getServicesData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addService(){
        $data = $this->input->post();
        $this->data['ref_id'] = $data['ref_id'];
        $this->data['item_id'] = $data['item_id'];
        $this->data['item_name'] = $data['item_name'];
        $this->data['trans_type'] = $data['trans_type'];
        $this->data['batch_no'] = $data['batch_no'];
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['kitList'] = $this->gateInward->getItemKitList(['mir_trans_id'=>$data['ref_id']]);
        $this->load->view($this->serviceForm,$this->data);
    }

    public function saveService(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['item_id']))
            $errorMessage['item_name'] = "Item Name is required.";
        if(empty($data['qty'])):
            $errorMessage['qty'] = "Qty is required.";
            $data['qty'] = 0;
        else:
            $giItem = $this->gateInward->getInwardItem(['id'=>$data['ref_id']]);
            if($data['qty'] > $giItem->short_qty):
                $errorMessage['qty'] = "Invalid Qty.";
            endif;
        endif;

        $statusCount = 0;$kitData = $bQty = array();$amount = $partAmount = 0;
        foreach($data['kitData'] as $key => $row):
            if(!empty($row['kit_status'])):
                if($row['kit_status'] == 1):
                    if(empty($row['price'])):
                        $errorMessage['price_'.$key] = "Price is required.";
                    else:
                        $row['location_id'] = $this->RTD_STORE->id;
                        $row['amount'] = round(($row['qty'] * $row['price']),2);
                        $partAmount += $data['qty'] * $row['amount'];
                    endif;

                    if(empty($row['batch_no'])):
                        $errorMessage['batch_no_'.$key] = "Batch No. is required.";
                    else:
                        $postData = ['location_id' => $this->RTD_STORE->id,'batch_no' => $row['batch_no'],'item_id' => $row['kit_item_id'],'stock_required'=>1,'single_row'=>1];                    
                        $stockData = $this->itemStock->getItemStockBatchWise($postData);
                        $stockQty = (!empty($stockData->qty))?$stockData->qty:0;

                        
                        if(empty($stockQty)):
                            $errorMessage['qty_'.$key] = "Stock not available.";
                        else:
                            if(!isset($bQty[$stockData->unique_id])):
                                $bQty[$stockData->unique_id] = $row['qty'] ;
                            else:
                                $bQty[$stockData->unique_id] += $row['qty'];
                            endif;

                            if($bQty[$stockData->unique_id] > $stockQty):
                                $errorMessage['qty_'.$key] = "Stock not available.";
                            endif;
                        endif;
                    endif;

                endif;

                if($row['kit_status'] == 2):
                    if(empty($row['price'])):
                        $errorMessage['price_'.$key] = "Price is required.";
                    else:
                        $row['amount'] = round(($row['qty'] * $row['price']),2);
                        $amount += $data['qty'] * $row['amount'];
                    endif;
                endif;

                $statusCount++;
                $kitData[] = $row;
            endif;
        endforeach;

        if(empty($statusCount))
            $errorMessage['kit_error'] = "Please select al least onle item action status.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['amount'] = $amount;
            $data['part_amount'] = $partAmount;
            $data['kitData'] = $kitData;
            $data['entry_type'] = $this->data['entryData']->id;
            $data['trans_date'] = date("Y-m-d");
            //print_r($data);exit;
            $this->printJson($this->services->saveService($data));
        endif;
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->services->delete($id));
        endif;
    }

    public function addCustomize(){
        $this->data['itemList'] = $this->item->getItemList();
        $this->load->view($this->customizeForm,$this->data);
    }

    public function getItemKitForCustomization(){
        $data = $this->input->post();

        $kitList = $this->gateInward->getItemKitList(['mir_trans_id'=>$data['mir_trans_id']]);

        $html = '';
        if(!empty($kitList)):
            $i=1;
            foreach($kitList as $row):
                $row->id = "";
                $row->kit_item_name = ((!empty($row->item_code))?"[".$row->item_code."] ":"").$row->item_name;
                $html .= '<tr>
                    <td>
                        '.$i.'
                    </td>
                    <td>
                        '.$row->kit_item_name.'
                        <input type="hidden" name="kitData['.$i.'][id]" id="id_'.$i.'" value="'.$row->id.'">
                        <input type="hidden" name="kitData['.$i.'][kit_item_id]" id="kit_item_id_'.$i.'" value="'.$row->kit_item_id.'">
                    </td>
                    <td>
                        '.floatVal($row->qty).'
                        <input type="hidden" name="kitData['.$i.'][qty]" id="qty_'.$i.'" value="'.$row->qty.'">
                    </td>
                    <td>
                        <select name="kitData['.$i.'][kit_status]" id="kit_status_'.$i.'" class="form-control kitStatus" data-row_id="'.$i.'">
                            <option value="">Select</option>
                            <option value="1">Replace</option>
                            <option value="3">Remove</option>
                        </select>
                        <div class="error kit_status_'.$i.'"></div>
                    </td>
                    <td>
                        <select name="kitData['.$i.'][batch_no]" id="batch_no_'.$i.'" class="form-control select2 batchNo"  data-row_id="'.$i.'">
                            <option value="">Select</option>
                        </select>
                        <input type="hidden" name="kitData['.$i.'][unique_id]" id="unique_id_'.$i.'" value="">
                        <div class="error batch_no_'.$i.'"></div>
                    </td>
                    <td>
                        <input type="text" name="kitData['.$i.'][price]" id="price_'.$i.'" class="form-control floatOnly" value="'.((!empty($row->price))?$row->price:"").'">
                        <div class="error price_'.$i.'"></div>
                    </td>
                </tr>';
                $i++;
            endforeach;
        else:
            $html .= '<tr>
                <td class="text-center" colspan="6">No data available in table</td>
            </tr>';
        endif;

        $this->printJson(['status'=>1,'kitHtml'=>$html]);
    }
}
?>