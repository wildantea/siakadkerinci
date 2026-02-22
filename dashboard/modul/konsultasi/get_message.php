<?php
session_start();
header('Content-Type: application/json');
include "../../inc/config.php";

// Validasi parameter
$category_id   = isset($_GET['category_id']) ? (int) $_GET['category_id'] : 0;
$mahasiswa_id  = isset($_GET['mahasiswa_id']) ? (int) $_GET['mahasiswa_id'] : 0;
$dosen_id      = isset($_GET['dosen_id']) ? (int) $_GET['dosen_id'] : 0;

if (!$category_id || !$mahasiswa_id || !$dosen_id) {
    echo json_encode(['error' => 'missing parameters']);
    exit;
}

// Ambil pesan dari DB
$stmt = $db->query("
  SELECT 
    m.id,
    m.sender_role,
    '".$_SESSION['group_level']."' as current_session,
    m.message,
    (select foto_user from sys_users where m.nim=sys_users.username) as foto_mhs,
    (select foto_user from sys_users where m.nip=sys_users.username) as foto_dosen,
    m.is_read,
    DATE_FORMAT(m.created_at, '%Y-%m-%d %H:%i:%s') as created_at,
    c.name AS category_name
  FROM chat_message m
  JOIN chat_category c ON c.id = m.category_id
  WHERE m.category_id = ?
    AND m.nim = ?
    AND m.nip = ?
  ORDER BY m.created_at ASC
",array('category_id' => $category_id,'nim' => $mahasiswa_id,'nip' => $dosen_id));
echo $db->getErrorMessage();

foreach ($stmt as $data) {
  $messages[] = $db->convert_obj_to_array($data);
}

echo json_encode($messages);
?>
