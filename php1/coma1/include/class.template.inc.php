<?php
/**
 * Klasse zum Parsen der Templates
 *
 * $Id$
 *
 */

if ( !defined('IN_COMA1') )
{
  exit('Hacking attempt');
}

class Template {

  function Template()
  {
    return true;
  }
  
  function readTemplate( $template )
  {
    $contents = file_get_contents( $template )
    // vor PHP 4.3 waere es das gewesen:
    // $contents = implode("", @file($template) );
    if ( (!$contents) or (empty($contents)))
    {
      this->error("Could not read Template [$template]");
    }
    return $contents;
  }

} // End Class

?>