<?php
class StoreReport extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Store Report";
		$this->data['headData']->controller = "reports/storeReport";
    }

    public function stockRegister(){
        $this->data['pageHeader'] = 'STOCK REGISTER';
        $this->data['headData']->pageUrl = "reports/storeReport/stockRegister";
        $this->load->view("reports/store_report/item_stock",$this->data);
    }

    public function getStockRegisterData(){
        $data = $this->input->post();
        $result = $this->storeReport->getStockRegisterData($data);

        $tbody = '';$i=1;
        foreach($result as $row):
            $tbody .= '<tr>
                <td  class="text-center">'.$i++.'</td>
                <td  class="text-left">'.$row->item_code.'</td>
                <td  class="text-left"><a href="'.base_url("reports/storeReport/stockTransactions/".$row->id."/".$row->cm_id).'">'.$row->item_name.'</a></td>
                <td  class="text-right">'.floatVal($row->stock_qty).'</td>
            </tr>';
        endforeach;

        $this->printJson(['status'=>1,'tbody'=>$tbody]);
    }

    public function stockTransactions($id = "",$cm_id = ''){
        $this->data['item_id'] = $id;
        $this->data['cm_id'] = $cmId = (!empty($cm_id))?$cm_id:$this->cm_id;
        $this->data['pageHeader'] = 'BATCH WISE STOCK REGISTER';
        $this->data['headData']->pageUrl = "reports/storeReport/stockRegister";
        $this->data['itemList'] = $this->item->getItemList($cmId);
        $this->load->view("reports/store_report/item_stock_trans",$this->data);
    }

    public function getStockTransaction(){
        $data = $this->input->post();
        $result = $this->storeReport->getStockTransaction($data);
        
        $tbody = ""; $i=1;
        foreach($result as $row):
            $tbody .= '<tr>
                <td>'.$i++.'</td>
                <td class="text-left">[ '.$row->store_name.' ] '.$row->location.'</td>
                <td class="text-left">'.$row->batch_no.'</td>
                <td  class="text-right">'.floatVal($row->stock_qty).'</td>
            </tr>';
        endforeach;
        
        $this->printJson(['status'=>1,'tbody'=>$tbody]);
    }
}
?>