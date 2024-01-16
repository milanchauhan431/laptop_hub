<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getStoreDtHeader($page){
    /* Location Master header */
    $data['storeLocation'][] = ["name"=>"Action","style"=>"width:5%;","sortable"=>"FALSE",'textAlign'=>'center'];
    $data['storeLocation'][] = ["name"=>"#","style"=>"width:5%;","sortable"=>"FALSE",'textAlign'=>'center']; 
    $data['storeLocation'][] = ["name"=>"Store Name"];
    $data['storeLocation'][] = ["name"=>"Location"];
    $data['storeLocation'][] = ["name"=>"Remark"];

    /* Gate Entry */
    $data['gateEntry'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['gateEntry'][] = ["name"=> "GE No.", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "GE Date", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "Transport"];
    $data['gateEntry'][] = ["name" => "LR No."];
    $data['gateEntry'][] = ["name" => "Vehicle Type"];
    $data['gateEntry'][] = ["name" => "Vehicle No."];
    $data['gateEntry'][] = ['name' => "Invoice No."];
    $data['gateEntry'][] = ['name' => "Invoice Date"];
    $data['gateEntry'][] = ['name' => "Challan No."];
    $data['gateEntry'][] = ['name' => "Challan Date"];

    /* Gate Inward Pending GE Tab Header */
    $data['pendingGE'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['pendingGE'][] = ["name"=> "GE No.", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "GE Date", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "Party Name"];
    $data['pendingGE'][] = ["name" => "Inv. No."];
    $data['pendingGE'][] = ["name" => "Inv. Date"];
    $data['pendingGE'][] = ['name' => "CH. NO."];
    $data['pendingGE'][] = ['name' => "CH. Date"];

    /* Gate Inward Pending/Compeleted Tab Header */
    $data['gateInward'][] = ["name" => "Action", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "#", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['gateInward'][] = ["name"=> "GI No.", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "GI Date", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "Party Name"];
    $data['gateInward'][] = ["name" => "Item Name"];
    $data['gateInward'][] = ["name" => "Qty"];
    $data['gateInward'][] = ["name" => "PO. NO."]; 
    
    /* FG Stock Inward Table Header */
    $data['stockTrans'][] = ["name" => "Action", "style" => "width:5%;", "textAlign" => "center"];
    $data['stockTrans'][] = ["name" => "#", "style" => "width:5%;", "textAlign" => "center"];
    $data['stockTrans'][] = ["name" => "Date"];
    $data['stockTrans'][] = ["name"=> "Item Code"];
    $data['stockTrans'][] = ["name" => "Item Name"];
    $data['stockTrans'][] = ["name" => "Qty"];
    $data['stockTrans'][] = ["name" => "Packing Standard"];
    $data['stockTrans'][] = ["name" => "Remark"];

    /* Service Table Header */
    $data['serviceGI'][] = ["name" => "Action", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['serviceGI'][] = ["name" => "#", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['serviceGI'][] = ["name"=> "GI No.", "textAlign" => "center"];
    $data['serviceGI'][] = ["name" => "GI Date", "textAlign" => "center"];
    $data['serviceGI'][] = ["name" => "Item Name"];
    $data['serviceGI'][] = ["name" => "Qty"];
    $data['serviceGI'][] = ["name" => "Repaired Qty"];
    $data['serviceGI'][] = ["name" => "Pending Qty"];

    $data['service'][] = ["name" => "Action", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['service'][] = ["name" => "#", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['service'][] = ["name"=> "Entry No.", "textAlign" => "center"];
    $data['service'][] = ["name" => "Entry Date", "textAlign" => "center"];
    $data['service'][] = ["name" => "Product Name"];
    $data['service'][] = ["name" => "Qty"];
    $data['service'][] = ["name" => "Amount"];
    $data['service'][] = ["name" => "Remark"];

    $data['pendingCustomization'][] = ["name" => "Action", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['pendingCustomization'][] = ["name" => "#", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['pendingCustomization'][] = ["name"=> "SO. No.", "textAlign" => "center"];
    $data['pendingCustomization'][] = ["name" => "SO. Date", "textAlign" => "center"];
    $data['pendingCustomization'][] = ["name" => "Product Name"];
    $data['pendingCustomization'][] = ["name" => "Qty"];
    $data['pendingCustomization'][] = ["name" => "Remark"];

    $data['customization'][] = ["name" => "Action", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['customization'][] = ["name" => "#", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['customization'][] = ["name"=> "Entry No.", "textAlign" => "center"];
    $data['customization'][] = ["name" => "Entry Date", "textAlign" => "center"];
    $data['customization'][] = ["name" => "Ref. No.", "textAlign" => "center"];
    $data['customization'][] = ["name" => "Product Name"];
    $data['customization'][] = ["name" => "Qty"];
    $data['customization'][] = ["name" => "Amount"];
    $data['customization'][] = ["name" => "Remark"];

    $data['externalServices'][] = ["name" => "Action", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['externalServices'][] = ["name" => "#", "style" => "width:5%;","sortable"=>"FALSE", "textAlign" => "center"];
    $data['externalServices'][] = ["name"=> "Entry No.", "textAlign" => "center"];
    $data['externalServices'][] = ["name" => "Entry Date", "textAlign" => "center"];
    $data['externalServices'][] = ["name" => "Party Name"];
    $data['externalServices'][] = ["name" => "Product Name"];
    $data['externalServices'][] = ["name" => "Qty"];
    $data['externalServices'][] = ["name" => "Amount"];
    $data['externalServices'][] = ["name" => "Remark"];
    $data['externalServices'][] = ["name" => "Service Inspector"];

    return tableHeader($data[$page]);
}

/* Store Location Data */
function getStoreLocationData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Store Location'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'editStoreLocation', 'title' : 'Update Store Location'}";

    $editButton = ''; $deleteButton = '';
    if(!empty($data->ref_id) && empty($data->store_type)):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
    endif;

    if($data->final_location == 0):
        $locationName = '<a href="' . base_url("storeLocation/list/" . $data->id) . '">' . $data->location . '</a>';
    else:
        $locationName = $data->location;
    endif;
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->store_name,$locationName,$data->remark];
}

/* Gate Entry Data  */
function getGateEntryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Gate Entry'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-lg', 'form_id' : 'editGateEntry', 'title' : 'Update Gate Entry'}";

    $editButton = "";
    $deleteButton = "";
    if($data->trans_status == 0):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->transport_name,$data->lr,$data->vehicle_type_name,$data->vehicle_no,$data->inv_no,((!empty($data->inv_date))?formatDate($data->inv_date):""),$data->doc_no,((!empty($data->doc_date))?formatDate($data->doc_date):"")];
}

/* GateInward Data Data  */
function getGateInwardData($data){
    $action = '';$editButton='';$deleteButton="";$pallatePrint="";
    if($data->trans_type == 1): //Pending GE Data
        $createGI = "";
        $createGIParam = "{postData:{'id' : ".$data->id."}, 'modal_id' : 'modal-xl', 'form_id' : 'addGateInward', 'title' : 'Gate Inward',fnsave: 'save',fnedit: 'createGI'}";

        $createGI = '<a class="btn btn-success btn-edit permission-write" href="javascript:void(0)" datatip="Create GI" flow="down" onclick="edit('.$createGIParam.');"><i class="fa fa-plus" ></i></a>';

        $action = getActionButton($createGI);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->inv_no,$data->inv_date,$data->doc_no,$data->doc_date];
    else: // Gate Inward Pending/Completed Data

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Gate Inward'}";
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'editGateInward', 'title' : 'Update Gate Inward'}";

        $editButton = "";
        $deleteButton = "";
        if($data->trans_status == 0):
            $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

            $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
        endif;

        $itemKitParam = "{'postData':{'id' : ".$data->mir_trans_id.",'item_id':".$data->item_id.",'unique_id':".$data->unique_id."},'modal_id' : 'modal-xl', 'form_id' : 'itemKitForm', 'title' : 'Item Kit [Item Name : ".$data->item_name."]','fnedit':'addItemKit','fnsave':'saveItemKit'}";
        $itemKit = '<a href="javascript:void(0);" type="button" class="btn btn-info permission-modify" datatip="Item Kit" flow="down" onclick="edit('.$itemKitParam.');"><i class="fas fa-plus"></i></a>';
        if($data->item_stock_type != 1 || empty($data->trans_status)):
            $itemKit = "";
        endif;

        $insParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'materialInspection', 'title' : 'Material Inspection','fnedit':'materialInspection','fnsave':'saveInspectedMaterial'}";
        $inspection = '<a href="javascript:void(0);" type="button" class="btn btn-warning permission-modify" datatip="Inspection" flow="down" onclick="edit('.$insParam.');"><i class="fas fa-search"></i></a>';

        if($data->short_qty > 0 && empty(floatval($data->reparing_pending_qty))):
            $itemKit = $inspection = "";
        endif;

	    $iirPrint = '<a href="'.base_url('gateInward/ir_print/'.$data->id).'" type="button" class="btn btn-primary" datatip="IIR Print" flow="down" target="_blank"><i class="fas fa-print"></i></a>';

	    $action = getActionButton($iirPrint.$itemKit.$inspection.$editButton.$deleteButton);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,$data->qty,$data->po_number];
    endif;
}

/* FG Stock Inward Table Data */
function getStockTransData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Stock'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

    $action = getActionButton($deleteButton);

    return [$action,$data->sr_no,formatDate($data->ref_date),$data->item_code,$data->item_name,$data->qty,$data->size,$data->remark];
}

/* Service Table Data */
function getServicesData($data){
    if($data->entry_type == 26):
        $serviceParam = "{'postData':{'ref_id' : ".$data->mir_trans_id.",'item_id':".$data->item_id.",'item_name':'".$data->item_name."','trans_type':1,'batch_no':'".$data->trans_number."'},'modal_id' : 'modal-xl', 'form_id' : 'serviceForm', 'title' : 'Service','fnedit':'addService','fnsave':'saveService'}";
        $service = '<a href="javascript:void(0);" type="button" class="btn btn-warning permission-modify" datatip="Service" flow="down" onclick="edit('.$serviceParam.');"><i class="fa fa-plus"></i></a>';
        $action = getActionButton($service);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->item_name,$data->short_qty,$data->repaired_qty,$data->pending_qty];
    else:
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Record'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
        $action = getActionButton($deleteButton);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->item_name,$data->qty,$data->total_amount,$data->remark];
    endif;
}

