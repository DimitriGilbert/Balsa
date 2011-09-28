<?php
global $path;
if(isset($_GET['install']))
{

}
else
{
  include_once($path.'admin/plugin/HelloWorld/fonction/HelloWorld.php');
  $hl=new HelloWorld();
  $hl->random();
  $hl->form_add_hello();
}

?>
