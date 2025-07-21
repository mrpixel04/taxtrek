<?php
/**
*
*	@Author 		:		Frankis Ismail(Mrpixel)
*	@Title			:		Singleton Class DB
*	@Date/Time	:		5/03/2014
*	@Desc				:		Handling database connections by implements patterns Singleton
*	@Update			:		30/10/2017 - 10:40AM - Updating from MySql to MySqli Syntax due to PHP updating.

*
*/

//error_reporting(E_ALL ^ E_DEPRECATED);

include("constant.php");

class DataBase{

	/*=====================================================================================
		     Singleton Instance
	=======================================================================================*/
	private static $db_Instance;
	private $numRows;
	private $conndb;
	private $lastInsertID;

	/*
		@CLASS CONSTRUCTORS
	*/
	private function __construct(){
		//echo "Constructor call.....<br />\n";
		if(is_null(self::$db_Instance)){
			/*=====================================================================================
		                 connection database attemptions here
	         =======================================================================================*/
			$this->conndb = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, self::$db_Instance) or die("Could Not Connect to Database");
			//mssql_connect(DB_SERVER, DB_USER, DB_PASS) or die("Could Not Connect to Database");
			mysqli_select_db($this->conndb,DB_NAME) or die("Failed Selecting DB");
            /*=====================================================================================
		                 Stating object was instantiating here
	         =======================================================================================*/
			self::$db_Instance = "not null";
		}//end if
	}
	/*=====================================================================================
		                 Returns Singleton Database instance here :P
	=======================================================================================*/
	public static function getInstance(){
		if (!self::$db_Instance){
			self::$db_Instance = new DataBase();
		}

		return self::$db_Instance;
	}

   /*=====================================================================================
		                 Descontructor by NUll the DB instance
   =======================================================================================*/
   public function __destruct( ){
		if(isset(self::$db_Instance)){
			self::$db_Instance = NULL;
			mysqli_close($this->conndb);
		}//end if
	}
    /*=====================================================================================
		      For Add/Delete/Update opreations purpose (return Boolean)
    =======================================================================================*/
	public function executeOperation($Query_String){
		$Query_ID = mysqli_query($this->conndb,$Query_String) or die("Error");
		if(!$Query_ID){
			return false;
		}else{
			$this->lastInsertID = mysqli_insert_id($this->conndb);
			return true;
		}//end if..else
	}


	/*=====================================================================================
		    Return result in 2D array (returning Boolean)
    =======================================================================================*/
	public function executeGrab($Query_String){
		$Resource = mysqli_query($this->conndb,$Query_String);
		//echo gettype($Resource);
		if(is_object($Resource)){
			$DataSet = array();
				//turn mysql resource into 2D array and return
			while($Record = mysqli_fetch_array($Resource,MYSQLI_ASSOC)){
				    //push into 2D Array
				array_push($DataSet, $Record);
			}//end while
			return $DataSet;
		}else if(is_bool($Resource)){
			return false;
		}
		/*
		if(is_array($Resource)){
			if(mysqli_num_rows($Resource) > 0){
				$this->numRows = mysqli_num_rows($Resource);
				$DataSet = array();
				//turn mysql resource into 2D array and return
				while($Record = mysqli_fetch_array($Resource,MYSQLI_ASSOC)){
				    //push into 2D Array
					array_push($DataSet, $Record);
				}//end while
				return $DataSet;
			}
		}else if(is_bool($Resource)){
			return false;
		}
		*/
		
    }
	/*=====================================================================================
			Return single Row as Single Array
    =======================================================================================*/
	public function executeSingle($Query_String){

		$Resource = mysqli_query($this->conndb,$Query_String);

		if(is_object($Resource)){
			//echo "object";
			$this->numRows = mysqli_num_rows($Resource);
			if($this->numRows>0){
				$DataRow = mysqli_fetch_array($Resource,MYSQLI_ASSOC);
				return $DataRow;
			}else if($this->numRows == 0){
				//echo "tiada data";

				return false;
			}

		}else if(is_bool($Resource)){
			//echo "bool";
			return false;
		}

		/*
		if(mysqli_num_rows($Resource) > 0){
			//echo "ada rekod dan match";
			//turn mysql resource into an array
			$this->numRows = mysqli_num_rows($Resource);
			$DataRow = mysqli_fetch_array($Resource,MYSQLI_ASSOC);
			return $DataRow;
		}else if(mysqli_num_rows($Resource) == 0){
			//echo "return row tp tk match";
			return false;
		}else if(is_bool($Resource)){
			//echo "error truh boh";
			return false;
		}
		*/
		
	}

	public function returnOperation($Query_String){
		$Resource = mysqli_query($Query_String);
		return $Resource;
	}

	public function getNumRow(){
		return $this->numRows;
	}

	public function getLastInsertID(){
		return $this->lastInsertID;
	}
}

?>