/* Customization Table Data */
function getCustomizationData($data){
    if($data->entry_type == 20):
        $customizeParam = "{'postData':{'ref_id' : ".$data->trans_child_id.",'item_id':".$data->item_id.",'item_name':'".$data->item_name."','trans_type':2,'qty':".floatVal($data->qty)."},'modal_id' : 'modal-xl', 'form_id' : 'customizeForm', 'title' : 'Customize','fnedit':'addCustomize','fnsave':'saveCustomize'}";
        $customize = '<a href="javascript:void(0);" type="button" class="btn btn-warning permission-modify" datatip="Customize" flow="down" onclick="edit('.$customizeParam.');"><i class="fa fa-plus"></i></a>';
        $action = getActionButton($customize);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->item_name,floatVal($data->qty),$data->item_remark];
    else:
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Record'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';
        $action = getActionButton($deleteButton);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->ref_no,$data->item_name,floatVal($data->qty),$data->total_amount,$data->remark];
    endif;
}

/* External Service Table Data */
function getExternalServicesData($data){
    $completeButton = $editButton = $deleteButton = "";

    if(empty($data->trans_status)):
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'editExternalService', 'title' : 'Update External Service'}";
        $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="ti-pencil-alt" ></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Record'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="ti-trash"></i></a>';

        $completeParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-xl', 'form_id' : 'completeExternalService', 'title' : 'Complete Service', 'fnedit' : 'completeService', 'fnsave' : 'saveCompleteService'}";
        $completeButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Complete" flow="down" onclick="edit('.$completeParam.');"><i class="ti-check" ></i></a>';
    else:
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Are you sure want to save this change ?','fnsave' : 'uncompleteService'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="confirmStore('.$deleteParam.');" datatip="Reversed" flow="down"><i class="ti-close"></i></a>';
    endif;

    $action = getActionButton($completeButton.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,$data->qty,$data->total_amount,$data->remark,$data->emp_name];
}
?>