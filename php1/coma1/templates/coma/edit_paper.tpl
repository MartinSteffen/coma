
{if1<p class="message">{message}</p>}

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
      <input type="text" name="name" size="32" maxlength="127" value="{title}"> *
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
    <td><a href="#">{authorname}</a></td>
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
    <td>{last-edited}</td>
  </tr>
  <tr>
    <td colspan="2">
      <input type="hidden" name="action" value="submit">
      <input type="submit" name="submit" value="Submit changes" class="button">
      <input type="reset"  name="reset" value="Reset settings" class="button">
    </td>
  </tr>
</table>
</form>

<form action="{basepath}{targetpage}.php{?SID}" method="post">
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

<form action="{basepath}{targetpage}.php{?SID}" method="post">
<table class="formtable">
  <tr>
    <th>Add co-author:</th>
    <td>
      <input type="text" name="coauthor" size="32" maxlength="127" value="">
    </td>
    <td>      
      <input type="hidden" name="action" value="add_coauthor">      
      <input type="submit" name="submit" value="Add" class="button">
    </td>
  </tr>
</table>
</form>

<form action="{basepath}{targetpage}.php{?SID}" method="post">
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
