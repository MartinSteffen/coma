<? 
include("header.tpl.php");
$paper = d('chair');
?>
<link rel="stylesheet" href="style.css" type="text/css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="textBold">Manage a paper</td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="117" class="textBold" align="left" valign="top">Paper name</td>
    <td width="100%" class="text" align="left" valign="top"> 
      <? echo $paper['paperName'] ?>
    </td>
    <td width="257" class="text" align="left" valign="top">&nbsp; </td>
  </tr>
  <tr> 
    <td class="textBold" align="left" valign="top" width="117">Author</td>
    <td class="text" align="left" valign="top"> <a href="index.php?m=chair&a=users&s=user&userID=<? echo $paper['authorID'] ?>" class="normal"> 
      <? echo $paper['authorName'] ?>
      </a> </td>
    <td class="text" align="left" valign="top" width="257"><a href="index.php?m=chair&a=papers&s=allPapersOfUser&userID=<? echo $paper['authorID'] ?>" class="normal">List 
      all papers of the author</a></td>
  </tr>
  <tr> 
    <td class="textBold" align="left" valign="top" width="117">In conference</td>
    <td class="text" align="left" valign="top"> <a href="index.php?m=chair&a=conferences&s=conference&confID=<? echo $paper['confID'] ?>" class="normal"> 
      <? echo $paper['confName'] ?>
      </a> </td>
    <td class="text" align="left" valign="top" width="257"><a href="index.php?m=chair&a=papers&s=allPapersOfConference&confID=<? echo $paper['confID'] ?>" class="normal">List 
      all papers in the conference</a></td>
  </tr>
  <tr> 
    <td class="textBold" align="left" valign="top" width="117">Description</td>
    <td class="text" align="left" valign="top"> 
      <? echo $paper['paperDesc'] ?>
    </td>
    <td class="text" align="left" valign="top" width="257">&nbsp; </td>
  </tr>
  <tr> 
    <td class="textBold" align="left" valign="top" width="117">&nbsp;</td>
    <td class="text" align="left" valign="top">&nbsp;</td>
    <td class="text" align="left" valign="top" width="257">&nbsp;</td>
  </tr>
  <tr> 
    <td height="1" width="117"><img height="1" width="117" src="/templates/images/spacer.gif"></td>
    <td></td>
    <td width="257"><img height="1" width="257" src="/templates/images/spacer.gif"></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Reviewers and so on...</td>
  </tr>
</table>
<br>
<?
include("footer.tpl.php");
?>
