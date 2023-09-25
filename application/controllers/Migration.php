<?php
class Migration extends MY_Controller{
    public function __construct(){
        parent::__construct();
    }

    public function addColumnInTable(){
        $result = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".MASTER_DB."' AND TABLE_NAME NOT IN ( SELECT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = 'cm_id' AND TABLE_SCHEMA = '".MASTER_DB."' )")->result();

        /* foreach($result as $row):
            $this->db->query("ALTER TABLE ".$row->TABLE_NAME." ADD `cm_id` INT NOT NULL DEFAULT '0' AFTER `is_delete`;");
        endforeach; */

        echo "success";exit;
    }

    public function defualtLedger(){
        $accounts = [
            ['name' => 'Sales Account', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESACC'],
            
            ['name' => 'Sales Account GST', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESGSTACC'],

            ['name' => 'Sales Account IGST', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESIGSTACC'],

            ['name' => 'Sales Account Tax Free', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESTFACC'],

            ['name' => 'Exempted Sales (Nill Rated)', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESEXEMPTEDTFACC'],

            ['name' => 'Sales Account GST JOBWORK', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESJOBGSTACC'],

            ['name' => 'Sales Account IGST JOBWORK', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESJOBIGSTACC'],

            ['name' => 'Export With Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'EXPORTGSTACC'],

            ['name' => 'Export Without Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'EXPORTTFACC'],

            ['name' => 'SEZ Supplies With Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SEZSGSTACC'],

            ['name' => 'SEZ Supplies Without Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SEZSTFACC'],

            ['name' => 'Deemed Export', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'DEEMEDEXP'],
            
            ['name' => 'CGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'CGSTOPACC'],
            
            ['name' => 'SGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'SGSTOPACC'],
            
            ['name' => 'IGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'IGSTOPACC'],
            
            ['name' => 'UTGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'UTGSTOPACC'],
            
            ['name' => 'CESS (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TCS ON SALES', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'Purchase Account', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURACC'],
            
            ['name' => 'Purchase Account GST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURGSTACC'],

            ['name' => 'Purchase Account IGST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURIGSTACC'],

            ['name' => 'Purchase Account URD GST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURURDGSTACC'],

            ['name' => 'Purchase Account URD IGST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURURDIGSTACC'],

            ['name' => 'Purchase Account Tax Free', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURTFACC'],

            ['name' => 'Exempted Purchase (Nill Rated)', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PUREXEMPTEDTFACC'],

            ['name' => 'Purchase Account GST JOBWORK', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURJOBGSTACC'],

            ['name' => 'Purchase Account IGST JOBWORK', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURJOBIGSTACC'],

            ['name' => 'Import', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'IMPORTACC'],

            ['name' => 'Import of Services', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'IMPORTSACC'],

            ['name' => 'Received from SEZ', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'SEZRACC'],
            
            ['name' => 'CGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'CGSTIPACC'],
            
            ['name' => 'SGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'SGSTIPACC'],
            
            ['name' => 'IGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'IGSTIPACC'],
            
            ['name' => 'UTGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'UTGSTIPACC'],
            
            ['name' => 'CESS (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TCS ON PURCHASE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TDS PAYABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TDS RECEIVABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'GST PAYABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'GST RECEIVABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'ROUNDED OFF', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => 'ROFFACC'],
            
            ['name' => 'CASH ACCOUNT', 'group_name' => 'Cash-In-Hand', 'group_code' => 'CS', 'system_code' => 'CASHACC'],
            
            ['name' => 'ELECTRICITY EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'OFFICE RENT EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'GODOWN RENT EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'TELEPHONE AND INTERNET CHARGES', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'PETROL EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'SALES INCENTIVE', 'group_name' => 'Expenses (Direct)', 'group_code' => 'ED', 'system_code' => ''],
            
            ['name' => 'INTEREST PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'INTEREST RECEIVED', 'group_name' => 'Income (Indirect)', 'group_code' => 'II', 'system_code' => ''],
            
            ['name' => 'SAVING BANK INTEREST', 'group_name' => 'Income (Indirect)', 'group_code' => 'II', 'system_code' => ''],
            
            ['name' => 'DISCOUNT RECEIVED', 'group_name' => 'Income (Indirect)', 'group_code' => 'II', 'system_code' => ''],
            
            ['name' => 'DISCOUNT PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'SUSPENSE A/C', 'group_name' => 'Suspense A/C', 'group_code' => 'AS', 'system_code' => ''],
            
            ['name' => 'PROFESSIONAL FEES PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'AUDIT FEE', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'ACCOUNTING CHARGES PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'LEGAL FEE', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'SALARY', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'WAGES', 'group_name' => 'Expenses (Direct)', 'group_code' => 'ED', 'system_code' => ''],
            
            ['name' => 'FREIGHT CHARGES', 'group_name' => 'Expenses (Direct)', 'group_code' => 'ED', 'system_code' => ''],
            
            ['name' => 'PACKING AND FORWARDING CHARGES', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'REMUNERATION TO PARTNERS', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'TRANSPORTATION CHARGES', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'DEPRICIATION', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'PLANT AND MACHINERY', 'group_name' => 'Fixed Assets', 'group_code' => 'FA', 'system_code' => ''],
            
            ['name' => 'FURNITURE AND FIXTURES', 'group_name' => 'Fixed Assets', 'group_code' => 'FA', 'system_code' => ''],
            
            ['name' => 'FIXED DEPOSITS', 'group_name' => 'Deposits (Assets)', 'group_code' => 'DA', 'system_code' => ''],
            
            ['name' => 'RENT DEPOSITS', 'group_name' => 'Deposits (Assets)', 'group_code' => 'DA', 'system_code' => '']	            
        ];
        try{
            $this->db->trans_begin();
            $accounts = (object) $accounts;
            foreach($accounts as $row):
                $row = (object) $row;

                $groupData = $this->db->where('group_code',$row->group_code)->get('group_master')->row();

                $ledgerData = [
                    'party_category' => 4,
                    'group_name' => $groupData->name,
                    'group_code' => $groupData->group_code,
                    'group_id' => $groupData->id,
                    'party_name' => $row->name,                    
                    'system_code' => $row->system_code
                ];

                $this->db->where('party_name',$row->name);
                $this->db->where('is_delete',0);
                $this->db->where('party_category',4);
                $checkLedger = $this->db->get('party_master');

                if($checkLedger->num_rows() > 0):
                    $id = $checkLedger->row()->id;
                    $this->db->where('id',$id);
                    $this->db->update('party_master',$ledgerData);
                else:
                    $this->db->insert('party_master',$ledgerData);
                endif;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Defualt Ledger Migration Success.";
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }
    
    public function updateLedgerClosingBalance(){
        try{
            $this->db->trans_begin();

            $partyData = $this->db->where('is_delete',0)->get("party_master")->result();
            foreach($partyData as $row):
                //Set oprning balance as closing balance
                $this->db->where('id',$row->id);
                $this->db->update('party_master',['cl_balance'=>'opening_balance']);

                //get ledger trans amount total
                $this->db->select("SUM(amount * p_or_m) as ledger_amount");
                $this->db->where('vou_acc_id',$row->id);
                $this->db->where('is_delete',0);
                $ledgerTrans = $this->db->get('trans_ledger')->row();
                $ledgerAmount = (!empty($ledgerTrans->ledger_amount))?$ledgerTrans->ledger_amount:0;

                //update colsing balance
                $this->db->set("cl_balance","`cl_balance` + ".$ledgerAmount,FALSE);
                $this->db->where('id',$row->id);
                $this->db->update('party_master');
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Closing Balance Migration Success.";
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }

    public function copyBrachPermission($cm_id,$emp_id){
        try{
            $this->db->trans_begin();

            $this->db->where('cm_id',1);
            $this->db->where('is_delete',0);
            $result = $this->db->get('menu_permission')->result();

            foreach($result as $row):
                $row = (array) $row;
                $row['id'] = "";
                $row['emp_id'] = $emp_id;
                $row['cm_id'] = $cm_id;

                $this->db->insert('menu_permission',$row);
            endforeach;

            $this->db->where('cm_id',1);
            $this->db->where('is_delete',0);
            $subResult = $this->db->get('sub_menu_permission')->result();

            foreach($subResult as $row):
                $row = (array) $row;
                $row['id'] = "";
                $row['emp_id'] = $emp_id;
                $row['cm_id'] = $cm_id;

                $this->db->insert('sub_menu_permission',$row);
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Permission created Successfully.";
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }

    public function createBranchLedgers($cm_id){
        try{
            $this->db->trans_begin();

            $this->db->where('cm_id',0);
            $this->db->where('is_delete',0);
            $result = $this->db->get('party_master')->result();

            foreach($result as $row):
                $row = (array) $row;
                $row['id'] = "";
                $row['cm_id'] = $cm_id;

                $this->db->where('party_name',$row['party_name']);
                $this->db->where('is_delete',0);
                $this->db->where('party_category',4);
                $this->db->where('cm_id',$cm_id);
                $checkLedger = $this->db->get('party_master');

                if($checkLedger->num_rows() > 0):
                    $id = $checkLedger->row()->id;
                    $this->db->where('id',$id);
                    $this->db->update('party_master',$row);
                else:
                    $this->db->insert('party_master',$row);
                endif;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Defualt Ledger created Successfully.";
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }

    public function createBranchTaxMaster($cm_id){
        try{
            $this->db->trans_begin();

            $this->db->where('cm_id',0);
            $this->db->where('is_delete',0);
            $result = $this->db->get('tax_master')->result();

            foreach($result as $row):
                $row = (array) $row;
                $row['id'] = "";
                $row['cm_id'] = $cm_id;                

                $this->db->where('name',$row['name']);
                $this->db->where('tax_type',$row['tax_type']);
                $this->db->where('is_delete',0);
                $this->db->where('cm_id',$cm_id);
                $checkLedger = $this->db->get('tax_master');

                if($checkLedger->num_rows() > 0):
                    $id = $checkLedger->row()->id;
                    $row['acc_id'] = $checkLedger->row()->acc_id;
                    $row['acc_name'] = $checkLedger->row()->acc_name;

                    $this->db->where('id',$id);
                    $this->db->update('tax_master',$row);
                else:
                    $row['acc_id'] = 0;
                    $row['acc_name'] = "";
                    $this->db->insert('tax_master',$row);
                endif;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Defualt Tax Master created Successfully.";
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }

    public function createBranchExpenseMaster($cm_id){
        try{
            $this->db->trans_begin();

            $this->db->where('cm_id',0);
            $this->db->where('is_delete',0);
            $result = $this->db->get('expense_master')->result();

            foreach($result as $row):
                $row = (array) $row;
                $row['id'] = "";
                $row['cm_id'] = $cm_id;                

                $this->db->where('exp_name',$row['exp_name']);
                $this->db->where('entry_type',$row['entry_type']);
                $this->db->where('is_delete',0);
                $this->db->where('cm_id',$cm_id);
                $checkLedger = $this->db->get('expense_master');

                if($checkLedger->num_rows() > 0):
                    $id = $checkLedger->row()->id;
                    $row['acc_id'] = $checkLedger->row()->acc_id;

                    $this->db->where('id',$id);
                    $this->db->update('expense_master',$row);
                else:
                    $row['acc_id'] = 0;
                    $this->db->insert('expense_master',$row);
                endif;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Defualt Expense Master created Successfully.";
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }
}
?>