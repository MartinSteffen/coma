<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Coma - The ultimate conference manager</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="templates/style.css" type="text/css">
<script>
function toggle(id)
{
	if( document.getElementById(id).style.display=='none' )
	{
		document.getElementById(id).style.display = '';
	}
	else
	{
		document.getElementById(id).style.display = 'none';
	}
}
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>

        <td align="left" valign="bottom" background="templates/images/blueBack.gif" width="78%"><img src="templates/images/logoUp.gif" width="200" height="83" border="0"></td>

        <td align="right" valign="bottom" background="templates/images/blueBack.gif" width="22%"><img src="templates/images/confManager.gif" width="215" height="34" border="0"></td>
        </tr>
        <tr>
          <td align="left" valign="top" height="62" width="78%">
            <table width="108%" border="0" cellspacing="0" cellpadding="0">
              <tr>

              <td width="19%"><img src="templates/images/logoDown.gif" width="200" height="49" border="0"></td>
                <td width="81%" align="left" valign="middle" class="text">
				    <?
					   if(isset($_SESSION['userID']))
					   {
					   		echo "Logged in : ".$_SESSION['userName'];
					   }
					?>
			    </td>
              </tr>
            </table>
          </td>
          <td height="62" align="right" valign="top" width="22%">
            <table width="98" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="77" align="right">
				   <?
				     if(isset($_SESSION['userID']))
					 {   ?>
					    <b><a href="index.php?m=logout" class="logout">Logout</a></b>
				   <? } ?>
				</td>
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
		<? /* START MENU */
		   if(isset($_SESSION['userID']))
		   {
		  ?>
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
                        <td class="menus"><a href="index.php?m=tasks" class="menus">My tasks</a></td>
                      </tr>
                      <tr>
                        <td class="menus"><a href="index.php?m=profile" class="menus">My profile</a></td>
                      </tr>
                      <tr>
                        <td class="menus">&nbsp;</td>
                      </tr>
	        <? if(isAdmin_Overall())
			   { ?>
                      <tr>
                        <td class="menus"><a href="index.php?m=admin" class="menus">Admin</a></td>
                      </tr>

			<? if(isset($_SESSION['role']))
			   {
			    if($_SESSION['role']=="Admin")
			    {
/* ------------------------------------------------------------ MENU FOR ADMIN ------------------------------------------------------------ */
				?>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=admin&a=conferences" class="menus">conference management</a></td>
						    </tr>
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=admin&a=conferences&s=create" class="menus">create new conference</a></td>
						    </tr>
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=admin&a=accessdata" class="menus">change access data</a></td>
						    </tr>
						  </table>
					    </td>
					  </tr>
	            <?
/* ---------------------------------------------------------------------------------------------------------------------------------------- */
			   }}}
	           if(isChair_Overall())
			   { ?>
                      <tr>
                        <td class="menus"><a href="index.php?m=chair" class="menus">Chair</a></td>
                      </tr>
			<? if(isset($_SESSION['role']))
			   {
			    if($_SESSION['role']=="Chair")
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
							    <td class="menus"><a href="index.php?m=chair&a=program" class="menus">Manage program</a></td>
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
			   }}}
	           if(isReviewer_Overall())
			   { ?>
                      <tr>
                        <td class="menus"><a href="index.php?m=reviewer" class="menus">Reviewer</a></td>
                      </tr>
			<? if(isset($_SESSION['role']))
			   {
			    if($_SESSION['role']=="Reviewer")
			    {
/* ------------------------------------------------------------ MENU FOR REVIEWER ------------------------------------------------------------ */
				?>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=reviewer&a=review" class="menus">Review a paper</a></td>
						    </tr>
						  </table>
					    </td>
					  </tr>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=reviewer&a=request" class="menus">Request a paper</a></td>
						    </tr>
						  </table>
					    </td>
					  </tr>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=reviewer&a=topic" class="menus">My prefered topics</a></td>
						    </tr>
						  </table>
					    </td>
					  </tr>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=reviewer&a=denied" class="menus">My denied papers</a></td>
						    </tr>
						  </table>
					    </td>
					  </tr>
	            <?
/* ---------------------------------------------------------------------------------------------------------------------------------------- */
			   }}}
	           if(isAuthor_Overall())
			   { ?>
                      <tr>
                        <td class="menus"><a href="index.php?m=author" class="menus">Author</a></td>
                      </tr>
			<? if(isset($_SESSION['role']))
			   {
			    if($_SESSION['role']=="Author")
			    {
/* ------------------------------------------------------------ MENU FOR AUTHOR ------------------------------------------------------------ */
				?>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=author&a=new" class="menus">commit a new paper</a></td>
						    </tr>
						  </table>
					    </td>
					  </tr>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
							    <td class="menus"><a href="index.php?m=author&a=view&s=papers" class="menus">manage papers</a></td>
						    </tr>
						  </table>
					    </td>
					  </tr>
	            <?
/* ---------------------------------------------------------------------------------------------------------------------------------------- */
			   }}} ?>
                      <tr>
                        <td class="menus">&nbsp;</td>
                      </tr>
                      <tr>
                        <td class="menus"><a href="index.php?m=forum" class="menus">Forum</a></td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </div>
          </td>
		  <? /* END MENU */
		  	}
			else
			{
			?>
			          <td width="271" align="left" valign="top">
            <div align="center">
              <table width="226" border="0" cellspacing="1" cellpadding="5" bgcolor="#000000" height="78">
                <tr>
                  <td bgcolor="#6699FF" height="29"><b><font face="Verdana, Arial, Helvetica, sans-serif">ANNOUNCEMENT</font></b></td>
                </tr>
                <tr>
                  <td bgcolor="#FFFFFF" height="31">
                    <table width="239" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td class="menus">&nbsp;</td>
                      </tr>
		      <?
/* ---------------------------------------------------------------------------------------------------------------------------------------- */
			?>
                      <tr>
                        <td class="menus"><a href="index.php" class="menus">call for papers</a></td>
                      </tr>
		      <?
/* ------------------------------------------------------------ MENU FOR CALL FOR PAPERS -------------------------------------------------- */
			$conf = get_cfp();
			foreach ($conf as $value) {
				?>
					  <tr>
					    <td>
						  <table width="230" border="0" cellspacing="0" cellpadding="0">
  							<tr>
							    <td align="right" valign="middle" width="30"><img src="templates/images/arrow.gif" width="30" height="17"></td>
						    <td class="menus"><a href="index.php?m=cfp&a=view&s=view&cid=<? echo $value['id']; ?>" class="menus"><? echo $value['name']; ?></a></td>
						    </tr>
						  </table>
					    </td>
					  </tr>

	            		<?
				}
/* ---------------------------------------------------------------------------------------------------------------------------------------- */
			    ?>
                      <tr>
                        <td class="menus">&nbsp;</td>
                      </tr>
		      <tr>
		        <td class="menus"><a href="index.php" class="menus">Click here to go back to login</a></td>
		      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </div>
          </td>
	  		<?
			}
		   /* END MENU */
		  ?>
          <td width="100%" valign="top">
            <div align="center">
