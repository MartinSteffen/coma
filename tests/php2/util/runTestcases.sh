#!/bin/sh
#
#     This script runs testcases in the TESTCASES directory,
# using Puretest runner. The files must be defined in the ORDER
# file, because the order they are runned is important
#
# @author Thiago Tonelli Bartolomei
#

# Source configs
. config/tests.conf

# HTML "templates"
HEADER="<html>\n
<head>\n
<title>Regression Tests</title>\n
<script>\n
\n
if (document.getElementById){\n
    document.write('<style type=\"text/css\">\\\n')\n
    document.write('.subitem{display: none;}\\\n')\n
    document.write('</style>\\\n')\n
}\n
\n
function switchItem(obj){\n
    if(document.getElementById){\n
        var el = document.getElementById(obj);\n
        var ar = document.getElementById(\"testcasesdiv\").getElementsByTagName(\"span\");\n
        if(el.style.display != \"block\"){\n
            for (var i=0; i<ar.length; i++){\n
                if (ar[i].className==\"subitem\") ar[i].style.display = \"none\";\n
            }\n
            el.style.display = \"block\";\n
        }else{\n
            el.style.display = \"none\";\n
        }\n
     }\n
}\n
</script>\n
\n
<style type=\"text/css\">\n
 .testcasetitle{
 cursor:pointer;
 margin: 0;
 padding: 0;
 background-color:#EBF2D3;
 color:#5E902F;
 width:800px;
 text-align:center;
 font-family: Verdana, Arial, Helvetica, sans-serif;
 font-size: 15px;
 font-weight:bold;
 border:1px solid #72AF39;
 }
 .testcasefailedtitle{
 cursor:pointer;
 margin: 0;
 padding: 0;
 background-color:#FF7373;
 color:#000000;
 width:800px;
 text-align:center;
 font-family: Verdana, Arial, Helvetica, sans-serif;
 font-size: 15px;
 font-weight:bold;
 border:1px solid #F60606;
 }
 .subitem{
 margin: 0;
 padding: 0;
 width:800px;
 background-color:#d9d9d9;
 font-family: Verdana, Arial, Helvetica, sans-serif;
 text-align:left;
 font-size: 12px;
 }

</style>\n
</script>\n
\n
</head>\n
<body>\n
<center>\n
\n
<h2>Regression Tests Results generated for Php2</h2>\n
<h4>`date`</h4>\n
\n
<!-- main table -->\n
<div id=\"testcasesdiv\">\n
"
FOOTER="
</div>
<!-- end of main table -->

</center>
</body>
</html>"


echo "Executing test cases from $TESTCASES..."

# Create the html file
echo -e $HEADER > $DEST

# For each test case in the list, execute it.
COUNT=0
for script in $TESTS
do
	echo "Executing $script.plc..."
	if [ "$1x" = "x" ]; then
		$RUNNER runner -v DEBUG $TESTCASES/$script.plc > $TMPDIR/$script.results
	fi
	$JAVA -cp util/Puretest2Html.jar org.macacos.coma.Puretest2Html $TMPDIR/$script.results >> $DEST
	COUNT=`expr $COUNT + 1`
done

# Close the html file
echo $FOOTER >> $DEST
