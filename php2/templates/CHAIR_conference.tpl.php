<? 
include("header.tpl.php");
$input = d('chair');
$conference = $input['conference'];
$topics = $input['topics'];
$criterions = $input['criterions'];
$forums = $input['forums'];
?>

<script language="JavaScript" src="templates/calendar.js"></script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="textBold" width="100%">Manage a conference.</td>
  </tr>
</table>
<br><form name="confForm" method="post" action="index.php?m=chair&a=conferences&s=updateConference">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="100%"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="146" class="textBold">Conference name</td>
            <td width="100%" class="text"> 
              <input type="text" name="confName" maxlength="127" size="60" value="<? echo $conference['confName'] ?>">
            </td>
          </tr>
          <tr> 
            <td width="146" class="textBold"><a href="http://<? echo $conference['homepage'] ?>" class="menus" target="_blank">Homepage</a></td>
            <td class="text"> 
              <input type="text" name="homepage" size="60" maxlength="127" value="<? echo $conference['homepage'] ?>">
            </td>
          </tr>
          <tr> 
            <td width="146" class="textBold">Description</td>
            <td class="text"> 
              <textarea name="description" rows="5" cols="50"><? echo $conference['description'] ?></textarea>
            </td>
          </tr>
          <tr> 
            <td height="1"><img height="1" width="146" src="/templates/images/spacer.gif"></td>
            <td></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="220" class="textBold">&nbsp;</td>
            <td class="text" width="148">&nbsp;</td>
            <td class="text" width="100%">&nbsp;</td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Abstract submission deadline</td>
            <td width="148"> 
              <div id=abstractSubmission><span class=text><? echo $conference['abstractSubmission'] ?></span></div>
              <input type="hidden" name="abstractSubmissionHidden" value="<? echo $conference['abstractSubmissionHidden'] ?>">
            </td>
            <td class="text"> <a href="javascript:show_calendar('abstractSubmission');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a> 
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Paper submition deadline</td>
            <td width="148"> 
              <div id=paperSubmission><span class=text><? echo $conference['paperSubmission'] ?></span></div>
              <input type="hidden" name="paperSubmissionHidden" value="<? echo $conference['paperSubmissionHidden'] ?>">
            </td>
            <td class="text"> <a href="javascript:show_calendar('paperSubmission');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a>	
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Review deadline</td>
            <td width="148"> 
              <div id=reviewSubmission><span class=text><? echo $conference['reviewSubmission'] ?></span></div>
              <input type="hidden" name="reviewSubmissionHidden" value="<? echo $conference['reviewSubmissionHidden'] ?>">
            </td>
            <td class="text"> <a href="javascript:show_calendar('reviewSubmission');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a>	
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Final version deadline</td>
            <td width="148"> 
              <div id=finalVersion><span class=text><? echo $conference['finalVersion'] ?></span></div>
              <input type="hidden" name="finalVersionHidden" value="<? echo $conference['finalVersionHidden'] ?>">
            </td>
            <td class="text"> <a href="javascript:show_calendar('finalVersion');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a>	
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Notification</td>
            <td width="148"> 
              <div id=notification><span class=text><? echo $conference['notification'] ?></span></div>
              <input type="hidden" name="notificationHidden" value="<? echo $conference['notificationHidden'] ?>">
            </td>
            <td class="text"> <a href="javascript:show_calendar('notification');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a>	
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">&nbsp;</td>
            <td width="148">&nbsp;</td>
            <td class="text">&nbsp;</td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Conference start</td>
            <td width="148"> 
              <div id=conferenceStart><span class=text><? echo $conference['conferenceStart'] ?></span></div>
              <input type="hidden" name="conferenceStartHidden" value="<? echo $conference['conferenceStartHidden'] ?>">
            </td>
            <td class="text"> <a href="javascript:show_calendar('conferenceStart');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a>	
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Conference end</td>
            <td width="148"> 
              <div id=conferenceEnd><span class=text><? echo $conference['conferenceEnd'] ?></span></div>
              <input type="hidden" name="conferenceEndHidden" value="<? echo $conference['conferenceEndHidden'] ?>">
            </td>
            <td class="text"> <a href="javascript:show_calendar('conferenceEnd');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a>	
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">&nbsp;</td>
            <td class="text" width="148">&nbsp;</td>
            <td class="text">&nbsp;</td>
          </tr>
          <tr> 
            <td height="1"><img height="1" width="220" src="/templates/images/spacer.gif"></td>
            <td><img height="1" width="148" src="/templates/images/spacer.gif"></td>
            <td></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="220" class="textBold">Minimum reviewers per paper</td>
            <td class="text" width="60"> 
              <input type="text" name="minimum" size="2" maxlength="2" value="<? echo $conference['minimum'] ?>">
            </td>
            <td class="text" width="100%"> 
			  <input type="hidden" name="confID" value="<? echo $conference['confID'] ?>">
              <input type="submit" name="Submit" value="Update changes">
            </td>
          </tr>
          <tr> 
            <td height="1" width="220"><img height="1" width="220" src="/templates/images/spacer.gif"></td>
            <td width="60"><img height="1" width="60" src="/templates/images/spacer.gif"></td>
            <td></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="left" valign="middle"> 
    <td width="37"><img src="templates/images/arrow.gif" width="30" height="17"></td>
    <td width="100%"><a href="index.php?m=chair&a=papers&s=allPapersOfConference&confID=<? echo $conference['confID'] ?>" class="menus">View 
      all papers in the conference</a></td>
  </tr>
  <tr align="left" valign="middle"> 
    <td><img src="templates/images/arrow.gif" width="30" height="17"></td>
    <td><a href="index.php?m=chair&a=users&s=allUsersOfConference&confID=<? echo $conference['confID'] ?>" class="menus">View 
      all users in the conference</a></td>
  </tr>
  <tr> 
    <td height="1"><img height="1" width="37" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
