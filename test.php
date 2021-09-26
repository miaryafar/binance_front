<?php
$txt = "asasd";
try {
 
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
                $txt,
                $txt ,
            ),'sender' => '982188050578')
    );


    // $result = $client->GetCredit(array('username'=>'testuser','password'=>'testpass'));

    var_dump($result);exit;


} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
?>