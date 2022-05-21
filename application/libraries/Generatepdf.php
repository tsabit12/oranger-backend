<?php

use Dompdf\Dompdf;
// define('DOMPDF_ENABLE_AUTOLOAD', tr);
define("DOMPDF_ENABLE_REMOTE", false);

class Generatepdf
{
    public function generate($html, $filename, $size)
    {
        $dompdf = new DOMPDF();
         $dompdf->load_html($html);
         // $dompdf->setBasePath(realpath(APPLICATION_PATH . '/b/'));
         $dompdf->set_paper('A5', $size);  
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->render();
        ob_end_clean();
        $dompdf->stream($filename.'.pdf', array("Attachment"=>0));
    }    
}
?>