function init_packager()
{
  var file_listing=jsi.div(['id','class'],['file_listing','file_listing']);
  return document.getElementById('').appendChild(file_listing);
}

function editFile(path)
{
  var ed=jsi.div(['id','class','onclick'],[path,'list_files','deleteFile("'+path+'")'],path);
  return document.getElementById('file_listing').appendChild(ed);
}

function deleteFile(path)
{
  document.getElementById('file_listing').removeChild(path);
}

function sortFile()
{

}

function run_packager()
{

}

