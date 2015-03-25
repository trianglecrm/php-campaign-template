$(document).ready(function() {
        getPixel();
        $('body').on('change','#paymentMethod' , function() {
            console.log('cambio');
            type = this.value;
            if(type == 1){
                $('#creditCard').attr('pattern','{15}');
                $('#creditCard').attr('maxlength','15');
                $("#creditCard").mask("9999-999999-99999");
                $('#cvv').attr('pattern','[0-9]{4}');
                $("#cvv").mask("9999");
                $('#cvv').attr('maxlength','4');
            }
            else{
                $('#creditCard').attr('pattern','{13,16}');
                $('#creditCard').attr('maxlength','16');
                $("#creditCard").mask("9999-9999-9999-9999");
                $('#cvv').attr('pattern','[0-9]{3}');
                $("#cvv").mask("999");
                $('#cvv').attr('maxlength','3');
            }
        });
        $('body').on('keydown','.onlyNumbers' , function(e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                 // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) || 
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        $('body').on('submit','form' , function() {
            internal = 1;
        });
        $('body').on('submit','.CCform' , function() {
            var ccVal = $('#creditCard').val().replace(/-/g, "");
            $('#creditCard').val(encryptData(ccVal));
        });
        
        $( "body" ).on( "change", "#expMonth , #expYear",(function () {
                selectedMonth = $('#expMonth').val();
                selectedYear = $('#expYear').val();
                if(selectedMonth && selectedYear){
                    today = new Date();
                    selected = new Date();
                    selected.setMonth(selectedMonth);
                    selected.setYear(selectedYear)
                    if(today >= selected){
                        alert('Please select a valid date!');
                        $('#expMonth').val('');
                        $('#expYear').val('');
                        return false;
                    }
                }
            }));
});

function encryptData(toencrypt){
    var $pem="-----BEGIN PUBLIC KEY-----\
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAM1RXGYKyXlCGcGvFYeNCD+yzVAOoK+w\
2awyE6vOCSqhR0pAWFgpWOuwbrL5M78PILmZc85ipbzoz6Vtv4IvYJUCAwEAAQ==\
-----END PUBLIC KEY-----";
    var $key = RSA.getPublicKey($pem);
    return RSA.encrypt(toencrypt,$key);
};

function getPixel(){
    var params = {},action = 'firepixel';
    if(getUrlVars()["aff"]){ 
        params.affiliate = getUrlVars()["aff"];
    }
    if($.cookie('ProspectID')){
        params.prospectID = $.cookie('ProspectID');
    }
    params.pageTypeID =  pageId;
    jsonObj = JSON.stringify(params);
    $.post( "TriangleCRM/Controller.php", { 
        action : action,
        data : jsonObj }).done(function(response){
            console.log('server response' + response);
            obj = JSON.parse(response);
            if(obj.State == 'error'){
                console.log(obj.Info)
            }
            else{
                document.body.innerHTML += obj.Result;
            }
    });
}

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}