<? if (count($topics) == 0)
   {  ?>
    <td class="textBold" width="100%">There are no topics in this conference</td>
<? }
   else
   {  ?>
    <td class="textBold" width="100%">List of topics:</td>   
<?  } ?>   
  </tr>
</table>
<br>
<?
  foreach ($topics as $topic)
  {  ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <form name="topicForm<? echo $topic['topicID'] ?>" method="post" action="index.php?m=chair&a=conferences&s=updateTopic">
  <input type="hidden" name="topicID" value="<? echo $topic['topicID'] ?>">
  <input type="hidden" name="confID" value="<? echo $conference['confID'] ?>">  
  <tr> 
      <td width="90" class="textBold" align="left" valign="middle">Topic name</td>
      <td width="100%" class="text" align="left" valign="middle"> 
        <input type="text" name="topicName" value="<? echo $topic['topicName'] ?>" maxlength="127" size="30">
      </td>
      <td width="200" align="center" valign="middle"><a href="index.php?m=chair&a=papers&s=allPapersOfTopic&topicID=<? echo $topic['topicID'] ?>" class="menus">List 
        all papers in the topic</a></td>
	  <td width="140" align="center" valign="middle"> 
        <input type="submit" name="Submit" value="Update topic">
    </td>
      <td width="100" align="right" class="normal" valign="middle"><a href="index.php?m=chair&a=conferences&s=deleteTopic&topicID=<? echo $topic['topicID'] ?>&confID=<? echo $conference['confID'] ?>" class="normal" onclick="return confirm('Are you sure you want to delete this topic?')">Delete 
        the topic</a></td>
    <td width="29">&nbsp;</td>	
  </tr>
 </form>  
  <tr> 
    <td height="1"><img height="1" width="90" src="/templates/images/spacer.gif"></td>
    <td></td>
    <td><img height="1" width="200" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="140" src="/templates/images/spacer.gif"></td>	
    <td><img height="1" width="100" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="29" src="/templates/images/spacer.gif"></td>
  </tr>
</table>
<? } ?>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="textBold" width="100%">Insert a new topic:</td>
  </tr>
  <tr> 
    <td class="textBold" width="100%">&nbsp;</td>
  </tr>  
  <form name="formNew" method="post" action="index.php?m=chair&a=conferences&s=addTopic">
    <input type="hidden" name="confID" value="<? echo $conference['confID'] ?>">
    <tr> 
      <td class="textBold"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="90" class="textBold" align="left" valign="middle">Topic 
              name</td>
            <td width="220" align="left" valign="middle"> 
              <input type="text" name="topicName" size="30" maxlength="127">
            </td>
            <td width="100%" align="left" valign="middle">
              <input type="submit" name="Submit" value="Add topic">
            </td>
          </tr>
          <tr> 
            <td height="1"><img height="1" width="90" src="/templates/images/spacer.gif"></td>
            <td><img height="1" width="220" src="/templates/images/spacer.gif"></td>
            <td></td>
          </tr>
        </table>
      </td>
    </tr>
  </form>
