function add_hello(hello)
{
  if(hello!='')
  {
    var hrep=serv_req('goulot.php?page=HelloWorld&action=add&hello='+hello,'GET','','');
  }
}

function hello()
{
  var hrep= serv_req('goulot.php?page=HelloWorld&action=hello','GET','','');
  alert(hrep);
}

function hello_div(id)
{
  var hrep= serv_req('goulot.php?page=HelloWorld&action=hello','GET','','');
  document.getElemntById(id).innerHTML=hrep;
}