<?php
function sendMessage($messaggio) {
    $token = "448955180:AAGKg9SJypCLrqvxZtANIZDH_5ujHuc2_HQ";
	$chatID = "88322099";

    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
    $url = $url . "&text=" . urlencode($messaggio);
    $ch = curl_init();
    $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
$mes = isset($_post['price ']) ? $_GET['message'] : "hi" ;
if($json = json_decode(file_get_contents("php://input"), true)) {
      //print_r($json);
      $mes = $json;
  } else {
      //print_r($_POST);
      $mes = $_POST;
  }

echo sendMessage($mes);




?>