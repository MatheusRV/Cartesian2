<?php
$data=array();

$thread = new class extends Thread {
	public function run() {
		echo "Hello World\n";
	}
};

$thread->start() && $thread->join();

die(json_encode($data))
?>