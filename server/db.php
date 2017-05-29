<?php
final class model_DB {
    private static $_instance;
    public $_connect;


    private function __construct(){
        $this->_connect = mysqli_connect("localhost", "root", "", "cinema");
        mysqli_set_charset($this->_connect, "utf8");
    }
    static function connect(){
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    static function getValue($query) {
        $obj = self::connect();
        $obj = $obj->_connect;
        if(isset($obj)){
        
            $info = mysqli_query($obj, $query);
            if(mysqli_num_rows($info)) {
                $data = mysqli_fetch_array($info);

                return $data[0];
            } else {
                return null;
            }
        } else {
            return false;
        }
    }
    static function getRow($query){
        $obj = self::connect();
        $obj = $obj->_connect;
        if(isset($obj)){
            $wtf = mysqli_query($obj, $query);
            if($code = mysqli_errno($obj)){
                $text = mysqli_error($obj);
                Err::logDbError($code, $text, $wtf);
            } else {
                if(mysqli_num_rows($wtf)) {
                    return mysqli_fetch_assoc($wtf);
                } else {
                    return null;
                }
            }
        } else {
            return false;
        }
    }
    static function setValue($query){
        $obj = self::connect();
        $obj = $obj->_connect;
        if(isset($obj)){
            $wtf = mysqli_query($obj, $query);
            if($code = mysqli_errno($obj)){
                $text = mysqli_error($obj);
                Err::logDbError($code, $text, $wtf);
            } else {
                return mysqli_insert_id($obj);
            }
        } else {
            return false;
        }
    }
    static function removeValue($query){
        $obj = self::connect();
        $obj = $obj->_connect;
        if(isset($obj)){
            $wtf = mysqli_query($obj, $query);
            if($code = mysqli_errno($obj)){
                $text = mysqli_error($obj);
                Err::logDbError($code, $text, $wtf);
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
    static function getArray($query){
        $obj = self::connect();
        $obj = $obj->_connect;
        if(isset($obj)){
            $wtf = mysqli_query($obj, $query);
            if($code = mysqli_errno($obj)){
                $text = mysqli_error($obj);
                Err::logDbError($code, $text, $wtf);
            } else {
                $dataArray = mysqli_fetch_all($wtf, MYSQLI_ASSOC);
                return $dataArray;
            }
        } else {
            return false;
        }
    }
    static function updateValue($query){
        $obj = self::connect();
        $obj = $obj->_connect;
        if(isset($obj)){
            $wtf = mysqli_query($obj, $query);
            if($code = mysqli_errno($obj)){
                $text = mysqli_error($obj);
                Err::logDbError($code, $text, $wtf);
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
    static function escape($str){
        $obj = self::connect();
        $obj = $obj->_connect;
        return $obj ? mysqli_real_escape_string($obj, $str) : false;
    }
    static function getHash($size) {
        $str = "abcdefghijklmnopqrstuvwxyz0123456789";
        $hash = "";
        for ($i = 0; $i < $size; $i++) {
            $hash.= $str[rand(0, 35)];
        }
        return $hash;
    }
    private function __clone(){}
    private function __sleep(){}
    private function __wakeup(){}
}