<table class="list">
  <tr>
    <td> distibute as 'distribute' marked articles </td> 
    <td>
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}" />
       <input type="submit" name="submit" value="calculate" class="button" />
       </form>
    </td>
  </tr>

</table>

<table class="list">
  <tr class="listheader">
    <th width="20%" class="listheader"> Articlename      </th> 
    <th width="20%" class="listheader"> Author           </th> 
    <th width="20%" class="listheader"> State            </th>
    <th width="20%" class="listheader"> Rating           </th>
    <th width="20%" class="listheader"> Destribution     </th>
   </tr>

  <tr class="ft-tr"> 
    <td class="listitem-1"> 
        <a href=""> Neueste Rezepte </a> 
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}" />
       <input type="submit" name="submit" value="add reviewer" class="button" />
       </form>
    </td> 
    <td class="listitem-1"> 
        <a href=""> Robby Rabbit </a> 
    </td>


    <td class="listitem-1"> 
       none
    </td> 
    <td class="listitem-1"> &nbsp; </td>
    <td class="listitem-1"> 
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}" />
       <input type="submit" name="submit" value="distribute" class="button" />
       </form>
    </td>

  </tr>

  <tr class="ft-tr"> 
    <td class="listitem-2"> 
       <a href="">  Insomnia, Probleme eines Studenten </a> 
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}" />
       <input type="submit" name="submit" value="add reviewer" class="button" />
       </form>
    </td> 
    <td class="listitem-2"> 
        <a href=""> Robby Rabbit </a> 
    </td>


    <td class="listitem-2"> 
       none
    </td> 
    <td class="listitem-2"> &nbsp; </td>
    <td class="listitem-2"> 
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}" />
       <input type="submit" name="submit" value="distribute" class="button" />
       </form>  
    </td>

  </tr>
  <tr class="ft-tr"> 
    <td class="listitem-1"> 
       <a href=""> Hypnotics, ways to escape students insomnia  </a>
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}" />
       <input type="submit" name="submit" value="add reviewer" class="button" />
       </form>
    </td> 
    <td class="listitem-1"> 
        <a href=""> Grinse  Katze </a> 
    </td>


    <td class="listitem-1"> reviewed </td> 
    <td class="listitem-1"> 95%  </td>
    <td class="listitem-1"> distributed  </td>
  </tr>

  <tr class="ft-tr"> 
    <td class="listitem-2"> 
       <a href=""> Schlaflos in Seattle </a>
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}" />
       <input type="submit" name="submit" value="add reviewer" class="button" />
       </form>
    </td> 
    <td class="listitem-2"> 
        <a href=""> Mieze  Kater </a> 
    </td>


    <td class="listitem-2"> none </td> 
    <td class="listitem-2"> &nbsp; </td>
    <td class="listitem-2"> distributed </td>
  </tr>

</table>

<!- ---------------------------- Liste ausgeschlossener / bevorzugter Reviewer eine Artikels ------------------ -->  



<table class="list">
  <tr class="listheader">
    <th width="25%" class="listheader"> Reviewer Name  </th> 
    <th width="25%" class="listheader"> Exclude / Prefere </th> 
  </tr>

  <tr class="ft-tr"> 
    <td class="listitem-1"> 
        <a href="">  Rudy  Review </a> 
    </td>
    <td class="listitem-1">
       <form action="" method="post">
       <input type="hidden" name="confid" value="{confid}" />
       <input type="submit" name="submit" value="exclude  " class="button" />
       <input type="hidden" name="confid" value="{confid}" />
       <input type="submit" name="submit" value=" prefer  " class="button" />
       </form>
   </td>
 
</tr>

<tr>
    <td class="listitem-2"> 
        <a href=""> Randy  Rabbit </a> 
    </td>


    <td class="listitem-2"> 
       <form action="" method="post">
         exclude
       <input type="hidden" name="confid" value="{confid}" />
       <input type="submit" name="submit" value=" delete choice" class="button" />
       </form>
   </td>
</tr>


</table>