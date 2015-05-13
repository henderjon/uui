<?php
/**
 * Method to create a compliant v5 UUID in accordance with:
 * https://tools.ietf.org/html/rfc4122
 *
 * @param string $n The name of the object the UUID will be used for
 * @param string $ns The namespace of the object the UUID will be used for
 * @return string
 */
function uuid5($n, $ns = "This is a namespace ..."){
	$ns_bytes = '';
	for ($i = 0; $i < strlen($ns); $i += 2) {
		$ns_bytes .= chr(hexdec($ns[$i] . $ns[$i+1]));
	}
	$hash = sha1($ns_bytes . $n);
	$uuid["time_low"] = substr($hash, 0, 8);
	$uuid["time_mid"] = substr($hash, 8, 4);
	$uuid["time_hi_and_version"] = ( hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000;
	$uuid["clk_seq_hi_res_variant"] = ( hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000;
	$uuid["node"] = substr($hash, 20, 12);
	return vsprintf("%08s-%04s-%04x-%04x-%12s", $uuid);
}
/**
 * Method to create a compliant v4 UUID in accordance with:
 * https://tools.ietf.org/html/rfc4122
 *
 * @param int $len The length of the random salt
 * @param string $charset The charset used to create the salt
 * @return string
 */
function uuid4($len = 32, $charset = ""){
	$str = ''; $i = 0;
	while( $i <= $len && ++$i ){
		$str .= chr( mt_rand(33, 126) );
	}
	$hash = sha1( gmdate("Y, d M H:i:s") . mt_rand(99, 99999999) . $str );
	$uuid["time_low"] = substr($hash, 0, 8);
	$uuid["time_mid"] = substr($hash, 8, 4);
	$uuid["time_hi_and_version"] = ( hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x4000;
	$uuid["clk_seq_hi_res_variant"] = ( hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000;
	$uuid["node"] = substr($hash, 20, 12);
	return vsprintf("%08s-%04s-%04x-%04x-%12s", $uuid);
}

function kuid(){
	$micro      = substr( microtime( ), 2, 8 );
	$seconds    = time();
	$seconds    = str_pad( dechex($seconds), 12, 0, STR_PAD_LEFT);
	$seconds    = str_split( $seconds, 4);
	$seconds[0] |= 1000;
	$seconds    = implode( "", $seconds );
	$micro      = str_pad( dechex($micro), 8, "0", STR_PAD_RIGHT);
	$uuid       = array(
		substr( $seconds, 0, 8),
		substr( $seconds, 8, 4),
		substr( $seconds, 12, 4),
		substr( $micro, 0, 8),
	);
	return vsprintf("%08s-%04s-%04s-%08s", $uuid );
}
