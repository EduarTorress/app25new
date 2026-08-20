<?php

namespace Core\Clases;

// use chillerlan\QRCode\QRCode as QRCodeQRCode;
use Core\Foundation\Application;
use Fpdf\Fpdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Picqer\Barcode\BarcodeGeneratorPNG;
use tFPDF;

class impresiondefault extends Imprimir
{
    public function generarpdfticket($rutapdf, $estilo = '')
    {
        $totalitems = count($this->items);
        $ti = 240;
        for ($i = 0; $i <= $totalitems; $i++) {
            $ti += 16;
        }
        $pdf = new FPDF('P', 'mm', array(80, $ti));
        $pdf->AddPage();
        $pdf->SetMargins(-5, -10, 5);
        $pdf->SetFont('Arial', 'B', 12);

        // Agregar logo
        if ($_SERVER['SERVER_NAME'] == 'app25.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logoticket.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logoticket.jpg';
        }
        if (\file_exists($logo)) {
            $pdf->Image($logo, 9, -12, 62, 60);
            // $pdf->Cell(70, 3, $pdf->Image($logo, 9, -12, 62, 60), 0, 'C');
        }

        // Información de la tienda
        $pdf->Ln(21);
        $pdf->setx(5);
        $pdf->MultiCell(70, 5, trim($this->empresa), 0, 'C');
        $pdf->Ln(2);
        $pdf->setx(5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(70, 4, trim($this->direccionempresa), 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, 'EMAIL: ' . trim($_SESSION['gene_correo']), 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, 'CELULAR: ' . trim($_SESSION['gene_fono']), 0, 'C');

        // datos de la venta
        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->setx(5);
        if (trim($this->rucempresa) != '-') {
            $pdf->MultiCell(70, 6, trim($this->rucempresa), 0, 'C');
            $pdf->setx(5);
        }
        $pdf->MultiCell(70, 6, trim($this->tipocomprobante), 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 6, trim($this->numero), 0, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 9);
        $pdf->setx(5);
        $pdf->Cell(17, 5, mb_convert_encoding('FECHA: ' . convertirformatofecha($this->fecha) . ' ' . $this->hora, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 9);
        $pdf->setx(5);
        $pdf->Cell(17, 5, mb_convert_encoding('FORMA DE PAGO: ' . $this->formadepago, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(5);

        if ($this->tdoc == '01') {
            $documentocliente = 'RUC: ' . $this->ruccliente;
        } else {
            $documentocliente = 'DNI: ' . $this->dnicliente;
        }

        $pdf->SetFont('Arial', '', 9);
        $pdf->setx(5);
        $pdf->Cell(17, 5, mb_convert_encoding($documentocliente, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Ln(5);

        if (strlen($this->cliente) > 28) {
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $cell_width = 120;
            $pdf->setx(5);
            $pdf->Multicell(70, 5, mb_convert_encoding('CLIENTE: ' . $this->cliente, 'ISO-8859-1', 'UTF-8'), '', '', false);
            $pdf->SetXY($current_x + $cell_width, $current_y);
            $pdf->Ln(11);
        } else {
            $pdf->SetFont('Arial', '', 9);
            $pdf->setx(5);
            $pdf->Cell(17, 5, mb_convert_encoding('CLIENTE: ' . $this->cliente, 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
            $pdf->Ln(6);
        }

        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->setx(5);
        $pdf->Multicell(70, 4, mb_convert_encoding('DIRECCIÓN: ' . $this->direccioncliente, 'ISO-8859-1', 'UTF-8'), '', '', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);
        if (strlen(trim($this->direccioncliente)) > 1) {
            $pdf->Ln(18);
        } else {
            $pdf->Ln(5);
        }
        // Línea divisora
        $pdf->Cell(70, 2, '----------------------------------------------------------------------------------------------', 0, 1, 'L');

        $pdf->setx(5);
        // Encabezados de productos
        $pdf->Cell(10, 4, 'Cant.', 0, 0, 'L');
        $pdf->Cell(15, 4, 'U.M.', 0, 0, 'L');
        $pdf->Cell(22, 4, mb_convert_encoding('Descripción', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(12, 4, 'Prec.', 0, 0, 'L');
        $pdf->Cell(15, 4, 'Impo.', 0, 1, 'L');

        // Línea divisora
        $pdf->Cell(70, 2, '----------------------------------------------------------------------------------------------', 0, 1, 'L');

        // Detalles de cada producto
        // $totalProductos = 0;
        $pdf->SetFont('Arial', '', 8);
        $i = 0;
        $pdf->setx(5);
        foreach ($this->items as $row_detalle) {
            if ($i != 0) {
                $pdf->ln(3);
            }
            $pdf->setx(5);
            $pdf->SetFont('Arial', '', 7);
            $importe = number_format($row_detalle['cant'] * $row_detalle['prec'], 2, '.', ',');
            // $totalProductos += $row_detalle['cant'];
            $pdf->Cell(10, 4,  number_format($row_detalle['cant'], 2, '.', ','), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 6);
            $yInicio = $pdf->GetY();
            $pdf->MultiCell(38, 4, trim($row_detalle['unid']), 0, 'L');
            $yFin = $pdf->GetY();
            $pdf->SetXY(30, $yInicio);

            $yInicio = $pdf->GetY();
            $pdf->SetFont('Arial', '', 8);
            $yFin = $pdf->GetY();
            $pdf->SetFont('Arial', '', 7);
            $pdf->SetXY(45, $yInicio);
            // $app = Application::getInstance();
            if ($this->optigv == 'N') {
                $precio = $row_detalle['prec'] / $_SESSION['gene_igv'];
            } else {
                $precio = $row_detalle['prec'];
            }
            $pdf->Cell(15, 4, ' ' . ' ' . number_format($precio, 2, '.', ','), 0, 0, 'R');
            $pdf->SetXY(60, $yInicio);
            $pdf->Cell(13, 4, ' ' . ' ' . $importe, 0, 1, 'R');
            $pdf->SetY($yFin);
            $pdf->ln();
            $i = $i + 1;
            $pdf->setx(5);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->MultiCell(70, 4, trim(mb_convert_encoding($row_detalle['descri'], 'ISO-8859-1', 'UTF-8')) . '.', 0, 'L');
        }

        // Información final
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 2, '----------------------------------------------------------------------------------------------', 0, 1, 'L');
        $pdf->ln(3);
        $pdf->setx(5);
        $pdf->Cell(70, 4, mb_convert_encoding('ITEMS:  ' . $i, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 9);

        if ($this->tdoc != '20') {
            // $pdf->setx(0);
            $pdf->Cell(67, 5, 'Op. Grav:', 0, 0, 'R');
            $pdf->setx(6.5);
            $pdf->Cell(67, 5, number_format($this->valorgravado, 2, '.', ','), 0, 1, 'R');

            // $pdf->setx(0);
            $pdf->Cell(67, 5, 'Op. Exon:', 0, 0, 'R');
            $pdf->setx(6.5);
            $pdf->Cell(67, 5, number_format($this->totalexonerado, 2, '.', ','), 0, 1, 'R');

            // $pdf->setx(0);
            $pdf->Cell(67, 5, 'IGV:', 0, 0, 'R');
            $pdf->setx(6.5);
            $pdf->Cell(67, 5, number_format($this->igv, 2, '.', ','), 0, 1, 'R');
        }
        // $pdf->setx(0);
        $pdf->Cell(67, 5, 'Total:', 0, 0, 'R');
        $pdf->setx(6.5);
        $pdf->Cell(67, 5, number_format($this->total, 2, '.', ','), 0, 1, 'R');

        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Ln(6);
        $pdf->setx(5);
        $pdf->Cell(70, 5, 'SON: ' . $this->importeletras, 0, 1, 'L');
        if (!empty($this->vuelto)) {
            $pdf->Ln(1);
            $pdf->setx(5);
            $pdf->Cell(70, 5, 'PAGO: ' . number_format($this->vuelto + $this->total, 2, '.', ','), 0, 1, 'L');
            $pdf->Ln(1);
            $pdf->setx(5);
            $pdf->Cell(70, 5, 'VUELTO: ' .  number_format($this->vuelto, 2, '.', ','), 0, 1, 'L');
        }
        $pdf->Ln(7);
        $pdf->SetFont('Arial', '', 8);
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, 'Agradecemos su preferencia, vuelva pronto!!!', 0, 'C');
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, mb_convert_encoding('Puede consultar este comprobante vía web:', 'ISO-8859-1', 'UTF-8'), 0, 'C');
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->setx(5);
        $pdf->MultiCell(70, 4, 'https://info.companiasysven.com/consulta', 0, 'C');
        $pdf->Ln(7);
        $texto_qr = $this->rucempresa . '|' . $this->tdoc . '|' . $this->serie . '|' . $this->ndoc . '|' . $this->igv . '|' . $this->total . '|' . $this->fecha;
        $ruta_qr = 'codigoqr' . '.png';
        $qr = QrCode::create($texto_qr);
        $writer = new PngWriter();
        $writer->write($qr)->saveToFile($ruta_qr);
        // $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);
        $pdf->Cell(70, 3, $pdf->Image($ruta_qr, 32, $pdf->GetY(), 15), 0, 'C');

        $y = $pdf->GetY();
        if ($this->clienteretencion == 'S') {
            if (floatval($_SESSION['gene_montoretencion']) <= floatval($this->total)) {
                if ($this->retencion > 0) {
                    $pdf->SetFont('Arial', '', 5);
                    $pdf->ln(17);
                    if (substr($this->formadepago, 0, 1) == 'C') {
                        $pdf->SetX(5);
                        $pdf->cell(20, 4, 'CUOTA', 1, 0, 'C');
                        $pdf->cell(16, 4, 'RETENCION', 1, 0, 'C');
                        $pdf->cell(16, 4, 'IMPORTE CUOTA', 1, 0, 'C');
                        $pdf->cell(16, 4, 'FECHA VENC.', 1, 0, 'C');
                        $pdf->SetY($y + 21);
                        $pdf->SetX(5);
                        $pdf->cell(20, 3, 'CUOTA 01', 1, 0, 'C', 0);
                        $pdf->cell(16, 3, 'S/ ' . round(floatval($_SESSION['gene_retencion'] / 100) * floatval($this->total), 2), 1, 0, 'C', 0);
                        $pdf->cell(16, 3, 'S/ ' . strval(floatval($this->total) - round(floatval($_SESSION['gene_retencion'] / 100) * floatval($this->total), 2)), 1, 0, 'C', 0);
                        $pdf->cell(16, 3, $this->fechavto, 1, 1, 'C', 0);
                    } else {
                        $pdf->SetX(22);
                        $pdf->cell(16, 4, 'RETENCION', 1, 0, 'C');
                        $pdf->cell(16, 4, 'A PAGAR', 1, 0, 'C');
                        $pdf->SetY($y + 21);
                        $pdf->SetX(22);
                        $pdf->cell(16, 3, 'S/ ' . round(floatval($_SESSION['gene_retencion'] / 100) * floatval($this->total), 2), 1, 0, 'C', 0);
                        $pdf->cell(16, 3, 'S/ ' . strval(floatval($this->total) - round(floatval($_SESSION['gene_retencion'] / 100) * floatval($this->total), 2)), 1, 0, 'C', 0);
                    }
                }
            }
        }

        // Cerrar conexiones y generar el PDF
        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            #DESCARGAR PDF
            // $pdf->Output('D', $rutapdf);
            $pdf->Output($rutapdf, 'D');
        }
    }
}
