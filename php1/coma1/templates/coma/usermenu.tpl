<table class="menu">
  <tr>
    <td class="menu"><a href="{basepath}user_profile.php{?SID}" class="{if1 marked}menu">
      Profile</a>
    </td>
  </tr>
  {menu1}
  {menu2}
  {menu3}
  {menu4}
  <tr>
    <td class="menu"><a href="{basepath}user_conference.php{?SID}" class="{if2 marked}menu">
      Conference</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}user_users.php?showchairs{&SID}" class="{if7 marked}submenu">
      List of chairs</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}user_papers.php?showacceptedpapers{&SID}" class="{if8 marked}submenu">
      Accepted papers</a>
    </td>
  </tr>
  <tr>
    <td class="menu"><a href="{basepath}forum.php?showforums=0{&SID}" class="{ifF0 marked}menu">
      Forums</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}forum.php?showforums=4{&SID}" class="{ifF4 marked}submenu">Global forums</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}forum.php?showforums=1{&SID}" class="{ifF1 marked}submenu">Open forums</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}forum.php?showforums=3{&SID}" class="{ifF3 marked}submenu">Paper forums</a>
    </td>
  </tr>
  <tr>
    <td class="submenu"><a href="{basepath}forum.php?showforums=2{&SID}" class="{ifF2 marked}submenu">Chair forums</a>
    </td>
  </tr>
  <tr>
    <td class="menu"><a href="{basepath}help.php{?SID}" class="{if5 marked}menu">
      Help</a>
    </td>
  </tr>

  <tr>
    <td class="menu"><a href="{basepath}logout_conference.php{?SID}" class="{if6 marked}menu">
      Select Conference</a>
    </td>
  </tr>

</table>
