<? 
include("header.tpl.php");
$input = d('chair');
?>
<link rel="stylesheet" href="style.css" type="text/css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <? if (!count($input)==0)
  	{ ?>  
    <td class="textBold">List of all your tasks that are waiting for you as chair.</td>
  <? }
     else
	 { ?>
    <td class="textBold">No tasks are waiting for you as chair.</td>	 
  <? } ?>	 	
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <? foreach($input as $task) 
  { ?>
  <tr align="left" valign="top"> 
    <td class="textBold" width="151">Paper name</td>
    <td class="text" width="100%"> 
      <? echo $task['paperName'] ?>
      <br>
    </td>
  </tr>
  <tr align="left" valign="top"> 
    <td class="textBold">Author</td>
    <td class="text"> 
      <? echo $task['userName'] ?>
    </td>
  </tr>
  <tr align="left" valign="top"> 
    <td class="textBold">In conference</td>
    <td class="text"> 
      <? echo $task['confName'] ?>
    </td>
  </tr>
  <tr align="left" valign="top"> 
    <td class="textBold">Task</td>
    <td class="text"><a href="index.php?m=chair&a=papers&s=paper&paperID=<? echo $task['paperID'] ?>" class="normal"> 
      <? echo $task['text'] ?>
      </a></td>
  </tr>
  <tr> 
    <td height="1"><img height="1" width="151" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
  <? } ?>
</table>
<?
include("footer.tpl.php");
?>
