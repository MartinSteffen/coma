<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>CoMa - Das Konferenzmanagement-Tool - {titel} </title>
<link rel="stylesheet" type="text/css" href="{path}styles.css">
</head>

<body>

<a name="top"></a>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr bgcolor="#DBE9EC">
  <td width="25">&nbsp;</td>
  <td height="90" width="150">
   <a href="{basepath}index.php{SID}">
      <img src="{path}images/logo.jpg" width="150" height="90" align="middle" border="0" alt="">
   </a>
  </td>
  <td>
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr bgcolor="#DBE9EC">     
     <td width="70">&nbsp;</td>
     <td height="32" align="right" valign="top">
      <a href="#">Impressum</a>&nbsp;|&nbsp;
      <a href="#">Forum</a>&nbsp;|&nbsp;
      <a href="{basepath}logout.php{SID}">Logout</a>&nbsp;&nbsp;
      <a href="#"><img src="{path}images/info.gif" width="15" height="15" border="0" alt=""></a> &nbsp;
     </td>
    </tr>
    <tr bgcolor="#DBE9EC">
     <td width="70"  style="background-image:url({path}images/menueleft2.gif)" height="50"> &nbsp; </td>
     <td colspan="2" height="50" valign="middle" style="background-image:url({path}images/menue2.gif)">
      {menue}
     </td>
    </tr>
    <tr bgcolor="#DBE9EC">
     <td width="70" height="25">&nbsp;</td>
     <td colspan="2" height="25" valign="middle">
      {submenue}
     </td>
     </tr>   
   </table>
  </td>  
 </tr>
</table>
<!-- - - - - - - - - - - - - - - - - - - - Body - - - - - - - - - - - - - - - -  -->

{content}

{body}
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>

<!-- - - - - - - - - - - - - - - - - - Footer - - - - - - - - - - - - - - - -  -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td style="background-image:url({path}images/menue.gif)" width="50">&nbsp;</td>
  <td style="background-image:url({path}images/menue.gif)" height="25">&nbsp;</td>
  <td style="background-image:url({path}images/menue.gif)" width="50" align="center">
   <a href="#top"><img src="{path}images/top.gif" width="15" height="15" border="0" align="middle" alt=""></a>
  </td>
 </tr>
 <tr bgcolor="#DBE9EC">
  <td>&nbsp;</td>
  <td colspan="2" height="32">&copy; 2004 by Sandro Esquivel, Daniel Miesling, Tom Scherzer, Falk Starke, Jan Waller</td>
 </tr>
</table>

</body>
</html>