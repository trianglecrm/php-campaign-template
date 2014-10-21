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
