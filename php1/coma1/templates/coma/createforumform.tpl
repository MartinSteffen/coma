<p>Create new {type} forum:<br>
<form action="{basepath}forum.php{?SID}" method="post">
Title: <input type="text" size="40" name="title">
{if1 <br>For Paper: <select name="paperid" size="4">}{paperoptions}{if2 </select>}
<input type="hidden" name="type" value="{inttype}">
<input type="hidden" name="createforum" value="true">
</form></p>