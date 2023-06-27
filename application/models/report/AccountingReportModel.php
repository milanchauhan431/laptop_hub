<?php
class AccountingReportModel extends MasterModel{

    public function getLedgerSummary($data){
        $startDate = (!empty($data['form_date']))?$data['form_date']:$this->startYearDate;
        $endDate = (!empty($toDate))?$toDate:$this->endYearDate;
        $startDate = date("Y-m-d",strtotime($startDate));
        $endDate = date("Y-m-d",strtotime($endDate));

        $ledgerSummary = $this->db->query("SELECT lb.id as id, am.party_name as account_name, CONCAT(am.credit_days, ' Days') as credit_days , CASE WHEN lb.op_balance > 0 THEN CONCAT(abs(lb.op_balance),' CR.') WHEN lb.op_balance < 0 THEN CONCAT(abs(lb.op_balance),' DR.') ELSE lb.op_balance END op_balance,am.group_name, lb.cr_balance, lb.dr_balance, CASE WHEN lb.cl_balance > 0 THEN CONCAT(abs(lb.cl_balance),' CR.') WHEN lb.cl_balance < 0 THEN CONCAT(abs(lb.cl_balance),' DR.') ELSE lb.cl_balance END as cl_balance 
        FROM (
            SELECT am.id, ((am.opening_balance) + SUM( CASE WHEN tl.trans_date < '".$startDate."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as op_balance, 
            SUM( CASE WHEN tl.trans_date >= '".$startDate."' AND tl.trans_date <= '".$endDate."' THEN CASE WHEN tl.c_or_d = 'DR' THEN tl.amount ELSE 0 END ELSE 0 END) as dr_balance,
            SUM( CASE WHEN tl.trans_date >= '".$startDate."' AND tl.trans_date <= '".$endDate."' THEN CASE WHEN tl.c_or_d = 'CR' THEN tl.amount ELSE 0 END ELSE 0 END) as cr_balance,
            ((am.opening_balance) + SUM( CASE WHEN tl.trans_date <= '".$endDate."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance 
            FROM party_master as am 
            LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id 
            WHERE am.is_delete = 0 GROUP BY am.id, am.opening_balance) as lb 
        LEFT JOIN party_master as am ON lb.id = am.id WHERE am.is_delete = 0 
        ORDER BY am.party_name ")->result();
        
        return $ledgerSummary;
    }

    public function getLedgerDetail($data){
        //tl.trans_number AS trans_number, 
        $ledgerTransactions = $this->db->query ("SELECT 
        tl.trans_main_id AS id, 
        tl.entry_type, 
        tl.trans_date, 
        tl.trans_number,
        tl.vou_name_s, 
        tl.amount,
        tl.c_or_d,
        tl.p_or_m,
        am.party_name AS account_name, 
        CASE WHEN tl.c_or_d = 'DR' THEN tl.amount ELSE 0 END AS dr_amount, 
        CASE WHEN tl.c_or_d = 'CR' THEN tl.amount ELSE 0 END AS cr_amount, 
        tl.remark AS remark 
        FROM ( trans_ledger AS tl LEFT JOIN party_master AS am ON am.id = tl.opp_acc_id ) 
        WHERE tl.vou_acc_id = ".$data['acc_id']." 
        AND tl.trans_date >= '".$data['from_date']."' 
        AND tl.trans_date <= '".$data['to_date']."'
        AND tl.is_delete = 0
        ORDER BY tl.trans_date, tl.trans_number")->result();
        return $ledgerTransactions;
    }

    public function getLedgerBalance($data){

        $ledgerBalance = $this->db->query ("SELECT am.id, am.party_name AS account_name, am.party_mobile AS contact_no, (am.opening_balance + SUM( CASE WHEN tl.trans_date < '".$data['from_date']."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as op_balance, 
        SUM( CASE WHEN tl.trans_date >= '".$data['from_date']."' AND tl.trans_date <= '".$data['to_date']."' THEN CASE WHEN tl.c_or_d = 'DR' THEN tl.amount ELSE 0 END ELSE 0 END) as dr_balance,
        SUM( CASE WHEN tl.trans_date >= '".$data['from_date']."' AND tl.trans_date <= '".$data['to_date']."' THEN CASE WHEN tl.c_or_d = 'CR' THEN tl.amount ELSE 0 END ELSE 0 END) as cr_balance,
        (am.opening_balance  + SUM( CASE WHEN tl.trans_date <= '".$data['to_date']."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance 
        FROM party_master as am 
        LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id 
        WHERE am.is_delete = 0 
        AND am.id = ".$data['acc_id']."
        GROUP BY am.id, am.opening_balance")->row();

        $ledgerBalance->op_balance_type=(!empty($ledgerBalance->op_balance) && $ledgerBalance->op_balance >= 0)?(($ledgerBalance->op_balance > 0)?'CR':''):(($ledgerBalance->op_balance < 0)?'DR':'');
        $ledgerBalance->cl_balance_type=(!empty($ledgerBalance->cl_balance) && $ledgerBalance->cl_balance >= 0)?(($ledgerBalance->cl_balance > 0)?'CR':''):(($ledgerBalance->cl_balance < 0)?'DR':'');

        return $ledgerBalance;
    }


    public function getRegisterData($data){
        $queryData['tableName'] = 'trans_main';
        $queryData['select'] = 'trans_main.id,trans_main.trans_number,trans_main.doc_no,trans_main.trans_date,trans_main.party_name,trans_main.party_state_code,trans_main.doc_no,trans_main.gstin,trans_main.currency,trans_main.vou_name_s,trans_main.total_amount,trans_main.disc_amount,trans_main.taxable_amount,trans_main.cgst_amount,trans_main.sgst_amount,trans_main.igst_amount,trans_main.cess_amount,trans_main.gst_amount,(trans_main.net_amount - trans_main.taxable_amount - trans_main.gst_amount) as other_amount,trans_main.net_amount';


        $queryData['where_in']['trans_main.vou_name_s'] = $data['vou_name_s'];
        $queryData['where']['trans_main.trans_date >='] = $data['from_date'];
        $queryData['where']['trans_main.trans_date <='] = $data['to_date'];

        if (!empty($data['party_id'])):
            $queryData['where']['trans_main.party_id'] = $data['party_id'];
        endif;

        if (!empty($data['state_code'])):
            if ($data['state_code'] == 1):
                $queryData['where']['trans_main.party_state_code']=24;
            endif;
            if ($data['state_code'] == 2) :
                $queryData['where']['trans_main.party_state_code !=']=24;
            endif;
        endif;

        $queryData['order_by']['trans_date']='ASC';
        return $this->rows($queryData);
    }
}
?>