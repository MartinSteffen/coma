<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CoMa - Your Conference Manager - {title}</title>
<link rel="stylesheet" type="text/css" href="{path}styles.css">
</head>

<body>

<a name="top"></a>

<table class="header">
 <tr>
  <td width="25">&nbsp;</td>
  <td height="90" width="150">
   <a href="{basepath}index.php{?SID}">
      <img src="{path}images/logo.jpg" width="150" height="90" border="0" alt="Logo">
   </a>
  </td>
  <td>
   <table class="header">
    <tr>
     <td width="70">&nbsp;</td>
     <td height="32" align="right" valign="top">
     <!--
      <a href="{basepath}imprint.php{?SID}">Imprint</a>&nbsp;|&nbsp;
      <a href="{basepath}forum.php{?SID}">Forum</a>&nbsp;|&nbsp;
      -->
      <a href="{basepath}logout.php{?SID}">Logout</a>&nbsp;&nbsp;
      <a href="{basepath}help.php{?SID}"><img src="{path}images/info.gif" width="15" height="15" border="0" alt="Info"></a>
     </td>
    </tr>
    <tr>
     <td width="70" style="background-image:url({path}images/menueleft.gif)" height="25"> &nbsp; </td>
     <td colspan="2" height="25" style="background-image:url({path}images/menue.gif); vertical-align: middle; ">
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
  <td class="menu">
    {menu}
  </td>
  <td class="body">
    <h2>{title}</h2>
    {content}
    <p>&nbsp;</p>
  </td>
 </tr>
</table>

<!-- - - - - - - - - - - - - - - - - - Footer - - - - - - - - - - - - - - - -  -->
<table class="footer">
 <tr>
  <td style="background-image:url({path}images/menue.gif)" width="50">&nbsp;</td>
  <td style="background-image:url({path}images/menue.gif)" height="25">&nbsp;</td>
  <td style="background-image:url({path}images/menue.gif)" width="50" align="center">
   <a href="#top"><img src="{path}images/top.gif" width="15" height="15" border="0" alt="Top"></a>
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