
{if9<p class="message-failed">{message}</p>}

<p class="message">
  Below you can submit your attitude towards certain {if1<a href="#topics" class="link">topics</a> or}
  <a href="#papers" class="link">papers</a>:<br>
  Selected <span class="emph">prefer</span> if you favor to review a certain paper{if1 or papers about a certain topic}.<br>
  Selected <span class="emph">deny</span> if you would like to review a certain paper
  less than the others.<br>
  Selected <span class="emph">exclude</span> if you want to state that you won't
  review a certain paper at all.
</p>

<form action="{basepath}{targetpage}{?SID}" method="post" accept-charset="UTF-8">
  <input type="hidden" name="action" value="submit">

{if1
<a name="topics"></a>
<table class="list">
  <tr class="listheader">
    <th class="listheader" colspan="2">Attitudes towards topics for reviewing:</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">&nbsp;</th>
  </tr>
  {topic_lines}
  <tr>
    <td colspan="2">
      <input type="submit" name="submit" value="Accept changes" class="button">
      <input type="reset"  name="reset" value="Reset settings" class="button">
    </td>
  </tr>
</table>
}
<a name="papers"></a>
<table class="list">
  <tr class="listheader">
    <th class="listheader" colspan="2">Attitudes towards papers for reviewing:</th>
    <th class="listheader">&nbsp;</th>
    <th class="listheader">&nbsp;</th>
  </tr>
  {paper_lines}
  <tr>
    <td colspan="2">
      <input type="submit" name="submit" value="Accept changes" class="button">
      <input type="reset"  name="reset" value="Reset settings" class="button">
    </td>
  </tr>
</table>

</form>

<p>&nbsp;</p>