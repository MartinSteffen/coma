
{if9<p class="message">{message}</p>}

<form action="{basepath}{targetpage}{?SID}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
  <input type="hidden" name="action" value="submit">
  <input type="hidden" name="paperid" value="{paper_id}">
  <input type="hidden" name="coauthors_num" value="{coauthors_num}">

<table class="formtable">
  <tr>
    <th colspan="2">Edit paper:</th>
  </tr>
  <tr>
    <td>
      Title:
    </td>
    <td>
      <input type="text" name="title" size="48" maxlength="127" value="{title}"> *
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
      <input type="submit" name="submit" value="Accept changes" class="button">
      <input type="reset"  name="reset" value="Reset settings" class="button">
    </td>
  </tr>
</table>

<table class="formtable">
  <tr>
    <td>Upload new document:</td>
    <td colspan="2">
      <input type="file" name="binFile">
      <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
      <input type="submit" name="upload" value="Upload" class="button">
    </td>
  </tr>
  <tr>
    <td>MIME type:</td>
    <td>
      <select name="mimetype" size="1">
        <option>image/gif</option>
        <option>image/jpeg</option>
        <option>text/html</option>        
        <option>text/plain</option>
        <option>text/richtext</option>
        <option>text/rtf</option>
        <option>application/latex</option>
        <option>application/msexcel</option>
        <option>application/mspowerpoint</option>
        <option>application/msword</option>
        <option selected>application/pdf</option>
        <option>application/postscript</option>
        <option>application/rtf</option>
        <option>application/tex</option>
        <option>application/x-tar</option>
        <option>application/zip</option>
      </select>
    </td>
</table>

<table class="formtable">
  <tr>
    <td>Delete this paper?
      <input type="checkbox" name="confirm_delete" value="1">
    </td>
    <td>
      <input type="submit" name="delete" value="Delete" class="button">
    </td>
  </tr>
</table>
</form>
