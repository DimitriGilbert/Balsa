<?php

class HelloWorld
{
  private $xml;
  private $xpath;

  function __construct()
  {
    global $path;
    $this->xml=new DOMDocument;
    $this->xml->load($path.'data/HelloWorld/hl.xml');
    $this->xpath=new DOMXpath($this->xml);
  }

  function random()
  {
    $hellos=$this->xml->getElementsByTagName('hello');
    $c_h=count($hellos);
    echo $hellos->item(rand(0,$c_h));
  }

  function form_add_hello()
  {

  }

  function add_hello($hello)
  {

  }
}

?>
