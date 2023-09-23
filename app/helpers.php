<?php

//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

function updateIfChanged($course, $type, $value)
{
    if($course->$type != null && $course->$type != $value){
        return $value;
    }else{
        return $course->$type;
    }
}
