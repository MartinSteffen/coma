<?
/*Diese Funktion leitet den HTML-Header auf eine ander URL */ 

function redirect($m = false, $a = false, $s = false, $mehr = false){
	// {{{
	if ($m){
		$m = "m=$m&";
	}
	if ($a){
		$a = "a=$a&";
	}
	if ($s){
		$s = "s=$s";
	}
  if (headers_sent()){
        print("<a href=\"http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}?{$m}{$a}{$s}{$mehr}\">Zum Fortfahren bitte hier klicken!</a>");
    }else{
      header("Location: http://{$_SERVER['SERVER_NAME']}{$_SERVER['PHP_SELF']}?{$m}{$a}{$s}{$mehr}");
    }
	exit();
	// }}}
}
?>
