
<p class="center">

<table class="formtable">
  <tr>
    <th colspan="2">{name}</th>
  </tr>
  <tr>
    <td>
     {description}
    </td>
  </tr>
  <tr>    
    <td>      
      Takes place from <span class="emph">{start_date}</span> to <span class="emph">{end_date}</span>.
    </td>
  </tr>
  <tr>
    <td>
      Website: <a href="{link}" target="_blank">{link}</a>
    </td>
  </tr>
</table>
</p>

<form action="{basepath}main_conferences.php{?SID}" method="post">
  <input type="submit" name="submit" value="Return" class="button" />
</form>

<p>&nbsp;</p>