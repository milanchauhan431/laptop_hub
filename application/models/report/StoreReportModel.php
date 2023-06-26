<?php
class StoreReportModel extends MasterModel{
    private $itemMaster = "item_master";
    private $stockTrans = "stock_transaction";

    public function getStockRegisterData($data){
        $queryData = array();
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.id,item_master.item_code,item_master.item_name,ifnull(st.stock_qty,0) as stock_qty";

        $queryData['leftJoin']['(SELECT SUM(qty * p_or_m) as stock_qty,item_id FROM stock_transaction WHERE is_delete = 0 GROUP BY item_id) as st'] = "item_master.id = st.item_id";

        $queryData['where']['item_master.item_type'] = $data['item_type'];
        if(!empty($data['stock_type'])):
            if($data['stock_type'] == 1):
                $queryData['where']['ifnull(st.stock_qty,0) > '] = "ifnull(st.stock_qty,0) > 0";
            else:
                $queryData['where']['ifnull(st.stock_qty,0) <= '] = "0";
            endif;
        endif;

        $result = $this->rows($queryData);
        return $result;
    }
}
?>