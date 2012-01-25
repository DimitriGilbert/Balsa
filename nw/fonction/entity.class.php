<?php

class bdd_entity
{
	var $mapped;
	var $table;
	var $id_field;
	var $update;
	var $BO_field;
	
	function __construct($map,$table,$id_field='id')
	{
		foreach($map as $m)
		{
			$this->mapped[$m]='';
			$this->BO_field[$m]=$m;
		}		
		$this->table=$table;
		$this->id_field=$id_field;
		$this->update=array();
		
	}
	
	function __toString()
	{
		echo $this->display();
	}
	
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
	
	function load_map($map)
	{
		$this->mapped=$map;
	}
	
	function load_xml($xml)
	{
		return false;
	}
	
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
	
	function save_xml($file)
	{
		return file_put_contents($file,'<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.$this->to_xml());
	}
	
	function insert($map='')
	{
		global $bdd;
		if($map=='')
		{
			$map=$this->mapped;
		}
		if(!in_array_keys('id',$map) or $map['id']=='')
		{
			$map['id']=$bdd->get_primkey();
		}
		$values=implode('","',$map);
		$keys=implode('`,`',array_keys($map));		
		$req='insert into '.$this->table.' (`'.$keys.'`) values ("'.$values.'")';
		$res=$bdd->query2($req);
		if($res)
		{
			return true;
			$this->mapped=$map;
			return $map['id'];
		}
		else
		{
			//repport_erreur('sql_probleme lors de l\'insertion '.$req);
			return false;
		}
		return $display;
	}
	
	function display()
	{
		$display='';
		foreach($this->mapped as $k=>$v)
		{
			$display.='<div>'.$k.' : '.$v.'</div>';
		}
		return $display;
	}
	
	function back_office($ac='')
	{
		$date_day=date('Y-m-d',time());
		$display=
		'
		<div class="formulaire">
		<form method="post" action="'.$ac.'">
		';
		foreach($this->mapped as $k => $v)
		{
			$display.=
			'
			<div>
				<label for="input_'.$k.'">'.$this->BO_field[$k].'</label>
			</div>
			<div>	
				<input type="text" value="'.$v.'" id="'.$k.'" name="'.$k.'" />
			</div>
			';
		}
		$display.=
		'
			<input type="submit" id="valider" value="valider" />
		</form>
		</div>
		';
		return $display;
	}
}


?>
