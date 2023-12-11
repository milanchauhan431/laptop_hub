<?php
class Customization extends MY_Controller{
    private $index = "customization/index";
    private $customizeForm = "customization/customize_form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Customization";
		$this->data['headData']->controller = "customization";
        $this->data['headData']->pageUrl = "customization";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'customization']);
    }

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("pendingCustomization");
		$this->load->view($this->index,$this->data);
    }

    public function getDTRows($type=2,$entry_type = 20){
        $data = $this->input->post();
        $data['trans_type'] = $type;
        $data['entry_type'] = $entry_type;

        $result = $this->customize->getDTRows($data);
        $sendData = array();$i=($data['start']+1);

        foreach($result['data'] as $row):
            $row->sr_no = $i++;        
            $row->controller = $this->data['headData']->controller;
            $sendData[] = getCustomizationData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }    

    public function addCustomize(){
        $data = $this->input->post();
        $this->data['dataRow'] = (object) $data;
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

    public function saveCustomize(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['item_id']))
            $errorMessage['item_id'] = "Product Name is required.";
        if(empty($data['batch_no']))
            $errorMessage['batch_no'] = "Batch No. is required.";
        if(empty($data['qty']))
            $errorMessage['qty'] = "Qty is required.";
        

        if(!empty($data['batch_no']) && !empty($data['qty'])):
            $postData = ['location_id' => $this->RTD_STORE->id,'batch_no' => $data['batch_no'],'item_id' => $data['item_id'],'stock_required'=>1,'single_row'=>1];                    
            $stockData = $this->itemStock->getItemStockBatchWise($postData);
            $stockQty = (!empty($stockData->qty))?$stockData->qty:0;
            
            if(empty($stockQty)):
                $errorMessage['qty'] = "Stock not available.";
            else:
                if($data['qty'] > $stockQty):
                    $errorMessage['qty'] = "Stock not available.";
                endif;
            endif;
        endif;

        $kitData = $bQty = array();$amount = $partAmount = 0;
        if(empty($data['kitData'])):
            $errorMessage['kit_error'] = "Product Configration is required.";
        else:
            foreach($data['kitData'] as $key => $row):
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

                if($row['kit_status'] == 3):
                    if(empty($row['price'])):
                        $errorMessage['price_'.$key] = "Price is required.";
                    else:
                        $row['amount'] = round(($row['qty'] * $row['price']),2);
                        $partAmount -= $data['qty'] * $row['amount'];
                    endif;
                endif;

                $kitData[] = $row;
            endforeach;
        endif;

        $newKitData = $bQty = array();
        if(!empty($data['newKitData'])):
            foreach($data['newKitData'] as $key => $row):                
                if(empty($row['price'])):
                    $errorMessage['kit_price_'.$key] = "Price is required.";
                else:
                    $row['location_id'] = $this->RTD_STORE->id;
                    $row['amount'] = round(($row['qty'] * $row['price']),2);
                    $partAmount += $data['qty'] * $row['amount'];
                endif;

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
                
                $newKitData[] = $row;
            endforeach;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['newKitData'] = $newKitData;
            $data['kitData'] = $kitData;
            $data['part_amount'] = $partAmount;
            $data['amount'] = $data['purchase_price'] + $partAmount;
            $data['price'] = round(($data['amount'] / $data['qty']),2);
            $data['entry_type'] = $this->data['entryData']->id;
            $data['trans_date'] = date("Y-m-d");
            
            $this->printJson($this->customize->saveCustomize($data));
        endif;
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->customize->delete($id));
        endif;
    }
}
?>