<?php
/*
bdd_entity is a class to help you manipulate entities in a database
$mapped => array; associative, the field you want to interact with in you entity, field_name => field_value
$table => string; the name of the database table
$id_field => string ('id'); the name of the id field
$update => array; the fields that have been set to a value, for saving purposes
$BO_field => array; associative, same keys as $mapped, the value of the back office label for the field
$BO_type => array; associative, same keys as $mapped, the type of back office field (none, text, checkbox, radio, select, textarea, password, upload)
$BO_value => array; associative, same keys as $mapped + taken indexes, useful for some field types
	$this->BO_value['form_div_class'] default div class for the back_office
	$this->BO_value['form_label_class']= default label class for the back_office
	$this->BO_value['form_label_2_class']= default options' label class for the back_office
	$this->BO_value['form_input_class']= default input class for the back_office
	$this->BO_value['form_checkbox_class']= default checkbox class for the back_office
	$this->BO_value['form_radio_class']= default radio button class for the back_office
	$this->BO_value['form_select_class']= default select class for the back_office
	$this->BO_value['form_textarea_class']= default textarea class for the back_office
	
	$this->BO_value[mapped indexe]= array; for custom info/conf on the output
		['labels'] => array; labels of input radio
		['values'] => array; values of input radio
		['verified'] => string; TODO, if filled a second filed with a "_v" suffix wil be build, they'll have to be identical. The label will be the value of the varaible.
		
$BO_search => array; associative, same keys as $mapped, the type of search on back office field (eq, gt, lt, ge, le, ne, like, _like, like_, _like_)
$BO_check => array; associative, same keys as $mapped, the type of check on back office field,
	$this->BO_check[mapped indexe]= array; 
		$this->BO_check[mapped indexe]['empty'] = bool; if the field can be empty, default is true
		$this->BO_check[mapped indexe]['type'] = str; the type of check, default is none (none, id, str, int, float, mail)
*/
class bdd_entity
{
	public $mapped, $table, $id_field, $update, $BO_field, $BO_type, $BO_value, $BO_search, $BO_check, $BO_manage;
	/*
	set the values at initialisation,customize it like that when you extend the object : 
	function __construct($id='')
	{
		parent::__construct(array('your','entity','fileds'),'your_entity','entity_id_field');
		//your customisation...
		//for example :
		if($id!='')
		{
			$this->load($id);
		}
		//define de BO_field value for all fields, default is filed name 
		//define the BO_type value for all fields, default is text
		//define the BO_search value for all fields, default is equal (eq)
		//define the BO_check value for all fields, default is empty=true type=none
	}
	
	*/
	function __construct($map,$table,$id_field='id',$table_prefix='')
	{
		foreach($map as $m)
		{
			$this->mapped[$m]='';
			$this->BO_field[$m]=$m;
			$this->BO_type[$m]='text';
			$this->BO_search[$m]='eq';
			$this->BO_check[$m]['empty']=true;
			$this->BO_check[$m]['type']='none';
		}
		$this->BO_value['form_div_class']='form_div';
		$this->BO_value['form_label_class']='form_label';
		$this->BO_value['form_label_2_class']='form_label_2';
		$this->BO_value['form_input_class']='form_input';
		$this->BO_value['form_checkbox_class']='form_checkbox';
		$this->BO_value['form_radio_class']='form_radio';
		$this->BO_value['form_select_class']='form_select';
		$this->BO_value['form_textarea_class']='form_textarea';
		$this->table=$table_prefix.$table;
		$this->id_field=$id_field;
		$this->update=array();
		
		if(trace_mod()==true)
		{
			add_trace('entity creation;'.$table.';field : '.implode($map,"\t").';id : '.$id_field.';'.time());
		}
		
	}
	
	/*
	when print()
	*/
	function __toString()
	{
		echo $this->display();
	}
	
	/*
	load the entity entry that have $id as value for $this->id_field
	return false when nothing match
	*/
	function load($id='')
	{
		global $bdd,$path,$path_w,$base_url;
		if($id!='')
		{
			$req='select * from '.$this->table.' where '.$this->id_field.'="'.$id.'"';//
			$res=$bdd->query2($req);
			if($res!=false)
			{
				$res=$res->fetch();
				if($res!=false)
				{
					foreach($res as $k=>$r)
					{
						if(isset($this->mapped[$k]))
						{
							$this->mapped[$k]=$r;
						}
					}
					if(trace_mod()==true)
					{
						add_trace('entity load;'.$id.';'.time());
					}
					return true;
				}
				else
				{
					report_erreur2('1001',__FILE__,__LINE__,'entity_load, no matching id in '.$this->table.' for '.$id);
					return false;
				}
			}
			else
			{
				report_erreur2('1002',__FILE__,__LINE__,'entity_load, failed request '.$this->table.' '.$this->get($this->id_field));
				return false;
			}
		}
		else
		{
			report_erreur2('3001',__FILE__,__LINE__,'entity_load No id provided');
			return false;
		}
	}
	
	/*
	load from an associative array 
	*/
	function load_map($map)
	{
		foreach($map as $k=>$v)
		{
			if(trace_mod()==true)
			{
				add_trace('entity load_map;'.implode($map,"\t").';'.time());
			}
			if(in_array_keys($k,$this->mapped))
			{
				$this->set($k,$v);
			}
		}
	}
	/*
	TO DO 
	load from an xml file output by $this->save_xml()
	*/
	function load_xml($xml)
	{
		return false;
	}
	
