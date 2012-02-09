<?php
global$path;
include_once($path.'admin/plugin/serv_req/fonction/serv_req.php');
function list_projects()
{
	global$path;
	$xml=new DOMDocument();
	$xml->load($path.'data/project_manager/projects.xml');
	$ps=$xml->getElementsByTagName('project');
	$display=
	'
		<div class="projects_cont">
	';
	foreach($ps as $p)
	{
		$display.=
		'
			<div class="project" id="project_'.$p->getAttribute('name').'" onclick="browse_project(\''.$p->getAttribute('name').'\');">
				'.$p->getAttribute('name').'
			</div>
		';
	}
	$display.=
	'
		</div>
	';
	return $display;
}

function create_project_xml($path_,$name)
{
	global$path;
	include_once($path.'admin/plugin/explorer/explorer.php');
	$e=new explorer();
	$new_p=new DOMDocument();
	$new_p->loadXML('<project name="'.$name.'" path="'.$path_.'" origin="'.$path_.'" time="'.time().'">'.$e->dir_xml($path_,true).'</project>');
	file_put_contents($path.'data/project_manager/projects/'.$name.'.xml',$new_p->saveXML()) ;
	return $new_p->saveXML();
}

function reload_project($name)
{
	global$path;	
	$xml=new DOMDocument();
	$xml->load($path.'data/project_manager/projects.xml');
	$xpath=new DOMXPath($xml);
	$p=$xpath->query('//projects/project[@name="'.$name.'"]')->item(0);
	return create_project_xml($p->getAttribute('path'),$name);
}

function add_project($path_,$name)
{	
	global$path;
	$xml=new DOMDocument();
	$xml->load($path.'data/project_manager/projects.xml');
	
	create_project_xml($path_,$name);
	
	$new_p=$xml->createElement('project');
	$new_p->setAttribute('name',$name);
	$new_p->setAttribute('path',$path_);
	$new_p=$xml->getElementsByTagName('projects')->item(0)->appendChild($new_p);
	$xml->save($path.'data/project_manager/projects.xml');
}

function form_add_project()
{
	$display=
	'
	<div id="new_project">
		<div id="form_new_project">
			<div>
				<label for="name_new_project">Name</label>
				<input type="text" id="name_new_project" />
			</div>
			<div>
				<label for="path_new_project">Path</label>
				<input type="text" id="path_new_project" />
			</div>
			<div>
				<input type="button" onclick="add_new_project();" value="Add!!" />
			</div>
		</div>
	</div>
	';
	
	return $display;
}

function ommitted($str)
{
	global $path;
	$ommitted=file($path.'data/project_manager/ommit');
	$return=false;
	foreach($ommitted as $o)
	{
		$o=rtrim($o,"\n");
		if(preg_match('#'.$o.'#',$str))
		{
			$return=true;
		}
	}
	return $return;
}

function pack_update($p_name,$pu_name='')
{
	global$path;
	
	if($pu_name=='')
	{
		$pu_name=$p_name;
	}
	
	$u_time=time();
	$display=
	'
	Preparing update package of '.$p_name.' for '.$pu_name.'
	Timestamp of the update : '.$u_time.'
	';
	
	$origin=new DOMDocument();
	$origin->load($path.'data/project_manager/projects/'.$p_name.'.xml');
	$origin_path=$origin->documentElement->getAttribute('path');
	if(substr($origin_path,-1)!='/')
	{
		$origin_path.='/';
	}
	
	$display.=
	'
	origin_path : '.$origin_path.'
	';
	
	$update=new DOMDocument();
	$update->loadXML('<update time="'.$u_time.'" origin="'.$origin_path.'"></update>');
	
	$files=$origin->getElementsByTagName('file');
	$fp='';
	$nmd5='';
	$md5='';
	$n_name='';
	
	$data_dir=$path.'data/project_manager/update/'.$pu_name.'_'.time().'/';
	if(!is_dir($data_dir))
	{
		mkdir($data_dir);
	}
	
	$x=0;
	$added='';
	foreach($files as $f)
	{
		$fp=$f->getAttribute('path');
		if(is_file($fp))
		{
			$nmd5=md5_file($fp);
			$md5=$f->childNodes->item(0)->nodeValue;
			if($nmd5!=$md5)
			{
				if(ommitted($fp)!=true)
				{
					$f=$update->importNode($f,true);
					$update->documentElement->appendChild($f);
					$n_name=preg_replace(array('#'.$origin_path.'#','#/#'),array('','--'),$fp);
					copy($fp,$data_dir.$n_name);
					$added.=
					'
					'.$fp;
					$x++;
				}				
			}
		}
		else
		{
			$del=$update->createElement('delete');
			$del->setAttribute('path',$fp);
			$update->documentElement->appendChild($del);
		}
	}
	
	$display.=
	'
	File added : '.$x.$added;
	
	if($x>0)
	{
		$update->save($data_dir.'update.xml');
	
		compress_dir($pu_name.'_'.$u_time,$data_dir.'..','update_'.$u_time);
		$display.='
	update_'.$u_time.'.tar.gz created !';
#		rmdir_r($data_dir);
		create_project_xml($origin_path,$p_name);
		$updates_=new DOMDocument();
		$updates_->load($path.'data/project_manager/updates.xml');
		$up_=$updates_->createElement('update');
		$up_->setAttribute('name',$p_name);
		$up_->setAttribute('for',$pu_name);
		$up_->setAttribute('time',$u_time);
		$up_->setAttribute('file','update_'.$u_time.'.tar.gz');
		$updates_->documentElement->appendChild($up_);
		$updates_->save($path.'data/project_manager/updates.xml');
		
		$display.='
	Removing temp files...
	Update packed !';
	}
	else
	{
		$display.='
		No changes detected, No update Packed ;).
		';
	}
	
	return $display;
	
}

