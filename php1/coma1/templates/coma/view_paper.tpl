
{if1<p class="message">{message}</p>}

<form action="{basepath}{targetpage}.php{?SID}" method="post" accept-charset="UTF-8">

<table class="formtable">
  <tr>
    <th colspan="2">Details of paper:</th>
  </tr>
  <tr>
    <td>
      Title:
    </td>
    <td>{title}</td>
  </tr>
  <tr>
    <td>
      Abstract:
    </td>
    <td>{abstract}</td>
  </tr>
  <tr>
    <td>
      Author:
    </td>
    <td><a href="#">{authorname}</a></td>
  </tr>
  <tr>
    <td>
      Co-Authors:
    </td>
    <td>{co-authornames}</td>
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
      <input type="hidden" name="action" value="view">
      <input type="submit" name="submit" value="View document" class="button">      
    </td>
  </tr>
</table>
</form>

<p>&nbsp;</p>
