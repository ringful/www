<?php
  $page_access_token = "CAAN8GOe2qSEBAAWv33lCD3JjhupZCRxP7ilLTv9wcjfx3nNVoPnb8XolBuSoRK0woc9QjZC2YGIE6LTkvXqawO3nHGh5iQt0LgFfk4ZC9tY1Moeid2njpcJ7ys2TUGaE8iu5Kvkmd7fZCA0prfwZCNtAJfZBd4E0qLn0RZA6xWVAvnWQkmXNZBqvfrD6U2gxKrfC1vPBRGBgWgZDZD";
  if ($_GET['hub_verify_token'] === 'my_special_token') {
    echo $_GET['hub_challenge'];
    exit;
  }

  $body = file_get_contents('php://input');
  error_log ($body);
  $json = json_decode($body, true);
  for($i=0; $i<count($json['entry']); $i++) {
    $messagings = $json['entry'][$i]['messaging'];
    for($j=0; $j<count($messagings); $j++) {
      $messaging = $messagings[$j];
      $sender_id = $messaging['sender']['id'];
      $message = $messaging['message']['text'];

      $arr = array(
        "recipient" => array("id" => $sender_id),
        "message" => array("text" => $message)
      );
      $url = "https://graph.facebook.com/v2.6/me/messages?access_token=" . $page_access_token;    
      $content = json_encode($arr);
      error_log($url);
      error_log($content);

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER,
        array("Content-type:application/json")
      );
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

      $resp = curl_exec($ch);
      $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      error_log($status . " " . $resp);
    }
  }
?>
