
{if9<p class="message">{message}</p>}

<form action="{basepath}{targetpage}.php{?SID}" method="post" accept-charset="UTF-8">

<table class="formtable">
  <tr>
    <th colspan="2">Edit paper:</th>
  </tr>
  <tr>
    <td>
      Title:
    </td>
    <td>
      <input type="text" name="title" size="32" maxlength="127" value="{title}"> *
    </td>
  </tr>
  <tr>
    <td>
      Abstract:
    </td>
    <td>
      <textarea name="description" rows="4" cols="48">{abstract}</textarea>
    </td>
  </tr>
  <tr>
    <td>
      Author:
    </td>
    <td><a href="{basepath}user_userdetails.php?userid={author_id}{&SID}>{author_name}</a></td>
  </tr>
  <tr>
    <td>
      Co-Authors:
    </td>
    <td>
      <table class="formlist">
        {coauthor_lines}
        <tr>
          <td>
            <input type="text" name="coauthor" size="32" maxlength="127" value="">
          </td>
          <td>
            <input type="submit" name="add_coauthor" value="add" class="smallbutton">
          </td>
        </tr>  
      </table>
    </td>      
  </tr>
  <tr>
    <td>
      Version:
    </td>
    <td>{version}</td>
  </tr>
  <tr>
    <td>
      Last edited:
    </td>
    <td>{last_edited}</td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="hidden" name="action" value="update">
      <input type="submit" name="submit" value="Accept changes" class="button">
      <input type="reset"  name="reset" value="Reset settings" class="button">
    </td>
  </tr>
</table>
</form>

<form action="{basepath}{targetpage}.php{?SID}" method="post" accept-charset="UTF-8">
<table class="formtable">
  <tr>
    <th>Upload new document:</th>
    <td colspan="2">
      <input type="hidden" name="action" value="upload">
      <input type="file" name="paperfile">      
      <input type="submit" name="submit" value="Upload" class="button">
    </td>
</table>
</form>

<form action="{basepath}{targetpage}.php{?SID}" method="post" accept-charset="UTF-8">
<table class="formtable">
  <tr>
    <td colspan="2">      
      <input type="checkbox" name="verify_delete" value="1">
      <input type="hidden" name="action" value="delete">
      <input type="submit" name="submit" value="Delete paper" class="button">
    </td>
  </tr>
</table>
</form>