</table>
<br>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
<? if (count($criterions) == 0)
   {  ?>
    <td class="textBold" width="100%">There are no criterions in this conference</td>
<? }
   else
   {  ?>
    <td class="textBold" width="100%">List of criterions:</td>   
<?  } ?>   
  </tr>
</table>
<br>
<? if (!(count($criterions) == 0))
   {  ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="left" valign="top"> 
    <td class="textBold" width="155">Criterion name</td>
    <td class="textBold" width="270">Description</td>
    <td class="textBold" width="111">Maximum value</td>
    <td class="textBold" width="110">Quality rating</td>
    <td width="150">&nbsp;</td>
    <td width="100%">&nbsp;</td>
  </tr>
  <?
  foreach ($criterions as $criterion)
  {  ?>
  <form name="criterionForm<? echo $criterion['criterionID'] ?>" method="post" action="index.php?m=chair&a=conferences&s=updateCriterion">
  <input type="hidden" name="confID" value="<? echo $conference['confID'] ?>">
  <input type="hidden" name="criterionID" value="<? echo $criterion['criterionID'] ?>">
    <tr align="left" valign="top"> 
      <td class="text"> 
        <input type="text" name="criterionName" value="<? echo $criterion['criterionName'] ?>" maxlength="127" size="20">
      </td>
      <td class="text"> 
        <textarea name="criterionDesc" rows="5" cols="30"><? echo $criterion['criterionDesc'] ?></textarea>
      </td>
      <td class="text"> 
        <input type="text" name="maxValue" value="<? echo $criterion['maxValue'] ?>" maxlength="4" size="4">
      </td>
      <td class="text"> 
        <input type="text" name="qualityRating" value="<? echo $criterion['qualityRating'] ?>" size="4" maxlength="4">
      </td>
      <td>
        <input type="submit" name="Submit" value="Update criterion">
      </td>
      <td> <a class="normal" href="index.php?m=chair&a=conferences&s=deleteCriterion&criterionID=<? echo $criterion['criterionID'] ?>&confID=<? echo $conference['confID'] ?>" onclick="return confirm('Are you sure you want to delete this criterion?\r\nAll the reviews for this criterion will be lost!')">Delete the criterion</a> </td>
    </tr>
  </form>
  <? } ?>
    <tr> 
      <td height="1"><img height="1" width="155" src="/templates/images/spacer.gif"></td>
      <td><img height="1" width="270" src="/templates/images/spacer.gif"></td>
      <td><img height="1" width="111" src="/templates/images/spacer.gif"></td>
      <td><img height="1" width="110" src="/templates/images/spacer.gif"></td>
      <td><img height="1" width="150" src="/templates/images/spacer.gif"></td>
      <td></td>	  
    </tr>  
</table>
<br>
<? } ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
  <tr> 
    <td class="textBold" width="100%" align="left">Insert a new criterion:</td>
  </tr>
  <tr> 
	<td class="text" width="100%">&nbsp;</td>
  </tr> 
