$(document).ready(function(){
	$(".cgstCol").show();$(".sgstCol").show();$(".igstCol").hide();
	$(".amountCol").hide();$(".netAmtCol").show();

	var numberOfChecked = $('.termCheck:checkbox:checked').length;
	$("#termsCounter").html(numberOfChecked);
	$(document).on("click", ".termCheck", function () {
		var id = $(this).data('rowid');
		var numberOfChecked = $('.termCheck:checkbox:checked').length;
		$("#termsCounter").html(numberOfChecked);
		if ($("#md_checkbox" + id).attr('check') == "checked") {
			$("#md_checkbox" + id).attr('check', '');
			$("#md_checkbox" + id).removeAttr('checked');
			$("#term_id" + id).attr('disabled', 'disabled');
			$("#term_title" + id).attr('disabled', 'disabled');
			$("#condition" + id).attr('disabled', 'disabled');
		} else {
			$("#md_checkbox" + id).attr('check', 'checked');
			$("#term_id" + id).removeAttr('disabled');
			$("#term_title" + id).removeAttr('disabled');
			$("#condition" + id).removeAttr('disabled');
		}
	});

    $(document).on('keyup', '.calculateSummary', function () { claculateColumn(); });
    $(document).on('change','#gstin', function(){ gstin(); });
});

function gstin(){
    var gstin = $("#gstin").find(":selected").val();	

    var gst_type= 1; var stateCode = 24;
    if(gstin != ""){
		if(gstin == "URP"){
			stateCode = 24;
			gst_type = 3;
		}else{
			stateCode = gstin.substr(0, 2);
        	if(stateCode == 24 || stateCode == "24"){gst_type= 1;}else{gst_type= 2;}
		}        
    }

    $("#gst_type").val(gst_type);
    $("#party_state_code").val(stateCode);

    if(gst_type == 1){ 
		$(".cgstCol").show();$(".sgstCol").show();$(".igstCol").hide();
		$(".amountCol").hide();$(".netAmtCol").show();
	}else if(gst_type == 2){
		$(".cgstCol").hide();$(".sgstCol").hide();$(".igstCol").show();
		$(".amountCol").hide();$(".netAmtCol").show();
	}else{
		$(".cgstCol").hide();$(".sgstCol").hide();$(".igstCol").hide();
		$(".amountCol").show();$(".netAmtCol").hide();
	}

    claculateColumn();
}

function claculateColumn() {
	var amountArray = $(".amount").map(function () { return $(this).val(); }).get();
	var amountSum = 0;
	$.each(amountArray, function () { amountSum += parseFloat(this) || 0; });
	$("#total_amount").html(amountSum.toFixed(2));

	var taxableAmountArray = $(".taxable_amount").map(function () { return $(this).val(); }).get();
	var taxableAmountSum = 0;
	$.each(taxableAmountArray, function () { taxableAmountSum += parseFloat(this) || 0; });
	$("#taxable_amount").val(taxableAmountSum.toFixed(2));

	calculateSummary();
}

function calculateSummary() {
	$(".calculateSummary").each(function () {
		var row = $(this).data('row');

		var map_code = row.map_code;
		var amtField = $("#" + map_code + "_amt");
		var netAmountField = $("#" + map_code + "_amount");
		var perField = $("#" + map_code + "_per");
		var sm_type = amtField.data('sm_type');

		if (sm_type == "exp") {
			if (row.position == "1") {
				var itemGstArray = $(".gst_per").map(function () { return $(this).val(); }).get();
				var maxGstPer = Math.max.apply(Math, itemGstArray);
				maxGstPer = (maxGstPer != "" && !isNaN(maxGstPer)) ? maxGstPer : 0;

				if (row.calc_type == "1") {
					var amount = (amtField.val() != "") ? amtField.val() : 0;
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
					netAmountField.val(amount);
					var gstAmount = parseFloat((parseFloat(maxGstPer) * parseFloat(amount)) / 100).toFixed(2);
				} else {
					var taxable_amount = ($("#taxable_amount").val() != "") ? $("#taxable_amount").val() : 0;
					var per = (perField.val() != "") ? perField.val() : 0;

					var amount = parseFloat((parseFloat(taxable_amount) * parseFloat(per)) / 100).toFixed(2);
					amtField.val(amount);
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
					netAmountField.val(amount);
					var gstAmount = parseFloat((parseFloat(maxGstPer) * parseFloat(amount)) / 100).toFixed(2);
				}

				$("#other_" + map_code + "_amount").val(gstAmount);

			} else {
				if (row.calc_type == "1") {
					var amount = (amtField.val() != "") ? amtField.val() : 0;
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
					netAmountField.val(amount);
				} else {
					var taxable_amount = ($("#taxable_amount").val() != "") ? $("#taxable_amount").val() : 0;
					var per = (perField.val() != "") ? perField.val() : 0;
					var amount = parseFloat((parseFloat(taxable_amount) * parseFloat(per)) / 100).toFixed(2);
					amtField.val(amount);
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
					netAmountField.val(amount);
				}
			}
		}

		if (sm_type == "tax") {
			if(row.calculation_type == 2){
				var oldAmt = amtField.val();
				oldAmt = (parseFloat(oldAmt) > 0)?oldAmt:0;	
				calculateSummaryAmount();		

				var summaryAmtArray = $(".summaryAmount").map(function(){return $(this).val();}).get();
				var summaryAmtSum = 0;
				$.each(summaryAmtArray,function(){summaryAmtSum += parseFloat(this) || 0;});
				
				if(parseFloat(summaryAmtSum) > 0){
					summaryAmtSum = parseFloat(parseFloat(summaryAmtSum) - parseFloat(oldAmt)).toFixed(2);
				}else{
					amtField.val(0);
				}
				
				var per = (perField.val() != "")?perField.val():0;				
				var amount = parseFloat((parseFloat(summaryAmtSum) * parseFloat(per)) / 100).toFixed(2);				
				amtField.val(amount);
				amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
				netAmountField.val(amount);
			}else if (row.calculation_type == 1) {
				var taxable_amount = ($("#taxable_amount").val() != "") ? $("#taxable_amount").val() : 0;
				var per = (perField.val() != "") ? perField.val() : 0;
				var amount = parseFloat((parseFloat(taxable_amount) * parseFloat(per)) / 100).toFixed(2);
				amtField.val(amount);
				amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
				netAmountField.val(amount);
			} else {
				var qtyArray = $(".item_qty").map(function () { return $(this).val(); }).get();
				var qtySum = 0;
				$.each(qtyArray, function () { qtySum += parseFloat(this) || 0; });

				var per = (perField.val() != "") ? perField.val() : 0;
				var amount = parseFloat(parseFloat(qtySum) * parseFloat(per)).toFixed(2);
				amtField.val(amount);
				amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(2);
				netAmountField.val(amount);
			}
		}


	});

	calculateSummaryAmount();
}

