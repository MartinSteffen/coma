<? 
include("header.tpl.php");
$input = d('chair');
$paper = $input['paper'];
$report = $input['report'];
$reviewers = $report['reviewers'];
?>

<script language="JavaScript" src="templates/calendar.js"></script>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td class="textBold" width="100%">Manage a conference.</td>
  </tr>
</table>
<br><form name="form1" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td width="100%"> 
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="146" class="textBold">Conference name</td>
            <td width="100%" class="text"> 
              <input type="text" name="textfield" maxlength="127" size="60">
            </td>
          </tr>
          <tr> 
            <td width="146" class="textBold">Homepage</td>
            <td class="text"> 
              <input type="text" name="textfield2" size="60" maxlength="127">
            </td>
          </tr>
          <tr> 
            <td width="146" class="textBold">Description</td>
            <td class="text"> 
              <textarea name="textfield3" rows="5" cols="50"></textarea>
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
              <div id=abstractSubmission><span class=text>submision date</span></div>
              <input type="hidden" name="abstractSubmissionHidden" value="test">
            </td>
            <td class="text"> <a href="javascript:show_calendar('abstractSubmission');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a> 
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Paper submition deadline</td>
            <td width="148"> 
              <div id=paperSubmission><span class=text>submision date</span></div>
              <input type="hidden" name="paperSubmissionHidden" value="test">
            </td>
            <td class="text"> <a href="javascript:show_calendar('paperSubmission');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a>	
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Review deadline</td>
            <td width="148"> 
              <div id=reviewSubmission><span class=text>submision date</span></div>
              <input type="hidden" name="reviewSubmissionHidden" value="test">
            </td>
            <td class="text"> <a href="javascript:show_calendar('reviewSubmission');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a>	
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Final version deadline</td>
            <td width="148"> 
              <div id=finalVersion><span class=text>submision date</span></div>
              <input type="hidden" name="finalVersionHidden" value="test">
            </td>
            <td class="text"> <a href="javascript:show_calendar('finalVersion');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a>	
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Notification</td>
            <td width="148"> 
              <div id=notification><span class=text>submision date</span></div>
              <input type="hidden" name="notificationHidden" value="test">
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
              <div id=conferenceStart><span class=text>submision date</span></div>
              <input type="hidden" name="conferenceStartHidden" value="test">
            </td>
            <td class="text"> <a href="javascript:show_calendar('conferenceStart');"><img src="templates/images/b_calendar.png" alt="Calender" border="0"/></a>	
            </td>
          </tr>
          <tr> 
            <td width="220" class="textBold">Conference end</td>
            <td width="148"> 
              <div id=conferenceEnd><span class=text>submision date</span></div>
              <input type="hidden" name="conferenceEndHidden" value="test">
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
              <input type="text" name="textfield4" size="2" maxlength="2">
            </td>
            <td class="text" width="100%"> 
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
    <td width="37"><img src="images/arrow.gif" width="30" height="17"></td>
    <td width="100%"><a href="index.php?m=chair&a=papers&s=allPapersOfConference&confID=" class="menus">View 
      all papers in the conference</a></td>
  </tr>
  <tr align="left" valign="middle"> 
    <td><img src="images/arrow.gif" width="30" height="17"></td>
    <td><a href="index.php?m=chair&a=users&s=allUsersOfConference&confID=" class="menus">View 
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
    <td class="textBold" width="100%">List of topics:</td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="110" class="textBold">Topic name</td>
    <td width="265" class="text">&nbsp;</td>
    <td width="100%" align="center"><a href="index.php?m=chair&a=papers&s=allPapersOfTopic&topicID=" class="menus">List 
      all papers in the topic</a></td>
    <td width="170" align="right" class="normal"><a href="index.php?m=chair&a=conferences&s=deleteTopic&topicID=" class="normal">Delete 
      the topic</a></td>
    <td width="29">&nbsp;</td>
  </tr>
  <tr> 
    <td height="1"><img height="1" width="110" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="265" src="/templates/images/spacer.gif"></td>
    <td></td>
    <td><img height="1" width="170" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="29" src="/templates/images/spacer.gif"></td>
  </tr>
</table>
<?
include("footer.tpl.php");
?>