
<p class="message" align="center"> {message} </p>


<div align="center">
<table>
<tr> <td> <b> neue Konferenz erstellen: <br>  <br> </b> </td>   <td>&nbsp; </td></tr>

<tr>
  <td valign="top"> 
       <form action="{basepath}start_neue.php{SID}" method="post">  

       Konferenzname: <br>
       <input type="text" name="name" size="80" maxlength="127" /> <br> <br>

       Homepage: <br>
       <input type="text" name="homepage" size="80" maxlength="127" /> <br> <br>

       Beschreibung: <br> 
       <input type="text" name="description" size="80" maxlength="250" /> <br> <br>
  
       abstrakte Deadline: <br> Tag Monat Jahr <br> 
       <input type="text" name="abstract_submission_deadline_d"   size="2" maxlength="2" /> 
       <input type="text" name="abstract_submission_deadline_m"   size="2" maxlength="2" /> 
       <input type="text" name="abstract_submission_deadline_y"   size="4" maxlength="4" /> &nbsp; 0 Uhr <br> <br>

       Deadline zum Einreichen von Paper: <br>
       <input type="text" name="paper_submission_deadline_d"      size="2" maxlength="2" />
       <input type="text" name="paper_submission_deadline_m"      size="2" maxlength="2" />
       <input type="text" name="paper_submission_deadline_y"      size="4" maxlength="4" /> &nbsp; 0 Uhr <br> <br>

       Review Deadline: <br>
       <input type="text" name="review_deadline_d" size="2" maxlength="2" />
       <input type="text" name="review_deadline_m" size="2" maxlength="2" />
       <input type="text" name="review_deadline_y" size="4" maxlength="4" /> &nbsp; 0 Uhr <br> <br>

       Deadline der Finalen Version: <br>
       <input type="text" name="final_version_deadline_d" size="2" maxlength="2" />
       <input type="text" name="final_version_deadline_m" size="2" maxlength="2" />
       <input type="text" name="final_version_deadline_y" size="4" maxlength="4" /> &nbsp; 0 Uhr <br> <br>

       Notifikation: <br>
       <input type="text" name="notification_d" size="2" maxlength="2" />
       <input type="text" name="notification_m" size="2" maxlength="2" />
       <input type="text" name="notification_y" size="4" maxlength="4" /> &nbsp; 0 Uhr <br> <br>

       Konferenzstart: <br>
       <input type="text" name=" conference_start_d" size="2" maxlength="2" />
       <input type="text" name=" conference_start_m" size="2" maxlength="2" />
       <input type="text" name=" conference_start_y" size="4" maxlength="4" /> &nbsp; 0 Uhr <br> <br>

       Ende der Konferenz: <br>
       <input type="text" name="conference_end_d" size="2" maxlength="2" />
       <input type="text" name="conference_end_m" size="2" maxlength="2" />
       <input type="text" name="conference_end_y" size="4" maxlength="4" />  &nbsp; 0 Uhr <br> <br>

       minimale Anzahl von Reviews für ein Paper:  <br>
       <input type="text" name="min_reviews_per_paper" size="4" maxlength="10" /> <br> <br>



       <input type="hidden" name="action" value="register" /> <br> <br> 
       <input type="submit" name="submit" value="Register" /> <br> <br>
       </form> 
  </td>
</tr>
</table>
</div>