function check_file_md5($f,$file_path)
{
	$nmd5=md5_file($file_path);
	$md5=$f->childNodes->item(0)->nodeValue;
	if($nmd5!=$md5)
	{
		return false;
	}
	else
	{
		return true;
	}
}

function unpack_update($update_pack,$update_n,$update_t,$unpack=false)
{
	global $path;
	uncompress_dir($update_pack,$path.'data/project_manager/to_apply/');
	$update_dir=$path.'data/project_manager/to_apply/'.$update_n.'_'.$update_t;
	if(substr($update_dir,-1)!='/')
	{
		$update_dir.='/';
	}
	$update_xml=new DOMDocument();
	$update_xml->load($update_dir.'update.xml');
	$u_files=$update_xml->getElementsByTagName('file');
	$update_path=$update_xml->documentElement->getAttribute('origin');
	if(substr($update_path,-1)!='/')
	{
		$update_path.='/';
	}
	
	$origin=new DOMDocument();
	$origin->load($path.'data/project_manager/projects/'.$update_n.'.xml');
	$origin_path=$origin->documentElement->getAttribute('path');
	if(substr($origin_path,-1)!='/')
	{
		$origin_path.='/';
	}
	
	$display='';
	$fp='';
	$nmd5='';
	$md5='';
	$n_name='';
	$copies=array("from"=>array(),'to'=>array());
	
	foreach($u_files as $f)
	{
		$fp=$f->getAttribute('path');
		$fp=preg_replace('#'.$update_path.'#',$origin_path,$fp);
		if(is_file($fp))
		{
			$display.='
file : '.$fp;
			if(check_file_md5($f,$fp)!=true)
			{
				$display.=' /!\CONFLICT/!\ md5 print of your file dont match the file from update repository...';
				$unpack=false;
			}
			else
			{
				$n_name=preg_replace(array('#'.$origin_path.'#','#/#'),array('','--'),$fp);
				$display.=' ok_for_update';
				array_push($copies['from'],$update_dir.$n_name);
				array_push($copies['to'],$fp);
			}
		}
	}
	
	if($unpack==true)
	{
		copies($copies);
#		rmdir_r($update_dir);
		create_project_xml($origin_path,$update_n);
		$repos=new DOMDocument();
		$repos->load($path.'data/project_manager/repos.xml');
		$xpath=new DOMXPath($repos);
		$res=$xpath->query('//repos/repo[@project="'.$update_n.'"]')->item(0);
		$res->setAttribute('last',$update_t);
		$repos->save($path.'data/project_manager/repos.xml');
		return true;
	}
	
	return $display;
}

function repack_update($for,$update_t,$comp_only=true)
{
	global $path;
	$update_dir=$path.'data/project_manager/to_apply/'.$for.'_'.$update_t;
	if(substr($update_dir,-1)!='/')
	{
		$update_dir.='/';
	}
	if($comp_only==true)
	{
		compress_dir($for.'_'.$update_t,$update_dir.'..','update_'.$update_t);
	}
	else
	{
		$update_xml=new DOMDocument();
		$update_xml->load($update_dir.'update.xml');
		$u_files=$update_xml->getElementsByTagName('file');
		$update_path=$update_xml->documentElement->getAttribute('origin');
		if(substr($update_path,-1)!='/')
		{
			$update_path.='/';
		}
	
		$origin=new DOMDocument();
		$origin->load($path.'data/project_manager/projects/'.$update_n.'.xml');
		$origin_path=$origin->documentElement->getAttribute('path');
		if(substr($origin_path,-1)!='/')
		{
			$origin_path.='/';
		}
	
		$display='';
		$fp='';
	
		$n_name='';
	
		foreach($u_files as $f)
		{
			$fp=$f->getAttribute('path');
			if(is_file($fp))
			{
				$display.='
	file : '.$fp;
				if(check_file_md5($f,$fp)!=true)
				{
					$display.=' /!\CONFLICT/!\ md5 print of your file dont match the file from update repository...';
					$unpack=false;
				}
				else
				{
					$n_name=preg_replace(array('#'.$origin_path.'#','#/#'),array('','--'),$fp);
					$display.=' ok_for_update';
					array_push($copies['from'],$update_dir.$n_name);
					array_push($copies['to'],$fp);
				}
			}
		}
	
		if($unpack==true)
		{
			copies($copies);
	#		rmdir_r($update_dir);
			create_project_xml($origin_path,$update_n);
			$repos=new DOMDocument();
			$repos->load($path.'data/project_manager/repos.xml');
			$xpath=new DOMXPath($repos);
			$res=$xpath->query('//repos/repo[@project="'.$update_n.'"]')->item(0);
			$res->setAttribute('last',$update_t);
			$repos->save($path.'data/project_manager/repos.xml');
			return true;
		}
	
		return $display;
	}
	
}

