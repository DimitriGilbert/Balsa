<?php
inclure_fonction('entity.class');

class bcm_comment extends bdd_entity
{
	
	function __construct($id='')
	{
		parent::__construct(array('id','publication','content','user','parution','status'),'Comments','id','bcm_');
		
		$this->BO_field['content']='Enter your comment ';
		
		$this->BO_type['id']='none';
		$this->BO_type['parution']='none';
		$this->BO_type['user']='none';
		$this->BO_type['content']='textarea';
		$this->BO_type['status']='radio';
		
		$this->BO_value['status']['labels']=array('Censored','Published');
		$this->BO_value['status']['values']=array('Censored','Published');
				
		$this->BO_check['content']['empty']=false;	
		
		$this->BO_search['content']='_like_';
		
		$this->BO_manage['map']=array();
		$this->BO_manage['map'][0]='publication';
		$this->BO_manage['map'][0]='user';
		
		
		if($id!='')
		{
			$this->load($id);
		}
	}
}


?>
