<?php
	class Message{
		private string $t = "";
		private string $m = "";
		private bool $r = false;

		public function setMessage(string $topico, string $mensagem, bool $retencao){
			$this->t = $topico;
			$this->m = $mensagem;
			$this->r = $retencao;
		}

		public function log(){
			return 'Foi recebido a mensagem "'.$this->m.'" do tópico "'.$this->t.'".';
		}

		public function getTopic(){ return $this->t; }
		public function getMessage(){ return $this->m; }
		public function getRetain(){ return $this->r; }
	}
?>