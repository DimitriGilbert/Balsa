<?php
/*
bdd_entity is a class to help you manipulate entities in a database
$mapped => array; associative, the field you want to interact with in you entity, field_name => field_value
$table => string; the name of the database table
$id_field => string ('id'); the name of the id field
$update => array; the fields that have been set to a value, for saving purposes
$BO_field => array; associative, same keys as $mapped, the value of the back office label for the field
$BO_type => array; associative, same keys as $mapped, the type of back office field
$BO_value => array; associative, same keys as $mapped, useful for some field types
*/
class bdd_entity
{
	public $mapped, $table, $id_field, $update, $BO_field, $BO_type, $BO_value;
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
	}
	
	*/
	function __construct($map,$table,$id_field='id')
	{
		foreach($map as $m)
		{
			$this->mapped[$m]='';
			$this->BO_field[$m]=$m;
			$this->BO_type[$m]='text';
		}
		$this->BO_value['form_div_class']='form_div';
		$this->BO_value['form_label_class']='form_label';
		$this->BO_value['form_label_2_class']='form_label_2';
		$this->BO_value['form_input_class']='form_input';
		$this->BO_value['form_checkbox_class']='form_checkbox';
		$this->BO_value['form_radio_class']='form_radio';
		$this->BO_value['form_select_class']='form_select';
		$this->BO_value['form_textarea_class']='form_textarea';
		$this->table=$table;
		$this->id_field=$id_field;
		$this->update=array();
		
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
					return true;
				}
				else
				{
#					echo"repport_erreur('sql_aucun enregistrement pour la requete '.$req)";
					return false;
				}
			}
			else
			{
#				echo"repport_erreur('sql_probleme lors de l\'execution de la requete '.$req)";
				return false;
			}
		}
		else
		{
			//repport_erreur();
			return false;
		}
	}
	
	/*
	load from an associative array 
	*/
	function load_map($map)
	{
		$this->mapped=$map;
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
	function get($k)
	{
		if(in_array_keys($k,$this->mapped))
		{
			return $this->mapped[$k];
		}
		else
		{
			//repport_erreur('entity.class_essais d'accés a la variable '.$k.' inconnnue');
			return false;
		}
	}
	
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
	function set($k,$v)
	{
		if(in_array_keys($k,$this->mapped))
		{
			$this->mapped[$k]=$v;
			$this->update[$k]=$v;
			return true;
		}
		else
		{
			//repport_erreur('entity.class_essais d'accés a la variable '.$k.' inconnnue');
			return false;
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
				return true;
			}
			else
			{
				//repport_erreur('sql_probleme lors de la mise a jour '.$req);
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
		foreach($this->update as $k=>$v)
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
			return true;
			$this->mapped=$map;
			return $map[$this->id_field];
		}
		else
		{
			//repport_erreur('sql_probleme lors de l\'insertion '.$req);
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

"everything" for th back office following, work in progress ;D

*/	
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
		
		foreach($this->BO_type as $k => $v)
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
				default:
					$display.=$this->input_text($k);
					break;
			}
		}
		if($wrap==true)
		{
			$display.=
			'
				<input type="submit" id="valider" value="valider" />
			</form>
			</div>
			';
		}
		
		return $display;
	}
	
	/*
	insert the posted content of the back office into an entry in the database
	*/
	function insert_back_office()
	{
		
		foreach($this->BO_type as $k => $v)
		{
			switch($v)
			{
				case'text':
					$this->set($k,$_POST['input_'.$k]);
					break;
				case'textarea':
					$this->set($k,$_POST['textarea_'.$k]);
					break;
				case'select':
#					$this->set($k,$_POST['input_'.$k]);
					break;
				case'checkbox':
					$this->set($k,$_POST['input_'.$k]);
					break;
				case'radio':
					$this->set($k,$_POST['input_'.$k]);
					break;
				default:
					$this->set($k,$_POST['input_'.$k]);
					break;
			}
		}
		
		return $this->insert();
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
			$req='select '.$this->id_field.','.$val.' from '.$this->table.' '.$where;
		}
		
		$res=$bdd->query2($req)->fetchAll();
		$display=
		'
		<div class="'.$this->BO_value['form_div_class'].'">
			<label for="selector_'.$this->table.'_'.$i.'" class="'.$this->BO_value['form_label_class'].'">'.$this->BO_field[$k].'</label>
			<select id="selector_'.$this->table.'_'.$i.'" name="selector_'.$this->table.'_'.$i.'"'
		
		if($onclick!='')
		{
			$display.=' onchange="'.$onchange.'"';
		}
		$display.=' class="'.$this->BO_value['form_select_class'].'">
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
			$display.='>'.$r[$val].'</option>
			';
		}
		$display.=
		'
			</select>
		</div>
		';
		
		return $display;
	}
	
	/*
	create an inpput field for a $this->mapped index
	$k => string; wich index of $this->mapped
	$utf8 => bool; if we encode the value in utf-8
	*/
	function input_text($k,$utf8=true)
	{
		if($utf8===true)
		{
			$v=utf8_encode($this->get($k));
		}
		else
		{
			$v=$this->get($k);
		}
		$display.=
		'
		<div class="'.$this->BO_value['form_div_class'].'">
			<label for="input_'.$k.'" class="'.$this->BO_value['form_label_class'].'">'.$this->BO_field[$k].'</label>	
			<input type="text" value="'.$v.'" id="input_'.$k.'" name="input_'.$k.'" class="'.$this->BO_value['form_input_class'].'" />
		</div>
		';
		
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
		$display.=
		'
		<div class="'.$this->BO_value['form_div_class'].'">
			<label for="input_'.$k.'" class="'.$this->BO_value['form_label_class'].'">'.$this->BO_field[$k].'</label>	
			<textarea id="textarea_'.$k.'" name="textarea_'.$k.'"  class="'.$this->BO_value['form_textarea_class'].'">'.$v.'</textarea>
		</div>
		';
		
		return $display;
	}
	
	/*
	create an <select> field for a $this->mapped index
	$k => string; wich index of $this->mapped
	*/
	function custom_select($k)
	{
		$type=$this->BO_value[$k]['type'];
		switch($type)
		{
			case'this':
				$return=selector('default',$this->id_field,'','','');
				break;
			default:
				return false;
		}
		return $return; 
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
		
		$display.=
		'
		<div class="'.$this->BO_value['form_div_class'].'">
			<label for="input_'.$k.'" class="'.$this->BO_value['form_label_class'].'">'.$this->BO_field[$k].'</label>	
			<input type="checkbox" value="1" id="input_'.$k.'" name="input_'.$k.'"'.$checked.' class="'.$this->BO_value['form_checkbox_class'].'" />
		</div>
		';
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
		
		$display.=
		'
		<div class="'.$this->BO_value['form_div_class'].'">
			<label for="input_'.$k.'" class="'.$this->BO_value['form_label_class'].'">'.$this->BO_field[$k].'</label>';
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
		
		$display.='
		</div>
		';
		return $display;
	}
}


?>
