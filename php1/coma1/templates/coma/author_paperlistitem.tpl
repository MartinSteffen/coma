<tr>
  <td>{title}</td>
  <td>{co_authors}</td>
  <td>{status}</td>
  <td>{avgrating}</td>
  <td><a href="{abstract_link}">read/download abstract</a></td>
  <td>{last_edited}</td>
  <td><a href="{paper_link}">read/download paper</a></td>
  <td><form action="{basepath}author_papers.php{?SID}" method="post" enctype="multipart/form-data" accept-charset="UTF-8"><input type="file" name="paper-new"><input type="hidden" name="paper" value="{paper_id}"><input type="submit" value="upload"></form></td>
  <td><form action="{basepath}author_papers.php{?SID}" method="post" enctype="multipart/form-data" accept-charset="UTF-8"><input type="file" name="abstract-new"><input type="hidden" name="paper" value="{paper_id}"><input type="submit" value="upload"></form></td>
  <td><a href="{basepath}author_editpaper.php?paper={paper_id}{&SID}">properties</a></td>
  <td><form action="{basepath}author_papers.php{?SID}" method="post" accept-charset="UTF-8"><input type="checkbox" name="delete" value="{paper_id}">I am sure I want to delete this paper <input type="submit" value="delete"></form></td>
</tr>
