<?php

function sanitizeInput($input){
    return trim(htmlspecialchars(htmlentities($input)));
}

function redirect($path){
    header ("location: $path");
    die;
}

function minimumslary($input,$salary){
    if($input<$salary){
        return true;
    }return false;
}
function maxSalary($input,$salary){
    if($input >$salary ){
        return true;
    }return false;
}