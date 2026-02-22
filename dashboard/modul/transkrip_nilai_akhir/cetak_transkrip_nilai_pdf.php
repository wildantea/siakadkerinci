<?php
session_start();
include "../../inc/config.php";
session_check();
require_once('../../assets/plugins/html2pdf/html2pdf.class.php');

// get the HTML
ob_start();
?>
<page backleft="0mm" backtop="0mm" backright="0mm" backbottom="0mm">    
    ID: 5000
    <table>
      <thead>
        <tr>
          <th>header</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>data</td>
        </tr>
      </tbody>
    </table>
</page>
<?php
$content = ob_get_clean();


try
{
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    //$html2pdf->setModeDebug();

    $html2pdf->writeHTML($content);

    $html2pdf->Output('aaa.pdf','D');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}

?>
