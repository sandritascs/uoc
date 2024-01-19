<?php


/**
 * DB
 * @copyright Wilowi
 * @version 1.0.0.0
 * @since 29/05/2020
 * @name DBConnector
 * @author Wilowi - Sandra Campos
 *
 */

class dbConnector{
	
	private $dbServer='';
	private $dbUser='';
	private $dbPassword='';
	private $dbName='';
	private $dbLink='';	
	private $root='';
	
	/**
	 * Construct
	 */
	public function __construct(){

		$this->root = dirname(__FILE__).'/../db.config';

		$this->assignDBData();		
	}
	

	/**
	 * Destructor de la clase
	 *
	 */
	public function __destruct()
	{
		
	}

	/**
	 * Execute the query
	 * @param string $query
	 * @param string $tipo
	 * @return string
	 */
	public function query($query,$tipo){

		$resultado = '';
		switch ($tipo) {
			case 'select':
				$resultado = $this->querySelect($query);
			break;
			
			case 'insert':
				$resultado = $this->queryInsert($query);
			break;
			
			case 'update':
				$resultado = $this->queryUpdate($query);
			break;
			
			case 'delete':
				$resultado = $this->queryDelete($query);
			break;
			
			default:
				$resultado = '';
			break;
		}
		
		return $resultado;
	}
	
	/**
	 * Escape character
	 * @param unknown $cadena
	 * @return unknown
	 */
	public function escapeChar($cadena){

		$result = '';
		
		try {
			$result = mysqli_real_escape_string($this->dbLink, $cadena);
		} catch (Exception $e) {
			throw new Exception('ERROR - '.__METHOD__.' : Error on Escape character => '.$e->getMessage(),1);
		}
		//$result = mysqli_real_escape_string($this->dbLink, $cadena);
		return $result;
		
	}
	
	public function setAutoCommit($value = true) {

		try {
			mysqli_autocommit($this->dbLink, $value);
		} catch (Exception $exc) {
			echo $exc->getTraceAsString();
		}
	}
	
	public function commit(){
		
		mysqli_commit($this->dbLink);
	}
	
	public function rollBack(){
		mysqli_rollback($this->dbLink);
	}
		
	/**
	 * Assign the values to connect DB
	 */
	private function assignDBData(){

		$file = json_decode(file_get_contents($this->root));

		$this->dbName = $file->name;
		$this->dbPassword = $file->password;
		$this->dbServer = $file->server;
		$this->dbUser = $file->user;
		
		try {
			$this->createLink();
			
		} catch (Exception $e) {
			echo $e->getMessage();
			echo $this->root;
			echo "Connection DB Error";
			return false;
		}
		
		
	}
	
	/**
	 * Create connection msqli
	 * @throws Exception
	 */
	private function createLink(){		
		
		$this->dbLink=new mysqli($this->dbServer,$this->dbUser,$this->dbPassword,$this->dbName);
		mysqli_select_db($this->dbLink, $this->dbName);
		//echo print_r($this->dbLink);
		if (mysqli_connect_error()){
			throw new Exception('ERROR - '.__METHOD__.' : It was not possible to connect the DB => '.mysqli_connect_error(),1);
		}
	}
	
	
	/**
	 * Execute query select
	 * @param string $query
	 * @throws Exception
	 * @return Ambigous <multitype:, multitype:unknown >
	 */
	private function querySelect($query){		
		
		$resulta=array();
		$cont = 0;

		$QAux=$this->dbLink->query($query);
		if(!$QAux){
			return false;
		}
		
		if(!$QAux->num_rows>0){
			$resulta=array();
		}
		
		while($RAux=$QAux->fetch_object()){
			$resulta[$cont]=$RAux;
			
			$cont++;
		}
		
		//$QAux->free();
		$QAux->close();
		//var_dump($resulta);
		return $resulta;
	}
	
	/**
	 * Execute query Insert
	 * @param string $query
	 * @throws Exception
	 * @return boolean
	 */
	private function queryInsert($query){	
			
		$QAux = $query;
		$resulta = '';
	
		if(!$QAux=$this->dbLink->query($QAux)){
			//echo $query;
            //echo "ERROR: ".$this->dbLink->error;
			return false;
		}

		$resulta = mysqli_insert_id($this->dbLink);
		
		//$QAux->free();
		//$QAux->close();
		return $resulta;
	}
	
	/**
	 * Execute query update
	 * @param unknown $query
	 * @throws Exception
	 * @return boolean
	 */
	private function queryUpdate($query){

		$QAux = $query;
	
		if(!$QAux=$this->dbLink->query($QAux)){
			return false;
		}
		
		//$QAux->free();
		//$QAux->close();

		return true;
	}
	
	/**
	 * Execute query Delete
	 * @param string $query
	 * @throws Exception
	 * @return boolean
	 */
	private function queryDelete($query){

		$QAux = $query;
			
		if(!$QAux=$this->dbLink->query($QAux)){
			return false;
		}
	
		return true;

	}
	
	

}
?>
