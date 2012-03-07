<?php
inclure_fonction('user.class');
$u=new user();
if(is_logged())
{
	$u->load($_SESSION['user_id']);
}

switch($_GET['do'])
{
	//unlogged function
	case'connection':
		echo $u->log_form().$u->inscription_form();
		break;
	case'inscription':
		if($u->insert_back_office('post')!=false)
		{
			$u->send_activation();
			echo'<div>Your inscription is almost complete, you now just have to activate your account by clicking on the link sent to you by mail.<br />Make sure you check your spam folder ;) .</div>';
		}
		break;
	case'log':
		echo $u->do_log();
		break;
	case'lost_form':
		echo $u->lost_mdp_form();
		break;
	case'lost_mdp':
		echo $u->lost_mdp();
		break;
	case'activate':
		$u->load($_GET['x']);
		if($u->activate($_GET['v'])!=false)
		{
			echo'<div>Your inscription is complete !! You are going to be redirected ;) .</div>';
		}
		else
		{
			echo'<div>An error occured during the activation process, our team have been alerted, sorry for the desagrement... :)</div>';
		}
		break;
	case'resend_activation':
		$u->load_from($_GET['login']);
		$u->send_activation();
		echo'<div>A new activation mail have been sent to you :).</div>';
		break;
	//logged function
	case'deco':
		if(is_logged())
		{
			session_destroy();
			echo'<script type="text/javascript">setTimeout("window.location.assign(base_url)",1000);</script><div>You Have been unlogged succesfully, you are going to be redirected...</div>';
		}
		else
		{
			echo $u->log_form().$u->inscription_form();
		}
		break;
	case'update':
		if(is_logged())
		{
			if($u->insert_back_office()!=false)
			{
				echo'<script type="text/javascript">setTimeout("window.location.assign(base_url+\'?page=user\')",1000);</script><div>You have succesfully updated your informations, you are going to be redirected...</div>';
			}			
		}
		else
		{
			echo'<div>You must be logged in to access this URL,<br />Please connect or create an account ! :)<br /></div>';
			echo $u->log_form().$u->inscription_form();
		}
		break;
	
	//default
	default:
		if(is_logged())
		{
			echo $u->custom_back_office(array('first_name','last_name','name','mail','avatar','website'));
		}
		else
		{
			echo $u->log_form().$u->inscription_form();
		}
		
		break;
}

?>
