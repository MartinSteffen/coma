<?php
/**
 * Gibt den Inhalt einer Variable zurück
 *
 * Gibt den eine Templatevariable zurücl, wenn sie existiert. $_TPL muss nicht 
 * übergeben werden, $TPL['text'] = "Hallo"; wird nun mit r('text') 
 * zurückgegeben.
 * 
 * @param String String mit dem Namen der zurückzugebenden Variable
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
 * Gibt das übergebene Template aus
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
