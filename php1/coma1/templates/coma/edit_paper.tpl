
{if9<p class="message">{message}</p>}

<form action="{basepath}{targetpage}{?SID}" method="post" enctype="multipart/form-data">
  <input type="hidden" name="action" value="submit">
  <input type="hidden" name="paperid" value="{paper_id}">

      <input type="file"   name="binFile">
      <input type="submit" name="upload" value="Upload" class="button">

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
        <option>application/force-download</option>
        <option selected>application/pdf</option>
        <option>application/postscript</option>
        <option>application/rtf</option>
        <option>application/tex</option>
        <option>application/x-tar</option>
        <option>application/zip</option>
      </select>
</form>
