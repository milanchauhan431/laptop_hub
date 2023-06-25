<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getAccountingDtHeader($page){
    /* Sales Invoice Header */
    $data['salesInvoice'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['salesInvoice'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['salesInvoice'][] = ["name"=>"Inv No."];
	$data['salesInvoice'][] = ["name"=>"Inv Date"];
	$data['salesInvoice'][] = ["name"=>"Customer Name"];
	$data['salesInvoice'][] = ["name"=>"Taxable Amount"];
	$data['salesInvoice'][] = ["name"=>"GST Amount"];
    $data['salesInvoice'][] = ["name"=>"Net Amount"];

    /* Purchase Invoice Header */
    $data['purchaseInvoice'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"];
	$data['purchaseInvoice'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['purchaseInvoice'][] = ["name"=>"Inv No."];
	$data['purchaseInvoice'][] = ["name"=>"Inv Date"];
	$data['purchaseInvoice'][] = ["name"=>"Party Name"];
	$data['purchaseInvoice'][] = ["name"=>"Taxable Amount"];
	$data['purchaseInvoice'][] = ["name"=>"GST Amount"];
    $data['purchaseInvoice'][] = ["name"=>"Net Amount"];

    return tableHeader($data[$page]);
}

/* Sales Invoice Table Data */
function getSalesInvoiceData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('salesInvoice/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Invoice'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->taxable_amount,$data->gst_amount,$data->net_amount];
}

/* Purchase Invoice Table Data */
function getPurchaseInvoiceData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('purchaseInvoice/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="ti-pencil-alt"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Invoice'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->taxable_amount,$data->gst_amount,$data->net_amount];
}
?>