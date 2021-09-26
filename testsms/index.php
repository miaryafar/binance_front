<?php
echo "hi";
echo(phpversion());
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>SendSMS</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>

</head>
<body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<div id="form_container">
        <?php
            $data = $_POST;
            if($data['submit']==="Submit")
            {
                $encoding = (mb_detect_encoding($data['message']) == 'ASCII') ? "1" : "8";
                $send = SendWSDL($data['username'], $data['password'],$data['Source'], $data['Destination'], $data['message'], $encoding);
                $status = object2array($send);
                if ($status['return']['status']==0)
                {
                    echo "Message Sent!";
                    echo " MsgID = ".$status['return']['msgIdArray'];
                }
                else{
                    echo $status['return']['errorMsg'];
                }
            }

        ?>

		<h1>
            <div style="width: 100%;text-align: center;">
                <img src="asanak.png" style="text-align: center;margin: 0 auto;width: 50%;"/>
            </div>
            <a>SendSMS</a>
        </h1>
        <?php
        $rand = rand(100000 , 999999);
        ?>
		<form id="form_<?php echo $rand;?>" class="appnitro"  method="post" action="">
					<div class="form_description">
			<h2>SendSMS</h2>
			<p></p>
		</div>						
			<ul >
			
					<li id="li_1" >
		<label class="description" for="username">Username </label>
		<div>
			<input id="username" name="username" class="element text medium" type="text" maxlength="255" value="<?php echo $_POST['username']?>"/>
		</div> 
		</li>		<li id="li_2" >
		<label class="description" for="password">Password </label>
		<div>
			<input id="password" name="password" class="element text medium" type="password" maxlength="255" value="<?php echo $_POST['password']?>"/>
		</div> 
		</li>

        <li id="li_4" >
            <label class="description" for="Source">Source </label>
            <div>
                <input id="Source" name="Source" class="element text medium" type="text" maxlength="255" value="<?php echo $_POST['Source']?>"/>
            </div>
        </li>
        <li id="li_5" >
            <label class="description" for="Destination">Destination </label>
            <div>
                <input id="Destination" name="Destination" class="element text medium" type="text" maxlength="255" value="<?php echo $_POST['Destination']?>"/>
            </div>
        </li>
        <li id="li_6" >
		<label class="description" for="message">Message</label>
		<div>
			<textarea id="message" name="message" class="element textarea medium"><?php echo $_POST['message']?></textarea>
		</div> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="<?php echo $rand?>" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	
		<div id="footer">

		</div>
	</div>
	<img id="bottom" src="bottom.png" alt="">
	</body>
</html>

<?php


function WSDLconnect()
{
$WSDL = "http://ws.asanak.ir:8082/services/CompositeSmsGateway?wsdl";
    try{
libxml_disable_entity_loader(false);       
	   $opts = array(
    'ssl' => array(
        'ciphers' => 'RC4-SHA',
        'verify_peer' => false,
        'verify_peer_name' => false
    )
);
	   
	   $conn = new SoapClient($WSDL, array('trace'=> true,'exceptions' => true, 'compression' => SOAP_COMPRESSION_ACCEPT, 'connection_timeout'=>120, 'cache_wsdl' => WSDL_CACHE_BOTH , 'encoding' => 'UTF-8',
    'verifypeer' => false,
    'verifyhost' => false,
    'soap_version' => SOAP_1_1,
    'trace' => 1,
    'exceptions' => 1,
    'connection_timeout' => 180,
    'stream_context' => stream_context_create($opts)));
		echo "sshi<br>";
    }  catch (Exception $ex)
    {
        echo "hiaasd<br>";
		exit($ex->getMessage());
    }
    return $conn;
}
function SendWSDL($username,$password, $Source, $Destination, $MsgBody, $Encoding)
{
	
	
    $client = WSDLconnect();
    if ($client)
    try {
			//$s = $client->sendSms(array('userCredential'=>array('username' => $username, 'password' => $password),'srcAddresses' => $Source , 'destAddresses' => '0780771046' , 'msgBody' => $MsgBody , 'msgEncoding' => $Encoding));
			$s = $client->sendSms(	array('userCredential'=>
					array('username' => 'asiapardazesh', 'password' => 'asiapardazesh@2917'),
						'srcAddresses' => $Source , 'destAddresses' => $Destination , 'msgBody' => $MsgBody , 'msgEncoding' => $Encoding,

						)
					);
			
			 print_r($s);
            echo "hiss<br>";
			return $s;
        } catch (SoapFault $ex) {
            echo "hi<br>";
			exit($ex->faultstring);
			print_r($ex->faultstring);
        }
}
function object2array($object) {
    if (is_object($object) || is_array($object)) {
        foreach ($object as $key => $value) {
            $array[$key] = object2array($value);
        }
    }
    else {
        $array = $object;
    }
    return $array;
}
?>