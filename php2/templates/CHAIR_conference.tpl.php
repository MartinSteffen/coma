<? 
include("header.tpl.php");
$input = d('chair');
$conference = $input['conference'];
$topics = $input['topics'];
?>

<script language="JavaScript" src="templates/calendar.js"></script>
<link rel="stylesheet" href="style.css" type="text/css">



<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="textBold" width="100%">Manage a conference.</td>
  </tr>
</table>
<br><form name="form1" method="post" action="index.php?m=chair&a=conferences&s=updateConference">
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
  <tr> 
    <td width="110" class="textBold">Topic name</td>
    <td width="100%" class="text"><? echo $topic['topicName'] ?></td>
    <td width="170" align="center"><a href="index.php?m=chair&a=papers&s=allPapersOfTopic&topicID=<? echo $topic['topicID'] ?>" class="menus">List 
      all papers in the topic</a></td>
    <td width="170" align="right" class="normal"><a href="index.php?m=chair&a=conferences&s=deleteTopic&topicID=<? echo $topic['topicID'] ?>" class="normal">Delete 
      the topic</a></td>
    <td width="29">&nbsp;</td>
  </tr>
  <tr> 
    <td height="1"><img height="1" width="110" src="/templates/images/spacer.gif"></td>
    <td></td>
    <td><img height="1" width="170" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="170" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="29" src="/templates/images/spacer.gif"></td>
  </tr>
</table>
<?
  }
include("footer.tpl.php");
?>