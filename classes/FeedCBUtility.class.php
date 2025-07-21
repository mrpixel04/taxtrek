<?php

/**
*
*	@Author			:	Frankis Ismail Mrpixel
*	@Title			:	FeedCBUtility utility class
*	@Date/time		:	Tuesday 16/04/2011
*	@Update			:	Thursday 12/04/2012
*	@Description	: 	Class Utility for feed combobox list 
*						Usage ;  
*
*							FeedCBUtility::feedCBWithValue('your_desired_tablename',array("field1","field2"),'your_cb_instance_name','options others');
*
*						Example;
*
*							FeedCBUtility::feedCBWithValue('your_desired_tablename',array("CODE","DESCRIPTION"),'your_cb_instance_name'); --> no options others
*
*							FeedCBUtility::feedCBWithValue('your_desired_tablename',array("CODE","DESCRIPTION"),'your_cb_instance_name',true); --> have options others
*
*/

//include("Utility.class.php");


class FeedCBUtility{
	private $db;

	public static function feedCBEditBookStatus($val){
			
			$arrstatpay = array("ACTIVE","DEACTIVATE");

			$len = count($arrstatpay);

			echo "<select name='CBSTATUSACTIVATION' id='CBSTATUSACTIVATION' class='form-control'>\n";
						echo "<option>-- Please select --</option>\n";
						for($i=0;$i<$len;$i++){
							if($val == $arrstatpay[$i]){
								echo "<option value='".$arrstatpay[$i]."' selected='selected'>".$arrstatpay[$i]."</option>\n";		
							}else{
								echo "<option value='".$arrstatpay[$i]."'>".$arrstatpay[$i]."</option>\n";		
							}
						}
			echo "</select>\n"; 
			
	}//end func

	public static function feedCBEditCatStatus($val){
			
			$arrstatpay = array("ACTIVE","DEACTIVATE");

			$len = count($arrstatpay);

			echo "<select name='CBSTATUSACTIVATION' id='CBSTATUSACTIVATION' class='form-control'>\n";
						echo "<option>-- Please select --</option>\n";
						for($i=0;$i<$len;$i++){
							if($val == $arrstatpay[$i]){
								echo "<option value='".$arrstatpay[$i]."' selected='selected'>".$arrstatpay[$i]."</option>\n";		
							}else{
								echo "<option value='".$arrstatpay[$i]."'>".$arrstatpay[$i]."</option>\n";		
							}
						}
			echo "</select>\n"; 
			
	}//end func



	public static function feedCBEditCategory($val){
			
		$db = DataBase::getInstance();
		if(is_object($db)){
			$sql = "SELECT * FROM ".TBL_CAT;
			$row = $db->executeGrab($sql);
			if(is_array($row)){

				$len = count($row);
				echo "<select name='CBCAT' id='CBCAT' class='form-control'>\n";
					
					for($i=0;$i<$len;$i++){
						if($val == $row[$i]['catn']){
							echo "<option value='".$row[$i]['catn']."' selected='selected'>".$row[$i]['catn']."</option>\n";	
						}else{
							echo "<option value='".$row[$i]['catn']."'>".$row[$i]['catn']."</option>\n";
						}
					}
				
			}						
				echo "</select>\n";	
		}
			
	}//end func

	public static function feedCBEditPaymentStatus($val){
			
			$arrstatpay = array("PAID","NOT PAID");

			$len = count($arrstatpay);

			echo "<select name='CBSTATUSPAID' id='CBSTATUSPAID' class='form-control'>\n";
						echo "<option>-- Please select --</option>\n";
						for($i=0;$i<$len;$i++){
							if($val == $arrstatpay[$i]){
								echo "<option value='".$arrstatpay[$i]."' selected='selected'>".$arrstatpay[$i]."</option>\n";		
							}else{
								echo "<option value='".$arrstatpay[$i]."'>".$arrstatpay[$i]."</option>\n";		
							}
						}
			echo "</select>\n"; 
			
	}//end func


	public static function feedCBWithValue($tblname,array $opsnvalfield,$cbname,$optothers=false){
		
		$db = DataBase::getInstance();
		if(is_object($db)){
			$sql = "SELECT * FROM ".$tblname;
			$row = $db->executeGrab($sql);
			if(is_array($row)){

				$len = count($row);
				echo "<select name='$cbname' id='$cbname' class='form-control'>\n";
				echo "<option>-- Sila pilih --</option>\n";
				for($i=0;$i<$len;$i++){
					echo "<option value='".$row[$i][$opsnvalfield[0]]."'>".$row[$i][$opsnvalfield[1]]."</option>\n";	
				}
				
			}
		
			echo ($optothers)?"<option>Lain-lain</option>\n":'';
						
			echo "</select>\n";	
		}

	}

	public static function feedCBCatWithValue($tblname,array $opsnvalfield,$cbname,$optothers=false){
		
		$db = DataBase::getInstance();
		if(is_object($db)){
			$sql = "SELECT * FROM ".$tblname;
			$row = $db->executeGrab($sql);
			if(is_array($row)){

				$len = count($row);
				echo "<select name='$cbname' id='$cbname' class='form-control'>\n";
				echo "<option>Category...</option>\n";
				for($i=0;$i<$len;$i++){
					echo "<option value='".$row[$i][$opsnvalfield[0]]."'>".$row[$i][$opsnvalfield[1]]."</option>\n";	
				}
				
			}
		
			echo ($optothers)?"<option>Others</option>\n":'';
						
			echo "</select>\n";	
		}

	}



	
	public static function feedCBEdit($tblname,array $arrtmp,$cbname){
			
			$db = DataBase::getInstance();

			if(is_object($db)){

				//echo $arrtmp[0];

				$flddb1 = $arrtmp[0];
				$flddb2 = $arrtmp[1];
				$fldvalpass = $arrtmp[2];

				
				$querygrab = "SELECT ".$flddb1.",".$flddb2." FROM ".$tblname;

				//echo $querygrab;
				
				$row = $db->executeGrab($querygrab);
				if(is_array($row)){
					$len = count($row);
					echo "<select name=\"{$cbname}\" id=\"{$cbname}\" class='form-control'>\n";
						echo "<option>-- Please select --</option>\n";
						for($i=0;$i<$len;$i++){
							//echo "VALUE PASS ".(int)$fldvalpass." : VALUE DB ".$row[$i][$flddb1];
							if((int)$fldvalpass == $row[$i][$flddb1]){
								echo "<option value='".$row[$i][$flddb1]."' selected='selected'>".$row[$i][$flddb2]."</option>\n";		
							}else{
								echo "<option value='".$row[$i][$flddb1]."'>".$row[$i][$flddb2]."</option>\n";		
							}							
						}
					echo "</select>\n"; 
				
				}		
				
			}//end if
			
	}//end function






}
/* end class FeedCBUtility */


						
?>