function valid_token($tok)
{
	return true;
}

//repository

function has_up($project,$for,$last)
{
	global $path;
	$updates=new DOMDocument();
	$updates->load($path.'data/project_manager/updates.xml');
	$xpath=new DOMXPath($updates);
	$req='//updates/update[@name="'.$project.'" and @for="'.$for.'" and @time>'.$last.']';
	$res=$xpath->query($req);
	if(count($res)>0)
	{
		return $res->item(0);
	}
	else
	{
		return false;
	}	
}

function serv_command($project,$for,$last)
{
	global $base_url;
	$up=has_up($project,$for,$last);
	if($up!=false)
	{
		$d_url=$base_url.'front_pm.php?tkn=123&ask=serv_up&f='.$up->getAttribute('file');
		$r_url=$base_url.'front_pm.php?tkn=123&ask=report_up&f='.$up->getAttribute('file');
		$return='UPDATE AVAILABLE
'.$up->getAttribute('file').'
'.$d_url.'
'.$r_url.'
'.$up->getAttribute('for').'
'.$up->getAttribute('time');
		return $return;
	}
	else
	{
		return 'NO COMMAND';
	}
}

function serv_up($update_name)
{
	global $path;
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename='.$update_name);
	header('Content-Transfer-Encoding: binary');
	echo file_get_contents($path.'data/project_manager/update/'.$update_name);
}

function check_up_md5($update_name,$md5)
{
	global $path;	
	if(md5_file($path.'data/project_manager/update/'.$update_name)==$md5)
	{
		return true;
	}
	else
	{
		return false;
	}
}


//client
function check_repos_command()
{	
	global $path;
	$repos=new DOMDocument();
	$repos->load($path.'data/project_manager/repos.xml');
	$repo=$repos->getElementsByTagName('repo');
	
	$url='';
	$cmd=array();
	$c='';
	foreach($repo as $r)
	{
		$url=$r->getAttribute('url').'?tkn='.$r->getAttribute('url').'&ask=serv_command&p='.$r->getAttribute('from').'&f='.$r->getAttribute('project').'&l='.$r->getAttribute('last');
#		plop('url : '.$url);
		$c=check_command($url);
#		plop('commande : '.$c);
		if($c!=false)
		{
			while(is_string($c))
			{
				$c=explode("\n",$c);
				$c=remote_command($c);
			}
		}
		
	}
}

function check_command($url)
{
	$sr=new serv_req($url,'get','','',true);
	$sr=explode("\n",$sr->get('rep'));
	print_pre($sr);
	if($sr[0]!='NO COMMAND')
	{
		return remote_command($sr);
	}
	else
	{
		return false;
	}
}

function remote_command($resp)
{
	global $path;
	$com=$resp[0];
#	plop('remote command : '.$com."\n");
	switch($com)
	{
		case'UPDATE AVAILABLE':
		//resp 1 : update_file name
		//resp 2 : dowload url
		//resp 3 : response url
		//resp 4 : project_name
		//resp 5 : update_time
			$sr=new serv_req($resp[2],'get','',$path.'data/project_manager/update/'.$resp[1],true);
			
			$return='APPLY UPDATE
'.$resp[1].'
'.$resp[2].'
'.$resp[3].'
'.$resp[4].'
'.$resp[5];
			break;
		case'APPLY UPDATE':
		//resp 1 : update_file name
		//resp 2 : dowload url
		//resp 3 : response url
		//resp 4 : project_name
		//resp 5 : update_time
			$return=unpack_update($path.'data/project_manager/update/'.$resp[1],$resp[4],$resp[5],true);
			print_pre($return);
			if($return!=true)
			{
				$sr=new serv_req($resp[3].'&report='.$return,'get','','',true);
				$return=false;
			}
			else
			{
				$sr=new serv_req($resp[3].'&report=ok','get','','',true);
			}
			break;
		default:
			$return=false;
	}
	
	return $return;
}

?>
