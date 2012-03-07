<?php
inclure_fonction('entity.class');

class user extends bdd_entity
{
	
	function __construct($id='')
	{
		global $path_w;
		parent::__construct(array('id','first_name','last_name','name','pass','mail','title','status','inscription','level','valid','avatar','website'),'Users');
				
		$this->BO_field['first_name']='Enter your first name ';
		$this->BO_field['last_name']='Enter your last name ';
		$this->BO_field['name']='Enter your display name ';
		$this->BO_field['pass']='Enter your password ';
		$this->BO_field['mail']='Enter your mail adress ';
		$this->BO_field['title']='Enter your title ';
		$this->BO_field['avatar']='Enter your avatar URL or upload image ';
		$this->BO_field['website']='Enter your website URL ';
		
		$this->BO_type['id']='none';
		$this->BO_type['level']='none';
		$this->BO_type['status']='none';
		$this->BO_type['valid']='none';
		$this->BO_type['pass']='password';
		$this->BO_type['inscription']='none';
		$this->BO_type['avatar']='upload';
		
		$this->BO_value['pass']='Confirm your password ';
		$this->BO_value['avatar']['max_size']=500;
		$this->BO_value['avatar']['target']=$path_w.'media/img/avatar/';
		$this->BO_value['avatar']['name']=$path_w.'media/img/avatar/';
		$this->BO_value['avatar']['keep_ext']=true;
				
		$this->BO_check['name']['empty']=false;	
		$this->BO_check['pass']['empty']=false;
		$this->BO_check['mail']['empty']=false;		
		
		$this->BO_search['title']='_like_';
		$this->BO_search['name']='_like_';
		$this->BO_search['first_name']='_like_';
		$this->BO_search['last_name']='_like_';
		$this->BO_search['mail']='_like_';
		
		$this->BO_manage['map']=array();
		$this->BO_manage['map'][0]='name';
		
		if($id!='')
		{
			$this->load($id);
		}
	}
	
	function load($id)
	{
		$return=parent::load($id);
		$this->BO_value['avatar']['name']=$id;
		return $return;
	}
	
	function insert($map='')
	{
		if($map=='')
		{
			$map=$this->mapped;
		}
		if(!isset($map['name']) or $map['name']=='')
		{
			$map['name']=$map['first_name'].' '.$map['last_name'][0];
		}
		return parent::insert($map);
	}
	
	function mail($subject,$message,$from,$reply)
	{
		inclure_fonction('mail');
		return mail_de_base($this->get('mail'),$subject,$message,$from,$reply);
	}
	
	function desactivate($str,$save=true)
	{
		$set=$this->set('valid',$str);
		if($save==true)
		{
			return $this->save();
		}
		else
		{
			return $set;
		}		
	}
	
