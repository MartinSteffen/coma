
{if9<p class="message-failed">{message}</p>}

<table class="list">
  <tr class="listheader">
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=1{&SID}" class="order{if1-active}">Title</a>
    </th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">Current status</th>
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=4{&SID}" class="order{if4-active}">Average rating</a>
    </th>
    <th class="listheader">
      <a href="{basepath}{targetpage}?order=5{&SID}" class="order{if5-active}">Last edited</a>
    </th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">
      <form action="{basepath}author_createpaper.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="submit" name="addpaper" value="Add new paper" class="button">
      </form>
    </th>
  </tr>
  {lines}
</table>

<p>&nbsp;</p>

<p class="message">
  Click on any paper, to edit the common information about the paper
  and to upload new versions of the document.<br>
  Click on <span class="emph">View</span> to download and read the paper.<br>
  Do you want to <a href="{basepath}author_createpaper.php{?SID}" class="link">create a new paper</a>?
</p>
