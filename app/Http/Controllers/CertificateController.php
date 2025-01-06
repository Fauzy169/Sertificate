<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use setasign\Fpdi\Tcpdf\Fpdi;


class CertificateController extends Controller
{
    public function index()
    {
        return view('certificate');
    }

    public function generateCertificate($download = false)
    {
        $name ="Ahmad Fausi Febrian";
        $credential = "SertifkatCredential";

        //Generate QR Code
        $qrCode =QrCode::format('png')->size(500)->generate($credential);
        $qrCodePath = public_path('qr/'.$credential.'.png');
        file_put_contents($qrCodePath, $qrCode);

        //Create instance of PDF
        $pdf = new Fpdi();

        $pathToTemplate = public_path('Certificate\Certificate.pdf');
        $pdf->setSourceFile($pathToTemplate);
        $template = $pdf->importPage(1);

        $size = $pdf->getTemplateSize($template);
        $pdf->AddPage($size['orientation'],[$size['width'], $size['height']]);
        $pdf->useTemplate($template,0,0,$size['width'], $size['height']);
        
        $pdf->SetFont('Crimson');
        $pdf->SetFontSize(44);
        $pdf->SetXY(100, 100);
        $pdf->Write(0, $name);

        //credential
        $pdf->SetFont('Crimson');
        $pdf->SetFontSize(20);
        $pdf->SetXY(50, 110);
        $pdf->Write(0, $credential);

        //Qr Code
        $pdf->Image($qrCodePath, 50, 130, 100, 100);
        $fileName = 'Certificate'.$name.'.pdf';

        if ($download) {
            
            return response()->make($pdf->Output('D',$fileName), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
            ]);
        } else {
            return response()->make($pdf->Output('I', $fileName), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$fileName.'"',
            ]);
        }
    }
    public function viewCertificate()
    {
        return $this->generateCertificate(false);
    }
    public function downloadCertificate()
    {
        return $this->generateCertificate(true);
    }
}