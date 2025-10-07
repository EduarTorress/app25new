<?php

namespace App\View\Components;

use App\Models\Documentos;
use Core\View\Component;

class DocumentoComponent extends Component
{
    private $nid;
    function __construct($nid)
    {
        $this->nid = $nid;
    }
    function render()
    {
        // $dctos = new Documentos();
        $lista = [
            'lista' => [
                'items' => [
                    ['tdoc' => '01', 'nomb' => 'FACTURA', 'idtdoc' => '7'],
                    ['tdoc' => '03', 'nomb' => 'BOLETA', 'idtdoc' => '10'],
                    ['tdoc' => '07', 'nomb' => 'NOTA DE CREDITO', 'idtdoc' => '16'],
                    ['tdoc' => '08', 'nomb' => 'NOTA DE DEBITO', 'idtdoc' => '17'],
                    ['tdoc' => '20', 'nomb' => 'NOTAS DE VENTA', 'idtdoc' => '28']
                ]
            ]
        ];
        if (!empty($_SESSION['config']['controlinterno'])) {
            $lista = [
                'lista' => [
                    'items' => [
                        ['tdoc' => '20', 'nomb' => 'NOTAS DE VENTA', 'idtdoc' => '28']
                    ]
                ]
            ];
        }
        return view('components/dctos', ['lista' => $lista, 'tdoc' => $this->nid]);
    }
    function rendercompras()
    {
        // $dctos = new Documentos();
        // $lista = $dctos->listardocumentoscompras("");
        $lista = [
            'lista' => [
                'items' => [
                    ['tdoc' => '01', 'nomb' => 'FACTURA', 'idtdoc' => '7'],
                    ['tdoc' => '03', 'nomb' => 'BOLETA', 'idtdoc' => '10'],
                    ['tdoc' => 'GI', 'nomb' => 'INTERNO', 'idtdoc' => '27']
                ]
            ]
        ];
        return view('components/dctos', ['lista' => $lista, 'tdoc' => $this->nid]);
    }
    function listardctosanular()
    {
        $dctos = new Documentos();
        $lista = $dctos->listardocumentosanular("");
        return view('components/dctos', ['lista' => $lista, 'tdoc' => $this->nid]);
    }
    function renderreports()
    {
        $dctos = new Documentos();
        $lista = $dctos->listardocumentosoventas("");
        return view('components/dctosreporte', ['lista' => $lista, 'tdoc' => $this->nid]);
    }
}
