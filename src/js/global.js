var globalFunctions = (function () {
	var obj = {};
        
	obj.alert = function(msg){
            jError(
                "<div>We are sorry but we could not process your request.<br/><h2>"+msg+"</h2>Please correct and try again.</div>",
                {
                    autoHide : true, // added in v2.0
                    TimeShown : 3000,
                    HorizontalPosition : 'center',
                    onCompleted : function(){ // added in v2.0

                    }
                }
            );
        };
        
        obj.ServiceHandlerPost = function(action,params){
            return $.post( "TriangleCRM/Controller.php", { 
                action : action,
                data : params });
        };
        
        obj.setCookie = function(name,data){
            $.cookie(name, JSON.stringify(data));
        };
        
        obj.removeCookie = function(name){
            $.removeCookie(name);
        };
        
        obj.getCookie = function(name){
            return ($.cookie(name)) ? JSON.parse($.cookie(name)) : '';
        
        };
        
        obj.encryptData = function(toencrypt){
            var $pem="-----BEGIN PUBLIC KEY-----\
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAM1RXGYKyXlCGcGvFYeNCD+yzVAOoK+w\
2awyE6vOCSqhR0pAWFgpWOuwbrL5M78PILmZc85ipbzoz6Vtv4IvYJUCAwEAAQ==\
-----END PUBLIC KEY-----";
            var $key = RSA.getPublicKey($pem);
            return RSA.encrypt(toencrypt,$key);
        };
        
        obj.getDate = function(days) {  
            var dayNames = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");    
            var monthNames = new Array("January","February","March","April","May","June","July","August","September","October","November","December"); 
            var now = new Date();   
            now.setDate(now.getDate() + days);   
            var nowString =  dayNames[now.getDay()] + ", " + monthNames[now.getMonth()] + " " + now.getDate() + ", " + now.getFullYear();   
            return nowString;
        };
        
        obj.getYear = function(year) {   
            var now = new Date();    
            var nowString = now.getFullYear();   
            document.write(nowString);
        };
        
        obj.processer = function(action){
            if(action == 'show'){
                $("#button-processing").show();
                $("#button-submit").hide();
            }
            else{
                $("#button-processing").hide();
                $("#button-submit").show();
            }
        };
        
        obj.getParameterByName = function( name ){
            name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
            var regexS = "[\\?&]"+name+"=([^&#]*)", 
                regex = new RegExp( regexS ),
                results = regex.exec( window.location.href );
            if( results == null ){
              return "";
            } else{
              return decodeURIComponent(results[1].replace(/\+/g, " "));
            }
        };
        
        obj.eventsHandler = function (){
            
        }
        
        function init(){
            obj.eventsHandler();
            $('.getDate').html(obj.getDate($('.getDate').attr('data-day')));
        };
        
        obj.init = init();
        return obj;
}());

var infoModule = (function (gf) {
        var billinfo = {}, obj = {} ;
        function init(){
            if(gf.getParameterByName('redirected')){  // check if the user is here because a redirect
                gf.alert("You're here because this information is needed");
            }
        }
        
	function save(){
            populateElements();
            gf.processer('show');
            jsonObj = JSON.stringify(billinfo);
            gf.ServiceHandlerPost('createprospect',jsonObj).done(function(response){
                console.log(response);
                response = JSON.parse(response);
                if(response.State == 'Success'){
                    billinfo.ProspectID = response.Result.ProspectID;
                    console.log(billinfo)
                    gf.setCookie('billingInfo',billinfo);
                    internal = true;
                    window.location.href = "/order.php";
                }
                else{
                    gf.processer();
                    gf.alert(response.Info);
                }
            });
            return false;
        };
        
        function populateElements(){
            billinfo.step = '1';
            billinfo.hasFormSubmitted = '';
            billinfo.campaign_id = '117';
            billinfo.domain_name = location.host +'/specialoffer/';
            billinfo.country = 'US';
            billinfo.firstname = $('#fields_fname').val() || '';
            billinfo.lastname = $('#fields_lname').val() || '';
            billinfo.address1 = $('#fields_address1').val() || '';
            billinfo.address2 = $('#fields_address2').val() || '';
            billinfo.city = $('#fields_city').val() || '';
            billinfo.state = $('#fields_state').val() || '';
            billinfo.zip = $('#fields_zip').val() || '';
            billinfo.country = $('#fields_country').val() || billinfo.country;
            billinfo.phone = $('#fields_phone').val() || '';
            billinfo.email = $('#fields_email').val() || '';
        };
        
        obj.init = init();
        obj.save = save;
        obj.billingInfo = billinfo;
        
        return obj;
        
}(globalFunctions));

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