<?php

namespace App\Controllers;

use App\Models\DatosGlobales;
use App\Models\Usuario;
use App\Models\Empresa;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;
use App\Models\Serie;
use App\Models\Vendedor;
use Core\Foundation\Application;
use Core\Routing\Modelo;

class LoginController extends Controller
{
    private Usuario $usuario;
    function __construct()
    {
        $this->usuario = new Usuario();
    }
    public function login()
    {
        // header('Location: https://yaquamarket.compania-sysven.com/');
        $errores = session()->getFlash('errores', []);
        $inputs = session()->getFlash('inputs', []);
        return view('auth/login', [
            "errores" => $errores,
            "inputs" => $inputs
        ]);
    }
    public function store(Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'usuario');
        $validator->rule('required', 'password');
        if (!$validator->validate()) {
            $errores = $validator->errors();
            session()->setFlash('inputs', $request->getBody());
            session()->setFlash('errores', $errores);
            header('Location: /login');
            return;
        }
        $password = $request->get("password");
        $valor = $this->usuario->verificarusuario(trim($request->get("usuario")));
        if (!empty($valor[0]['idusua'])) {
            if (password_verify($password, $valor[0]['clave']) === false) {
                session()->setFlash('errores', ['password' => ['Contraseña incorrecta']]);
                session()->setFlash('inputs', $request->getBody());
                header("Location: /login");
                return;
            }
            if ($request->get('cmbtipoacceso') == 'C') {
                header('Location:  http://contabilidad.companysysven.com/');
                return;
            }
            cargarconfig();

            $usuarioxalmacen = (empty($_SESSION['config']['usuarioxalmacen']) ? 'N' : $_SESSION['config']['usuarioxalmacen']);
            if ($usuarioxalmacen == 'S') {
                if ($valor[0]['idalma'] != '0') {
                    if ($request->get("cmbAlmacen") != ($valor[0]['idalma'])) {
                        header("Location: /login");
                        return;
                    }
                }
            }
            session()->set('usuario_id', $valor[0]['idusua']);
            session()->set('usua_apro', $valor[0]['usua_apro']);
            session()->set('usuario', $valor[0]['nomb']);
            session()->set('tipoacceso', $request->get('cmbtipoacceso'));
            session()->set('tipousuario', left($valor[0]['tipo'], 1));
            $_SESSION['monedap'] = 'NO';
            $_SESSION['moneda'] = 'NO';
            $_SESSION['igvsololectura'] = 'NO';
            $_SESSION['opigv'] = 'I';
            $ser = new Serie();
            $ser->obtenerSerieDadoAlma($request->get("cmbAlmacen"));
            datosglobales();

            $em = new Empresa();
            $em->actualizaFecha();
            $modelo = new Modelo();
            $modelo->cargarsucursalesindex();
            $vendedor = new Vendedor();
            $vendedor->cargarvendedoresindex();
            header("location: /admin");
        } else {
            session()->setFlash('errores', ['usuario' => ['No existe usuario']]);
            session()->setFlash('inputs', $request->getBody());
            header('location: /login');
            return;
        };
    }
    function salir()
    {
        session()->cerrarsesion();
        $modelo = new Modelo();
        $modelo->cargarsucursalesindex();
        header("location: /login");
    }
}
