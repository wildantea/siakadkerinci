<?php
session_start();
include "../../inc/config.php";


$data_group = $db2->query("select level from sys_group_users where id > 11");

echo "<pre>";


foreach ($data_group as $dt) {
	$admin_role = $db2->query("select * from sys_menu_role where group_level='administrator'");
	foreach ($admin_role as $admin) {
		if ($admin->import_act=='') {
			$import_act = 'N';
		}
		$data_insert[] = array(
			'id_menu' => $admin->id_menu,
			'group_level' => $dt->level,
			'read_act' => $admin->read_act,
			'insert_act' => $admin->insert_act,
			'update_act' => $admin->update_act,
			'delete_act' => $admin->delete_act,
			'import_act' => $import_act
		);
		
	}
	print_r($data_insert);
	$db2->insertMulti('sys_menu_role',$data_insert);
	$data_insert = array();
	
}

//749