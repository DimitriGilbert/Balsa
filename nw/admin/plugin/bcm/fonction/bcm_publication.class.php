<?php
inclure_fonction('entity.class');

class bcm_publication extends bdd_entity
{
	public $parents,$tags,$comments;
	
	function __construct($id='')
	{
		parent::__construct(array('id','title','excerpt','content','author','parution','status'),'Publications','id','bcm_');
		
		$this->parents=array();
		
		$this->BO_field['parent']='Select the parent category ';
		$this->BO_field['content']='Enter the content of the publication ';
		$this->BO_field['title']='Name of the publication ';
		
		$this->BO_type['id']='none';
		$this->BO_type['parution']='none';
		$this->BO_type['author']='none';
		$this->BO_type['content']='textarea';
		$this->BO_type['excerpt']='textarea';
		$this->BO_type['title']='text';
		$this->BO_type['status']='radio';
		
		$this->BO_value['status']['labels']=array('Draft','Published');
		$this->BO_value['status']['values']=array('Draft','Published');
		$this->BO_value['content']['editor']=true;
		$this->BO_value['excerpt']['editor']=true;
				
		$this->BO_check['title']['empty']=false;
		$this->BO_check['content']['empty']=false;	
		
		$this->BO_search['title']='_like_';
		$this->BO_search['content']='_like_';
		$this->BO_search['excerpt']='_like_';
		
		$this->BO_manage['map']=array();
		$this->BO_manage['map'][0]='title';
		
		
		if($id!='')
		{
			$this->load($id);
		}
	}
	
	function load($id)
	{
		$this->parents=array();
		$this->tags=array();
		$this->comments=array();
		$res=bcm_get_requested('publication',$id);
		if($res!=false)
		{
			return $res;
		}
		else
		{
			$res=parent::load($id);
			if($res!=false)
			{
				bcm_set_requested('publication',$id,$this->mapped);
			}
			return $res;
		}		
	}
	
	function get($k)
	{
		$return=parent::get($k,false);
		if($return==false)
		{
			switch($k)
			{
				case'parent':
					if(count($this->parents)==0)
					{
						$return=$this->get_parents();
					}
					else
					{
						$return=$this->parents;
					}
					break;
				case'tags':
					if(count($this->tags)==0)
					{
						$return=$this->get_tags();
					}
					else
					{
						$return=$this->tags;
					}
					break;
				case'comments':
					if(count($this->comments)==0)
					{
						$return=$this->get_comments();
					}
					else
					{
						$return=$this->comments;
					}
					break;
				default:
					$return=false;
			}
		}
		return $return;
	}
	
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
		
		inclure_fonction('bcm_category.class');
		$no_=new bcm_category();
		
		$display.=parent::back_office('',false);
		$display.=$no_->picker($this->get_parents_id());
		
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
	
	function insert_back_office($met='post',$update='')
	{
		global$bdd;
		if($met=='post')
		{
			$met2=$_POST;
		}
		else
		{
			$met2=$_GET;
		}
		if($update=='')
		{
			$this->set('publication',time());
		}
		else
		{
		
		}
		$p_id=parent::insert_back_office($met,$update);
		$req='insert into bcm_publication_parent (id,category,publication) values ';
		$req_t=array();
		foreach($met2['category_picker_item_value'] as $p)
		{
			array_push($req_t,'("'.$bdd->get_primkey().'","'.$p.'","'.$p_id.'")');
		}
		if(count($req_t>0))
		{
			$req.=implode($req_t,',');
			$res=$bdd->query2($req);
		}
		
		return $p_id;
	}
	
	function get_parents()
	{
		if($this->get($this->id_field)=='')
		{
			return array();
		}
		else
		{
			global $bdd;
			$req='select * from bcm_Categories C, bcm_publication_parent pp where C.id=pp.category and pp.publication="'.$this->get($this->id_field).'"';
			$this->parents=$bdd->query2($req)->fetchAll();
			return $this->parents;
		}
		
	}
	
	function get_parents_id()
	{
		$parents=$this->get('parent');
		$ids=array();
		foreach($parents as $p)
		{
			array_push($ids,$p['category']);
		}
		return $ids;
	}
	
	
	function get_tags()
	{
		global $bdd;
		$req='select * from bcm_Tags T, bcm_publication_tag pt where T.id=pt.tag and pt.publication="'.$this->get($this->id_field).'"';
		$this->tags=$bdd->query2($req)->fetchAll();
		return $this->tags;
	}
	
	function get_comments()
	{
		global $bdd;
		$req='select * from bcm_Comments where publication="'.$this->get($this->id_field).'"';
		$this->tags=$bdd->query2($req)->fetchAll();
		return $this->tags;
	}
	
	function display_title($date=false,$tr='')
	{
		global $base_url;
		$e=$this->get('title');
		if($tr!='')
		{
			$e=truncate_str($e,$tr);
		}
		$display=
		'
		<div class="publication_title" id="title_'.$this->get('id').'">';
		if($date==true)
		{
			$display.=$this->display_date();
		}
		$display.='
			<a href="'.$base_url.'?page=bcm_publication&title='.$this->get('title').'&id='.$this->get('id').'">'.$e.'</a>
		</div>
		';
		return $display;
	}
	
	function display_excerpt($tr='')
	{
		$e=$this->get('excerpt');
		if($tr!='')
		{
			$e=truncate_str($e,$tr);
		}
		$display=
		'
		<div class="publication_excerpt" id="excerpt_'.$this->get('id').'">
			'.$e.'
		</div>
		';
		return $display;
	}
	
	function display_content()
	{
		$display=
		'
		<div class="publication_content" id="content_'.$this->get('id').'">
			'.$this->get('content').'
		</div>
		';
		return $display;
	}
	
	function display_date()
	{
		$display=
		'
		<span class="publication_date" id="date_'.$this->get('id').'">
			'.date('d/m/Y',$this->get('parution')).'
		</span>
		';
		return $display;
	}
	
	function display_small()
	{
		$display=
		'
		<div class="publication_small" id="small_'.$this->get('id').'">
			'.$this->display_title(true,15).'
			'.$this->display_excerpt(80).'
		</div>
		';
		return $display;
	}
	
	function display_tags()
	{
		inclure_fonction('bcm_tags.class');
		$publi=new bcm_tags();
		
		$pub=$this->get('tags');
		
		$display=
		'
		<div class="publication_tags"  id="tags_'.$this->get('id').'">
		';
		
		foreach($pub as $p)
		{
			$publi->load_map($p);
			$display.=$publi->display_small();
		}
		
		
		$display=
		'
		</div>
		';
		
		return $display;
	}
	
	function display_full()
	{
		$display=
		'
		<div class="publication_full" id="full_'.$this->get('id').'">
			<h1>'.$this->display_title().'</h1>
			'.$this->display_content().'
			'.$this->display_tags().'
		</div>
		';
		return $display;
	}
}


?>
