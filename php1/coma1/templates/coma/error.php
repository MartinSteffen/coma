<?php
/**
 * Die einzige .php Datei die zu den Templates gehoert.
 * Grund: Fehlerausgabe darf niemals einen Fehler produzieren!
 *
 * @version $Id$
 * @package coma1
 * @subpackage core
 */
/***/
if (!defined('IN_COMA1')) {
  die('Hacking attempt');
}
if (!defined('SID')) {
  /**@ignore*/
  define('SID','');
} 
elseif (SID != '') {
  /**@ignore*/
  define('SID','?'.SID);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>CoMa - Your Conference Manager - Error</title>
<meta http-equiv="expires" content="0">
<meta http-equiv="Content-Style-Type" content="text/css">
<link rel="stylesheet" type="text/css" href="<?=TPLURL?>styles.css">
</head>

<body>

<a name="top"></a>

<table class="header">
 <tr>
  <td width="25">&nbsp;</td>
  <td height="90" width="150">
    <img src="<?=TPLURL?>images/logo.jpg" width="150" height="90" border="0" alt="Logo">
  </td>
  <td>
   <table class="header">
    <tr>
     <td width="70">&nbsp;</td>
     <td height="32" align="right" valign="top">
       &nbsp;
     </td>
    </tr>
    <tr>
     <td width="70" style="background-image:url(<?=TPLURL?>images/menueleft.gif)" height="25"> &nbsp; </td>
     <td colspan="2" height="25" style="background-image:url(<?=TPLURL?>images/menue.gif); vertical-align: middle; ">
      Coma  |  Error
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<!-- - - - - - - - - - - - - - - - - - - - Body - - - - - - - - - - - - - - - -  -->

<table class="body">
 <tr>
  <td class="menu">
    &nbsp;
  </td>
  <td class="body">
    <h2>Error</h2>
    
    <p class="message"><?=$strError?></p>

    <p>&nbsp;</p>
    
    <p>You have the following options:</p>
    <ul>
<?php
if (isset($popup)) && $popup) {
  echo '      <li>Try again later and <a href="javascript:close()" class="link">close this page</a>!';
}
else {
  echo '      <li>Return to the <a href="'.COREURL.'index.php'.SID.'" class="link">start page</a>!';
}
?>

      <li>Try again and <a href="javascript:location.reload()" class="link">reload the page</a>!
      <li>Go one step <a href="javascript:history.back()" class="link">back in time</a>!
    </ul>

    <p>&nbsp;</p>
  </td>
 </tr>
</table>

<!-- - - - - - - - - - - - - - - - - - Footer - - - - - - - - - - - - - - - -  -->
<table class="footer">
 <tr>
  <td style="background-image:url(<?=TPLURL?>images/menue.gif)" width="50">&nbsp;</td>
  <td style="background-image:url(<?=TPLURL?>images/menue.gif)" height="25">&nbsp;</td>
  <td style="background-image:url(<?=TPLURL?>images/menue.gif)" width="50" align="center">
   <a href="#top"><img src="<?=TPLURL?>images/top.gif" width="15" height="15" border="0" alt="Top"></a>
  </td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td height="32">&copy; 2005 by Sandro Esquivel, Daniel Miesling, Tom Scherzer, Falk Starke, Jan Waller</td>
  <td>&nbsp;</td>
 </tr>
</table>

</body>
</html>
<?php
  // Ausfuehrung beenden
  die(1);
?>