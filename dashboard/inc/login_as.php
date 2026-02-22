<?php
session_start();
include "config.php";
$id_user = $_GET['id'];
$admin_id = $_GET['adm_id'];
if ($_GET['url']=='mahasiswa' or $_GET['url']=='dosen') {
	$dt = $db->fetch_single_row("sys_users","username",$id_user);
} else {
	$dt=$db->fetch_single_row('sys_users','id',$id_user);
}
$group_dt=$db->fetch_single_row('sys_group_users','id',$dt->group_level);

$_SESSION['group_level']=$group_dt->level;
$_SESSION['id_group_level']=$group_dt->id;
$_SESSION['id_user']=$dt->id;
$_SESSION['login']=1;
$_SESSION['username'] = $dt->username;
$_SESSION['nama'] = $dt->first_name." ".$dt->last_name;
$_SESSION['level']=$dt->group_level;

$_SESSION['admin_id']= $admin_id;
$_SESSION['login_as']=1;
$_SESSION['url']=$_GET['url'];
$_SESSION['back_uri']=$_GET['back_uri'];
header("location:".base_admin());
//print_r($_SESSION);
//header("location:./");