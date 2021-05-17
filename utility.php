<?php
    require 'globals.php';
    //Utility Functions
    function debug($var){
        var_dump($var);
        die();
    }
    function json($arr){
        header('Content-Type: application/json');
        echo json_encode($arr);
        die();
    }
    function pathTo($string){
        $string = ltrim($string, "/");
        $root = rtrim(APP_ROOT, "/");
        return $root.'/'.$string;
    }
    function url($string){
        $string = ltrim($string, "/");
        $root = rtrim(APP_URL, "/");
        return $root.'/'.$string;
    }
    function generateRandomString($length = 10) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    function sanitizeInput($string){
        //Retorna a string sem caracteres HTML, com as single quotes convertidas para double e todos os backslashs são duplicados para evitar erros internos
        if(gettype($string) == 'array' or gettype($string) == 'object'){
            $sanitizedArr = sanitizeInputArr($string);
            return $sanitizedArr;
        };
        $result = strip_tags($string);
        $result = preg_replace('/\\\\/', '\\\\\\', $result);
        $result = str_replace('\'','"',$result);
        return $result;
    }

    function sanitizeInputArr($arr){
        $sanitizedArr = array();
        foreach ($arr as $key => $value) {
            $sanitizedArr[$key] = sanitizeInput($value);
        }
        return $sanitizedArr;
    }

    function sanitizeOutput($string){
        if(gettype($string) == 'array'){
            $sanitizedArr = sanitizeOutputArray($string);
            return $sanitizedArr;
        };
        $string = strip_tags($string);
        return str_replace('\\\\', '\\', $string);
    }
    function sanitizeOutputArray($arr){
        $sanitizedArr = array();
        foreach ($arr as $key => $value) {
            $sanitizedArr[$key] = sanitizeOutput($value);
        }
        return $sanitizedArr;
    }
    function timeDiff($date1,$date2){
        $d1 = new DateTime($date1);
        $d2 = new DateTime($date2);
        return date_diff($d1,$d2);
    }

?>