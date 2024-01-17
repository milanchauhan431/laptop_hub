<?php
class StoreReportModel extends MasterModel{
    private $itemMaster = "item_master";
    private $stockTrans = "stock_transaction";

    public function getStockRegisterData($data){
        $queryData = array();
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.id,item_master.item_code,item_master.item_name,item_master.cm_id,ifnull(st.stock_qty,0) as stock_qty,,ifnull(st.ready_material_qty,0) as ready_material_qty,ifnull(st.repairable_qty,0) as repairable_qty,ifnull(st.scrap_qty,0) as scrap_qty,ifnull(st.customized_system_qty,0) as customized_system_qty";

        $queryData['leftJoin']['(SELECT 
        SUM(stock_transaction.qty * stock_transaction.p_or_m) as stock_qty,
        SUM(CASE WHEN stock_transaction.p_or_m = 1 THEN stock_transaction.qty ELSE 0 END) rec_qty,
        SUM(CASE WHEN stock_transaction.p_or_m = -1 THEN stock_transaction.qty ELSE 0 END) issue_qty,
        SUM((CASE WHEN location_master.store_type = 1 THEN (stock_transaction.qty * stock_transaction.p_or_m) ELSE 0 END)) as ready_material_qty,
        SUM((CASE WHEN location_master.store_type = 2 THEN (stock_transaction.qty * stock_transaction.p_or_m) ELSE 0 END)) as repairable_qty,
        SUM((CASE WHEN location_master.store_type = 3 THEN (stock_transaction.qty * stock_transaction.p_or_m) ELSE 0 END)) as scrap_qty,
        SUM((CASE WHEN location_master.store_type = 4 THEN (stock_transaction.qty * stock_transaction.p_or_m) ELSE 0 END)) as customized_system_qty,
        item_id 
        FROM stock_transaction 
        LEFT JOIN location_master ON stock_transaction.location_id = location_master.id
        WHERE stock_transaction.is_delete = 0 
        AND stock_transaction.cm_id IN ('.$data['cm_id'].')
        GROUP BY stock_transaction.item_id) as st'] = "item_master.id = st.item_id";

        $queryData['where']['item_master.item_type'] = $data['item_type'];
        if(!empty($data['stock_type'])):
            if($data['stock_type'] == 1):
                $queryData['where']['ifnull(st.stock_qty,0) > '] = "ifnull(st.stock_qty,0) > 0";
            else:
                $queryData['where']['ifnull(st.stock_qty,0) <= '] = "0";
            endif;
        endif;

        if(!empty($data['cm_id'])):
            $queryData['cm_id'] = [0,$data['cm_id']];
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