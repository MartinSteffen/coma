<table class="viewtable">
  <tr class="viewheader">
    <th class="viewheader" colspan="2">{name}&nbsp;</th>
  </tr>
  <tr class="viewlinep">
    <td class="viewline" colspan="2">{description}&nbsp;</td>
  </tr>
  <tr class="viewlineNoWrap">
    <td class="viewlineNoWrap">Takes place at:</td>
    <td class="viewlineNoWrap">{date}&nbsp;</td>
  </tr>
  <tr class="viewlineNoWrap">
    <td class="viewlineNoWrap">Website:</td>
    <td class="viewlineNoWrap">{if1<a href="{link}" target="_blank" class="link">}{link}{if1</a>}&nbsp;</td>
  </tr>
  <tr class="viewlineNoWrap">
    <td class="viewlineNoWrap" colspan="2">&nbsp;</td>
  </tr>
  <tr class="viewlineNoWrap">
    <td class="viewlineNoWrap">Number of papers to chose:</td>
    <td class="viewlineNoWrap">{paper_number}&nbsp;</td>
  </tr>
  <tr class="viewlineNoWrap">
    <td class="viewlineNoWrap">Submit paper abstracts until:</td>
    <td class="viewlineNoWrap">{abstract_deadline}&nbsp;</td>
  </tr>
  <tr class="viewlineNoWrap">
    <td class="viewlineNoWrap">Submit papers until:</td>
    <td class="viewlineNoWrap">{paper_deadline}&nbsp;</td>
  </tr>
  <tr class="viewlineNoWrap">
    <td class="viewlineNoWrap">Submit final paper version until:</td>
    <td class="viewlineNoWrap">{final_deadline}&nbsp;</td>
  </tr>
  <tr class="viewlineNoWrap">
    <td class="viewlineNoWrap">Submit reviews until:</td>
    <td class="viewlineNoWrap">{review_deadline}&nbsp;</td>
  </tr>
  <tr class="viewlineNoWrap">
    <td class="viewlineNoWrap">Decide papers until:</td>
    <td class="viewlineNoWrap">{notification}&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>

<p class="message">
  Show <a href="{basepath}user_users.php?showchairs{&SID}" class="link">list of all chairs</a> of this conference.<br>
  Show <a href="{basepath}user_papers.php?showacceptedpapers{&SID}" class="link">list of all papers</a> accepted for this conference.
</p>
