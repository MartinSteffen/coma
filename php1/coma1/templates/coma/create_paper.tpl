
{if9<p class="message-failed">{message}</p>}

<form action="{basepath}{targetpage}{?SID}" method="post" accept-charset="UTF-8">
  <input type="hidden" name="action" value="submit">
  <input type="hidden" name="coauthors_num" value="{coauthors_num}">

<table class="formtable">
  <tr>
    <th colspan="2">Add a new paper:</th>
  </tr>
  <tr>
    <td>
      <span class="red">*</span>Title:
    </td>
    <td>
      <input type="text" name="title" size="48" maxlength="127" value="{title}">
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
    <td>{author_name}</td>
  </tr>
  <tr>
    <td colspan="2">
      <table class="formlist" width="100%">
        <tr class="formlistheader">
          <td class="formlistheader">Co-authors:</td>
          <td class="formlistheader">&nbsp;</td>
        </tr>
        {coauthor_lines}
        <tr class="formlistitem">
          <td class="formlistitem">
            <input type="text" name="coauthor" size="48" maxlength="127" value="">
          </td>
          <td class="formlistitem">
            <input type="submit" name="add_coauthor" value="add" class="smallbutton">
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <table class="formlist" width="100%">
        <tr class="formlistheader">
          <td class="formlistheader">Topics:</td>
          <td class="formlistheader">&nbsp;</td>
        </tr>
        {topic_lines}
      </table>
    </td>
   </tr>
  <tr>
    <td colspan="2">
      <input type="submit" name="submit" value="Add paper" class="button">
      <form action="{basepath}author_papers.php{?SID}" method="post" accept-charset="UTF-8">
        <input type="submit" name="cancel" value="Cancel" class="button">
      </form>
    </td>
  </tr>
</table>
</form>
