<?php
class StoreReportModel extends MasterModel{
    private $itemMaster = "item_master";
    private $stockTrans = "stock_transaction";

    public function getStockRegisterData($data){
        $queryData = array();
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.id,item_master.item_code,item_master.item_name,item_master.cm_id,ifnull(st.stock_qty,0) as stock_qty";

        $queryData['leftJoin']['(SELECT SUM(qty * p_or_m) as stock_qty,SUM(CASE WHEN p_or_m = 1 THEN qty ELSE 0 END) rec_qty,SUM(CASE WHEN p_or_m = -1 THEN qty ELSE 0 END) issue_qty,item_id FROM stock_transaction WHERE is_delete = 0 GROUP BY item_id) as st'] = "item_master.id = st.item_id";

        $queryData['where']['item_master.item_type'] = $data['item_type'];
        if(!empty($data['stock_type'])):
            if($data['stock_type'] == 1):
                $queryData['where']['ifnull(st.stock_qty,0) > '] = "ifnull(st.stock_qty,0) > 0";
            else:
                $queryData['where']['ifnull(st.stock_qty,0) <= '] = "0";
            endif;
        endif;

        if(!empty($data['cm_id'])):
            $queryData['cm_id'] = $data['cm_id'];
        endif;

        $result = $this->rows($queryData);
        return $result;
    }

    public function getStockTransaction($data){
        $queryData = array();
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_transaction.batch_no,SUM(stock_transaction.qty * stock_transaction.p_or_m) as stock_qty,location_master.location, location_master.store_name";

        $queryData['leftJoin']['location_master'] = "location_master.id = stock_transaction.location_id";
        $queryData['where']['stock_transaction.item_id'] = $data['item_id'];

        if(!empty($data['cm_id'])):
            $queryData['cm_id'] = $data['cm_id'];
        endif;

        $queryData['having'][] = "SUM(stock_transaction.qty * stock_transaction.p_or_m) > 0";        
        $queryData['group_by'][] = "stock_transaction.unique_id";
        return $this->rows($queryData);
    }
}
?>