<?php

inclure_fonction('entity.class');

class bcm_category extends bdd_entity
{
	public $childs;
	
	function __construct($id='')
	{
		parent::__construct(array('id','title','parent','description','level'),'Categories','id','bcm_');
		
		$this->childs=array();
		$this->childs['categories']=array();
		$this->childs['publications']=array();
		
		$this->BO_field['parent']='Select the parent category ';
		$this->BO_field['description']='Enter the description of the category ';
		$this->BO_field['title']='Name of the category ';
		
		$this->BO_type['id']='none';
		$this->BO_type['level']='none';
		$this->BO_type['parent']='select';
		$this->BO_type['description']='textarea';
		$this->BO_type['title']='text';
				
		$this->BO_check['title']['empty']=false;	
		
		$this->BO_search['title']='_like_';
		$this->BO_search['description']='_like_';
		
		$this->BO_manage['map']=array();
		$this->BO_manage['map'][0]='title';
		
		$this->BO_value['parent']['type']='entity';
		$this->BO_value['parent']['entity']='bcm_category';
		$this->BO_value['parent']['id']='parent';
		$this->BO_value['parent']['val']='title';
		$this->BO_value['description']['editor']=true;
		
		if($id!='')
		{
			$this->load($id);
		}
	}
	
	function load($id)
	{
		$this->childs=array();
		$this->childs['categories']=array();
		$this->childs['publications']=array();
		$res=bcm_get_requested('category',$id);
		if($res!=false)
		{
			return $res;
		}
		else
		{
			$res=parent::load($id);
			if($res!=false)
			{
				bcm_set_requested('category',$id,$this->mapped);
			}
			return $res;
		}
		
	}
	
	function load_map($map)
	{
		$this->childs=array();
		$this->childs['categories']=array();
		$this->childs['publications']=array();
		$this->mapped['parent']=$map['parent'];
		unset($map['parent']);
		return parent::load_map($map);
	}
	
	function get($k)
	{
		$return=parent::get($k,false);
		if($return==false)
		{
			switch($k)
			{
				case'childs':
					if(count($this->childs['categories'])==0 and count($this->childs['publications'])==0)
					{
						$return=$this->get_childs();
					}
					else
					{
						$return=$this->childs;
					}
					break;
				case'categories':
					if(count($this->childs['categories'])==0 and count($this->childs['publications'])==0)
					{
						$return=$this->get_childs();
						$return=$return['categories'];
					}
					else
					{
						$return=$this->childs['categories'];
					}
					break;
				case'publications':
					if(count($this->childs['categories'])==0 and count($this->childs['publications'])==0)
					{
						$return=$this->get_childs();
						$return=$return['publications'];
					}
					else
					{
						$return=$this->childs['publications'];
					}
					break;
				default:
					$return=false;
			}
		}
		return $return;
	}
	
	function set($k,$v)
	{
		$return=parent::set($k,$v,false);
		if($return!=false and $k=='parent')
		{
			$level=$this->get_level($v);
			$level++;
			return $this->set('level',$level);
		}
		return $return;
	}
	
	function all($select='',$where='',$nb=0,$page=1)
	{
		$res=parent::all($select,$where,$nb,$page);
		
		if($select=='*')
		{
			foreach($res as $r)
			{
				bcm_set_requested('category',$r['id'],$r);
			}
		}
			
		return $res;
	}
	
	function get_childs()
	{
		global $bdd;
		
		$res=bcm_get_requested('category_childs',$id);
		if($res!=false)
		{
			return $res;
		}
		else
		{
			$req='select * from bcm_Categories where parent="'.$this->get('id').'"';
			$this->childs['categories']=$bdd->query2($req)->fetchAll();
			foreach($this->childs['categories'] as $r)
			{
				bcm_set_requested('category',$r['id'],$r);
			}
		
			$req='select * from bcm_Publications P,bcm_publication_parent pp where pp.category="'.$this->get('id').'" and pp.publication=P.id';
			$this->childs['publications']=$bdd->query2($req)->fetchAll();
			foreach($this->childs['publications'] as $r)
			{
				bcm_set_requested('publications',$r[0],array('id'=>$r[0],'title'=>$r['title'],'excerpt'=>$r['excerpt'],'content'=>$r['content'],'author'=>$r['author'],'parution'=>$r['parution'],'status'=>$r['status']));
			}
			$res=$this->childs;
#			bcm_set_requested('category_childs',$this->get['id'],$res);
			return $res;
		}
	}
	
	static function get_level($id)
	{
		global $bdd;
		$req='select level from bcm_Categories where id="'.$id.'" limit 1';
		$res=$bdd->query2($req)->fetch();
		return (int)$res['level'];
	}
	
