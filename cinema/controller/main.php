<?php
class controller_main extends controller_base {

	static function main($method, $id){
		switch($method) {
			case "cinema":
				self::index();
				break;
			case "hall":
				self::hall($id);
				break;
			case "hall-info":
				self::hallInfo($id);
				break;
			default:
				self::index();
		}
		self::showPage();
		
	}
	
	private static function index() {
		$soapClient = new SoapClient("http://localhost/exam-service/server/cinema.wsdl");
		$cinemaList  = json_decode($soapClient->getCinemas());
		self::$title = "Главная";
		self::$main = ["index/index" => [
			"cinemas" => $cinemaList
		]];
	}
	private static function hall($id) {
		$soapClient = new SoapClient("http://localhost/exam-service/server/cinema.wsdl");
		$hallInfo  = json_decode($soapClient->getHalls($id));
		self::$title = "Залы";
		self::$main = ["index/halls" => [
			"halls" => $hallInfo
		]];
	}
	private static function hallInfo($id) {
		$soapClient = new SoapClient("http://localhost/exam-service/server/cinema.wsdl");
		$info = json_decode($soapClient->getPlace($id));
		self::$title = "Зал";
		self::$main = ["index/hall" => [
			"info" => $info,
		]];
	}
}