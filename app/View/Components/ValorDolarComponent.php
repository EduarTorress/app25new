<?php

namespace App\View\Components;

use Core\View\Component;
use App\Models\Dolar;
use App\Models\Empresa;

class ValorDolarComponent extends Component
{
    function render()
    {
        $dolar = new Dolar();
        $valord = $dolar->obtenerDolar(date("Y-m-d"), 'C');
        return view('components/valordolar', ['dolar' => $valord]);
    }
}
