<?php
include "config.php";
session_start();
$id_user = $_GET['id'];
$dt=$db->fetch_single_row('sys_users','id',$id_user);

$group_dt=$db->fetch_single_row('sys_group_users','id',$dt->group_level);
$_SESSION['group_level']=$group_dt->level;
$_SESSION['id_user']=$dt->id;
$_SESSION['login']=1;
$_SESSION['username'] = $dt->username;
$_SESSION['nama'] = $dt->first_name." ".$dt->last_name;
$_SESSION['level']=$dt->group_level;
$url = $_SESSION["back_uri"];
unset ($_SESSION["admin_id"]);
unset ($_SESSION["login_as"]);
unset ($_SESSION["url"]);

header("location:".base_admin().'index.php/'.$url);