<?php

use App\Models\Option;

function get_options($name){
    if(Option::where('name',$name)->count()){
        return Option::where('name',$name)->first()->value;
    }else{
        return null;
    }
}