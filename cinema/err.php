<?php
class Err {
	static function logDbError($code, $text, $sql) {
		if (!file_exists('logs')) {
			mkdir('logs', 0755);
		}

		$f = fopen('logs/db_error.txt', 'a');
		$str = date('d m y h:i:s', time()) . "\t" . $code . "\t" . $text . "\t" . $sql . "\r\n";
		fwrite($f, $str);
		fclose($f);
	}
}