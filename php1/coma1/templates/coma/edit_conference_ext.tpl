
{if1<p class="message">{message}</p>}

<form action="{basepath}chair_confconfig.php?{SID}" method="post">

<table class="formtable">
  <tr>
    <th colspan="2">Configurate conference (advanced):</th>
  </tr>
  <tr>
    <td>
      Min. number of papers:
    </td>
    <td>
      <input type="text" name="min_papers" size="8" maxlength="8" value="{min_papers}"/ >
    </td>
  </tr>
  <tr>
    <td>
      Max. number of papers:
    </td>
    <td>
      <input type="text" name="max_papers" size="8" maxlength="8" value="{max_papers}"/ >
    </td>
  </tr>
  <tr>
    <td>
      Min. number of reviewers per paper:
    </td>
    <td>
      <input type="text" name="min_reviews" size="8" maxlength="8" value="{min_reviews}" />
    </td>
  </tr>
  <tr>
    <td>
      Default number of reviewers per paper:
    </td>
    <td>
      <input type="text" name="def_reviews" size="8" maxlength="8" value="{def_reviews}" />
    </td>
  </tr>
  <tr>
    <td>
      Critical variance of different paper ratings:
    </td>
    <td>
      <input type="text" name="variance" size="8" maxlength="8" value="{variance}" />
    </td>
  </tr>

  <tr>
    <td colspan="2">
      Activate accounts automatically
      <input type="checkbox" name="auto_actaccount" value="1"{if2 checked} />
    </td>
  </tr>
  <tr>
    <td colspan="2">
      Start discussion of critical papers automatically
      <input type="checkbox" name="auto_paperforum" value="1"{if3 checked} />
    </td>
  </tr>
  <tr>
    <td colspan="2">
      Add reviewers to critical papers automatically
      <input type="checkbox" name="auto_addreviewer" value="1"{if4 checked} />
    </td>
  </tr>
  <tr>
    <td>
      Number of automatically added reviewers:
    </td>
    <td>
      <input type="text" name="auto_numreviewer" size="16" maxlength="20" value="{auto_numreviewer}" />
    </td>
  </tr>

  <tr>
    <td colspan="2">
      <input type="hidden" name="name" value="{name}" />
      <input type="hidden" name="description" value="{description}" />
      <input type="hidden" name="homepage" value="{homepage}" />
      <input type="hidden" name="start_date" value="{start_dl}" />
      <input type="hidden" name="end_date" value="{end_dl}" />
      <input type="hidden" name="abstract_dl" value="{abstract_dl}" />
      <input type="hidden" name="paper_dl" value="{paper_dl}" />
      <input type="hidden" name="review_dl" value="{review_dl}" />
      <input type="hidden" name="final_dl" value="{final_dl}" />
      <input type="hidden" name="notification" value="{notification}" />
      <input type="hidden" name="criteria" value="{criteria}" />
      <input type="hidden" name="topics" value="{topics}" />
      <input type="hidden" name="crit_max" value="{crit_max}" />
      <input type="hidden" name="crit_descr" value="{crit_descr}" />

      <input type="hidden" name="action" value="submit" />
      <input type="submit" name="submit" value="Submit changes" class="button" />
      <input type="submit" name="simple_config" value="Simple settings" class="button" />
    </td>
  </tr>
</table>
</form>

<form action="{basepath}chair_confconfig.php?{SID}" method="post">
  <input type="submit" name="cancel" value="Cancel" class="button" />
</form>

