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
	private $tables = array();
	private $bdUser;
	private $bdPassWord;
	private $bdDataBase;
	private $bdServer;
	private $connexion;
	private $estConnecte;
	public $queries_trace=array();

		/*
		 * Constructeur
		 */
	function Bdd()
	{
		$this->bdUser = "root";
		$this->bdPassWord = "satelite";
		$this->bdDataBase = "Balsa";
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
			if(trace_mod()==true)
			{
				add_trace('bdd connexion;'.$this->bdUser.'@'.$this->bdServer.':'.$this->bdDataBase.';'.time());
			}
			
			return true;
		}
		catch (PDOException $e)
		{
			report_erreur2('1000',__FILE__,__LINE__,'bdd unable to connect');
			return "Database connection error : ".$e->getMessage();
		}
	}
	
		/*
		 * Se déconnecte de la base de donnée
		 */
	public function disconnect()
	{
		$this->connexion = null;
		$this->estConnecte = false;
		if(trace_mod()==true)
		{
			add_trace('bdd deconnect;;'.time());
		}
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
				if(trace_mod()==true)
				{
					add_trace('bdd query;'.$requete.';'.time());
				}
#				plop($requete);
				if($reponse===false)
				{
					report_erreur2('1001',__FILE__,__LINE__,'bdd invalid request '.$requete);
					if(trace_mod()==true)
					{
						add_trace('bdd query error;');
					}
				}
				return $reponse;
		}
		else
		{
			report_erreur2('1002',__FILE__,__LINE__,'bdd trying to perform a request but not connected');
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
	
//back up functions made from http://www.phpkode.com/scripts/item/db-backup-class/
	function back_up()
	{
		if($this->estConnecte==false)
		{
			$this->connect();
		}
		
		$this->getTables();
		$this->generate_back_up();
	}
	
	function generate_back_up()
	{
		$sql_str='--CREATING TABLE '.$tbl['name']."\n";
		foreach ($this->tables as $tbl) 
		{
			$sql_str.= $tbl['create'] . ";\n\n";
			$sql_str.= '--INSERTING DATA INTO '.$tbl['name']."\n";
			$sql_str.= $tbl['data']."\n\n\n";
		}
		$sql_str.= '-- THE END'."\n\n";
		
		return $sql_str;
	}
	
	function get_tables()
	{
		try
		{
			$stmt = $this->query2('SHOW TABLES');
			$tbs = $stmt->fetchAll();
			$i=0;
			foreach($tbs as $table)
			{
				$this->tables[$i]['name'] = $table[0];
				$this->tables[$i]['create'] = $this->get_table_create($table[0]);
				$this->tables[$i]['data'] = $this->get_table_data($table[0]);
				$i++;
			}
			unset($stmt);
			unset($tbs);
			unset($i);

			return true;
		} 
		catch (PDOException $e) 
		{
			report_erreur2('1008',__FILE__,__LINE__,'get_tables '.$e->getMessage());
			return false;
		}
	}

	function get_table_create($name)
	{
		try
		{
			$stmt = $this->handler->query('SHOW CREATE TABLE '.$name);
			$q = $stmt->fetch();
			$q[1] = preg_replace("/AUTO_INCREMENT=[\w]*./", '', $q[1]);
			return $q[1];
		}
		catch (PDOException $e)
		{
			report_erreur2('1006',__FILE__,__LINE__,'get_table_create '.$e->getMessage());
			return false;
		}
	}

	
	function get_table_data($name)
	{
		try
		{
			$stmt = $this->query2('SELECT * FROM '.$name);
			$q = $stmt->fetchAll(PDO::FETCH_NUM);
			$data = '';
			foreach ($q as $pieces)
			{
				foreach($pieces as &$value)
				{
					$value = htmlentities(addslashes($value));
				}
				$data .= 'INSERT INTO '. $tableName .' VALUES (\'' . implode('\',\'', $pieces) . '\');'."\n";
			}
			return $data;
		} 
		catch (PDOException $e)
		{
			report_erreur2('1007',__FILE__,__LINE__,'get_table_data '.$e->getMessage());
			return false;
		}
	}
}
?>
