<?php

/*

	@Author 	: 	FRANKIS BIN ISMAIL (Mrpixel)
	@Date/Time 	:	15/05/2014 {02.00PM}


*/


class RandomStringUtils{
	
	
	public static function randomString($len){
		$key = '';
    	$keys = array_merge(range(0, 9), range('a', 'z'));

    	for ($i = 0; $i <$len; $i++) {
        	$key .= $keys[array_rand($keys)];
    	}
		return $key;
	}
	
}



?>