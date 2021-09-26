<?php

try {

    /**
     *
     *
     * different result codes:
     *
     * 15: at least one of the numbers is invalid
     * 11: user does not own a number
     * 12: user does not own this number
     * 13: user does not have a default number
     * 4 : the webservice is not activated
     * 14: the number is not activated
     * 16: at least one of the numbers is blocked
     * 0 : successful operation
     * 6 : insufficient user credit
     * 5 : invalid request IP
     * 3 : wrong credentials
     *
     *
     */

    $client = new SoapClient("http://panel.isms.ir/IsmsWebService.wsdl", 
        array('trace' => true, 'exceptions' => true, 'compression' => SOAP_COMPRESSION_ACCEPT, 
        'connection_timeout' => 120, 'cache_wsdl' => WSDL_CACHE_NONE));

    //var_dump($client->__getFunctions());exit;

    //$result = $client->GetReport(array('username'=>'testuser','password'=>'testpass','sender'=>'','ids' =>array("1494558360","1494558361")));

    $result = $client->SendSms(array('username'=>'milad','password'=>'Salam@123',
            'destination'=>array(
                "09123762171",
                "09121464352",
            ),
            'content'=>array(
                "test1",
                "test2" ,
            ),'sender' => '982188050578')
    );


    // $result = $client->GetCredit(array('username'=>'testuser','password'=>'testpass'));

    var_dump($result);exit;


} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