function calculateSummaryAmount() {
	var gst_type = $("#gst_type").val();

	$('#cgst_amount').val("0");
	$('#sgst_amount').val("0");
	if (gst_type == 1) {
		var cgstAmtArr = $(".cgst_amount").map(function () { return $(this).val(); }).get();
		var cgstAmtSum = 0;
		$.each(cgstAmtArr, function () { cgstAmtSum += parseFloat(this) || 0; });
		$('#cgst_amount').val(parseFloat(cgstAmtSum).toFixed(2));

		var sgstAmtArr = $(".sgst_amount").map(function () { return $(this).val(); }).get();
		var sgstAmtSum = 0;
		$.each(sgstAmtArr, function () { sgstAmtSum += parseFloat(this) || 0; });
		$('#sgst_amount').val(parseFloat(sgstAmtSum).toFixed(2));
	}

	$('#igst_amount').val("0");
	if (gst_type == 2) {
		var igstAmtArr = $(".igst_amount").map(function () { return $(this).val(); }).get();
		var igstAmtSum = 0;
		$.each(igstAmtArr, function () { igstAmtSum += parseFloat(this) || 0; });
		$('#igst_amount').val(parseFloat(igstAmtSum).toFixed(2));
	}

	var otherGstAmtArray = $(".otherGstAmount").map(function () { return $(this).val(); }).get();
	var otherGstAmtSum = 0;
	$.each(otherGstAmtArray, function () { otherGstAmtSum += parseFloat(this) || 0; });

	var cgstAmt = 0;
	var sgstAmt = 0;
	var igstAmt = 0;
	if (gst_type == 1) {
		cgstAmt = parseFloat(parseFloat(otherGstAmtSum) / 2).toFixed(2);
		sgstAmt = parseFloat(parseFloat(otherGstAmtSum) / 2).toFixed(2);
		$("#cgst_amount").val(parseFloat(parseFloat($("#cgst_amount").val()) + parseFloat(cgstAmt)).toFixed(2));
		$("#sgst_amount").val(parseFloat(parseFloat($("#sgst_amount").val()) + parseFloat((sgstAmt))).toFixed(2));
	} else if (gst_type == 2) {
		igstAmt = otherGstAmtSum;
		$("#igst_amount").val(parseFloat(parseFloat($("#igst_amount").val()) + parseFloat((igstAmt))).toFixed(2));
	}

	var summaryAmtArray = $(".summaryAmount").map(function () { return $(this).val(); }).get();
	var summaryAmtSum = 0;
	$.each(summaryAmtArray, function () { summaryAmtSum += parseFloat(this) || 0; });

	if ($("#roff_amount").length > 0) {
		var totalAmount = parseFloat(summaryAmtSum).toFixed(2);
		var decimal = totalAmount.split('.')[1];
		var roundOff = 0;
		var netAmount = 0;
		if (decimal !== 0) {
			if (decimal >= 50) {
				if ($('#apply_round').val() == "1") { roundOff = (100 - decimal) / 100; }
				netAmount = parseFloat(parseFloat(totalAmount) + parseFloat(roundOff)).toFixed(2);
			} else {
				if ($('#apply_round').val() == "1") { roundOff = (decimal - (decimal * 2)) / 100; }
				netAmount = parseFloat(parseFloat(totalAmount) + parseFloat(roundOff)).toFixed(2);
			}
			$("#roff_amount").val(parseFloat(roundOff).toFixed(2));
		}
		$("#net_amount").val(netAmount);
	} else {
		$("#net_amount").val(summaryAmtSum.toFixed(2));
	}
}


