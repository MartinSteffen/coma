<?php
function createNavigatorContent($strPath) {
  $navigatorItem = new Template(TPLPATH.'navigatoritem.tpl');
  $i = 0;
  $strNavigator = '';
  $strNavigatorAssocs = defaultAssocArray();
  foreach ($strPath as $strName=>$strLink) {
    if ($strLink != '') {
      $strNavigatorAssocs['node'] = $strName;
      $strNavigatorAssocs['link'] = $strLink;
      $navigatorItem->assign($strNavigatorAssocs);
      $navigatorItem->parse();
      $strNavigator .= $navigatorItem->strOutput;
    }
    else {
      $strNavigator .= $strName;
    }
    $i++;
    if ($i < count($strPath)) {
      $strNavigator .= '  |  ';
    }
  }
  return $strNavigator;
}
?>