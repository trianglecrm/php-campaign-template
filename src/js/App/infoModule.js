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
