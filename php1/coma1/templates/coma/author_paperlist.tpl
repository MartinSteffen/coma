
{if9<p class="message">{message}</p>}

<table class="list">
  <tr class="listheader">
    <th class="listheader">Title</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">Current status</th>
    <th class="listheader">Average rating</th>    
    <th class="listheader">Last edited</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">
      <button name="addpaper" type="button" class="button" value="edit"
              onClick="self.location.href='{basepath}author_createpaper.php{?SID}'">
              Add new paper</button>    
    </th>        
  </tr>
  {lines}
</table>

<p>&nbsp;</p>

<p class="message2">
  Click on any paper, to edit the common information about the paper
  and to upload new versions of the document.<br>
  Click on <span class="emph">View</span> to download and read the paper.<br>
  Do you want to <a href="{basepath}author_createpaper.php{?SID}" class="link">create a new paper</a>?  
</p>