	/*
	return the value of the associative array $this->mapped at index $k if it exists
	you can customize like that :
	function get($k)
	{
		$r=parent::get($k);
		if($r==false)
		{
			switch($k)
			{
				default:
					$r=false:
					break;
			}
		}
		return $r;
	} 
	*/
	function get($k,$report=true)
	{
		if(trace_mod()==true)
		{
			add_trace('entity get;'.$k.';'.time());
		}
		if(in_array_keys($k,$this->mapped))
		{
			return $this->mapped[$k];
		}
		else
		{
			if($report==true)
			{
				report_erreur2('3002',__FILE__,__LINE__,'entity_get unknown key '.$k);
			}
			return false;
		}
	}
	
#	/*
#	static version of get
#	*/
#	static function s_get($k,$id)
#	{
#		global $bdd;
#		$req='select '.$k.' from '.$this->table.' where '.$this->id_field.'="'.$id.'"';
#	}
	
	/*
	set the value of the associative array $this->mapped at index $k if it exists
	you can customize like that :
	function set($k,$v)
	{
		$r=parent::set($k,$v);
		if($r==false)
		{
			switch($k)
			{
				default:
					$r=false:
					break;
			}
		}
		return $r;
	} 
	*/
	function set($k,$v,$report=true)
	{
		if(!is_numeric($k))
		{
			if(trace_mod()==true)
			{
				add_trace('entity set;'.$k.' '.$v.';'.time());
			}
			if(in_array_keys($k,$this->mapped))
			{	
				if($this->BO_type[$k]='text' or $this->BO_type[$k]='textarea')
				{
					$v=addslashes($v);
				}		
				$this->mapped[$k]=$v;
				$this->update[$k]=$v;
				return true;
			}
			else
			{
				if($report==true)
				{
					report_erreur2('3003',__FILE__,__LINE__,'entity_set unknown key '.$k);
				}
				
				return false;
			}
		}		
	}
	
	/*
	save the current instance into the database
	erase previous datas for the id_field's value
	*/
	function save()
	{
		global $bdd,$path,$path_w,$base_url;
		if(count($this->update)>0)
		{
			$req='update '.$this->table.' set ';
			foreach($this->update as $k=>$v)
			{
				$req.='`'.$k.'`="'.$v.'",';
			}
			$req=substr($req,0,-1);
			$req.=' where '.$this->id_field.'="'.$this->mapped[$this->id_field].'"';
			$res=$bdd->query2($req);
			if($res)
			{
				if(trace_mod()==true)
				{
					add_trace('entity save;;'.time());
				}
				return true;
			}
			else
			{
				report_erreur2('1003',__FILE__,__LINE__,'entity_save failed save request');
				return false;
			}
		}
		else
		{
			return true;
		}
	}
	
	/*
	trznsform the current instance into a xml string
	*/
	function to_xml()
	{
		$xml='<'.$this->table.'>';
		foreach($this->mapped as $k=>$v)
		{
			$xml.='<'.$k.'>'.$v.'</'.$k.'>';
		}
		$xml.='</'.$this->table.'>';
		return $xml;
	}
	
