<?php
global $base_url,$path,$js_base_url,$js_files;


//definition : base_url

$js_base_url='var base_url="'.$base_url.'";';

//end_of definition 

$js_files=array();
//defintion : registered_js_files

array_push($js_files,$path.'media/js/jsinterface.js');
array_push($js_files,$path.'media/js/main.js');

//end_of definition

//definition : minify

$minify_js=false;

//end_of
?>
