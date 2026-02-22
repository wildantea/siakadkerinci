<?php
session_start();
include "../../inc/config.php";
session_check_json();
switch ($_GET["act"]) {
  
  case "detail_tagihan":
  $nim = $_POST['nim'];
  ?>
  <table class="table">
    <thead>
      <tr>
      
          <th>No</th>
          <th>No Resi</th>
          <th>Keterangan</th>
          <th>Jumlah Bayar Rp</th>
          <th>Tanggal Bayar</th>
          <th>Status</th>
        </th>
      </tr>
    </thead>
    <tbody>
  <?php
  $no=1;
  $q = $db->query("select * from v_resi where nim='$nim' order by tgl_bayar asc");
  foreach ($q as $k) {
    echo "<tr>
    <td>$no</td>
    <td>$k->no_resi</td>
    <td>$k->ket</td>
    <td>".rupiah($k->jml_bayar)."</td>
      <td>".tgl_indo($k->tgl_bayar)."</td>
    <td>$k->status</td>
    </tr>";
    $no++;
  }
  ?>
  </tbody>
</table>
  <?php
    break;

  case "in":
    
  
  
  
  $data = array(
      "id_agama" => $_POST["id_agama"],
      "nm_agama" => $_POST["nm_agama"],
  );
  
  
  
   
    $in = $db->insert("agama",$data);
    
    
    action_response($db->getErrorMessage());
    break;
  case "delete":
    
    
    
    $db->delete("agama","id_agama",$_GET["id"]);
    action_response($db->getErrorMessage());
    break;
   case "del_massal":
    $data_ids = $_REQUEST["data_ids"];
    $data_id_array = explode(",", $data_ids);
    if(!empty($data_id_array)) {
        foreach($data_id_array as $id) {
          $db->delete("agama","id_agama",$id);
         }
    }
    action_response($db->getErrorMessage());
    break;
  case "up":
    
   $data = array(
      "id_agama" => $_POST["id_agama"],
      "nm_agama" => $_POST["nm_agama"],
   );
   
   
   

    
    
    $up = $db->update("agama",$data,"id_agama",$_POST["id"]);
    
    action_response($db->getErrorMessage());
    break;
  default:
    # code...
    break;
}

?>