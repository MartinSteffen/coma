     <!-- Stylesheet for servlet/servlets/ShowReports. author:ums  -->
     <!-- No, not much functionality yet. (Well, there's not much in ShowReports either) -->
     <!-- see NO PAGE YET for demo of showreports. -->

     <xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

       <xsl:output method="xml" 
         indent="yes" 
         doctype-public="-//W3C//DTD XHTML 1.1//EN"
         doctype-system="http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"
         encoding="iso-8859-1"
         />
       <xsl:include href="navcolumn.xsl" />
       <xsl:include href="stderrors.xsl" />

       <xsl:template match="/">
         <html>
           <head>
             <link rel="stylesheet" type="text/css" href="style/css/comastyle.css" />
             <title>
               JCoMa: 
               <xsl:value-of select="/content/pagetitle" />
             </title>
             <xsl:value-of select="meta" />
           </head>
           <body>
             <xsl:call-template name="navcolumn" />
             <xsl:apply-templates select="/result" />
           </body>
         </html>
       </xsl:template>

       <xsl:template match="/result">
         <div class="header"><h1><xsl:value-of select="//pagetitle" /></h1></div>
         <div class="content">

           <xsl:call-template name="stderrors" />
           <!--
           <xsl:apply-templates select="noSession" />
           <xsl:apply-templates select="databaseError" />
           <xsl:apply-templates select="unauthorized" /> 
           -->

           <xsl:choose>

             <xsl:when test="allReportsIntro">
               These are all the reports visible to you at this time.
               <xsl:apply-templates select="info" />
             </xsl:when>

             <xsl:when test="detailedReportView">
               <xsl:apply-templates select="ReviewReport" />
             </xsl:when>

             <xsl:otherwise>Neither report selected, nor showing all
             reports: Nothing to do here?</xsl:otherwise>
           </xsl:choose>
         </div>
       </xsl:template>

       <xsl:template match="ReviewReport">
         <h2>Review Report</h2>
         <div>
           <table>
             <tr>
               <th>On:</th> 
               <td>
                 <xsl:value-of select="paper/person/last_name" />,
                 <a>
                   <xsl:attribute name="href">/papers/<xsl:value-of select="paper/filename" /></xsl:attribute>
                   <xsl:value-of select="paper/title" />
                 </a>
               </td>
             </tr>
             <tr>
               <th>By:</th>
               <td>
                 <xsl:value-of select="person/first_name" />
                 <xsl:value-of select="person/last_name" />
               </td>
             </tr>
             <tr>
               <th>Remarks:</th><td><xsl:value-of select="remarks" /></td>
             </tr>
             <xsl:for-each select="rating">
               <tr>
                 <th><xsl:value-of select="criterion/name" />:</th>
                 <td>
                   <xsl:value-of select="grade" /> 
                   (out of <xsl:value-of select="criterion/maxValue" />):
                   <xsl:value-of select="comment" />
                 </td>
               </tr>
             </xsl:for-each>
             <tr>
               <th>Summary:</th><td><xsl:value-of select="summary" /></td>
             </tr>
             <xsl:if test="ancestor-or-self::*//navcolumn/isChair">
               <!--yes, that's a hack :-D -->
               <tr>
                 <th>Confidental remarks</th><td><xsl:value-of select="confidental" /></td>
               </tr>
             </xsl:if>
           </table>
         </div>
       </xsl:template>

       <xsl:template match="info">      
       <xsl:for-each select="reportblock">
         <div>
           <xsl:if test="paper">
             <div><h2>On 
             <xsl:value-of select="paper/person/last_name" />:
             <i><a>
             <xsl:attribute name="href">/papers/<xsl:value-of select="paper/filename" /></xsl:attribute>
               <xsl:value-of select="paper/title" />
             </a>
             </i> </h2></div>
           </xsl:if>
           <xsl:for-each select="ReviewReport">
             <div>
               <h3><xsl:value-of select="person/last_name" />:</h3>
               <xsl:value-of select="summary" />
             </div>
           </xsl:for-each>
           <xsl:if test="statistics">
             <div>
               <h3>Overall numerical verdict:</h3>
               <table>
                 <tr><th>Criterion</th><th>avg</th><th>rms</th></tr>
                 <xsl:for-each select="statistics/criterion">
                   <tr>
                     <td><xsl:value-of select="@name" /></td>
                     <td><xsl:value-of select="mean" /></td>
                     <td><xsl:value-of select="rms" /></td>
                   </tr>
                 </xsl:for-each>
               </table>
             </div>
           </xsl:if>
         </div>
       </xsl:for-each>

     </xsl:template>

     <xsl:template match="noSession">
       <div>
         You have no session open. Maybe you forgot to log in, or your
         browser does not support cookies.
       </div>
     </xsl:template>

     <xsl:template match="databaseError">
       <div>
         Something went wrong with the database backend. Please tell a
         wizard to look at the logs.
       </div>
     </xsl:template>

     <xsl:template match="unauthorized">
       <div>
         You're not currently allowed to know that. I suspect you are not
         logged in.
       </div>
     </xsl:template>

   </xsl:stylesheet>