<tr>
  <td>{title}</td>
  <td>{co-authors}</td>
  <td>{status}</td>
  <td>{avgrating}</td>
  <td><a href="{abstract-link}">read/download abstract</a></td>
  <td>{last-edited}</td>
  <td><a href="{paper-link}">read/download paper</a></td>
  <td><form action="{basepath}author_papers.php{?SID}" method="post" enctype="multipart/form-data"><input type="file" name="paper-new"><input type="hidden" name="paper" value="{paper-id}"><input type="submit" value="upload"></form></td>
  <td><form action="{basepath}author_papers.php{?SID}" method="post" enctype="multipart/form-data"><input type="file" name="abstract-new"><input type="hidden" name="paper" value="{paper-id}"><input type="submit" value="upload"></form></td>
  <td><a href="{basepath}author_editpaper.php?paper={paper-id}{&SID}">properties</a></td>
  <td><form action="{basepath}author_papers.php{?SID}" method="post"><input type="checkbox" name="delete" value="{paper-id}">I am sure I want to delete this paper <input type="submit" value="delete"></form></td>
</tr>
