<?

/* DEBUG START */
$conferences[] = array("id"=>1, "name"=>"Conference A", "desc"=>"Desc A");
$conferences[] = array("id"=>2, "name"=>"Conference B", "desc"=>"Desc B");
$conferences[] = array("id"=>3, "name"=>"Conference C", "desc"=>"Desc C");
$conferences[] = array("id"=>4, "name"=>"Conference D", "desc"=>"Desc D");
/* DEBUG END */

$TPL['chair'] = $conferences;
template("CHAIR_chooseConf");
$TPL['chair'] = array();
?>