public function creat_db_Balsa($db_name='Balsa',$crea_db=true)
	{
		try
		{
			if ($this->bdPassWord=="")
				$this->connexion=new PDO('mysql:host='.$this->bdServer,$this->bdUser);
			else
				$this->connexion=new PDO('mysql:host='.$this->bdServer,$this->bdUser,$this->bdPassWord);
			$this->estConnecte = true;
			if($crea_db)
			{			
				$req='CREATE DATABASE `'.$db_name.'` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;';
        $res=$this->query2($req);

        $req1=
        '
        CREATE TABLE `'.$db_name.'`.`admin` (`id` VARCHAR( 20 ) NOT NULL , `login` VARCHAR( 128 ) NOT NULL ,`mail` VARCHAR( 512 )NOT NULL ,`pass` TEXT NOT NULL) ENGINE = MYISAM ;
        ';
        $res=$this->query2($req1);
      }
      $this->chgParam($db_name);
			return true;
		}
		catch (PDOException $e)
		{
			return "Erreur lors de la connection : ".$e->getMessage();
		}
	}
		/*
		 * Se connecte à la base de donnée
		 */
	public function connect()
	{
		try
		{
			if ($this->bdPassWord=="")
				$this->connexion=new PDO('mysql:host='.$this->bdServer.';dbname='.$this->bdDataBase,$this->bdUser);
			else
				$this->connexion=new PDO('mysql:host='.$this->bdServer.';dbname='.$this->bdDataBase,$this->bdUser,$this->bdPassWord);
			$this->estConnecte = true;
			return true;
		}
		catch (PDOException $e)
		{
			return "Erreur lors de la connection : ".$e->getMessage();
		}
	}
	
		/*
		 * Se déconnecte de la base de donnée
		 */
	public function disconnect()
	{
		$this->connexion = null;
		$this->estConnecte = false;
	}
	
		/*
		 * Exécute une requete
		 */
	public function query($requete)
	{
		if($this->estConnecte)
		{
				$reponse = $this->connexion->query($requete);
				return $reponse;
		}
		else
		{
			return false;
		}
	}
	
	public function query2($requete)
	{
		if($this->estConnecte)
		{
				$microin=microtime(true);
				$reponse = $this->connexion->query($requete);
				$microout=microtime(true);
				$this->reqtime=($microout-$microin)+$this->reqtime;
				$this->nbreq++;
				if($reponse===false)
				{
					if(isset($_SESSION['sql_err']))
					{
						$nbsqlerr=count($_SESSION['sql_err']);
						$_SESSION['sqi_err'][$nbsqlerr]=$requete;
					}
					else
					{
						$_SESSION['sql_err'][0]=$requete;
					} 
					
				}
				return $reponse;
		}
		else
		{
			return false;
		}
	}
	
	/*public function get_stats()
	{
		$stats = array('nb'=>$this->nbreq,'reqtime'=$this->reqtime);
		return $stats;
	}
		
		 * Change les parametres
		 */
	public function chgParam($dbName,$host="",$user="",$pass="")
	{
		$this->disconnect();
		$this->bdDataBase=$dbName;
		
		if($host != "")
		{
			$this->bdUser=$user;
			$this->bdPassWord=$pass;
			$this->bdServer=$host;
		}
		
		return $this->connect();
	}
	
	public function get_primkey()
	{
		$key=time();
		for($i=0;$i<2;$i++)
		{
			$k=rand(48 ,57);
			$key.=chr($k);
		}
		for($i=0;$i<5;$i++)
		{
			$k=rand(97 ,122);
			$key.=chr($k);
		}
		return $key;
	}
}
?>

