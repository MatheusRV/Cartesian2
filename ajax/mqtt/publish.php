<?php
	ob_start();
	include('../../config.php');
	require DIR.'/composer/vendor/autoload.php';

	use PhpMqtt\Client\ConnectionSettings;
	use PhpMqtt\Client\Examples\Shared\SimpleLogger;
	use PhpMqtt\Client\Exceptions\MqttClientException;
	use PhpMqtt\Client\MqttClient;

	$p = $_POST;
	$data = array();
	$permission = User::getPermission('cartesian');

  if(!isset($_SESSION['verify_authentication_cartesian_key']) || !password_verify('cartesian_'.date('Y-m-d').'_login', $_SESSION['verify_authentication_cartesian_key'])){
     die(json_encode($data));
  }
  else if(!$permission){
    die(json_encode($data));
  }

	$server   = BROKER_ADDRESS;
	$port     = BROKER_PORT;
	$clientId = BROKER_CLIENT;
	$connectionSettings = (new ConnectionSettings)
        ->setUsername(AUTHORIZATION_USERNAME)
        ->setPassword(AUTHORIZATION_PASSWORD);

	$mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
	try{
		$mqtt->connect($connectionSettings, true);

		if($mqtt->isConnected()){
			$mqtt->publish('cartesian/esp/'.$p['topic'], $p['message'], 1, $p['qos']);
			$mqtt->publish('cartesian/web/'.$p['topic'], $p['message'], 1, $p['qos']);
			$mqtt->disconnect();
			$data['success'] = true;
		}
	}
	catch(MqttClientException $e){ $data['success'] = false; }

	ob_end_clean();
	die(json_encode($data));
?>