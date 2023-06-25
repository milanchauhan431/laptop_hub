<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getSalesDtHeader($page){
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

    return tableHeader($data[$page]);
}

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
?>