<?php

namespace App\View\Components;

use App\Models\TiposUsuario;
use Core\View\Component;

class TiposUsuarioComponent extends Component
{
    private $tipo;
    function __construct($tipo)
    {
        $this->tipo = $tipo;
    }
    function render()
    {
        $tiposusuarios = new TiposUsuario();
        $listatiposusuarios = $tiposusuarios->listarTipos();
        return view('components/tiposusuario', ['lista' => $listatiposusuarios, 'tipo' => $this->tipo]);
    }
}
