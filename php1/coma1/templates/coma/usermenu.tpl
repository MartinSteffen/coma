<table class="menu">
  <tr>
    <td class="menu"><a href="{basepath}user_profile.php{?SID}" class="{if01 marked}menu">
      Profile</a>
    </td>
  </tr>
  {menu1}
  {menu2}
  {menu3}
  {menu4}
  <tr>
    <td class="menu"><a href="{basepath}user_conference.php{?SID}" class="{if02 marked}menu">
      Conference</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}user_users.php?showchairs{&SID}" class="{if07 marked}submenu">
      List of chairs</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}user_papers.php?showacceptedpapers{&SID}" class="{if08 marked}submenu">
      Accepted papers</a>
    </td>
  </tr>
  <tr>
    <td class="menu"><a href="{basepath}forum.php?showforums=0{&SID}" class="{if10 marked}menu">
      Forums</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}forum.php?showforums=1{&SID}" class="{if11 marked}submenu">Open forums</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}forum.php?showforums=3{&SID}" class="{if13 marked}submenu">Paper forums</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}forum.php?showforums=2{&SID}" class="{if12 marked}submenu">Chair forums</a>
    </td>
  </tr>
  <tr>
    <td class="menu"><a href="{basepath}help.php{?SID}" class="{if05 marked}menu">
      Help</a>
    </td>
  </tr>

  <tr>
    <td class="menu"><a href="{basepath}logout_conference.php{?SID}" class="{if06 marked}menu">
      Select Conference</a>
    </td>
  </tr>

</table>
