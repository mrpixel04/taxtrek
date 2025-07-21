<?php

/*

	@Date			:			11 January 2016


*/

date_default_timezone_set('Asia/kuala_lumpur');




class UtilityGetRecord{
	private $db;

	public static function getTotalUser(){
		$db = DataBase::getInstance();
		
		if(is_object($db)){
			$selectsql = "SELECT * FROM ".TBL_USERS." WHERE userlevel='CUSTOMER'";
			$row = $db->executeGrab($selectsql);
			
			if(is_array($row)){
				$count = count($row);
			}else if(is_bool($row)){
				$count = 0;
			}
		}
		return $count;
	}

	public static function getTotalOrders(){
		$db = DataBase::getInstance();
		
		if(is_object($db)){
			$selectsql = "SELECT * FROM ".TBL_ORDERS;
			$row = $db->executeGrab($selectsql);
			
			if(is_array($row)){
				$count = count($row);
			}else if(is_bool($row)){
				$count = 0;
			}
		}
		return $count;
	}

	public static function getTotalDocIn(){
		$db = DataBase::getInstance();
		
		if(is_object($db)){
			$selectsql = "SELECT * FROM ".TBL_MASTER_DOC." WHERE doc_type=1";
			$row = $db->executeGrab($selectsql);
			
			if(is_array($row)){
				$count = count($row);
			}else if(is_bool($row)){
				$count = 0;
			}
		}
		return $count;
	}

	public static function getTotalDocOut(){
		$db = DataBase::getInstance();
		
		if(is_object($db)){
			$selectsql = "SELECT * FROM ".TBL_MASTER_DOC." WHERE doc_type=2";
			$row = $db->executeGrab($selectsql);
			
			if(is_array($row)){
				$count = count($row);
			}else if(is_bool($row)){
				$count = 0;
			}
		}
		return $count;
	}


	public static function getTotalFiles(){
		$db = DataBase::getInstance();
		
		if(is_object($db)){
			$selectsql = "SELECT * FROM ".TBL_FILES;
			$row = $db->executeGrab($selectsql);
			
			if(is_array($row)){
				$count = count($row);
			}else if(is_bool($row)){
				$count = 0;
			}
		}
		return $count;
	}

	public static function getDocTotalFiles(){
		$db = DataBase::getInstance();
		
		if(is_object($db)){
			$selectsql = "SELECT * FROM ".TBL_FILING_DOC;
			$row = $db->executeGrab($selectsql);
			
			if(is_array($row)){
				$count = count($row);
			}else if(is_bool($row)){
				$count = 0;
			}
		}
		return $count;
	}

	public static function getTotalProjector(){
		$db = DataBase::getInstance();
		
		if(is_object($db)){
			$selectsql = "SELECT * FROM ".TBL_PROJECTOR." WHERE conditions=1";
			$row = $db->executeGrab($selectsql);
			
			if(is_array($row)){
				$count = count($row);
			}else if(is_bool($row)){
				$count = 0;
			}
		}
		return $count;
	}

	public static function getTotalComplaint(){
		$db = DataBase::getInstance();
		
		if(is_object($db)){
			$selectsql = "SELECT * FROM ".TBL_COMPLAINTS;
			$row = $db->executeGrab($selectsql);
			
			if(is_array($row)){
				$count = count($row);
			}else if(is_bool($row)){
				$count = 0;
			}
		}
		return $count;
	}

	public static function getTotalBooking(){
		$db = DataBase::getInstance();
		
		if(is_object($db)){
			$selectsql = "SELECT * FROM ".TBL_BOOKINGS;
			$row = $db->executeGrab($selectsql);
			
			if(is_array($row)){
				$count = count($row);
			}else if(is_bool($row)){
				$count = 0;
			}
		}
		return $count;
	}


	
}






?>