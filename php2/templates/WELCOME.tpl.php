<? 
include("header.tpl.php");
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left">
      <p class="textBold">Welcome <? echo $_SESSION['userName'] ?>.</p>
      <p class="text">This is your control panel. On the left you may manage your 
        tasks.</p>
      <p class="text">In &quot;My Profile&quot; you may create new roles.</p>
    </td>
  </tr>
</table>
<?
include("footer.tpl.php");
?>