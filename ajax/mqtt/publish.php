<?php
	include('../../config.php');
	require DIR.'/composer/vendor/autoload.php';

	$p = $_POST;
	$data = array();

	$server   = 'some-broker.example.com';
	$port     = 1883;
	$clientId = 'computador';

	$mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
	$mqtt->connect();
	$mqtt->publish('Embarcados/'.$p['topic'], $p['message'], 0);
	$mqtt->disconnect();

	die(json_encode($data));
?>