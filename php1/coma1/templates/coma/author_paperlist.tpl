<table class="list">
  <tr class="listheader">
    <th class="listheader">Title</th>
    <th class="listheader">Co-Authors</th>
    <th class="listheader">Current status</th>
    <th class="listheader">Average rating</th>
    <th class="listheader">Abstract</th>
    <th class="listheader">Last edited</th>
    <th class="listheader">Download this paper</th>
    <th class="listheader">Upload new version</th>
    <th class="listheader">Upload abstract</th>
    <th class="listheader">Edit paper properties</th>
    <th class="listheader">Delete this paper</th>
  </tr>
  {paper-rows}
</table>

<p class="message2">Upload new paper:<br>
  <form action="{basepath}author_papers.php{?SID}" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <td>Title:</td><td><input type="text" size="50" name="title"></td>
      </tr>
      <tr>
        <td>Co-Authors:</td><td><input type="text" size="50" name="co-authors"></td>
      </tr>
      <tr>
        <td>File:</td><td><input type="file" name="paper"></td>
      </tr>
    </table>
    <input type="submit" value="submit">
  </form>
</p>