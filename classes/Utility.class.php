<?php

/*
	

	@Author			:   		Frankis Ismail (Mrpixel)
	@Date			:			03 May 2016


*/



class Utility{
	private $db;

	
	public static function getUserDetailsByID($id){
		$data;

		$db = DataBase::getInstance();
		
		if(is_object($db)){

			$selsql = "SELECT * FROM ".TBL_USERS." WHERE iduser=".(int)$id;
			$row = $db->executeSingle($selsql);
			if(is_array($row)){
				//echo "YUHUU";
				$data =  $row['iduser']."|".$row['fn']."|".$row['hp']."|".$row['e']."|".$row['co']."|".$row['ulevel']."|".$row['upass'];	
			}
		}

		return $data;
	}

	public static function getCatDetailsByID($id){
		$detailsstr;

		$db = DataBase::getInstance();
		
		if(is_object($db)){

			$selsql = "SELECT * FROM ".TBL_KATEGORI." WHERE idcat=".(int)$id;
			$row = $db->executeSingle($selsql);
			if(is_array($row)){
				$detailsstr = $row['idcat']."|".$row['cn'];
			}
		}

		return $detailsstr;
	}

	public static function getDefaultAddr($id){
		$data;

		$db = DataBase::getInstance();
		
		if(is_object($db)){

			$selsql = "SELECT * FROM ".TBL_ADDRESS." WHERE ins_by=".(int)$id." AND is_set_default=1";
			$row = $db->executeSingle($selsql);
			if(is_array($row)){
				
				$data =  $row['idaddr']."|".$row['addr']."|".$row['postcode']."|".$row['state'];	
			}else if(is_bool($row)){
				$data = null;
			}
		}

		return $data;
	}



}


?>