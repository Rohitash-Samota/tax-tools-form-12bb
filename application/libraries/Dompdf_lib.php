<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Dompdf_lib
{
    protected $dompdf;

    public function __construct()
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $this->dompdf = new Dompdf($options);
        $this->dompdf->setPaper('A4', 'portrait');
    }

    public function render_html($html)
    {
        $this->dompdf->loadHtml($html, 'UTF-8');
        $this->dompdf->render();
    }

    public function stream($filename, $attachment = false)
    {
        $this->dompdf->stream($filename, ['Attachment' => $attachment ? 1 : 0]);
    }
}
