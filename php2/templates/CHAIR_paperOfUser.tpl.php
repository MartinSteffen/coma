<? 
include("header.tpl.php");
$userPaperConf = d("chair");
?>
<link rel="stylesheet" href="style.css" type="text/css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="textBold">Manage the paper of a user</td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td> 
      <p><span class="textBold">Paper title : </span> <span class="text"> 
        <? echo $userPaperConf['paperName'] ?>
        </span> <br>
        <span class="textBold">By user : </span> <span class="text"> 
        <? echo $userPaperConf['userName'] ?>
        </span> <br>
        <span class="textBold">In conference :</span><span class="text"> 
        <? echo $userPaperConf['confName'] ?>
        </span></p>
      </td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<?
include("footer.tpl.php");
?>
