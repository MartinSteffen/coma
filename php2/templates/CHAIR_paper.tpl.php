<? 
include("header.tpl.php");
$input = d('chair');
$paper = $input['paper'];
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
    <td height="1" width="117"><img height="1" width="117" src="/templates/images/spacer.gif"></td>
    <td></td>
    <td width="257"><img height="1" width="257" src="/templates/images/spacer.gif"></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="left" valign="top"> 
    <td width="116" class="textBold">State</td>
    <td width="204" class="<? echo $paper['class']; ?>"> 
      <? echo $paper['state']; ?>
    </td>
    <td width="100%"> 
      <form name="form1" method="post" action="index.php?m=chair&a=papers&s=updateState">
        <select name="stateID">
          <option value="0" <? if($paper['stateID']==0) echo "selected" ?>>Open</option>
          <option value="1" <? if($paper['stateID']==1) echo "selected" ?>>Being 
          reviewed</option>
          <option value="2" <? if($paper['stateID']==2) echo "selected" ?>>Being 
          reviewed, conflicting</option>
          <option value="3" <? if($paper['stateID']==3) echo "selected" ?>>Accepted</option>
          <option value="4" <? if($paper['stateID']==4) echo "selected" ?>>Rejected</option>
        </select>
        <input type="hidden" name="paperID" value="<? echo $paper['paperID'] ?>">
        <input type="submit" name="Submit" value="Change">
      </form>
    </td>
  </tr>
  <tr align="left" valign="top"> 
    <td width="116" class="textBold">Last edited</td>
    <td width="204" class="text"> 
      <? echo $paper['lastEdited'] ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr align="left" valign="top"> 
    <td width="116" class="textBold">Version</td>
    <td width="204" class="text"> 
      <? echo $paper['version'] ?>
    </td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="1"><img height="1" width="116" src="/templates/images/spacer.gif"></td>
    <td><img height="1" width="204" src="/templates/images/spacer.gif"></td>
    <td></td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="middle"><img src="templates/images/arrow.gif" width="30" height="17"><a href="index.php?m=chair&a=papers&s=paperReviewerAuto" class="menus">Send 
      the paper to reviewers automatically</a></td>
  </tr>
  <tr> 
    <td align="left" valign="middle"><img src="templates/images/arrow.gif" width="30" height="17"><a href="index.php?m=chair&a=papers&s=paperReviewerManual" class="menus">Send 
      the paper to reviewers manually</a></td>
  </tr>
  <tr> 
    <td align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="middle" class="textBold">Reports from the reviewers:</td>
  </tr>
</table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="100%"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="86" class="textBold">Reviewer</td>
          <td width="274" class="text">Reviewer A</td>
          <td width="100%">Remove this reviewer</td>
        </tr>
        <tr> 
          <td height="1"><img height="1" width="86" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="274" src="/templates/images/spacer.gif"></td>
          <td></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="86">&nbsp;</td>
          <td width="95" class="textBold">Criterion</td>
          <td width="100%" class="text">Criterion A</td>
        </tr>
        <tr> 
          <td height="1"><img height="1" width="86" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="95" src="/templates/images/spacer.gif"></td>
          <td></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="86">&nbsp;</td>
          <td width="95" class="textBold">Summary</td>
          <td width="100%" class="text">Summary A</td>
        </tr>
        <tr> 
          <td height="1"><img height="1" width="86" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="95" src="/templates/images/spacer.gif"></td>
          <td></td>
        </tr>		
      </table>
    </td>
  </tr>
  <tr> 
    <td> 	  
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="86">&nbsp;</td>
          <td width="95" class="textBold">Remarks</td>
          <td width="100%" class="text">Remarks A</td>
        </tr>
        <tr> 
          <td height="1"><img height="1" width="86" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="95" src="/templates/images/spacer.gif"></td>
          <td></td>
        </tr>		
      </table>
    </td>
  </tr>
  <tr> 
    <td> 	  
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="86">&nbsp;</td>
          <td width="95" class="textBold">Confidential</td>
          <td width="100%" class="text">Confidential A</td>
        </tr>
        <tr> 
          <td height="1"><img height="1" width="86" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="95" src="/templates/images/spacer.gif"></td>
          <td></td>
        </tr>		
      </table>
    </td>
  </tr>
  <tr> 
    <td> 	  
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="86">&nbsp;</td>
          <td width="95" class="textBold">Grade</td>
          <td width="70" class="text">Grade A</td>
          <td width="45" class="textBold">Ratio</td>
          <td width="100%" class="text">Ratio A</td>
        </tr>
        <tr> 
          <td height="1"><img height="1" width="86" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="95" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="70" src="/templates/images/spacer.gif"></td>
          <td><img height="1" width="45" src="/templates/images/spacer.gif"></td>		  		  
          <td></td>
        </tr>		
      </table>
    </td>
  </tr>
  <tr> 
    <td> 	  
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="100%">&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<br>
<?
include("footer.tpl.php");
?>