	static function get_parent($id,$select='parent',$recursive=false)
	{
		global $bdd;
		if(is_array($select))
		{
			if(!in_array('parent'))
			{
				array_push($select,'parent');
			}
			$select=implode($select,',');
		}
		$req='select '.$select.' from bcm_Categories where id="'.$id.'" limit 1';
		$res=$bdd->query2($req)->fetch();
		if($recursive==false)
		{
			if($select!='parent')
			{
				return $res;
			}
			else
			{
				return $res['parent'];
			}
			
		}
		else
		{
			$parents=array($res['parent']);
			while($res['parent']!='')
			{
				$req='select '.$select.' from bcm_Categories where id="'.$res['parent'].'" limit 1';
				$res=$bdd->query2($req)->fetch();
				if($select!='parent')
				{
					array_push($parents,$res);
				}
				else
				{
					array_push($parents,$res['parent']);
				}				
			}
			
			return $parents;
		}
	}
	
	function picker($ids)
	{
		$all=$this->all(array('id','title'));
		$display=
		'
		<div class="category_picker">
		';
		
		foreach($all as $a)
		{
			$display.=
			'
			<div class="category_picker_item" id="category_picker_item_'.$a['id'].'">
				<label for="category_picker_item_'.$a['id'].'">'.$a['title'].'</label>
				<input type="checkbox" value="'.$a['id'].'" id="category_picker_item_'.$a['id'].'" name="category_picker_item_value[]"';
			if(in_array($a['id'],$ids))
			{
				$display.=' checked';
			}
			$display.=' />
			</div>
			';
		}
		
		$display.=
		'
		</div>
		';
		
		return $display;
	}
	
	function display_title($tr='')
	{
		global $base_url;
		$e=$this->get('title');
		if($tr!='')
		{
			$e=truncate_str($e,$tr);
		}
		$display=
		'
		<div class="category_title" id="title_'.$this->get('id').'">
			<a href="'.$base_url.'?page=bcm_category&title='.$this->get('title').'&id='.$this->get('id').'">'.$e.'</a>
		</div>
		';
		return $display;
	}
	
	function display_description($tr='')
	{
		$e=$this->get('description');
		if($tr!='')
		{
			$e=truncate_str($e,$tr);
		}
		$display=
		'
		<div class="category_description" id="description_'.$this->get('id').'">
			'.$e.'
		</div>
		';
		return $display;
	}
	
	function display_publications($wrap=true)
	{
		inclure_fonction('bcm_publication.class');
		$publi=new bcm_publication();
		
		$pub=$this->get('publications');
#		print_pre($pub);
		if($wrap==true)
		{
			$display=
			'
			<div class="category_publications"  id="publications_'.$this->get('id').'">
			<h2>Publications</h2>
			';
		}
		else
		{
			$display='';
		}
		
		if(count($pub)>0)
		{
			foreach($pub as $p)
			{
				$p['id']=$p[0];
				$publi->load_map($p);
				$display.=$publi->display_small();
			}
		}
		else
		{
			if($wrap==true)
			{
				$display.='<h3>No publications in this category...</h3>';
			}
		}		
		
		if($wrap==true)
		{
			$display.=
			'
			<div class=float_clear></div>
			</div>
			';
		}
		
		return $display;
	}
	
	function display_categories($wrap=true)
	{
		$publi=new bcm_category();
		
		$pub=$this->get('categories');
		
		if($wrap==true)
		{
			$display=
			'
			<div class="category_categories"  id="categories_'.$this->get('id').'">
			<h2>Sub-Categories</h2>
			';
		}
		else
		{
			$display='';
		}
		
		
		if(count($pub)>0)
		{
			foreach($pub as $p)
			{
				$publi->load_map($p);
				$display.=$publi->display_small();
			}
		}
		else
		{
			if($wrap==true)
			{
				$display.='<h3>No child Categories...</h3>';
			}
		}
			
		
		if($wrap==true)
		{
			$display.=
			'
			<div class="float_clear"></div>
			</div>
			';
		}		
		return $display;
	}
	
	function display_childs()
	{
		return $this->display_publications().$this->display_categories();
	}
	
	function display_small()
	{
		$display=
		'
		<div class="category_small" id="small_'.$this->get('id').'">
			'.$this->display_title(23).'
			'.$this->display_description(100).'
		</div>
		';
		return $display;
	}
	
	function display_full()
	{
		$display=
		'
		<div class="category_full" id="full_'.$this->get('id').'">
			<h1>'.$this->display_title().'</h1>
			'.$this->display_description().'
			'.$this->display_childs().'
		</div>
		';
		return $display;
	}
	
	function menu($id='',$child=true)
	{
		global $bdd,$base_url;
		
		if($id!='')
		{
			$this->load($id);
		}
		
		$menu='
		<li id="menu_'.$this->get('id').'">
			<a id="menu_link_'.$this->get('id').'" href="'.$base_url.'?page=bcm_category&title='.$this->get('title').'&id='.$this->get('id').'"';
		
		if($child==true)
		{
			$child=$this->get('categories');
			if(count($child)>0)
			{
				$menu.=' class="menu_parent"';
			}
		}
		
		$menu.='>'.$this->get('title').'</a>
		';
		
		if(is_array($child))
		{
			$menu.='
			<ul id="menu_childs_'.$this->get('id').'">';
			$cat=new bcm_category();
			foreach($child as $c)
			{
				$cat->load_map($c);
				$menu.='
				'.$cat->menu();
			}
			$menu.='
			</ul>';
		}
		$menu.='
		</li>';
		
		return $menu;
	}
}


?>
