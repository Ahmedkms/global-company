<?php 

function required($input){
    if(empty($input)){
        return true;
    }else{
        return false;
    }
}

function minimumchars($input, $lentgh){
    if(strlen($input)< $lentgh){
        return true;
    }elseif (strlen($input)> $lentgh){
        return false;
    }
}

function maximumchars($input, $lentgh){
    if(strlen($input) >$lentgh){
        return true;
    }elseif(strlen($input)<$lentgh){
        return false;
    }
}

function emailvalidate($input){
   if(filter_var($input , FILTER_VALIDATE_EMAIL)){
    return false;
   }return true;
}

