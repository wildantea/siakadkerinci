<?php
session_start();
include "../../inc/config.php";
session_check_adm();
switch ($_GET["act"]) {
	
	case 'change_read':
		print_r($_POST);
		$get_read = $db->fetch_custom_single("select read_access from sys_token where id=?",array('id' => $_POST['token_id']));
		$get_read = json_decode($get_read->read_access);
		$read_access = $_POST['data_act'];
		$token_enable = $get_read->{'token'};
		$json_data = '{"access":'.$read_access.',"token":'.$token_enable.'}';
		$data = array('read_access' => $json_data);
		$update = $db->update('sys_token',$data,'id',$_POST['token_id']);
		break;
	case 'change_read_token':
		print_r($_POST);
		$get_read = $db->fetch_custom_single("select read_access from sys_token where id=?",array('id' => $_POST['token_id']));
		$get_read = json_decode($get_read->read_access);
		$read_access =  $get_read->{'access'};
		$token_enable = $_POST['data_act'];
		$json_data = '{"access":'.$read_access.',"token":'.$token_enable.'}';
		$data = array('read_access' => $json_data);
		$update = $db->update('sys_token',$data,'id',$_POST['token_id']);
		break;
	case 'change_create':
		print_r($_POST);
		$get_create = $db->fetch_custom_single("select create_access from sys_token where id=?",array('id' => $_POST['token_id']));
		$get_create = json_decode($get_create->create_access);
		$create_access = $_POST['data_act'];
		$token_enable = $get_create->{'token'};
		$json_data = '{"access":'.$create_access.',"token":'.$token_enable.'}';
		$data = array('create_access' => $json_data);
		$update = $db->update('sys_token',$data,'id',$_POST['token_id']);
		break;
	case 'change_create_token':
		print_r($_POST);
		$get_create = $db->fetch_custom_single("select create_access from sys_token where id=?",array('id' => $_POST['token_id']));
		$get_create = json_decode($get_create->create_access);
		$create_access =  $get_create->{'access'};
		$token_enable = $_POST['data_act'];
		$json_data = '{"access":'.$create_access.',"token":'.$token_enable.'}';
		$data = array('create_access' => $json_data);
		$update = $db->update('sys_token',$data,'id',$_POST['token_id']);
		break;
	case 'change_update':
		print_r($_POST);
		$get_update = $db->fetch_custom_single("select update_access from sys_token where id=?",array('id' => $_POST['token_id']));
		$get_update = json_decode($get_update->update_access);
		$update_access = $_POST['data_act'];
		$token_enable = $get_update->{'token'};
		$json_data = '{"access":'.$update_access.',"token":'.$token_enable.'}';
		$data = array('update_access' => $json_data);
		$update = $db->update('sys_token',$data,'id',$_POST['token_id']);
		break;
	case 'change_update_token':
		print_r($_POST);
		$get_update = $db->fetch_custom_single("select update_access from sys_token where id=?",array('id' => $_POST['token_id']));
		$get_update_token = json_decode($get_update->update_access);
		$update_access_token = $get_update_token->{'access'};
		$token_enable = $_POST['data_act'];
		$json_data = '{"access":'.$update_access_token.',"token":'.$token_enable.'}';
		$data = array('update_access' => $json_data);
		$update = $db->update('sys_token',$data,'id',$_POST['token_id']);
		break;
	case 'change_delete':
		print_r($_POST);
		$get_delete = $db->fetch_custom_single("select delete_access from sys_token where id=?",array('id' => $_POST['token_id']));
		$get_delete = json_decode($get_delete->delete_access);
		$delete_access = $_POST['data_act'];
		$token_enable = $get_delete->{'token'};
		$json_data = '{"access":'.$delete_access.',"token":'.$token_enable.'}';
		$data = array('delete_access' => $json_data);
		$update = $db->update('sys_token',$data,'id',$_POST['token_id']);
		break;
	case 'change_delete_token':
		print_r($_POST);
		$get_delete = $db->fetch_custom_single("select delete_access from sys_token where id=?",array('id' => $_POST['token_id']));
		$get_delete = json_decode($get_delete->delete_access);
		$delete_access_token = $get_delete->{'access'};
		$token_enable = $_POST['data_act'];
		$json_data = '{"access":'.$delete_access_token.',"token":'.$token_enable.'}';
		$data = array('delete_access' => $json_data);
		$update = $db->update('sys_token',$data,'id',$_POST['token_id']);
		break;
		
	default:
		# code...
		break;
}

?>