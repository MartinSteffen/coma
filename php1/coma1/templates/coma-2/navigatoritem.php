function createNavigatorContent($strPath) {
  $navigatorItem = new Template(TPLPATH.'navigatoritem.tpl');
  $i = 0;
  foreach ($strPath as $strName=>$strLink) {
    $strNavigatorAssocs['node'] = $strName;
    $strNavigatorAssocs['link'] = $strLink;
    $navigatorItem->assign($strNavigatorAssocs);
    $navigatorItem->parse()
    $strNavigator .= $navigatorItem->strOutput;
    $i++;
    if ($i < count($strPath)) {
      $strNavigator .= '  |  ';
    }
  }
  return $strNavigator;
}

