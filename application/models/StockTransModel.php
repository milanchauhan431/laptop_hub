<?php
class StockTransModel extends MasterModel{
    private $stockTrans = "stock_transaction";

    /* Created At : 09-12-2022 [Milan Chauhan] */
    public function getItemStockBatchWise($data){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_transaction.item_id, item_master.item_code, item_master.item_name, SUM(stock_transaction.qty * stock_transaction.p_or_m) as qty, stock_transaction.unique_id, stock_transaction.batch_no,  stock_transaction.location_id, lm.location, lm.store_name";
        
        $queryData['leftJoin']['location_master as lm'] = "lm.id=stock_transaction.location_id";
        $queryData['leftJoin']['item_master'] = "stock_transaction.item_id = item_master.id";

        if(!empty($data['item_id'])): 
            $queryData['where']['stock_transaction.item_id'] = $data['item_id'];           
        endif;

        if(!empty($data['location_id'])):
            $queryData['where']['stock_transaction.location_id'] = $data['location_id'];
        endif;

        if(!empty($data['batch_no'])):
            $queryData['where']['stock_transaction.batch_no'] = $data['batch_no'];
        endif;
        
        if(!empty($data['p_or_m'])):
            $queryData['where']['stock_transaction.p_or_m'] = $data['p_or_m'];
        endif;

        if(!empty($data['entry_type'])):
            $queryData['where']['stock_transaction.entry_type'] = $data['entry_type'];
        endif;

        if(!empty($data['main_ref_id'])):
            $queryData['where']['stock_transaction.main_ref_id'] = $data['main_ref_id'];
        endif;

        if(!empty($data['child_ref_id'])):
            $queryData['where']['stock_transaction.child_ref_id'] = $data['child_ref_id'];
        endif;

        if(!empty($data['ref_no'])):
            $queryData['where']['stock_transaction.ref_no'] = $data['ref_no'];
        endif;

        if(!empty($data['customWhere'])):
            $queryData['customWhere'][] = $data['customWhere'];
        endif;
        
        if(!empty($data['stock_required'])):
            $queryData['having'][] = 'SUM(stock_transaction.qty * stock_transaction.p_or_m) > 0';
        endif;

        //$queryData['where']['lm.final_location'] = 0;
        $queryData['group_by'][] = "stock_transaction.unique_id";
        $queryData['order_by']['lm.location'] = "ASC";

        if(isset($data['single_row']) && $data['single_row'] == 1):
            $stockData = $this->row($queryData);
        else:
            $stockData = $this->rows($queryData);
        endif;
        return $stockData;
    }

}
?>