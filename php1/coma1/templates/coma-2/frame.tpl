<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>CoMa - Das Konferenzmanagement-Tool - {title}</title>
<link rel="stylesheet" type="text/css" href="{path}styles.css">
</head>

<body>

<a name="top"></a>

<table class="header">
 <tr>
  <td width="25">&nbsp;</td>
  <td height="90" width="150">
   <a href="{basepath}index.php?{SID}">
      <img src="{path}images/logo.jpg" width="150" height="90" align="middle" border="0" alt="">
   </a>
  </td>
  <td>
   <table class="header">
    <tr>
     <td width="70">&nbsp;</td>
     <td height="32" align="right" valign="top">
      <a href="#">Impressum</a>&nbsp;|&nbsp;
      <a href="#">Forum</a>&nbsp;|&nbsp;
      <a href="{basepath}logout.php?{SID}">Logout</a>&nbsp;&nbsp;
      <a href="#"><img src="{path}images/info.gif" width="15" height="15" border="0" alt=""></a> &nbsp;
     </td>
    </tr>
    <tr height="25">
     <td width="70" style="background-image:url({path}images/menueleft.gif)" height="25"> &nbsp; </td>
     <td colspan="2" height="25" valign="middle" style="background-image:url({path}images/menue.gif)">
      {navigator}
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<!-- - - - - - - - - - - - - - - - - - - - Body - - - - - - - - - - - - - - - -  -->

<table class="body">
 <tr>
  <td width="150">{menu}</td>
  <td>{content}</td>
 </tr>
</table>

<!-- - - - - - - - - - - - - - - - - - Footer - - - - - - - - - - - - - - - -  -->
<table class="footer">
 <tr>
  <td style="background-image:url({path}images/menue.gif)" width="50">&nbsp;</td>
  <td style="background-image:url({path}images/menue.gif)" height="25">&nbsp;</td>
  <td style="background-image:url({path}images/menue.gif)" width="50" align="center">
   <a href="#top"><img src="{path}images/top.gif" width="15" height="15" border="0" align="middle" alt=""></a>
  </td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td colspan="2" height="32">&copy; 2004 by Sandro Esquivel, Daniel Miesling, Tom Scherzer, Falk Starke, Jan Waller</td>
 </tr>
</table>

</body>
</html>