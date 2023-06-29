<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getSalesDtHeader($page){
    /* Sales Enquiry Header */
    $data['salesEnquiry'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['salesEnquiry'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['salesEnquiry'][] = ["name"=>"SE. No."];
	$data['salesEnquiry'][] = ["name"=>"SE. Date"];
	$data['salesEnquiry'][] = ["name"=>"Customer Name"];
	$data['salesEnquiry'][] = ["name"=>"Item Name"];
    $data['salesEnquiry'][] = ["name"=>"Qty"];
    $data['salesEnquiry'][] = ["name"=>"Price"];

    /* Sales Order Header */
    $data['salesOrders'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['salesOrders'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['salesOrders'][] = ["name"=>"SO. No."];
	$data['salesOrders'][] = ["name"=>"SO. Date"];
	$data['salesOrders'][] = ["name"=>"Customer Name"];
	$data['salesOrders'][] = ["name"=>"Item Name"];
    $data['salesOrders'][] = ["name"=>"Order Qty"];
    $data['salesOrders'][] = ["name"=>"Dispatch Qty"];
    $data['salesOrders'][] = ["name"=>"Pending Qty"];

    /* Estimate [Cash] Header */
    $data['estimate'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['estimate'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['estimate'][] = ["name"=>"Inv No."];
	$data['estimate'][] = ["name"=>"Inv Date"];
	$data['estimate'][] = ["name"=>"Customer Name"];
	$data['estimate'][] = ["name"=>"Taxable Amount"];
    $data['estimate'][] = ["name"=>"Net Amount"];

    /* Lead Header  */
    $data['lead'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['lead'][] = ["name"=>"#","style"=>"width:5%;","textAlign"=>"center"]; 
	$data['lead'][] = ["name"=>"Approach Date"];
	$data['lead'][] = ["name"=>"Approach No"];
	$data['lead'][] = ["name"=>"Lead From"];
	$data['lead'][] = ["name"=>"Party Name"];
    $data['lead'][] = ["name"=>"Contact No."];
    $data['lead'][] = ["name"=>"Sales Executive"];
    $data['lead'][] = ["name"=>"Appointmens","textAlign"=>"center"];
    $data['lead'][] = ["name"=>"Followup Date"];
    $data['lead'][] = ["name"=>"Followup Remark"];

    return tableHeader($data[$page]);
}

/* Sales Enquiry Table data */
function getSalesEnquiryData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('salesEnquiry/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Order'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    if($data->trans_status > 0):
        $editButton = $deleteButton = "";
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->item_name,$data->qty,$data->price];
}

/* Sales Order Table data */
function getSalesOrderData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('salesOrders/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Order'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    if($data->trans_status > 0):
        $editButton = $deleteButton = "";
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->item_name,$data->qty,$data->dispatch_qty,$data->pending_qty];
}

/* Estimate [Cash] Table Data */
function getEstimateData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('estimate/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Invoice'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $print = '';
    //$print = '<a href="javascript:void(0)" class="btn btn-warning btn-edit printDialog permission-approve1" datatip="Print Invoice" flow="down" data-id="'.$data->id.'" data-fn_name="printInvoice"><i class="fa fa-print"></i></a>';

    if($data->trans_no == 0):
        $editButton = $deleteButton = "";
    endif;

    $action = getActionButton($print.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->taxable_amount,$data->net_amount];
}

/* Lead Table Data */
function getLeadData($data){

    $followupBtn = '';$appointmentBtn ='';$enqBtn='';$editButton="";$deleteButton="";
       
    if(in_array($data->lead_status,[0,4])){
        $followupParam = "{'postData': {'id' : ".$data->id.",'entry_type':1}, 'modal_id' : 'modal-lg', 'form_id' : 'followUp', 'title' : 'Follow up', 'fnedit' : 'addFollowup', 'fnsave' : 'saveFollowup','res_function' : 'resFollowup', 'button' : 'close'}";
        $followupBtn = '<a class="btn btn-primary" href="javascript:void(0)" datatip="Followup" flow="down" onclick="edit('.$followupParam.');" ><i class="fas fa-clipboard-check"></i></a>';

        $appointmentParam = "{'postData': {'id' : ".$data->id.",'entry_type':1}, 'modal_id' : 'modal-lg', 'form_id' : 'appointment', 'title' : 'Appointments', 'fnedit' : 'addAppointment', 'fnsave' : 'saveAppointment','res_function' : 'resAppointments', 'button' : 'close'}";
        $appointmentBtn = '<a class="btn btn-info leadAction" href="javascript:void(0)" datatip="Appointment" flow="down" onclick="edit('.$appointmentParam.');"><i class="far fa-calendar-check"></i></a>';
    }

    if($data->lead_status == 0 && empty($data->enq_id)){
        //$enqBtn = '<a href="'.base_url('salesEnquiry/addEnquiry/'.$data->id).'" class="btn btn-info permission-write" datatip="Move To  Enquiry" flow="down"><i class="fa fa-file-alt"></i></a>';        
        $editParam = "{'postData' : {'id' : ".$data->id."}, 'modal_id' : 'modal-xl', 'form_id' : 'editLead', 'title' : 'Update Approch'}";
    
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt"></i></a>';
    }

    $action = getActionButton($enqBtn.$appointmentBtn.$followupBtn.$editButton.$deleteButton);

    $responseData = [$action,$data->sr_no,formatDate($data->lead_date),sprintf("%04d",$data->lead_no),$data->lead_from,$data->party_name,$data->party_phone,$data->emp_name,$data->appointments,$data->followupDate,$data->followupNote];

    return $responseData;
}

?>