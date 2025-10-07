<?php
namespace App\View\Components;

use App\Models\Unidades;
use Core\View\Component;
class VehiculoComponent extends Component
{
    private $nid;
    function __construct($nid)
    {
        $this->nid=$nid;
    }
    function render()
    {
        $ovh=new Unidades();
        $lista=$ovh->listar("%%");
        return view('components/vehiculos',['lista'=>$lista]);
    }
}
