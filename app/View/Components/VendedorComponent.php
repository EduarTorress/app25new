<?php

namespace App\View\Components;

use App\Models\Vendedor;
use Core\View\Component;

class VendedorComponent extends Component
{
    private $idven;
    function __construct($idven)
    {
        $this->idven = $idven;
    }
    function render()
    {
        if (empty($_SESSION['vendedores'])) {
            $vendedor = new Vendedor();
            $vendedores = $vendedor->cargarvendedoresindex('');
            $vendedores = $_SESSION['vendedores'];
        } else {
            $vendedores = $_SESSION['vendedores'];
        }
        return view('components/vendedor', ['vendedores' => $vendedores, 'idven' => $this->idven]);
    }
}
