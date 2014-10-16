<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function my_autoloader($class)
{
    $filename =  '../' . str_replace('\\', '/', $class) . '.php';
    include($filename);
}
spl_autoload_register('my_autoloader');

use TriangleCRM\TriangleAPI as api;

/**
 * Description of ProspectTest
 *
 * @author e
 */
class CRMTest extends \PHPUnit_Framework_TestCase{
    //put your code here
    
    public function testConfigApi()
    {
        // API
        $a = new api();

        $this->assertClassHasAttribute('configOptions', 'TriangleCRM\TriangleAPI');
        $this->assertNotEmpty($a->configOptions);
        $this->assertNotEmpty($a->configOptions['Settings']);
        $this->assertArrayHasKey('DOMAIN', $a->configOptions['Settings']);
    }
    
    public function testProcessRequest(){
         $a = new api();
          $params = array(
                   'affiliate'=> 'DFOfS1',
                   'pageTypeID'=>3,
                   'op'=>'firepixel'
                );
        $a->ProcessRequest($params);
         
       
    }
    
    public function testCreateProspect(){
        // API
        $a = new api();
        //leave campaign empty so should call CreateProspect vs EX
        $params = array(
                'productTypeID' => 3,   // hardcoded to associate with primary project for now
                'campaignID' => '',
                'firstName' => 'unit-test',
                'lastName' => 'local',
                'address1' => '124 test lane',
                'address2' => '',
                'city' => '',
                'state' => '',
                'zip' => '',
                'country' => '',
                'phone' => '',
                'email' => '',
                'ip' => '127.0.0.1',
                'affiliate' => '',
                'subAffiliate' => '',
                'internalID' => '',
                'customField1' => '',
                'customField2' => '',
                'customField3' => '',
                'customField4' => '',
                'customField5' => ''
            );
        
         $result = $a->CreateProspect($params);
         
         $this->assertNotEmpty($result->State,"State empty");
         $this->assertNotEmpty($result->Info,"Info empty");
         $this->assertTrue($result->State === "Success");
         $this->assertNotEmpty($result->Result,"Result should have come back");
         $this->assertNotEmpty($result->Result->ProspectID,"No Prospect ID returned");
         
    }
    //IF Campaign is Set CreateProspect will call the extend version of API method
    public function testCreateProspectWithCampaignSet(){
         $a = new api();
        //leave campaign empty so should call CreateProspect vs EX
        $params = array(
                'productTypeID' => 3,   // hardcoded to associate with primary project for now
                'campaignID' => 122,
                'firstName' => 'unit-test',
                'lastName' => 'local',
                'address1' => '124 test lane',
                'address2' => '',
                'city' => '',
                'state' => '',
                'zip' => '',
                'country' => '',
                'phone' => '',
                'email' => '',
                'ip' => '127.0.0.1',
                'affiliate' => '',
                'subAffiliate' => '',
                'internalID' => '',
                'customField1' => '',
                'customField2' => '',
                'customField3' => '',
                'customField4' => '',
                'customField5' => ''
            );
        
         $result = $a->CreateProspect($params);
         
         $this->assertNotEmpty($result->State,"State empty");
         $this->assertNotEmpty($result->Info,"Info empty");
         $this->assertTrue($result->State === "Success");
         $this->assertNotEmpty($result->Result,"Result should have come back");
         $this->assertNotEmpty($result->Result->ProspectID,"No Prospect ID returned");
        
    }
    public function testFirePixelWithResults(){
          $a = new api();
         
         $params = array(
                        'affiliate'=> 'DFOfS1',
                        'pageTypeID'=>4,
                        'prospectID'=>''
                        );
         
         $result = $a->FireAffiliatePixel($params);
         
         $this->assertNotEmpty($result->State,"State empty");
         $this->assertNotEmpty($result->Info,"Info empty");
         $this->assertTrue($result->State === "Success");
         $this->assertNotEmpty($result->Result,"Result should have come back");
         
         
    }
    public function testFirePixelWithNoResults(){
         $a = new api();
         
         $params = array(
                        'affiliate'=> 'DFOfS1',
                        'pageTypeID'=>2,
                        'prospectID'=>''
                        );
         
         $result = $a->FireAffiliatePixel($params);
         
         $this->assertNotEmpty($result->State,"State empty");
         $this->assertNotEmpty($result->Info,"Info empty");
         $this->assertTrue($result->State === "Success");
         $this->assertEmpty($result->Result);
         
    }
    
    public function testUpdateShippingInfo(){
        $a = new api();
        
        $params = array(
                'prospectID' => 58, 
                'internalID' => '',
                'firstName' => 'unit-test',
                'lastName' => 'local',
                'address1' => '124 test lane',
                'address2' => '',
                'city' => '',
                'state' => '',
                'zip' => '',
                'country' => '',
                'phone' => '',
                'email' => ''
            );
        
        $result = $a->UpdateShippingInfo($params);
        $this->assertNotEmpty($result->State,"State empty");
        $this->assertNotEmpty($result->Info,"Info empty");
        $this->assertTrue($result->State === "Success");  
    }
    
    public function testIsCardDup(){
        $a = new api();
        
        $params = array(
               'creditCard'=>'4111111111111111',
                'productID'=>1
            );
        
        $result = $a->IsCreditCardDupe($params);
        $this->assertNotEmpty($result->State,"State empty");
        $this->assertNotEmpty($result->Info,"Info empty");
        $this->assertTrue($result->State === "Success");  
        $this->assertFalse($result->Result);  
    }
    
   public function testCharge(){
        $a = new api();
        //leave campaign empty so should call CreateProspect vs EX
        $params = array(
                'sendConfirmationEmail'=>FALSE,
                'amount'=>1.00,
                'paymentType'=>2,
                'creditCard'=>'4111111111111111',
                'cvv'=>'1111',
                'expMonth'=>1,
                'expYear'=>2015,
                'productID'=>1,  // hardcoded to associate with primary project for now
                'campaignID' => 2,
                'firstName' => 'unit-test',
                'lastName' => 'local',
                'address1' => '124 test lane',
                'address2' => '',
                'city' => 'test',
                'state' => 'city',
                'zip' => '08888',
                'country' => 'US',
                'phone' => '',
                'email' => '',
                'ip' => '127.0.0.1',
                'affiliate' => '',
                'subAffiliate' => '',
                'internalID' => '',
                'customField1' => '',
                'customField2' => '',
                'customField3' => '',
                'customField4' => '',
                'customField5' => ''
            );
        
         $result = $a->Charge($params);
         
         $this->assertNotEmpty($result->State,"State empty");
         $this->assertNotEmpty($result->Info,"Info empty");
         $this->assertTrue($result->State === "Success");
         $this->assertNotEmpty($result->Result,"Result should have come back");
         $this->assertNotEmpty($result->Result->ProspectID,"No Prospect ID returned");
        
       
       
   }

}
