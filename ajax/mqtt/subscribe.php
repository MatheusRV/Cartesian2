<?php
	include('../../config.php');

	$p = $_POST;
	$data = array();

	$server   = 'some-broker.example.com';
	$port     = 1883;
	$clientId = 'computador';

	$mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
	$mqtt->connect();
	$mqtt->subscribe($p['topic'], function ($topic, $message, $retained, $matchedWildcards) {
		if($topic == 'x'){
	    echo sprintf("Received message on topic [%s]: %s\n", $topic, $message);
		}
		else{
	    echo sprintf("Received message on topic [%s]: %s\n", $topic, $message);
		}
		$data['topic'] = $topic;
		$data['message'] = $message;

		if($topic == 'x' | $topic == 'y'){
			Db::update('position.data', ['id' => 1, $topic -> $message]);
		}
	}, 0);
	$mqtt->loop(true);
	$mqtt->disconnect();

	die(json_encode($data));
?>