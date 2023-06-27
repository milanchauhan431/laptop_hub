<?php
class AccountingReport extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->isLoggedin();
        $this->data['headData']->pageTitle = "Accounting Report";
        $this->data['headData']->controller = "reports/accountingReport";
    }

    public function salesRegister(){
        $this->data['headData']->pageUrl = "reports/accountingReport/salesRegister";
        $this->data['headData']->pageTitle = "SALES REGISTER";
        $this->data['pageHeader'] = 'SALES REGISTER';
        $this->data['startDate'] = getFyDate(date("Y-m-01"));
        $this->data['endDate'] = getFyDate(date("Y-m-d"));
        $this->load->view("reports/accounting_report/sales_register",$this->data);
    }

    public function getSalesRegisterData(){
        $data = $this->input->post();
        $result = $this->accountReport->getRegisterData($data);

        $thead = '<tr>
            <th>#</th>
            <th>Inv Date</th>
            <th>Inv No.</th>
            <th>Party Name</th>
            <th>Gst No.</th>
            <th>Total Amount</th>
            <th>Disc. Amount</th>
            <th>Taxable Amount</th>
            <th>CGST Amount</th>
            <th>SGST Amount</th>
            <th>IGST Amount</th>
            <th>Other Amount</th>
            <th>Net Amount</th>
        </tr>';

        $tbody = ''; $i =1;
        
        $totalAmount = $totalDiscAmount = $totalTaxableAmount = $totalCgstAmount = $totalSgstAmount = $totalIgstAmount = $totalOtherAmount = $totalNetAmount = 0;
        foreach($result as $row):
            $tbody .= '<tr>
                <td>'.$i++.'</td>
                <td>'.$row->trans_date.'</td>
                <td>'.$row->trans_number.'</td>
                <td class="text-left">'.$row->party_name.'</td>
                <td class="text-left">'.$row->gstin.'</td>
                <td>'.floatVal($row->total_amount).'</td>
                <td>'.floatVal($row->disc_amount).'</td>
                <td>'.floatVal($row->taxable_amount).'</td>
                <td>'.floatVal($row->cgst_amount).'</td>
                <td>'.floatVal($row->sgst_amount).'</td>
                <td>'.floatVal($row->igst_amount).'</td>
                <td>'.floatVal($row->other_amount).'</td>
                <td>'.floatVal($row->net_amount).'</td>
            </tr>';

            $totalAmount += $row->total_amount;
            $totalDiscAmount += $row->disc_amount;
            $totalTaxableAmount += $row->taxable_amount;
            $totalCgstAmount += $row->cgst_amount;
            $totalSgstAmount += $row->sgst_amount;
            $totalIgstAmount += $row->igst_amount;
            $totalOtherAmount += $row->other_amount;
            $totalNetAmount += $row->net_amount;
        endforeach;

        $tfoot = '<tr>
            <th colspan="5" class="text-right">Total</th>
            <th>'.floatVal($totalAmount).'</th>
            <th>'.floatVal($totalDiscAmount).'</th>
            <th>'.floatVal($totalTaxableAmount).'</th>
            <th>'.floatVal($totalCgstAmount).'</th>
            <th>'.floatVal($totalSgstAmount).'</th>
            <th>'.floatVal($totalIgstAmount).'</th>
            <th>'.floatVal($totalOtherAmount).'</th>
            <th>'.floatVal($totalNetAmount).'</th>
        </tr>';

        $this->printJson(['status'=>1,'thead'=>$thead,'tbody'=>$tbody,'tfoot'=>$tfoot]);
    }

    public function purchaseRegister(){
        $this->data['headData']->pageUrl = "reports/accountingReport/purchaseRegister";
        $this->data['headData']->pageTitle = "PURCHASE REGISTER";
        $this->data['pageHeader'] = 'PURCHASE REGISTER';
        $this->data['startDate'] = getFyDate(date("Y-m-01"));
        $this->data['endDate'] = getFyDate(date("Y-m-d"));
        $this->load->view("reports/accounting_report/purchase_register",$this->data);
    }

    public function getPurchaseRegisterData(){
        $data = $this->input->post();
        $result = $this->accountReport->getRegisterData($data);

        $thead = '<tr>
            <th>#</th>
            <th>Inv Date</th>
            <th>Inv No.</th>
            <th>Party Name</th>
            <th>Gst No.</th>
            <th>Total Amount</th>
            <th>Disc. Amount</th>
            <th>Taxable Amount</th>
            <th>CGST Amount</th>
            <th>SGST Amount</th>
            <th>IGST Amount</th>
            <th>Other Amount</th>
            <th>Net Amount</th>
        </tr>';

        $tbody = ''; $i =1;
        
        $totalAmount = $totalDiscAmount = $totalTaxableAmount = $totalCgstAmount = $totalSgstAmount = $totalIgstAmount = $totalOtherAmount = $totalNetAmount = 0;
        foreach($result as $row):
            $tbody .= '<tr>
                <td>'.$i++.'</td>
                <td>'.$row->trans_date.'</td>
                <td>'.$row->trans_number.'</td>
                <td class="text-left">'.$row->party_name.'</td>
                <td class="text-left">'.$row->gstin.'</td>
                <td>'.floatVal($row->total_amount).'</td>
                <td>'.floatVal($row->disc_amount).'</td>
                <td>'.floatVal($row->taxable_amount).'</td>
                <td>'.floatVal($row->cgst_amount).'</td>
                <td>'.floatVal($row->sgst_amount).'</td>
                <td>'.floatVal($row->igst_amount).'</td>
                <td>'.floatVal($row->other_amount).'</td>
                <td>'.floatVal($row->net_amount).'</td>
            </tr>';

            $totalAmount += $row->total_amount;
            $totalDiscAmount += $row->disc_amount;
            $totalTaxableAmount += $row->taxable_amount;
            $totalCgstAmount += $row->cgst_amount;
            $totalSgstAmount += $row->sgst_amount;
            $totalIgstAmount += $row->igst_amount;
            $totalOtherAmount += $row->other_amount;
            $totalNetAmount += $row->net_amount;
        endforeach;

        $tfoot = '<tr>
            <th colspan="5" class="text-right">Total</th>
            <th>'.floatVal($totalAmount).'</th>
            <th>'.floatVal($totalDiscAmount).'</th>
            <th>'.floatVal($totalTaxableAmount).'</th>
            <th>'.floatVal($totalCgstAmount).'</th>
            <th>'.floatVal($totalSgstAmount).'</th>
            <th>'.floatVal($totalIgstAmount).'</th>
            <th>'.floatVal($totalOtherAmount).'</th>
            <th>'.floatVal($totalNetAmount).'</th>
        </tr>';

        $this->printJson(['status'=>1,'thead'=>$thead,'tbody'=>$tbody,'tfoot'=>$tfoot]);
    }

    public function accountLedger(){
        $this->data['headData']->pageUrl = "reports/accountingReport/accountLedger";
        $this->data['headData']->pageTitle = "ACCOUNT LEDGER";
        $this->data['pageHeader'] = 'ACCOUNT LEDGER';
        $this->data['startDate'] = $this->startYearDate;
        $this->data['endDate'] = $this->endYearDate;
        $this->load->view("reports/accounting_report/account_ledger",$this->data);
    }

    public function getAccountLedgerData($jsonData=""){
        if(!empty($jsonData)):
            $postData = (Array) decodeURL($jsonData);
        else: 
            $postData = $this->input->post();
        endif;

        $ledgerSummary = $this->accountReport->getLedgerSummary($postData);
        $i=1; $tbody="";
        foreach($ledgerSummary as $row):
            if(empty($jsonData)):
                $accountName = '<a href="' . base_url('reports/accountingReport/ledgerDetail/' . $row->id) . '" target="_blank" datatip="Account Details" flow="down"><b>'.$row->account_name.'</b></a>';
            else:
                $accountName = $row->account_name;
            endif;

            $tbody .= '<tr>
                <td>'.$i++.'</td>
                <td class="text-left">'.$accountName.'</td>
                <td class="text-left">'.$row->group_name.'</td>
                <td class="text-right">'.$row->op_balance.'</td>
                <td class="text-right">'.$row->cr_balance.'</td>
                <td class="text-right">'.$row->dr_balance.'</td>
                <td class="text-right">'.$row->cl_balance.'</td>
            </tr>';
        endforeach;         
        
        if(!empty($postData['pdf'])):
            $reportTitle = 'ACCOUNT LEDGER';
            $report_date = date('d-m-Y',strtotime($postData['from_date'])).' to '.date('d-m-Y',strtotime($postData['to_date']));   
            $thead = (empty($jsonData)) ? '<tr class="text-center"><th colspan="11">'.$reportTitle.' ('.$report_date.')</th></tr>' : '';
            $thead .= '<tr>
                <th>#</th>
                <th class="text-left">Account Name</th>
                <th class="text-left">Group Name</th>
                <th class="text-right">Opening Amount</th>
                <th class="text-right">Credit Amount</th>
                <th class="text-right">Debit Amount</th>
                <th class="text-right">Closing Amount</th>
            </tr>';

            $companyData = $this->masterModel->getCompanyInfo();
            $logoFile = (!empty($companyData->company_logo)) ? $companyData->company_logo : 'logo.png';
            $logo = base_url('assets/images/' . $logoFile);
            $letter_head = base_url('assets/images/letterhead_top.png');
            
            $pdfData = '<table class="table table-bordered item-list-bb" repeat_header="1">
                <thead class="thead-info" id="theadData">'.$thead.'</thead>
                <tbody>'.$tbody.'</tbody>
            </table>';
            $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                <tr>
                    <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%">'.$reportTitle.'</td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$companyData->company_name.'</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%">'.$report_date.'</td>
                </tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:2px;">
                <tr><td class="org-address text-center" style="font-size:13px;">'.$companyData->company_address.'</td></tr>
            </table>';
            $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                <tr>
                    <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                    <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                </tr>
            </table>';

            $mpdf = new \Mpdf\Mpdf();
            $filePath = realpath(APPPATH . '../assets/uploads/');
            $pdfFileName = $filePath.'/AccountLedger.pdf';
            $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetWatermarkImage($logo, 0.08, array(120, 120));
            $mpdf->showWatermarkImage = true;
            $mpdf->SetTitle($reportTitle);
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('L','','','','',5,5,19,20,3,3,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData);
            
            ob_clean();
            $mpdf->Output($pdfFileName, 'I');
        
        else:
            $this->printJson(['status'=>1, 'tbody'=>$tbody]);
        endif;
    }

    public function ledgerDetail($acc_id,$start_date="",$end_date=""){
        $this->data['headData']->pageUrl = "reports/accountingReport/accountLedger";
	    $this->data['headData']->pageTitle = "ACCOUNT LEDGER DETAIL";
        $this->data['pageHeader'] = 'ACCOUNT LEDGER DETAIL';
        $ledgerData = $this->party->getParty(['id'=>$acc_id]);
        $this->data['acc_id'] = $acc_id;
        $this->data['acc_name'] = $ledgerData->party_name;
        $this->data['ledgerData'] = $ledgerData;
        $this->data['startDate'] = $this->startYearDate;
        $this->data['endDate'] = $this->endYearDate;
        $this->load->view("reports/accounting_report/account_ledger_detail",$this->data);
    }

    public function getLedgerTransaction($jsonData=""){
        if(!empty($jsonData)):
            $postData = (Array) decodeURL($jsonData);
        else:
            $postData = $this->input->post();
        endif;
        
        $ledgerTransactions = $this->accountReport->getLedgerDetail($postData);
        $ledgerBalance = $this->accountReport->getLedgerBalance($postData);

        $i=1; $tbody="";$balance = $ledgerBalance->op_balance;
        foreach($ledgerTransactions as $row):
            $balance += round(($row->amount * $row->p_or_m),2); 
            $balanceText = ($balance > 0)?abs($balance)." CR":abs($balance)." DR";

            $tbody .= '<tr>
                <td>'.$i++.'</td>
                <td>'.$row->trans_date.'</td>
                <td>'.$row->account_name.'</td>
                <td>'.$row->vou_name_s.'</td>
                <td>'.$row->trans_number.'</td>
                <td class="text-right">'.$row->cr_amount.'</td>
                <td class="text-right">'.$row->dr_amount.'</td>
                <td style="text-align: center;">'.$balanceText.'</td>
            </tr>';
        endforeach;    
        
        $ledgerBalance->cl_balance = abs($ledgerBalance->cl_balance);
        $ledgerBalance->op_balance = abs($ledgerBalance->op_balance);
        
        if(!empty($postData['pdf'])):
            $acc_name=$this->party->getParty(['id'=>$postData['acc_id']])->party_name;
            $reportTitle = $acc_name;
            $report_date = date('d-m-Y',strtotime($postData['from_date'])).' to '.date('d-m-Y',strtotime($postData['to_date']));   
            $thead = (empty($jsonData)) ? '<tr class="text-center"><th colspan="11">'.$reportTitle.' ('.$report_date.')</th></tr>' : '';

            $companyData = $this->masterModel->getCompanyInfo();
			$logoFile = (!empty($companyData->company_logo)) ? $companyData->company_logo : 'logo.png';
			$logo = base_url('assets/images/' . $logoFile);
			$letter_head = base_url('assets/images/letterhead_top.png');

            $thead .= '<tr>
                <th>#</th>
                <th>Date</th>
                <th>Particulars</th>
                <th>Voucher Type</th>
                <th>Ref.No.</th>
                <th>Amount(CR.)</th>
                <th>Amount(DR.)</th>
                <th>Balance</th>
            </tr>';

            $pdfData = '<table id="commanTable" class="table table-bordered item-list-bb" repeat_header="1">
                <thead class="thead-info" id="theadData">'.$thead.'</thead>
                <tbody id="receivableData">'.$tbody.'</tbody>
                <tfoot class="thead-info">
                    <tr>
                        <th colspan="5" class="text-right">Total</th>
                        <th id="cr_balance">'.$ledgerBalance->cr_balance.'</th>
                        <th id="dr_balance">'.$ledgerBalance->dr_balance.'</th>
                        <th></th>
                    </tr>
                </tfoot>    
            </table>
            <table class="table" style="border-top:1px solid #036aae;border-bottom:1px solid #036aae;margin-bottom:10px;margin-top:10px;">
                <tr>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"> Closing Balance: '.$ledgerBalance->cl_balance.' '.$ledgerBalance->cl_balance_type.'</td>
                </tr>
            </table>';

            $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                <tr>
                    <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%"></td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$companyData->company_name.'</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"></td>
                </tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:2px;">
                <tr><td class="org-address text-center" style="font-size:13px;">'.$companyData->company_address.'</td></tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:10px;">
                <tr>
                    <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%">Date : '.$report_date.'</td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$reportTitle.'</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"> Opening Balance: '.$ledgerBalance->op_balance.' '.$ledgerBalance->op_balance_type.'</td>
                </tr>
            </table>';  
			$htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                <tr>
                    <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                    <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                </tr>
            </table>';
                        
            $mpdf = new \Mpdf\Mpdf();
            $filePath = realpath(APPPATH . '../assets/uploads/');
            $pdfFileName = $filePath.'/AccountLedgerDetail.pdf';
            $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetWatermarkImage($logo, 0.08, array(120, 120));
            $mpdf->showWatermarkImage = true;
            $mpdf->SetTitle($reportTitle);
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('L','','','','',5,5,30,5,3,3,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData);
            
            ob_clean();
            $mpdf->Output($pdfFileName, 'I');
        
        else:
            $this->printJson(['status'=>1, 'tbody'=>$tbody,'ledgerBalance'=>$ledgerBalance]);
        endif;
    }
}
?>