<?php

global $bcm;

class bcm
{
	public $publication,$category,$tags,$comment,$entity,$requested;
	
	function __construct()
	{
		$this->requested=array();
		$this->requested['publication']=array();
		$this->requested['publication_parents']=array();
		$this->requested['category']=array();
		$this->requested['category_childs']=array();
		$this->requested['tags']=array();
		$this->requested['comment']=array();
		
	}
	
	function init()
	{
		inclure_fonction('bcm_category.class');
		inclure_fonction('bcm_publication.class');
		inclure_fonction('bcm_tags.class');
		inclure_fonction('bcm_comment.class');
		$this->publication=new bcm_publication();
		$this->category=new bcm_category();
		$this->tags=new bcm_tags();
		$this->comment=new bcm_comment();
		$this->requested=array();
		
		switch($_GET['page'])
		{
			case'bcm_category':
				$this->entity=$this->category;
				break;
			case'bcm_publication':
				$this->entity=$this->publication;
				break;
			case'bcm_comment':
				$this->entity=$this->comment;
				break;
			case'bcm_tags':
				$this->entity=$this->tags;
				break;
			default:
				$this->entity=false;
				break;
		}
		
		if(isset($_GET['id']) and $this->entity!=false)
		{
			return $this->entity->load($_GET['id']);
		}
		else
		{
			return true;
		}
	}
	
	
	function get_requested($type,$id)
	{
		if(isset($this->requested[$type][$id]) and is_array($this->requested[$type][$id]))
		{
			return $this->requested[$type][$id];
		}
		else
		{
			return false;
		}
	}	
	
	function set_requested($type,$id,$map)
	{
		$this->requested[$type][$id]=$map;
		return true;
	}
	
	function get($k)
	{
		if($this->entity!=false)
		{
			return $this->entity->get($k);
		}		
	}
	
	function manage($url='dummy !')
	{
		global$base_url;
		$display=
		'
		<div class="bcm_BO_content">
			<div>
				<a href="'.$base_url.'?page=BO&w=home">Home</a>
			</div>
			<div>
				<a href="'.$base_url.'?page=BO&w=bcm_category">Categories</a>
			</div>
			<div>
				<a href="'.$base_url.'?page=BO&w=bcm_publication">Publications</a>
			</div>
			<div>
				<a href="'.$base_url.'?page=BO&w=bcm_comment">Comments</a>
			</div>
			<div>
				<a href="'.$base_url.'?page=BO&w=bcm_tags">Tags</a>
			</div>
		</div>
		';
		return $display;
	}
}

$bcm=new bcm();
$bcm->init();

function bcm_get_requested($type,$id)
{
#	global $bcm;
#	return $bcm->get_requested($type,$id);
	return false;
}

function bcm_set_requested($type,$id,$map)
{
#	global $bcm;
#	$res=$bcm->set_requested($type,$id,$map);
#	
#	return$res;
	return false;
}

?>
