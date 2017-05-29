<?php
include_once "db.php";
class cinema{
	function getCinemas(){
		$sql = "SELECT * FROM `cinema`;";
		return json_encode(model_DB::getArray($sql));
	}
	function getHalls($idCinema){
		$sql = "SELECT * FROM `hall` WHERE `ID_cinema` = ". $idCinema .";";
		return json_encode(model_DB::getArray($sql));
	}
	function getPlace($idHall){
		$sql = "SELECT * FROM `place` WHERE `ID_hall` = ". $idHall .";";
		return json_encode(model_DB::getArray($sql));
	}
	// возвращает инфу о сеансе с занятыми и свободными местами `hall`.`name` AS `hall_name`,
	function getReserved($idSeance){
		$sql = "SELECT 
					`seance`.`ID` AS `id_seance`,
					`ID_hall`,
 					`datetime`,
 					`movies`.`name` AS `movie_name`,
 					`ticket`.`row` AS `place_row`,
 					`ticket`.`number` AS `place_number`,
 					`status`.`ID` AS `ticket_status`		
 				FROM `seance` 
 				LEFT JOIN `ticket` ON `seance`.`ID` = `ticket`.`id_seance`
 				LEFT JOIN `status` ON `ticket`.`ID_status` = `status`.`ID`
 				LEFT JOIN `movies` ON `seance`.`ID_movie` = `movies`.`ID`
 				WHERE `seance`.`ID` = ". $idSeance .";";
		return json_encode(model_DB::getArray($sql));
	}
	function getFilmInfo($id){
		$sql = "SELECT 
					`desc`,
					`movies`.`name` AS `movie_name`, 
					`census`,
					`genre`.`name` AS `genre_name`,
					CONCAT(`directed`.`first_name`, ' ', `directed`.`last_name`) AS `directed_name`
				FROM `movies`
				LEFT JOIN `genre` ON `movies`.`ID_genre` = `genre`.`ID`
				LEFT JOIN `directed` ON `movies`.`ID_directed` = `directed`.`ID`

				WHERE `movies`.`ID` = ". $id .";";
		$filmInfo = model_DB::getRow($sql);
		$sql = "SELECT CONCAT(`actor`.`first_name`, ' ', `actor`.`last_name`) AS `actor_name` 
				FROM `actor_list` 
				LEFT JOIN `actor` ON `actor_list`.`ID_actor`=`actor`.`ID` 
				WHERE `ID_movis` = ". $id .";";
		$filmInfo["actors"] = model_DB::getArray($sql);

		return json_encode($filmInfo);
	}
	function getSeances($day){
		$sql = "SELECT 
					`movies`.`ID` AS `ID_movie`,
					`seance`.`ID` AS `ID_seance`,
					`price`,
					`seance`.`datetime` AS `time`, 
					`movies`.`name` AS `movie_name`, 
					`census`				FROM `seance` 
				LEFT JOIN `movies` ON `seance`.`ID_movie` = `movies`.`ID`
				WHERE `datetime` REGEXP '^". $day ."'
				ORDER BY `time`;";
		return json_encode(model_DB::getArray($sql));
	}
	// принимает Id сеанса и массив мест, содержащий ряд и место
	function reservedPlace($idSeance, $places){
		$sql = "SELECT * FROM `ticket` 
				WHERE ";
		$orCheck = 0;
		foreach($places as $place){
			if($orCheck == 0) {
				$sql .= " `ID_seance` =" . (int)$idSeance . " AND `row` = " . $place['row'] . " AND `number` = " . $place['number'];
				$orCheck = 1;
			} else {
				$sql .= " OR ";
				$sql .= " `ID_seance` =" . (int)$idSeance . " AND `row` = " . $place['row'] . " AND `number` = " . $place['number'];
			}
		}
		$sql .= ";";
		if(empty(model_DB::getRow($sql))){
			$sql = "INSERT INTO `ticket` (`ID_seance`,`ID_status`, `row` , `number`) VALUES ";
			foreach($places as $place){
				$sql .= "(".(int)$idSeance . ",1," . $place['row'] . "," . $place['number'] ."),";

			}
			$sql = trim($sql,",").";";
			return model_DB::setValue($sql);
		} else {
			return false;
		}
	}
	function buyTicket($idSeance, $places){
		$sql = "SELECT * FROM `ticket` 
				WHERE ";
		$orCheck = 0;
		foreach($places as $place){
			if($orCheck == 0) {
				$sql .= " `ID_seance` =" . (int)$idSeance . " AND `row` = " . $place['row'] . " AND `number` = " . $place['number'];
				$orCheck = 1;
			} else {
				$sql .= " OR ";
				$sql .= " `ID_seance` =" . (int)$idSeance . " AND `row` = " . $place['row'] . " AND `number` = " . $place['number'];
			}
		}
		$sql .= ";";
		$ids = model_DB::getArray($sql);
		if(empty($ids)){
			$sql = "INSERT INTO `ticket` (`ID_seance`,`ID_status`, `row` , `number`) VALUES ";
			foreach($places as $place){
				$sql .= "(".(int)$idSeance . ",2," . $place['row'] . "," . $place['number'] ."),";

			}
			$sql = trim($sql,",").";";
			return model_DB::setValue($sql);
		} else {
			$sql = "UPDATE `ticket` SET `ID_status` = 2 WHERE `ID` IN (";
			foreach($ids as $id){
				$sql .= $id["ID"].",";

			}
			$sql = trim($sql,",").");";
			return model_DB::updateValue($sql);
		}
	}

}
ini_set("soap.wsdl_cache_enabled", "0");
$url = "http://localhost/exam-service/server/cinema.wsdl";
$server = new SoapServer($url);
$server->setClass("cinema");
$server->handle();
//$check = new cinema;
//print_r($check->buyTicket(34, array(array("number"=>8,"row"=>1), array("number"=>9,"row"=>1))));

?>