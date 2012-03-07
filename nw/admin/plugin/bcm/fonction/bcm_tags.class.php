<?php
inclure_fonction('entity.class');

class bcm_tags extends bdd_entity
{
	public $publications;
	
	function __construct($id='')
	{
		parent::__construct(array('id','content','description'),'Tags','id','bcm_');
		
		$this->parents=array();
		
		$this->BO_field['content']='Enter the tag ';
		$this->BO_field['description']='Enter a description ';
		
		$this->BO_type['id']='none';
		$this->BO_type['content']='text';
		$this->BO_type['description']='textarea';
				
		$this->BO_check['content']['empty']=false;	
		
		$this->BO_search['content']='_like_';	
		
		$this->BO_manage['map']=array();
		$this->BO_manage['map'][0]='content';	
		
		if($id!='')
		{
			$this->load($id);
		}
	}
	
	function get_publications()
	{
		global $bdd;
		$req='select * from bcm_Publications P, bcm_publication_tag pp where P.id=pp.publication and pp.tag="'.$this->get($this->id_field).'"';
		$this->publications=$bdd->query2($req)->fetchAll();
		return $this->publications;
	}
}


?>
