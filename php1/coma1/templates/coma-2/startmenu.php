<?php
function openStartMenuItem ($intItem) {
  $menu = new Template(TPLPATH.'startmenu.tpl');
  $strMenuAssocs = defaultAssocArray();
  for ($i = 1; $i <= 4; $i++) {
    $strMenuAssocs['marked-'.$i] = '';
  }
  $strMenuAssocs['marked-'.$intItem] = ' class=\"markedmenuitem\"';
  $menu->assign($strMenuAssocs);
  $menu->parse();
  return $menu->strOutput;
}
?>