<?

$SQL = "SELECT conference_id FROM paper WHERE author_id = ".$_SESSION['userID'];
$result = $sql->query($SQL);
// var_dump($result);
// exit();

template('AUTHOR_view_papers')

?>
