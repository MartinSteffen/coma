<?

/* DEBUG START */
$conferences[] = array("id"=>1, "name"=>"Conference A", "desc"=>"Desc A");
$conferences[] = array("id"=>2, "name"=>"Conference B", "desc"=>"Desc B");
/* DEBUG END */

$TPL['chair'] = $conferences;
template("CHAIR_chooseConf");
$TPL['chair'] = "";
?>