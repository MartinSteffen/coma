<html>
<head>
<title>Comma - The ultimate conference manager</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="templates/style.css" type="text/css">
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="100%"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td align="left" valign="bottom" background="templates/images/blueBack.gif" width="78%"><img src="templates/images/logoUp.gif" width="200" height="83"></td>
          <td align="right" valign="bottom" background="templates/images/blueBack.gif" width="22%"><img src="templates/images/confManager.gif" width="215" height="34"></td>
        </tr>
        <tr> 
          <td align="left" valign="top" height="62" width="78%"> 
            <table width="108%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="19%"><img src="templates/images/logoDown.gif" width="200" height="49"></td>
                <td width="81%" align="left" valign="middle" class="text"><? /* Hello <? echo($_SESSION['name']; */ ?></td>
              </tr>
            </table>
          </td>
          <td height="62" align="right" valign="top" width="22%"> 
            <table width="98" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="77" align="right"><b><a href="index.php?logout=1" class="logout">Logout</a></b></td>
                <td width="21"></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="271" align="left" valign="top"> 
            <div align="center"> 
              <table width="226" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" height="78">
                <tr> 
                  <td bgcolor="#6699FF" height="29"><b><font face="Verdana, Arial, Helvetica, sans-serif">Menu</font></b></td>
                </tr>
                <tr> 
                  <td bgcolor="#FFFFFF" height="31"> 
                    <table width="239" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td class="menu"><a href="index.php?m=roles" class="menus">Change 
                          Roles</a></td>
                      </tr>
                      <tr> 
                        <td class="menus"><a href="index.php?m=session" class="menus">Show 
                          Session</a></td>
                      </tr>
                      <tr> 
                        <td class="menus"><a href="index.php?m=admin" class="menus">Admin</a></td>
                      </tr>
			<? if($_SESSION['role']=="Admin")
			   {  
/* ------------------------------------------------------------ MENU FOR ADMIN ------------------------------------------------------------ */
				?>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=admin&a=action" class="menus">Action (bitte selber bearbeiten!)</a></td>
						    </tr>
						  </table>					  
					    </td>
					  </tr>
	            <? 
/* ---------------------------------------------------------------------------------------------------------------------------------------- */
			   } ?>					  
                      <tr> 
                        <td class="menus"><a href="index.php?m=chair" class="menus">Chair</a></td>
                      </tr>
			<? if($_SESSION['role']=="Chair") 
			   {  
/* ------------------------------------------------------------ MENU FOR CHAIR ------------------------------------------------------------ */
				?>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=chair&a=papers" class="menus">Manage papers</a></td>
						    </tr>
						  </table>					  
					    </td>
					  </tr>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=chair&a=conferences" class="menus">Manage conferences</a></td>
						    </tr>
						  </table>					  
					    </td>
					  </tr>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=chair&a=users" class="menus">Manage users</a></td>
						    </tr>
						  </table>					  
					    </td>
					  </tr>					  					  					  
	            <? 
/* ---------------------------------------------------------------------------------------------------------------------------------------- */
			   } ?>
                      <tr> 
                        <td class="menus"><a href="index.php?m=reviewer" class="menus">Reviewer</a></td>
                      </tr>
			<? if($_SESSION['role']=="Reviewer")
			   {  
/* ------------------------------------------------------------ MENU FOR REVIEWER ------------------------------------------------------------ */
				?>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=reviewer&a=action" class="menus">Action (bitte selber bearbeiten!)</a></td>
						    </tr>
						  </table>					  
					    </td>
					  </tr>
	            <? 
/* ---------------------------------------------------------------------------------------------------------------------------------------- */
			   } ?>					  
                      <tr> 
                        <td class="menus"><a href="index.php?m=author" class="menus">Author</a></td>
                      </tr>
			<? if($_SESSION['role']=="Author")
			   {  
/* ------------------------------------------------------------ MENU FOR AUTHOR ------------------------------------------------------------ */
				?>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=author&a=new" class="menus">New paper</a></td>
						    </tr>
						  </table>					  
					    </td>
					  </tr>			   
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=author&a=action" class="menus">Action (bitte selber bearbeiten!)</a></td>
						    </tr>
						  </table>					  
					    </td>
					  </tr>
	            <? 
/* ---------------------------------------------------------------------------------------------------------------------------------------- */
			   } ?>				  
                      <tr> 
                        <td class="menus"><a href="index.php?m=forum" class="menus">Forum</a></td>
                      </tr>					  					  					  					  
                    </table>
                  </td>
                </tr>
              </table>
            </div>
          </td>
          <td width="100%" valign="top">
            <div align="center">
