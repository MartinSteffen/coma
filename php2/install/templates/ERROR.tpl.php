<? include("header.tpl.php"); ?>
<TABLE>
	<TR>
		<TD align="center">
<H1>Oops...</H1>

<H3>The page you tried to access returned an error while processing.<BR>
This is our fault, we apologize.<BR>
<BR>
This error came up at module <?=d("module")?d("module"):"unknown" ?>, file <?=d("file")?d("file"):"unknown" ?>, line <?=d(line)?d(line):"unknown" ?>.</H3><BR>
<BR>
<H2>Please help us by submitting this bug to<BR>
<I><B>coma(at)softup-server(dot)de</B></I><BR>
Or by creating a <A href="http://snert.informatik.uni-kiel.de:8080/~swprakt/bugzilla/" target="_blank">Bugzilla Bug...</A></H2>

		</TD>
	</TR>
</TABLE>
<HR>

<B>SESSION VARIABLES:</B>
<PRE>
<?php
var_dump($_SESSION);
echo "\n\n\n</PRE><B>GLOBAL VARIABLES</B><PRE>";
var_dump($GLOBALS);
?>
</PRE>

<? include("footer.tpl.php"); ?>
