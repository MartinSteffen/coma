<?
if(isset($_POST['role'])){
	$roleID	= $_POST['role'];
	switch ($roleID){
		case 1:
			$role = "Admin";
			break;
		case 2:
			$role = "Chair";
			break;
		case 3:
			$role = "Lector";
			break;
		case 4:
			$role = "Author";
			break;
		default:
			echo("Usage Roles/change/save: No such RoleID specified");
			exit();
			}
	$_SESSION['role'] = $role;	}

redirect("roles");
?>
