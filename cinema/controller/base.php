<?php
abstract class controller_base {
	protected static $user;
	protected static $title;
	protected static $main;
	protected static $css = array(
			CSS . "font-awesome.css",
            CSS . "bootstrap.min.css",
            CSS . "style.css",
		);
	protected static $js = array(
			JS . "jquery-1.11.0.min.js",
			JS . "script.js"
		);
	//protected static $ApiUrl = "http://localhost/services/exam/server/cinema.wsdl";
	protected function showPage(){
		if(!IS_AJAX){
			self::view("main/head", ["css" => self::$css, "title" => self::$title]);
			self::view("main/header", ["user" => self::$user, "title" => self::$title]);
			foreach(self::$main as $page => $data) {
				self::view($page, $data);
			}
			self::view("main/footer");
			self::view("main/scripts", ["js" => self::$js]);
		}
	}
	abstract static function main($method, $id);
	protected function view($page, $data = []){
		extract($data);
		if (preg_match("/([a-z_\/0-9]*)\.?\w*/i", $page, $result)){
			include_once VIEW . $result[1] . ".html";
		}
	}
    
}
?>