</table>
<form name="newCriterionForm" method="post" action="index.php?m=chair&a=conferences&s=addCriterion">
  <input type="hidden" name="confID" value="<? echo $conference['confID'] ?>">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr align="left" valign="top"> 
      <td class="textBold" width="120">Criterion name</td>
      <td class="text" width="100%"> 
        <input type="text" name="criterionName" value="" maxlength="127" size="20"></td>
  </tr>   
    <tr align="left" valign="top"> 
      <td class="textBold" width="120">Description</td>
	  <td class="text" width="100%"> 
        <textarea name="criterionDesc" rows="5" cols="30"></textarea></td>
  </tr> 
    <tr align="left" valign="top"> 
      <td class="textBold" width="120">Maximum value</td>
      <td class="text" width="100%"> 
        <input type="text" name="maxValue" value="" maxlength="4" size="4"></td>
  </tr>  
    <tr align="left" valign="top"> 
      <td class="textBold" width="120">Quality rating</td>
      <td class="text" width="100%"> 
        <input type="text" name="qualityRating" value="" size="4" maxlength="4"></td>
  </tr>  
    <tr align="left" valign="top"> 
      <td class="textBold" width="120">&nbsp;</td>
      <td class="text" width="100%">&nbsp;</td>
  </tr>    
    <tr align="left" valign="top"> 
      <td class="textBold" width="120">&nbsp;</td>
      <td class="text" width="100%"> 
        <input type="submit" name="Submit" value="Add criterion"></td>
  </tr>      
  <tr>
	<td><img height="1" width="120" src="/templates/images/spacer.gif"></td> 
	<td></td>   
  </tr>
</table>
</form>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
<? if (count($forums) == 0)
   {  ?>
    <td class="textBold" width="100%">There are no forums for this conference</td>
<? }
   else
   {  ?>
    <td class="textBold" width="100%">List of forums:</td>   
<?  } ?>   
  </tr>
</table>
<br>
<? if (!(count($forums) == 0))
   {  ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="textBold" width="400">Title</td>
    <td class="textBold" width="100%">Type</td>
  </tr>
  <?
  foreach ($forums as $forum)
  {  ?>  
  <tr>
    <td class="text" width="400">
      <? echo $forum['title'] ?>
    </td>
    <td class="text" width="100%">
      <? echo $forum['forum_type'] ?>
    </td>
  </tr> 
<?  } ?> 
  <tr>
    <td><img height="1" width="400" src="/templates/images/spacer.gif"></td>
	<td></td>	
  </tr>
</table>
<br>
<?  } ?> 
<table width="100%" border="0" cellspacing="0" cellpadding="0"> 
  <tr> 
    <td class="textBold" width="100%" align="left">Insert a new forum:</td>
  </tr>
  <tr> 
	<td class="text" width="100%">&nbsp;</td>
  </tr> 
</table>
<form name="formForForum" method="post" action="index.php?m=forum&a=newforum">
<input type="hidden" name="confID" value="<? echo $conference['confID'] ?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
      <td class="textBold" width="100">Title</td>
      <td class="text" width="100%"> 
        <input type="text" name="title" size="80" maxlength="127">
      </td>
  </tr>
  <tr>
      <td class="textBold" width="100">Type</td>
      <td class="text" width="100%"> 
        <select name="type" size="1">
          <option value="1">Open forum</option>
          <option value="2">Comittee forum</option>
        </select>
      </td>
  </tr>
  <tr>
      <td class="textBold" width="100">&nbsp;</td>
      <td class="text" width="100%">&nbsp; </td>
  </tr>   
  <tr>
      <td class="textBold" width="100">&nbsp;</td>
      <td class="text" width="100%"> 
        <input type="submit" name="Submit" value="Add forum">
      </td>
  </tr>  
  <tr>
  	   <td><img height="1" width="100" src="/templates/images/spacer.gif"></td>
	   <td></td>	   	   
  </tr>
</table>
</form>
<?
include("footer.tpl.php");
?>
