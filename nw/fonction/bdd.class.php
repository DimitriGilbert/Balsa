<?php
/*
 *	Connexion à une base de donnée avec pdo
 *  var :
 *    bdUser : nom d'utilisateur de la base de donnée
 *    bdPassWord : mot de passe
 *    bdDataBase : nom de la base de donnée
 *    bdServer : serveur de la bdd
 *  fonc :
 *    Bdd() : constructeur
 *    connect() : effectue une connexion (retourn true si ok, sinon msg d'erreur)
 *    disconnect() : deconnecte la bdd
 *    query(requete) : lance la requete (retourne un tableau si ok, sinon false)
 *    chgParam(dbName,^host,^user,^pass) : change les parametres de la connexion (var avec ^ optionnelles)
 */

class Bdd
{
	private $bdUser;
	private $bdPassWord;
	private $bdDataBase;
	private $bdServer;
	private $connexion;
	private $estConnecte;

		/*
		 * Constructeur
		 */
	function Bdd()
	{
		$this->bdUser = "plop";
		$this->bdPassWord = "azerty";
		$this->bdDataBase = "plop";
		$this->bdServer = "localhost";
		$this->estConnecte = false;
		$this->nbreq=0;
		$this->reqtime=0;
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
