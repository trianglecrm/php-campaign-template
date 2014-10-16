var billing_fields = ['#Billing_Address_1', '#Billing_Country', '#Billing_City', 'select[id^="Billing_State_"]:visible', 'input[name="Billing_Zip"]'];
var billing_field_vals = [];
var billing_state_field = null;
var billing_saved = false;

$(document).ready(function(){

	$('input[name="same_address"]').click(function(){
		if($('input[name="same_address"]:checked').val() == 'no') {
			$('#billing-fields').show();
			save_fields();
		} else {
			reset_fields();
			$('#billing-fields').hide();
		}	
	});
	
	$('#CC_Type').val(2);
	
	$('a.cvv-help').mousemove(function(e){
		$('#cvv_help').show();
	});
	$('a.cvv-help').mouseout(function(e){
		$('#cvv_help').hide();
	});
	
});

function save_fields(){
	if(billing_saved == false){
		billing_state_field = '#'+$('select[id^="Billing_State_"]:visible').attr('id');
		for(i=0;i<=billing_fields.length-1;i++){
			var f = billing_fields[i];
			billing_field_vals[i] = $(f).val();
		}	
	}
}	
function reset_fields(){
	$('select[id^="Billing_State_"]').hide();
	$(billing_state_field).show();
	for(i=0;i<=billing_fields.length-1;i++){
		var f = billing_fields[i];
		$(f).val(billing_field_vals[i]);
	}
}



function IsCreditCardNumberMatchType() {
    var ccNumber = $('#CC_Number').val();
    var ccType = $('#CC_Type').val();
    var calculatedCCType = GetCreditCardTypeByNumber(ccNumber);
    if (ccType != calculatedCCType) {
        return false;
    }
    return true;
}

function GetCreditCardTypeByNumber(ccnumber) {
	var cc = (ccnumber + '').replace(/\s/g, ''); //remove space
	if ((/^(34|37)/).test(cc) && cc.length == 15) {
	    return 1; //AMEX begins with 34 or 37, and length is 15.
	} else if ((/^(51|52|53|54|55)/).test(cc) && cc.length == 16) {
	    return 3; //MasterCard beigins with 51-55, and length is 16.
	} else if ((/^(4)/).test(cc) && (cc.length == 13 || cc.length == 16)) {
	    return 2; //VISA begins with 4, and length is 13 or 16.
	} else if ((/^(6011)/).test(cc) && cc.length == 16) {
	    return 4; //Discover begins with 6011, and length is 16.
	}
	return '?'; //unknow type
}

function submitOrder() {
	if (!IsCreditCardNumberMatchType()) {
        alert('Enter valid Credit Card type.');
        return false;
	}
	window.internal = true;
 	IHS.submit('Billing'); 
	return false;
} 