	/*
	save the current instance into an xml file, with the path $file
	*/
	function save_xml($file)
	{
		return file_put_contents($file,'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.$this->to_xml());
	}
	
	/*
	trznsform the current instance into a xml string
	*/
	function to_csv($head=true)
	{
		if($head==true)
		{
			$csv=implode(array_keys($this->mapped),',')."\n";
		}
		else
		{
			$csv='';
		}
		
		$csv.='"'.implode($this->mapped,'","').'"
';		
		return $csv;
	}
	
	/*
	save the current instance into an xml file, with the path $file
	*/
	function save_csv($file)
	{
		return file_put_contents($file,'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.$this->to_csv());
	}
	
	/*
	insert the current instance into database
	if $map is given, it will overwrite $this->mapped previous data before inserting
	*/
	function insert($map='')
	{
		global $bdd;
		if($map=='')
		{
			$map=$this->mapped;
		}
		if(!in_array_keys($this->id_field,$map) or $map[$this->id_field]=='')
		{
			$map[$this->id_field]=$bdd->get_primkey();
		}
		$values=implode('","',$map);
		$keys=implode('`,`',array_keys($map));		
		$req='insert into '.$this->table.' (`'.$keys.'`) values ("'.$values.'")';
		$res=$bdd->query2($req);
		if($res)
		{
			if(trace_mod()==true)
			{
				add_trace('entity insert;'.time());
			}
#			return true;
			$this->mapped=$map;
			return $map[$this->id_field];
		}
		else
		{
			report_erreur2('1004',__FILE__,__LINE__,'entity_insert failed insert request');
			return false;
		}
	}
	
	/*
	return a simple display of the current instance.
	*/
	function display()
	{
		$display='';
		foreach($this->mapped as $k=>$v)
		{
			$display.='<div>'.$k.' : '.$v.'</div>';
		}
		return $display;
	}
	
	/*
	return an array with all entry
	$select => mixed; what you select
	$nb => int; the number of result wanted, default=0, 0 means no limit
	$page => int; the "page" of result
	*/
	
	function all($select='',$where='',$nb=0,$page=1)
	{
		global $bdd;
				
		if($select=='')
		{
			$select=$this->id_field;
		}
		elseif(is_array($select))
		{
			$select=implode($select,',');
		}
		
		$req='select '.$select.' from '.$this->table;
		
		if($where!='')
		{
			$req.=' where '.$where;
		}
		if($nb!=0)
		{
			$from=$nb*($page-1);
			$to=$nb*$page-1;
			$req.=' limit '.$from.','.$to;
		}
			
		return $bdd->query2($req)->fetchAll();
	}
	
	function menu($url)
	{
		global $bdd,$base_url;
		
		$link='';
		$info='';
		foreach($BO_manage['map'] as $l)
		{
			$link.='&'.$l.'='.$this->get($l);
			$link.=' '.$this->get($l);
		}
		
		$menu='<li id="menu_'.$this->get('id').'"><a id="menu_link_'.$this->get('id').'" href="'.$url.$link.'">'.$this->get('title').'</a></li>';
		
		return $menu;
		
	}
/*

"everything" for th back office following, work in progress ;D

*/	
	function manage($url,$nb=0,$page=0)
	{
		global $bdd;
		$req='select * from '.$this->table;
		if($this->BO_manage['order']!='')
		{
			$req.=' order by '.$this->BO_manage['order'];
			if($this->BO_manage['order_type']!='')
			{
				$req.=' '.$this->BO_manage['order_type'];
			}
		}
		if($this->BO_manage['group']!='')
		{
			$req.=' group by '.$this->BO_manage['group'];
		}
		$res=$bdd->query2($req)->fetchAll();
		
		return $this->link_result($res,$url,$this->BO_manage['map']);
	}
	
	/*
	build the display for back office purpose
	$ac => string; url for the form action
	$wrap => bool; if extending this function, please set it to false to avoid havin the <form> wrapping
	*/
	function back_office($ac='',$wrap=true)
	{
		$date_day=date('Y-m-d',time());
		if($wrap==true)
		{
			if(in_array('upload',$this->BO_type))
			{
				$form_up=' enctype="multipart/form-data"';
			}
			else
			{
				$form_up='';
			}
			$display=
			'
			<div class="formulaire">
			<form method="post" action="'.$ac.'"'.$form_up.'>
			';
		}
		else
		{
			$display='';
		}
		
		foreach($this->BO_type as $k => $v)
		{
			if($k!=$this->id_field)
			{
				switch($v)
				{
					case'text':
						$display.=$this->input_text($k);
						break;
					case'textarea':
						$display.=$this->textarea($k);
						break;
					case'select':
						$display.=$this->custom_select($k);
						break;
					case'checkbox':
						$display.=$this->checkboxed($k);
						break;
					case'radio':
						$display.=$this->radioboxed($k);
						break;
					case'password':
						$display.=$this->passworded($k);
						break;
					case'upload':
						$display.=$this->input_upload($k);
						break;
					case'none':
						break;
					default:
						$display.=$this->input_text($k);
						break;
				}
			}
		}
		if($wrap==true)
		{
			$display.=
			'
				<input type="button" id="valider" value="valider" onclick="if(check_'.$this->table.'_BO()==true){this.parentNode.submit();}"/>
				<script type="text/javascript">'.$this->js_checker(array_keys($this->mapped)).'</script>
			</form>
			</div>
			';
		}
		
		return $display;
	}
	
	/*
	insert the posted content of the back office into an entry in the database
	$met => string; the method of the form (post, get)
	$update => string; the id of the entry to update
	*/
	function insert_back_office($met='post',$update='')
	{
		if($met=='post')
		{
			$met=$_POST;
		}
		else
		{
			$met=$_GET;
		}
		foreach($this->BO_type as $k => $v)
		{
			switch($v)
			{
				case'text':
					if(isset($met['input_'.$k]))
					{
						$this->set($k,$met['input_'.$k]);
					}
					break;
				case'password':
					if(isset($met['input_'.$k]) and $met['input_'.$k]==$met['input_verification_'.$k])
					{
						
						$v=hash('sha512',$met['input_'.$k]);
						
						$this->set($k,$v);
					}
					
					break;
				case'textarea':
					if(isset($met['textarea_'.$k]))
					{
						$this->set($k,$met['textarea_'.$k]);
					}
					
					break;
				case'select':
					if($this->insert_custom_select($k,$met)!=false)
					{
						$this->set($k,$this->insert_custom_select($k,$met));
					}

					break;
				case'checkbox':
					if(isset($met['input_'.$k]))
					{
						$this->set($k,$met['input_'.$k]);
					}
					
					break;
				case'radio':
					if(isset($met['input_'.$k]))
					{
						$this->set($k,$met['input_'.$k]);
					}
					
					break;
				case'upload':
					if(isset($_FILES['input_'.$k]))
					{
						$this->set($k,$this->input_upload_handler($k));
					}
					
					break;
				case'none':
					break;
				default:
					if(isset($met['input_'.$k]))
					{
						$this->set($k,$met['input_'.$k]);
					}
					
					break;
			}
		}
		
		if($update!='')
		{
			$this->set($this->id_field,$update);
			return $this->save();
		}
		else
		{
			return $this->insert();
		}		
	}
	
	/*
	build the custom display for back office purpose
	$map => array; each field you want to have the back office
	$ac => string; url for the form action
	$wrap => bool; if extending this function, please set it to false to avoid havin the <form> wrapping
	*/
	function custom_back_office($map,$ac='',$wrap=true)
	{
		$date_day=date('Y-m-d',time());
		if($wrap==true)
		{
			$display=
			'
			<div class="form_wrap">
			<form method="post" action="'.$ac.'">
			';
		}
		else
		{
			$display='';
		}
		$v='';
		foreach($map as $k)
		{
			$v=$this->BO_type[$k];
			switch($v)
			{
				case'text':
					$display.=$this->input_text($k);
					break;
				case'textarea':
					$display.=$this->textarea($k);
					break;
				case'select':
					$display.=$this->custom_select($k);
					break;
				case'checkbox':
					$display.=$this->checkboxed($k);
					break;
				case'radio':
					$display.=$this->radioboxed($k);
					break;
				case'password':
					$display.=$this->passworded($k);
					break;
				case'upload':
					$display.=$this->input_upload($k);
					break;
				case'none':
					break;
				default:
					$display.=$this->input_text($k);
					break;
			}
		}
		if($wrap==true)
		{
			$display.=
			'
				<input type="button" id="valider" value="valider" onclick="if(check_'.$this->table.'_BO()==true){this.parentNode.submit();}"/>
				<script type="text/javascript">'.$this->js_checker($map).'</script>
			</form>
			</div>
			';
		}
		
		return $display;
	}
	
	function form_div($str,$k)
	{
		$div_class='';
		if(isset($this->BO_value[$k]['custom_div_class']))
		{
			$div_class=$this->BO_value[$k]['custom_div_class'];			
		}
		else
		{
			$div_class=$this->BO_value['form_div_class'];
			if(isset($this->BO_value[$k]['added_div_class']))
			{
				$div_class.=' '.$this->BO_value[$k]['added_div_class'];
			}
		}
		
		$display=
		'
		<div class="'.$div_class.'">
			'.$str.'
		</div>
		';
		
		return $display;
	}
	
	function form_label($k)
	{
		$label_class='';
		if(isset($this->BO_value[$k]['custom_label_class']))
		{
			$label_class=$this->BO_value[$k]['custom_label_class'];			
		}
		else
		{
			$label_class=$this->BO_value['form_label_class'];
			if(isset($this->BO_value[$k]['added_label_class']))
			{
				$label_class.=' '.$this->BO_value[$k]['added_label_class'];
			}
		}
		
		$display=
		'
		<label for="input_'.$k.'" class="'.$div_class.'">
			'.$this->BO_field[$k];
		
		if($this->BO_check[$k]['empty']!=true)
		{
			$display.=' *';
		}
		
		$display.=' :
		</label>
		';
		
		return $display;
	}
	
	/*
	build a <select> from a query on $this->table
	$i => string; unique id of the node
	$val => string; the name of the field wich will be displayed in the option
	$where => string; a custom condition for the query
	$onchange => string; a javascript function applied on the onchange event
	*/
	function selector($i,$val='',$where='',$sel='',$onchange='')
	{
		global$bdd;
		if($val=='')
		{
			$val=$this->id_field;
			$req='select '.$this->id_field.' from '.$this->table.' '.$where;
		}
		else
		{
			if(is_array($val))
			{
				$val2=implode($val,',');
			}
			else
			{
				$val2=$val;
			}
			$req='select '.$this->id_field.','.$val2.' from '.$this->table.' '.$where;
		}
		
		$res=$bdd->query2($req)->fetchAll();
		$display=
		'
			<select id="selector_'.$this->table.'_'.$i.'" name="selector_'.$this->table.'_'.$i.'"';
		
		if($onclick!='')
		{
			$display.=' onchange="'.$onchange.'"';
		}
		$display.=' class="'.$this->BO_value['form_select_class'].'">
		<option value=""></option>
		';
		
		foreach($res as $r)
		{
			$display.=
			'
			<option value="'.$r[$this->id_field].'"';
			if($sel==$r[$this->id_field])
			{
				$display.=' selected';
			}
			if(is_array($val))
			{
				$display.='>';
				
				foreach($val as $v)
				{
					$display.=$r[$v].' ';
				}
				
				$display.='</option>
				';
			}
			else
			{
				$display.='>'.$r[$val].'</option>
			';
			}
			
		}
		$display.=
		'
			</select>
		';
		
		return $display;
	}
	
	/*
	create an inpput field for a $this->mapped index
	$k => string; wich index of $this->mapped
	$utf8 => bool; if we encode the value in utf-8
	*/
	function input_text($k,$utf8=false)
	{
		if($utf8===true)
		{
			$v=utf8_encode($this->get($k));
		}
		else
		{
			$v=$this->get($k);
		}
		$display.=$this->form_label($k).'	
			<input type="text" value="'.$v.'" id="input_'.$k.'" name="input_'.$k.'" class="'.$this->BO_value['form_input_class'].'" />
		';
		$display=$this->form_div($display,$k);
		
		return $display;
	}
	
	/*
	create an inpput field for a $this->mapped index
	$k => string; wich index of $this->mapped
	*/
	function input_upload($k)
	{
		$display.=$this->form_label($k).'	
			<input type="file" id="input_'.$k.'" name="input_'.$k.'" class="'.$this->BO_value['form_input_class'].'" />			
		';
		if(isset($this->BO_value[$k]['max_size']))
		{
			$display.='<input type="hidden" name="MAX_FILE_SIZE" value="'.($this->BO_value[$k]['max_size']*1024).'" />';
		}
		$display=$this->form_div($display,$k);
		
		return $display;
	}
	
	/*
	create an inpput field for a $this->mapped index
	$k => string; wich index of $this->mapped
	*/
	function input_upload_handler($k)
	{
		$target=$this->BO_value[$k]['target'];
		if($this->BO_value[$k]['name']!='')
		{
			$target.=$this->BO_value[$k]['name'];
			if($this->BO_value[$k]['keep_ext']==true)
			{
				$target.='.'.end(explode('.', $_FILES['input_'.$k]['name']));
			}
			
		}
		else
		{
			$target.=basename($_FILES['input_'.$k]['name']); 
		}
		
		if(is_file($target))
		{
			copy($target,$target.'.old_'.time());
			unlink($target);
		}
		
		if(!copy($_FILES['input_'.$k]['tmp_name'], $target))
		{
			report_erreur2('3008',__FILE__,__LINE__,'input_upload_handler could not copy the uploaded file');
		}	
		else
		{
			return $target;
		}	
	}
	
	/*
	create an inpput password for a $this->mapped index,
	create a verification field.
	$k => string; wich index of $this->mapped
	*/
	function passworded($k)
	{
		$display.=$this->form_label($k).'	
			<input type="password" id="input_'.$k.'" name="input_'.$k.'" class="'.$this->BO_value['form_input_class'].'" />
		';
		$display.='<br />'.$this->BO_value[$k].' * :
			<input type="password" id="input_verification_'.$k.'" name="input_verification_'.$k.'" class="'.$this->BO_value['form_input_class'].'" />
		';
		$display=$this->form_div($display,$k);
		
		return $display;
	}
	
	/*
	create an text field for a $this->mapped index
	$k => string; wich index of $this->mapped
	$utf8 => bool; if we encode the value in utf-8
	*/
	function textarea($k,$utf8=true)
	{
		if($utf8===true)
		{
			$v=utf8_encode($this->get($k));
		}
		else
		{
			$v=$this->get($k);
		}
		$display.=$this->form_label($k).'	
			<textarea id="textarea_'.$k.'" name="textarea_'.$k.'"  class="'.$this->BO_value['form_textarea_class'].'">'.$v.'</textarea>
		';
		if($this->BO_value[$k]['editor']==true)
		{
			$display.='<script type="text/javascript">document.getElementsByTagName("body")[0].setAttribute("onload",document.getElementsByTagName("body")[0].getAttribute("onload")+";init_tiny_mce(\"textarea_'.$k.'\")")</script>';
		}
		
		$display=$this->form_div($display,$k);
		return $display;
	}
	
	/*
	create an <select> field for a $this->mapped index
	$k => string; wich index of $this->mapped
	extend like that :
	function custom_select($k,$strict=false)
	{
		$return=parent::custom_select($k,true);
		if($return==false)
		{
			$type=$this->BO_type[$k];
			switch($type)
			{
				case'...':
					$return=...;
					break;
				...
				default:
					if($strict==true)
					{
						return false;
					}
					$return=$this->empty_select($k);
					break;
			}
		}
		return $return;
	}
	*/
	function custom_select($k,$strict=false)
	{
		$type=$this->BO_value[$k]['type'];
		switch($type)
		{
			case'this':
				$return=$this->selector('default',$this->id_field,'','','');
				break;
			case'entity':
				$entity=$this->BO_value[$k]['entity'];
				if(isset($this->BO_value[$k]['entity_file']) and $this->BO_value[$k]['entity_file']!='')
				{
					$entity_file=$this->BO_value[$k]['entity_file'];
				}
				else
				{
					$entity_file=$this->BO_value[$k]['entity'].'.class';
				}
				inclure_fonction($entity_file);
				$entity=new $entity();
				$return=$entity->selector($this->BO_value[$k]['id'],$this->BO_value[$k]['val'],$this->BO_value[$k]['where'],$this->mapped[$k],$this->BO_value[$k]['onchange']);
				$return=$this->form_div($this->form_label($k).$return,$k);
				break;
			default:
				if($strict==true)
				{
					report_erreur2('3004',__FILE__,__LINE__,'entity_custom_select unknown key '.$k);
					return false;
				}
				$return=$this->empty_select($k);
				break;
		}
		return $return; 
	}
	
	function insert_custom_select($k,$met)
	{
		$type=$this->BO_value[$k]['type'];
		switch($type)
		{
			case'this':
				if(isset($met['selector_'.$this->table.'_default']))
				{
					$return=$met['selector_'.$this->table.'_default'];
				}
				else
				{
					$return=false;
				}				
				break;
			case'entity':
				$entity=$this->BO_value[$k]['entity'];
				if($this->BO_value[$k]['entity_file']!='')
				{
					inclure_fonction($this->BO_value[$k]['entity_file']);
				}
				else
				{
					inclure_fonction($this->BO_value[$k]['entity'].'.class');
				}
				$entity=new $entity();
				if(isset($met['selector_'.$entity->table.'_'.$this->BO_value[$k]['id']]))
				{
					$return=$met['selector_'.$entity->table.'_'.$this->BO_value[$k]['id']];
				}
				else
				{
					$return=false;
				}	
				break;
			default:
				$return=false;
				break;
		}
		return $return; 
	}
	
	/*
	create an empty <select> field for a $this->mapped index
	$k => string; wich index of $this->mapped
	*/
	function empty_select($k)
	{
		return $this->form_div('
					<label for="select_'.$k.'" class="'.$this->BO_value['form_label_class'].'">'.$this->BO_field[$k].'</label>	
					<select id="select_'.$k.'"><option>Empty select !</option></select>',$k);
	}
	
	/*
	create an checkbox field for a $this->mapped index
	$k => string; wich index of $this->mapped
	*/
	function checkboxed($k)
	{
		$checked='';
		if($this->get($k)!='0' or $this->get($k)!='')
		{
			$checked=' checked';
		}
		
		$display.=$this->form_label($k).'	
			<input type="checkbox" value="1" id="input_'.$k.'" name="input_'.$k.'"'.$checked.' class="'.$this->BO_value['form_checkbox_class'].'" />
		';
		$display=$this->form_div($display,$k);
		return $display;
	}
	
	/*
	verify if an index of $this->mapped have been checked
	$k => str; $this->mapped index
	$str => mixed; returned if true
	*/
	function checked($k,$str=true)
	{
		if($this->get($k)!='0' and $this->get($k)!='')
		{
			return $str;
		}
		else
		{
			return false;
		}
	}
	
	/*
	create an radio field for a $this->mapped index
	$k => string; wich index of $this->mapped
	*/
	function radioboxed($k)
	{
		$values=$this->BO_value[$k]['values'];
		$labels=$this->BO_value[$k]['labels'];
		$v=$this->get($k);
		
		$display.=$this->form_label($k);
		$x=0;
		foreach($values as $val)
		{
			$display.='
			<label for="input_'.$k.'_'.$x.'" class="'.$this->BO_value['form_label_2_class'].'">'.$labels[$x].'</label>
			<input type="radio" value="'.$val.'" id="input_'.$k.'_'.$x.'" name="input_'.$k.'"';
			
			if($this->get($k)==$val)
			{
				$display.=' checked';
			}
			
			$display.=' class="'.$this->BO_value['form_radio_class'].'" />
			<br />';
			
			$x++;
		}
		
		
		$display=$this->form_div($display,$k);
		return $display;
	}
	
	/*
	create a search form with the field from $map
	*/
	function search($map='',$ac='',$wrap=true)
	{
		if($map=='')
		{
			$map=array_keys($this->mapped);
		}
		if($wrap==true)
		{
			$display=
			'
			<div class="formulaire">
			<form method="post" action="'.$ac.'">
			';
		}
		else
		{
			$display='';
		}
		$display.=$this->custom_back_office($map,'',false);
		
		if($wrap==true)
		{
			$display.=
			'
				<input type="submit" id="rechercher" value="rechercher" />
			</form>
			</div>
			';
		}
		
		return $display;
	}
	
	/*
	search from the form, return an array of $this->mapped, loadable through $this->load_map();
	*/
	function search_result($url,$met='post',$wrap=true,$nb=0,$page=1)
	{
		global $bdd;
		if($met=='post')
		{
			$met=$_POST;
		}
		else
		{
			$met=$_GET;
		}
		$req='select * from '.$this->table;
		$req_comp=array();
		foreach($this->BO_type as $k => $v)
		{
			switch($v)
			{
				case'text':
					if(isset($met['input_'.$k]) and $met['input_'.$k]!='')
					{
						array_push($req_comp,$this->search_field_str($k,$met['input_'.$k]));
					}
					
					break;
				case'textarea':
					if(isset($met['textarea_'.$k]) and $met['textarea_'.$k]!='')
					{
						array_push($req_comp,$this->search_field_str($k,$met['textarea_'.$k]));
					}
					
					break;
				case'select':
					if($this->insert_custom_select($k,$met)!=false)
					{
						$v=$this->insert_custom_select($k,$met);
						array_push($req_comp,$this->search_field_str($k,$v));
					}

					break;
				case'checkbox':
					if(isset($met['input_'.$k]) and $met['input_'.$k]!='')
					{
						array_push($req_comp,$this->search_field_str($k,$met['input_'.$k]));
					}
					
					break;
				case'radio':
					if(isset($met['input_'.$k]) and $met['input_'.$k]!='')
					{
						array_push($req_comp,$this->search_field_str($k,$met['input_'.$k]));
					}
					
					break;
				case'none':
					break;
				default:
					if(isset($met['input_'.$k]) and $met['input_'.$k]!='')
					{
						array_push($req_comp,$this->search_field_str($k,$met['input_'.$k]));
					}
					
					break;
			}
		}
		
		if(count($req_comp)>0)
		{		
			$req.=' where '.implode(' and ',$req_comp);
		}
		
		if($nb!=0)
		{
			$from=$nb*($page-1);
			$to=$nb*$page-1;
			$req.=' limit '.$from.','.$to;
		}
		
		$res=$bdd->query2($req)->fetchAll();
		
		if($wrap==true)
		{
			return $this->link_result($res,$url,$this->BO_manage['map']);
		}
		else
		{
			return $res;
		}
		
	}
	
	function link_result($res,$url,$map)
	{
		$display=
		'
		<div class="BO_results">
		';
		foreach($res as $r)
		{
			$display.='<div class="BO_result"><a href="'.$url.$r[$this->id_field].'">';
			foreach($map as $l)
			{
				$display.=$r[$l].' ';
			}
			$display.='</a></div>';
		}
		return $display;
	}
	
	/*
	build the search request part to search on the $k indexes of $this->mapped
	*/
	function search_field_str($k,$v)
	{
		switch($this->BO_search[$k])
		{
			case'eq':
				$str='`'.$k.'`="'.$v.'"';
				break;
			case'ne':
				$str='`'.$k.'`!="'.$v.'"';
				break;
			case'gt':
				$str='`'.$k.'`>"'.$v.'"';
				break;
			case'lt':
				$str='`'.$k.'`<"'.$v.'"';
				break;
			case'ge':
				$str='`'.$k.'`>="'.$v.'"';
				break;
			case'le':
				$str='`'.$k.'`<="'.$v.'"';
				break;
			case'like':
				$str='`'.$k.'` like "'.$v.'"';
				break;
			case'_like':
				$str='`'.$k.'` like "%'.$v.'"';
				break;
			case'like_':
				$str='`'.$k.'` like "'.$v.'%"';
				break;
			case'_like_':
				$str='`'.$k.'` like "%'.$v.'%"';
				break;
			default:
				$str='`'.$k.'`="'.$v.'"';
				break;
		}
		return $str;
	}
	
	/*
	check the value of the field to see if it matches the BO_check value
	*/
	function BO_checker($k,$v)
	{
		if($this->BO_check[$k]['empty']!=true and $v=='')
		{
			report_erreur2('3005',__FILE__,__LINE__,'entity_BO_checker '.$k.' cant be empty');
			return false;
		}
		switch($this->BO_check[$k]['type'])
		{
			case'none':
				$return=true;
				break;
			case'id':
				$return=true;
				break;
			case'str':
				$return=true;
				break;
			case'int':
				$return=is_numeric($v);
				break;
			case'float':
				$return=is_numeric($v);
				break;
			case'mail':
				$return=valid_input($v,array('mail'));
				break;
		}
		
		return $return;
	}
	
	/*
	create a javascript algorithme to check a form created with $map
	$map => array; the $this->mapped fielf concerned
	$wrap => bool; true wrap the algo in a function called "check_<$this->table>_BO()";
	$error_msg => str; the error message intro followed by the bad fields
	*/
	function js_checker($map,$wrap=true,$error_msg='Les champs suivant sont mal remplis :')
	{
		if($wrap==true)
		{
			$jsf='function check_'.$this->table.'_BO(){';
		}
		else
		{
			$jsf='';
		}
		$id='';
		$jsf.='var valid_form=true;var error_msg=\''.$error_msg.'\\n\';';
		foreach($map as $k)
		{
			if($this->BO_type[$k]=='textarea')
			{
				$id='textarea_'.$k;
			}
			else
			{
				$id='input_'.$k;
			}
			if($this->BO_check[$k]['empty']!=true)
			{
				$jsf.='if(docel.id(\''.$id.'\').value==\'\'){valid_form=false;error_msg+=\''.$this->BO_field[$k].'\\n\'}';
			}
			if($this->BO_type[$k]=='password')
			{
				$id2='input_verification_'.$k;
				$jsf.='if(docel.id(\''.$id2.'\').value==\'\' || docel.id(\''.$id2.'\').value!=docel.id(\''.$id.'\').value){valid_form=false;error_msg+=\''.$this->BO_field[$k].'\\n\'}';
			}
			switch($this->BO_check[$k]['type'])
			{
				case'none':
					break;
				case'id':
					break;
				case'str':
					break;
				case'int':
					break;
			}
		}
		
		if($wrap==true)
		{
			$jsf.='if(valid_form!=true){alert(error_msg);}return valid_form;}';
		}
		return $jsf;
	}
	
	/*
	create a self checking algorithme for a field
	work in progress !!!!
	*/
	function self_js_checker($k,$event='onkeyup')
	{
		$jsf=' '.$event.'="';
		if($this->BO_check[$k]['type']!='none')
		{
			switch($this->BO_check[$k]['type'])
			{
				case'none':
					break;
				case'id':
					break;
				case'str':
					break;
				case'int':
					break;
			}
			$jsf.='"';
		}
		if($this->BO_check[$k]['empty']!=true)
		{
			$jsf.='" onblur="if(this.value==\'\'){docel.id(\'\').style.color=\'#f00\';}"';
		}
		return $jsf;
	}
	
	/*
	create a javascript string to send the request through an ajax request
	$map => array, the field to be sent
	$wrap => bool; create a function returning the result if true, if false it returns the parameter string
	$url => string; will be concatanated with the parameter string, required if $wrap=true
	$met => string; the sending method (post, get), default=post
	*/
	function form_2_ajax_param($map,$wrap=true,$url='',$met='post')
	{
		$id='';
		$url2='\''.$url;
		foreach($map as $k)
		{
			if($this->BO_type[$k]=='textarea')
			{
				$id='textarea_'.$k;
			}
			else
			{
				$id=='input_'.$k;
			}
			$url2.='&'.$id.'=\'+docel.id(\''.$id.'\').value+\'';
			
		}
		$url2.='\'';
		if($wrap==true)
		{
			if($met=='post')
			{				
				$url='var url=\''.$url.'\';';
				$data=',\''.$url2.'\'';
			}
			else
			{
				$url='var url='.$url2.';';
				$data='';
			}
			$jsf='function send_'.$this->table.'_BO(){'.$url.'var res=serv_req(url,\'text\',\''.$met.'\''.$data.');return res;}';
		}
		else
		{
			$jsf=$url2;
		}
		return $jsf;
	}
	
/*
a bunch of tools 
*/

	/*
	encode every string entry with utf8 and put it back in the database.
	$from=> int; starting rank
	$to => int; ending rank, if -1 select the all table content
	*/
	function to_utf8($from=0,$to=-1)
	{
		global$bdd;
		$req='select * from '.$this->table.' ';
		if($to!=-1)
		{
			$to=$bdd->query2('select count('.$this->id_field.') as c from '.$this->table)->fetch();
			$to=$to['c'];
		}
		$exec_time=time();
		$req.='limit '.$from.','.$to;
		$x=$from;
		$res=$bdd->query2($req)->fetchAll();
		
		foreach($res as $r)
		{
			$this->load_map($r);
			foreach($this->mapped as $m)
			{
				if($this->BO_check['type']=='str')
				{
					$this->set($m,utf8_encode($this->get($m)));
				}
			}
			$this->save();
			$x++;
			if((time()-$exec_time)>28)
			{
				return $x;
			}
		}
		
		return true;
	}
	
	function filize($file,$format='xml',$from=0,$to=-1)
	{
		global$bdd;
		$req='select * from '.$this->table.' ';
		if($to!=-1)
		{
			$to='(select count(`'.$this->id_field.'`) from '.$this->table.')';
		}
		
		if($from==0)
		{
			if($format=='xml')
			{
				$export='<'.$this->table.'_export date="'.time().'">';
			}
			else
			{
				$export=implode(array_keys($this->mapped),',')."\n";
			}
		}
		else
		{
			$export='';
		}
		$exec_time=time();
		$req.='limit '.$from.','.$to;
		$x=$from;
		$res=$bdd->query2($req)->fetchAll();
		
		foreach($res as $r)
		{
			if($format=='xml')
			{
				$export.=$this->to_xml();
			}
			else
			{
				$export.=$this->to_csv();
			}
			
			$this->load_map($r);
			
			$x++;
			if((time()-$exec_time)>28)
			{
				file_put_contents($file,$xml);
				return $x;
			}
		}
		
		if($format=='xml')
		{
			$export.='</'.$this->table.'_export>';
		}
		else
		{
			$export.='';
		}
		
		return file_put_contents($file,$xml);;
	}
	
	function sqlize($file='')
	{
		global$bdd;
		$data=$bdd->get_table_data($this->table);
		if($file!='')
		{
			file_put_contents($file,$data);
		}
		return $data;
	}
}

class bdd_relation extends bdd_entity
{
	public $master,$listed,$classes,$entity;
	
	function __construct($fields,$table,$id_field='id',$class_files,$class_names)
	{
		parent::__construct($fields,$table,$id_field);
		$this->master='';
		$this->entity='';
		$x=0;
		foreach($fields as $f)
		{
			if($f!=$this->id_field)
			{
				$this->classes[$f]['file']=$class_files[$x];
				$this->classes[$f]['name']=$class_names[$x];
			}
		}
	}
	
	function get_list($k,$v,$custom='')
	{
		global $bdd;
		if(in_array_keys($this->mapped,$k) and $k!=$this->id_field)
		{
			$this->master=$k;
			$req='select * from '.$this->table.' where `'.$k.'`="'.$v.'"'.$custom;
			$this->listed=$bdd->query2($req)->fetchAll();
			return $this->listed;
		}
		else
		{
			return false;
		}
	}
	
	function load($k,$id)
	{
		if(in_array_keys($this->mapped,$k) and $k!=$this->id_field)
		{
			if(is_file($this->classes[$k]['file']))
			{
				$this->entity=new $this->classes[$k]['name']($id);
				return $this->entity;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	function entitied()
	{
		if($this->entity!='')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function __toString()
	{
		if($this->entitied())
		{
			return $this->entity->__toString();
		}
		else
		{
			parent::__toString();
		}
	}
	
	function load_map($map)
	{
		if($this->entitied())
		{
			return $this->entity->load_map($map);
		}
	}
	
	function load_xml($xml)
	{
		if($this->entitied())
		{
			return $this->entity->load_xml($xml);
		}
	}
	
	function get($k)
	{
		if($this->entitied())
		{
			return $this->entity->get($k);
		}
	}
	
	function set($k,$v)
	{
		if($this->entitied())
		{
			return $this->entity->set($k,$v);
		}
	}
	
	function save()
	{
		if($this->entitied())
		{
			return $this->entity->save();
		}
	}
	
	
	function to_xml()
	{
		if($this->entitied())
		{
			return $this->entity->to_xml();
		}
	}
	
	function save_xml($file)
	{
	if($this->entitied())
		{
			return $this->entity->save_xml($file);
		}
	}
		
	function insert($map='')
	{
		if($this->entitied())
		{
			return $this->entity->insert($map);
		}
	}
	
	function display()
	{
		if($this->entitied())
		{
			return $this->entity->display();
		}
	}

	function back_office($ac='',$wrap=true)
	{
		if($this->entitied())
		{
			return $this->entity->back_office($ac,$wrap);
		}
	}
	
	function insert_back_office($met='post',$update='')
	{
		if($this->entitied())
		{
			return $this->entity->insert_back_office($met,$update);
		}		
	}
	
	function custom_back_office($map,$ac='',$wrap=true)
	{
		if($this->entitied())
		{
			return $this->entity->custom_back_office($map,$ac,$wrap);
		}
	}
	
	function form_div($str,$k)
	{
		if($this->entitied())
		{
			return $this->entity->form_div($str,$k);
		}
	}
	
	function form_label($k)
	{
		if($this->entitied())
		{
			return $this->entity->form_label($k);
		}
	}
	
	function selector($i,$val='',$where='',$sel='',$onchange='')
	{
		if($this->entitied())
		{
			return $this->entity->selector($i,$val,$where,$sel,$onchange);
		}
	}
	
	function input_text($k,$utf8=true)
	{
		if($this->entitied())
		{
			return $this->entity->input_text($k,$utf8);
		}
	}
	
	function textarea($k,$utf8=true)
	{
		if($this->entitied())
		{
			return $this->entity->textarea($k,$utf8);
		}
	}
	
	function custom_select($k,$strict=false)
	{
		 if($this->entitied())
		{
			return $this->entity->custom_select($k,$strict);
		}
	}
	
	function empty_select($k)
	{
	if($this->entitied())
		{
			return $this->entity->empty_select($k);
		}
	}
	
	function checkboxed($k)
	{
		if($this->entitied())
		{
			return $this->entity->checkboxed($k);
		}
	}
	
	function checked($k,$str=true)
	{
		if($this->entitied())
		{
			return $this->entity->checked($k,$str);
		}
	}
	function radioboxed($k)
	{
		if($this->entitied())
		{
			return $this->entity->radioboxed($k);
		}
	}
	function search($map,$ac='',$wrap=true)
	{
		if($this->entitied())
		{
			return $this->entity->search($map,$ac,$wrap);
		}
	}
	function search_result($met='post')
	{
		if($this->entitied())
		{
			return $this->entity->search_result($met);
		}
	}
	
	function search_field_str($k,$v)
	{
		if($this->entitied())
		{
			return $this->entity->search_field_str($k,$v);
		}
	}
	
	function BO_checker($k,$v)
	{
		if($this->entitied())
		{
			return $this->entity->BO_checker($k,$v);
		}
	}
	
	function js_checker($map,$wrap=true,$error_msg='Les champs suivant sont mal remplis :')
	{
		if($this->entitied())
		{
			return $this->entity->js_checker($map,$wrap,$error_msg);
		}
	}
	
	function self_js_checker($k,$event='onkeyup')
	{
		if($this->entitied())
		{
			return $this->entity->self_js_checker($k,$event);
		}
	}
	
	function form_2_ajax_param($map,$wrap=true,$url='',$met='post')
	{
		if($this->entitied())
		{
			return $this->entity->form_2_ajax_param($map,$wrap,$url,$met);
		}
	}
}


?>
