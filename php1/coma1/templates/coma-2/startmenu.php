<?php
function openStartMenuItem ($intItem) {
  $menu = new Template(TPLPATH.'startmenu.tpl');
  $strMenuAccocs = defaultAssocArray();
  for ($i = 1; $i <= 4; $i++) {
    $strMenuAccocs['marked-'.$i] = '';
  }
  $strMenuAccocs['marked-'.$intItem] = ' class=\"markedmenuitem\"';
  $menu->assign(strMenuAccocs);
  $menu->parse();
  return $menu->strOutput;
}
?>