<?php
class Route {
	// белый список допустимых запросов
	public static $patterns = array(
		"controller_main" => [
			"#^" . SUBSERVER . "$#",
			"#^" . SUBSERVER . "(main)/$#",
			"#^" . SUBSERVER . "cinema/$#",
			"#^" . SUBSERVER . "cinema/(hall)/([0-9]+)/$#",
			"#^" . SUBSERVER . "cinema/(hall-info)/([0-9]+)/$#",
		],
        "controller_films" => [
            "#^" . SUBSERVER . "films/$#",
            "#^" . SUBSERVER . "films/(info)/([0-9]+)/$#",
			"#^" . SUBSERVER . "films/(buy)/([0-9]+)/$#",
			"#^" . SUBSERVER . "films/(reserved-place)/$#",
			"#^" . SUBSERVER . "films/(buy-ticket)/$#",
        ],
		"error" => [
			"#^.*$#"
		]
	);
	public function start($url) {
		foreach(self::$patterns as $class => $list){
			foreach ($list as $pattern) {
				if (preg_match($pattern, $url, $info)){
					$method = isset($info[1]) && $info[1] !== "" ? htmlspecialchars($info[1]) : "main";
					$id = isset ($info[2]) ? (int)$info[2] : 0;
					break 2;
				}
			}
		}
		$path = str_replace("_", "/", $class) . ".php";
		if ($class != "error" AND file_exists($path)) {
			include_once $path;
			// если есть информация для метода
			if(isset($id)) {
				$new = new $class();
				$new->main($method, $id);
			} else {
				$new = new $class();
				$new->main($method);

			}
		} else {
			die("ERRORRRR");
		}

	}
}
