<?php
namespace ikatyo\ServerNotify;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
	function onEnable(){
		$this->sendData("https://notify-api.line.me/api/notify", array("message" => "ServerRun"), array("Authorization: Bearer (LINENotifyのAPIコード)"));
	}

	public function onDisable(){
		$this->sendData("https://notify-api.line.me/api/notify", array("message" => "ServerShutdown"), array("Authorization: Bearer (LINENotifyのAPIコード)"));
	}

	public function sendData($url, $data, $header = ""){
    $curl = curl_init($url);
	curl_setopt($curl,CURLOPT_POST, TRUE);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl,CURLOPT_POSTFIELDS, $data);
	curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl,CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_TIMEOUT, 5);
	curl_setopt($curl,CURLOPT_USERAGENT,"PMMP");
	return curl_exec($curl);
	}
}