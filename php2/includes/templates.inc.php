<?php
/**
 * Gibt den Inhalt einer Variable zur�ck
 *
 * Gibt den eine Templatevariable zur�cl, wenn sie existiert. $_TPL muss nicht 
 * �bergeben werden, $TPL['text'] = "Hallo"; wird nun mit r('text') 
 * zur�ckgegeben.
 * 
 * @param String String mit dem Namen der zur�ckzugebenden Variable
 * @returns mixed Inhalt der Variable oder false, wenn die Variable nicht 
 * existiert
 */
function d()
{
	// {{{
	global $TPL;
	
    if (isset($TPL))
    {
        $var = $TPL;
    }
    else
    {
        $var = false;
    }
    for($i = 0; $var !== false && $i < func_num_args(); $i++)
    {
        if (isset($var[func_get_arg($i)]) && is_array($var))
        {
            $var = $var[func_get_arg($i)];
        }
        else
        {
            $var = false;
        }
    }
    return $var;
	// }}}
}

/**
 * Gibt ein Template aus
 *
 * Gibt das �bergebene Template aus
 *
 * @param String Dateiname des auszugebenden Templates
 * @returns null
 */
function template($datei)
{
    global $TPL, $smarty;
	// {{{
	$tpl = "templates/". basename($datei). ".tpl.php";

	if (!file_exists($tpl))
	{
		exit("Das Template <b>$datei</b> konnte nicht gefunden werden.");
	}
	if (!is_readable($tpl))
	{
		exit("Das Template <b>$datei</b> existiert, die Rechte sind aber nicht ausreichend, um es zu lesen!");
	}

	require($tpl);
	// }}}
}
