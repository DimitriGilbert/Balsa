<?php

  inclure_fonction('HelloWorld');
  $h=new HelloWorld();
if($_GET['action']=='hello')
{
  $h->random();
}
elseif($_GET['action']=='add')
{
  $h->add($_GET['hello'];
}
?>