var ccModule = (function (gf) {
        var ccinfo = {}, obj = {}, billingInfo = {};
        
        function init(){
            obj.eventsHandler();
            billingInfo = gf.getCookie('billingInfo');
            if(billingInfo == undefined){ // check if the user went through the correct order 
                window.location.href = "/index.php?redirected=1";
            }
            console.log(billingInfo);
            for (j = new Date().getFullYear(),i = j; i < j+12; i++)
            {
                $('#expYear').append($('<option />').val(i).html(i));
            }
        }
        
	function save(){
            populateElements();
            var oldCC = ccinfo.creditCard;
            ccinfo.creditCard = gf.encryptData(ccinfo.creditCard);
            gf.processer('show');
            jsonObj = JSON.stringify(ccinfo);
            gf.ServiceHandlerPost('CreateSubscription',jsonObj).done(function(response){
                response = JSON.parse(response);
                if(response.State == 'Success'){
                    internal = true;
                    window.location.href = "/"+ccinfo.successRedirect;
                }
                else{
                    if(response.Info == 'Test charge. ERROR'){ // testing env, remove on PROD
                        internal = true;
                        window.location.href = "/"+ccinfo.successRedirect;
                    }
                    else{
                        $('#creditCard').val(oldCC);
                        gf.processer();
                        gf.alert(response.Info);
                    }
                }
            });
            return false;
        };
        
        function populateElements(){
            ccinfo.planID = orderSettings.Result.planID;
            ccinfo.trialPackageID = orderSettings.Result.trialPackageID;
            ccinfo.chargeForTrial = orderSettings.Result.chargeForTrial;
            ccinfo.campaign_id = orderSettings.Result.campaign_id;
            ccinfo.firstName = billingInfo.firstname || '';
            ccinfo.lastName = billingInfo.lastname || '';
            ccinfo.address1 = billingInfo.address1 || '';
            ccinfo.address2 = billingInfo.address2 || '';
            ccinfo.city = billingInfo.city || '';
            ccinfo.state = billingInfo.state || '';
            ccinfo.zip = billingInfo.zip || '';
            ccinfo.country = billingInfo.country || '';
            ccinfo.phone = billingInfo.phone || 0;
            ccinfo.email = billingInfo.email || '';
            ccinfo.sendConfirmationEmail = orderSettings.Result.sendConfirmationEmail;
            ccinfo.affiliate = gf.getParameterByName('aff') || '';
            ccinfo.subAffiliate = gf.getParameterByName('sub') || '';
            ccinfo.prospectID = billingInfo.ProspectID;
            ccinfo.description = orderSettings.Result.description || '';
            ccinfo.successRedirect = orderSettings.Result.successRedirect || '';
            ccinfo.paymentType = $('#paymentMethod').val() || '';
            ccinfo.creditCard = $('#creditCard').val() || '';
            ccinfo.expMonth = $('#expMonth').val() || '';
            ccinfo.expYear = $('#expYear').val() || '';
            ccinfo.cvv = $('#cvv').val() || '';
        };
        
        obj.eventsHandler = function (){
            $('.cvv-help').stop(true, false).mouseover(function() {
                $(".whatiscvv").show('slow');
            })
            .mouseout(function() {
                $(".whatiscvv").stop(true, false).hide('slow');
            });
            $('#paymentMethod').on('change', function() {
                type = this.value;
                if(type == 1){
                    $('#creditCard').attr('pattern','[0-9]{4} *[0-9]{6} *[0-9]{5}');
                    $('#creditCard').attr('maxlength','15');
                    $('#cvv').attr('pattern','[0-9]{4}');
                    $('#cvv').attr('maxlength','4');
                }
                else{
                    $('#creditCard').attr('pattern','[0-9]{13,16}');
                    $('#creditCard').attr('maxlength','16');
                    $('#cvv').attr('pattern','[0-9]{3}');
                    $('#cvv').attr('maxlength','3');
                }
            });
            $('#expMonth , #expYear').on('change', function() {
                selectedMonth = $('#expMonth').val();
                selectedYear = $('#expYear').val();
                if(selectedMonth && selectedYear){
                    today = new Date();
                    selected = new Date();
                    selected.setMonth(selectedMonth);
                    selected.setYear(selectedYear)
                    if(today >= selected){
                        gf.alert('Please select a valid date!');
                        $('#expMonth').val('');
                        $('#expYear').val('');
                        return false;
                    }
                }
            });
        }
        
        obj.init = init();
        obj.save = save;
        obj.ccinfo = ccinfo;
        
        return obj;
        
}(globalFunctions));