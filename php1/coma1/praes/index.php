<html>
<head>
<title>CoMa PHP1</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<?php
  $MAX = 24;
  $MAX2 = 38;
  $ID = 1;
  if (!empty($_GET['id'])) {
    $ID = $_GET['id'];
  }
?>
<body bgcolor="#FFFFFF">
<div align="center">
  <font face="Arial, Helvetica, sans-serif">
  <table width="95%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td>
        <div align="left">
        <?php
          if ($ID != $MAX && $ID < $MAX2) { // damit man nach $MAX weiterblättern kann != statt <
            echo('<a href="index.php?id='.($ID+1).'">');
          }
          echo('<img src="Folie'.$ID.'.GIF">');
          if ($ID != $MAX && $ID < $MAX2) { // damit man nach $MAX weiterblättern kann != statt <
            echo('</a>');
          }
        ?>
        </div>
      </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr>
      <td>
        <?php
          if ($ID > 1) {
            echo ('<a href="index.php?id='.($ID-1).'">Prev</a>');
          }
          else {
            echo ('Prev');
          }
          echo('&nbsp;');
          for ($i = 1; $i <= $MAX; $i++) {
            echo ('<a href="index.php?id='.$i.'">'.$i.'</a>&nbsp;');
          }
          if ($ID != $MAX && $ID < $MAX2) { // damit man nach $MAX weiterblättern kann != statt <
            echo ('<a href="index.php?id='.($ID+1).'">Next</a>');
          }
          else {
            echo ('Next');
          }
        ?>
      </td>
    </tr>
    <tr>
      <td>
        <?php
          for ($i = $MAX+1; $i <= $MAX2; $i++) {
            echo ('<a href="index.php?id='.$i.'">'.$i.'</a>&nbsp;');
          }
        ?>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
        <a href="index.php?id=3">(I) Struktur von CoMa <b>PHP1</b></a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="index.php?id=11">(II) Entwicklungsprozess</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="index.php?id=15">(III) Demonstration</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="index.php?id=16">(IV) Abschlie&szlig;ende Bemerkungen</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="index.php?id=26">(Architektur)</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="index.php?id=30">(Zust&auml;ndigkeiten)</a>
      </td>
    </tr>
    <tr>
      <td>
        <a href="index.php?id=34">(Eingeschr&auml;nkte Funktionalit&auml;ten)</a>
      </td>
    </tr>
  </table>
  </font>
</div>
<div align="center"></div>
</body>
</html>
