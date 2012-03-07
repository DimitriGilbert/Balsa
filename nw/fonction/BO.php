<?php

function home_form()
{
	global$base_url,$path;
	$display=
	'
	<form method="post" action="'.$base_url.'?page=BO&w=home&ask=insert">
	<div>
		<label for="home_content">Content of your home page : </label>
		<textarea id="home_content" name="home_content">'.file_get_contents($path.'page/home.php').'</textarea>
		<script type="text/javascript">document.getElementsByTagName("body")[0].setAttribute("onload",document.getElementsByTagName("body")[0].getAttribute("onload")+";init_tiny_mce(\"home_content\")")</script>
	</div>
	<div>
		<input type="submit" value="valid !">
	</div>
	</form>
	';
	return $display;
}

function insert_home()
{
	global $path;
	return file_put_contents($path.'page/home.php',$_POST['home_content']);
}

?>
