<?
function mysql_exec_batch ($p_query, $p_transaction_safe = true) {
  if ($p_transaction_safe) {
     $p_query = 'START TRANSACTION; ' . $p_query . '; COMMIT;';
   };
  $query_split = preg_split ("/[;]+/", $p_query);
  foreach ($query_split as $command_line) {
   $command_line = trim($command_line);
   if ($command_line != '') {
     $query_result = mysql_query($command_line);
     if ($query_result == 0) {
       break;
     };
   };
  };
  return $query_result;
}
?>