	function activate($str,$save=true)
	{
		if($str==$this->get('valid') and $str!='-1')
		{
			$this->set('valid',1);
			if($save==true)
			{
				return $this->save();
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}		
	}
	
	function is_active($id='')
	{
		if($id!='')
		{
			$this->load($id);
		}
		if($this->get('valid')!='1')
		{	
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function load_from($v,$from='mail',$load=true)
	{
		global$bdd;
		$req='select * from '.$this->table.' where '.$from.'="'.$v.'"';
		$res=$bdd->query2($req)->fetchAll();
		if(count($res)>1)
		{
			report_erreur2('3100',__FILE__,__LINE__,'load_from_mail multiple result for '.$from.' : '.$v);
			return false;
		}
		else
		{
			if(count($res)==0)
			{
				return false;
			}
			else
			{
				if($load==true)
				{
					$this->load_map($res[0]);
				}
				
				return true;
			}			
		}
	}
	
	function is_unique($v,$from='mail')
	{
		if(load_from($v,$from,false)==true)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function inscription_form($map=array('first_name','last_name','mail','pass'),$wrap=true)
	{
		global $base_url;
		if($wrap==true)
		{
			$display='
		<div class="user_inscription_form" id="user_inscription_form">
			<form method="post" action="'.$base_url.'?page=user&do=inscription">';
		}
		
		$display.=$this->custom_back_office($map,'',false);
		
		if($wrap==true)
		{
			$display.='
				<div class="'.$this->BO_value['form_div_class'].'">
					<input type="submit" value="Create your account !" />
				</div>
			</form>
		</div>';
		}
		
		return $display;
	}
	
	function log_form($login='mail',$wrap=true)
	{
		global $base_url;
		if($wrap==true)
		{
			$display='
		<div class="user_log_form" id="user_log_form">
			<form method="post" action="'.$base_url.'?page=user&do=log">';
		}
		if(isset($_SESSION['connect_try']))
		{
			$try=$_SESSION['connect_try']['value'];
		}
		else
		{
			$try=0;
		}
		$display.=
		'
				<div class="'.$this->BO_value['form_label_class'].'">
					<label class="'.$this->BO_value['form_div_class'].'">'.$this->BO_field[$login].'</label>
					<input type="text" name="user_log_login" id="user_log_login" />
				</div>
				<div class="'.$this->BO_value['form_div_class'].'">
					<label class="'.$this->BO_value['form_label_class'].'">'.$this->BO_field['pass'].'</label>
					<input type="text" name="user_log_pass" id="user_log_pass" />
				</div>
				<input type="hidden" name="user_log_try" id="user_log_try" value="'.$try.'" />
		';
		
		if($wrap==true)
		{
			$display.='
				<div class="'.$this->BO_value['form_div_class'].'">
					<input type="submit" value="Connect !" />
				</div>
			</form>
		</div>';
		}
		
		return $display;
	}
	
	function do_log($met='post',$from='mail')
	{
		global$bdd,$base_url;
		if($met=='post')
		{
			$met=$_POST;
		}
		else
		{
			$met=$_GET;
		}
		$this->load_from($met['user_log_login']);
		if($this->is_active()==true)
		{
			if(isset($_SESSION['connect_try']))
			{
				if($_SESSION['connect_try']['login']!=$met['user_log_login'])
				{
					$_SESSION['connect_try']=array('value'=>'0','login'=>$met['user_log_login'],'status'=>true);
				}
				
				if($_SESSION['connect_try']['value']!=(int)$met['user_log_try'] and $_SESSION['connect_try']['login']==$met['user_log_login'])
				{
					report_erreur2('666_a',__FILE__,__LINE__,'/!\ Intrusion detection ! /!\ unmatching user_log_try during login attempt ! IP : '.$_SERVER['REMOTE_ADDR'].' ; time : '.date('d-m-Y h:i:s').' ; login : '.$met['user_log_login']);
				
					$reac=md5($bdd->get_primkey());
					$this->desactivate($reac,true);
				
					$message=
					'
					A suspicious activity have been detected on your account on <a href="'.$base_url.'">'.$base_url.'</a>
					<br /><br />
					To prevent any damage to your account, it has been suspended, you can reactivate it through the following link :<a href="'.$base_url.'?page=user&do=activate&v='.$reac.'&q='.md5($bdd->get_primkey()).'&x='.$this->get('id').'">'.$base_url.'?page=user&do=activate&v='.$reac.'&q='.md5($bdd->get_primkey()).'&x='.$this->get('id').'</a>
					<br /><br />
					For your information, the following informations have been recorded:
					<br />
					IP adresse of the try : '.$_SERVER['REMOTE_ADDR'].'
					<br />
					Time of the try : '.date('d-m-Y h:i:s').'
					<br />
					Last password tried : '.$_POST['user_log_pass'].'
					<br /><br />
					We hope this protection didn\'t cause you any trouble :)
					<br />
					See you soon !!
					<br /><br />
					The team of <a href="'.$base_url.'">'.$base_url.'</a>
					<br />
					';
					$this->mail('Suspicious activity on your account !',$message,'security@'.$base_url,'no_reply@'.$base_url);
					$_SESSION['connect_try']['status']=false;
				}
			
				if($_SESSION['connect_try']['value']>=5 and $_SESSION['connect_try']['login']==$met['user_log_login'])
				{
					$_SESSION['connect_try']['status']=false;
					$failed_str='<failed date="'.time().'" ip="'.$_SERVER['REMOTE_ADDR'].'" />';
					$this->set('failed_log',$failed_str);
					$this->save();
				}
			}
			else
			{
				$_SESSION['connect_try']=array('value'=>0,'login'=>$met['user_log_login'],'status'=>true);
			}
		
			if($_SESSION['connect_try']['status']!=false)
			{
#				plop('PASS BDD');
#				plop($this->get('pass'));
#				plop('entered');
#				plop(hash('sha512',$met['user_log_pass']));
				if(hash('sha512',$met['user_log_pass'])==$this->get('pass'))
				{
					unset($_SESSION['connect_try']);
					$_SESSION['user_id']=$this->get('id');
					$_SESSION['user_info']=array('level'=>$this->get('level'));
					return '<script type="text/javascript">setTimeout("window.location.assign(base_url)",1000);</script><div>You Have been logged in succesfully, you are going to be redirected...</div>';
				}
				else
				{
					
#				plop('wrong pass !');
#				plop('PASS BDD');
#				plop($this->get('pass'));
#				plop('entered');
#				plop(hash('sha512',$met['user_log_pass']));
					$_SESSION['connect_try']['value']++;
				}
			}
			return $this->log_form($from,true);
		}
		else
		{
			if($_SESSION['connect_try']['status']!=false)
			{
				return '<div>Your account has not been validated !<br />Please check your inbox for our activation mail and do not forget your spam folder ;) . In case you would not have received it, please click <a href="'.$base_url.'?page=user&do=resend_activation&login='.$met['user_log_login'].'">here</a>.</div>';
			}
			else
			{
				return $this->log_form($from,true);
			}
			
		}	
	}
	
	function send_activation()
	{
		global$bdd,$base_url;
		$reac=md5($bdd->get_primkey());
		$this->desactivate($reac,true);
	
		$message=
		'
		You just created your account on <a href="'.$base_url.'">'.$base_url.'</a>
		<br /><br />
		The final step is to activate your account, you can do it through the following link :<a href="'.$base_url.'?page=user&do=activate&v='.$reac.'&q='.md5($bdd->get_primkey()).'&x='.$this->get('id').'">'.$base_url.'?page=user&do=activate&v='.$reac.'&q='.md5($bdd->get_primkey()).'&x='.$this->get('id').'</a>
		<br /><br />
		See you soon !!
		<br /><br />
		The team of <a href="'.$base_url.'">'.$base_url.'</a>
		<br />
		';
		return $this->mail('Activate your account on '.$base_url.' !',$message,'account@'.$base_url,'no_reply@'.$base_url);
	}
	
	function lost_mdp_form($wrap=true)
	{
		global$base_url;
		if($wrap==true)
		{
			$display='
		<div class="user_lost_form" id="user_lost_form">
			<form method="post" action="'.$base_url.'?page=user&do=lost_mdp">';
		}
		if(isset($_SESSION['connect_try']))
		{
			$try=$_SESSION['connect_try']['value'];
		}
		else
		{
			$try=0;
		}
		$display.=
		'
				<div class="'.$this->BO_value['form_label_class'].'">
					<label class="'.$this->BO_value['form_div_class'].'">'.$this->BO_field['mail'].'</label>
					<input type="text" name="user_lost_mail" id="user_lost_mail" />
				</div>
		';
		
		if($wrap==true)
		{
			$display.='
				<div class="'.$this->BO_value['form_div_class'].'">
					<input type="submit" value="Confirm !" />
				</div>
			</form>
		</div>';
		}
		
		return $display;
	}
	
	function lost_mdp($mail)
	{
		$return='';
		if($this->load_from($mail))
		{
			$pass='aqwCDE751';
			$message=
			'
			Hello,
			<br />
			You just requested a new password on <a href="'.$base_url.'">'.$base_url.'</a> .
			<br />
			Your new password is : '.$pass.' .
			<br />
			For information the request have been made on : '.date('d-m-Y h:i:s').'
			<br />
			By the following IP adresse'.$_SERVER['REMOTE_ADDR'].'
			<br />
			See you soon !!
			<br /><br />
			The team of <a href="'.$base_url.'">'.$base_url.'</a>
			<br />
			';
			
			if($this->mail('Your new password',$message,'lost_password@'.$base_url.'.com','no_reply@'.$base_url.'.com'))
			{
				$this->set('pass',$pass);
				if($this->save()!=false)
				{
					$return.='<div>A mail with your new password has been sent !<br /> Please check your inbox :) .</div>';
				}
				else
				{
					$return.='<div>Error during the process, please try again later...</div>';
				}
			}
			else
			{
				report_erreur2('3101',__FILE__,__LINE__,'lost_mdp : mail could not be sent to : '.$mail);
				$return.='<div>The mail could not be sent, please try again later...</div>';
			}
		}
		else
		{
			report_erreur2('3102',__FILE__,__LINE__,'lost_mdp : mail does not exist : '.$mail);
			$return.='<div>The following mail adress does not have an account : '.$mail.'</div>';
		}
		
		return $return;
	}
}

?>
