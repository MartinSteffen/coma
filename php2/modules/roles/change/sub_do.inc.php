<?php
$roles[] = array("id"=>1, "role"=>"Admin");
$roles[] = array("id"=>2, "role"=>"Chair");
$roles[] = array("id"=>3, "role"=>"Lector");
$roles[] = array("id"=>4, "role"=>"Author");
// $roles[] = array("id"=>5, "role"=>"Guest");

$TPL['roles'] = $roles;
template("ROLES_change");

?>
