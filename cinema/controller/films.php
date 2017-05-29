<?php
class controller_films extends controller_base {

    static function main($method, $id){
        switch($method) {
            case "info":
                self::filmInfo($id);
                break;
            case "buy":
                self::seanceInfo($id);
                break;
            case "reserved-place":
                self::reserve();
                break;
            case "buy-ticket":
                self::buy();
                break;
            default:
                self::index();
        }
        self::showPage();

    }

    private static function index() {
        if(!empty($_POST)){
            $soapClient = new SoapClient("http://localhost/exam-service/server/cinema.wsdl");

            $seances  = json_decode($soapClient->getSeances($_POST['day']));
            //print_r($seances);
            self::$main = ["index/films" => ["seances" => $seances]];
        } else {
            $soapClient = new SoapClient("http://localhost/exam-service/server/cinema.wsdl");
            $seances  = json_decode($soapClient->getSeances(date("Y-m-d",time())));
            self::$title = "Фильмы";
            self::$main = ["index/films" => ["seances" => $seances, "today" => 1]];
        }
    }
    private static function filmInfo($id) {
        if(!empty($id)){
            $soapClient = new SoapClient("http://localhost/exam-service/server/cinema.wsdl");
            $info  = json_decode($soapClient->getFilmInfo($id));
            self::$main = ["index/film" => ["info" => $info]];
        } else {
            header("Location:".MAIN."films/");
        }
    }
    private static function seanceInfo($idSeance){
        if(!empty($idSeance)){
            $soapClient = new SoapClient("http://localhost/exam-service/server/cinema.wsdl");
            $info = json_decode($soapClient->getReserved($idSeance));
            $places = json_decode($soapClient->getPlace($info[0]->ID_hall));
            self::$main = ["index/seance" => ["info" => $info, "places" => $places]];
        } else {
            header("Location:".MAIN."films/");
        }
    }

    private static function reserve(){
        if(IS_AJAX){
            if(isset($_POST["id_seance"])){
                $idSeance = $_POST["id_seance"];
                unset($_POST["id_seance"]);
                $places = array();
                $need = array("row"=>0,"number"=>0);
                foreach($_POST as $key=>$place){
                    $tmp = explode("/",$place);
                    $need["row"] = $tmp [0];
                    $need["number"] = $tmp [1];
                    $places[$key] = $need;
                }
                $soapClient = new SoapClient("http://localhost/exam-service/server/cinema.wsdl");
                $info = json_decode($soapClient->reservedPlace($idSeance, $places));
                $answer = "Места успешно забронированы";
            } else {
                $answer = $_POST;
            }
            echo json_encode($answer);
        }else {

           header("Location:".MAIN."films/");
        }

    }
    private static function buy(){
        if(isset($_POST["id_seance"])){
            $idSeance = $_POST["id_seance"];
            unset($_POST["id_seance"]);
            $places = array();
            $need = array("row"=>0,"number"=>0);
            foreach($_POST as $place){
                $tmp = explode("/",$place);
                $need["row"] = $tmp [0];
                $need["number"] = $tmp [1];
                $places[] = $need;
            }
            $soapClient = new SoapClient("http://localhost/exam-service/server/cinema.wsdl");
            $info = json_decode($soapClient->buyTicket($idSeance, $places));
            header("Location:".MAIN."films/buy/".$idSeance."/");
        }
    }
}