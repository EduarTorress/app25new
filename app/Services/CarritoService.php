<?php

namespace App\Services;

class CarritoService
{
    public static function obtenerCantidadActual($presentacion): int
    {
        $carrito = session()->get('carrito', []);
        foreach ($carrito as $item) {
            if ($item['presentacion']->id == $presentacion->id) {
                return $item['cantidad'];
            }
        }
        return 0;
    }
    //Para pedidos
    public static function agregar($producto)
    {
        $carrito = session()->get('carrito', []);

        if ($producto['mone'] == 'D') {
            $producto['precio'] = $producto['precio'] / $_SESSION['gene_dola'];
            $producto['precio1'] = $producto['precio1'] / $_SESSION['gene_dola'];
            $producto['precio2'] = $producto['precio2'] / $_SESSION['gene_dola'];
            $producto['precio3'] = $producto['precio3'] / $_SESSION['gene_dola'];
        }

        $carrito[] = [
            'coda' => $producto['coda'],
            'descri' => $producto['descri'],
            'unidad' => $producto['unidad'],
            'cantidad' => $producto['cantidad'],
            'precio' => $producto['precio'],
            'stock' => $producto['stock'],
            'costo' => $producto['costo'],
            'precio1' => $producto['precio1'],
            'precio2' => $producto['precio2'],
            'precio3' => $producto['precio3'],
            'tipoproducto' => $producto['tipoproducto'],
            'nreg' => 0,
            'idcliente' => 0,
            'textopresentacion' => $producto['textopresentacion'],
            'cantpresentacion' =>  $producto['cantpresentacion'],
            'preciopresentacion' =>  $producto['preciopresentacion'],
            'eptaidep' =>  $producto['eptaidep'],
            'activo' => 'A'
        ];
        session()->set('carrito', $carrito);
    }

    public static function verutilidadpedido()
    {
        $carrito = session()->get('carrito', []);
        $totalcosto = 0;
        $total = 0;
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                $cantidad = $item['cantidad'];
                $costo = $item['costo'];
                $precio = $item['precio'];
                $total += $cantidad * $precio;
                $totalcosto += ($cantidad * $costo);
            }
        }
        return $total - $totalcosto;
    }

    public static function cambiardetalledolarpedido()
    {
        $carrito = session()->get('carrito', []);
        $i = 0;
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                $preciodolar = $item['precio'] / $_SESSION['gene_dola'];
                $carrito[$i]['precio'] = Round($preciodolar, 2);
            }
            $i = $i + 1;
        }
        session()->set('carrito', $carrito);
    }
    public static function cambiardetallesolespedido()
    {
        $carrito = session()->get('carrito', []);
        $i = 0;
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                $preciodolar = $item['precio'] * $_SESSION['gene_dola'];
                $carrito[$i]['precio'] = Round($preciodolar, 2);
            }
            $i = $i + 1;
        }
        session()->set('carrito', $carrito);
    }
    public static function buscarsiyaesta($idart)
    {
        $carrito = \session()->get("carrito", []);
        $encontrado = \false;
        foreach ($carrito as $item) {
            if ($item['coda'] == $idart and $item['activo'] == 'A') {
                $encontrado = true;
            }
        }
        return $encontrado;
    }
    public static function editar($producto)
    {
        $carrito = session()->get('carrito', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carrito as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carrito[$indice]['cantidad'] = $producto['cantidad'];
        $carrito[$indice]['textopresentacion'] = $producto['textopresentacion'];
        $carrito[$indice]['cantpresentacion'] = $producto['cantpresentacion'];
        $carrito[$indice]['eptaidep'] = $producto['eptaidep'];

        if ($producto['mone'] == 'D') {
            $carrito[$indice]['precio'] = $producto['precio'] / $_SESSION['gene_dola'];
        } else {
            $carrito[$indice]['precio'] = $producto['precio'];
        }
        session()->set('carrito', $carrito);
    }
    // SOLO SE UTILIZARÁ PARA BUSTAMANTE
    public static function cambiaritem($producto)
    {
        $carrito = session()->get('carrito', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carrito as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }

        $indice = $indice + 1;

        $i = 0;
        $j = $indice;
        foreach ($carrito as $c) {
            if ($i >= $indice) {
                if ($c['activo'] == 'A') {
                    $j = $j + 1;
                    $carrito[$j] = $c;
                }
            }
            $i = $i + 1;
        }

        $carrito[$indice]['coda'] = $producto['codigo'];
        $carrito[$indice]['descri'] = $producto['txtdescripcion'];
        $carrito[$indice]['unidad'] = $producto['txtunidad'];
        $carrito[$indice]['cantidad'] = $producto['cantidad'];
        $carrito[$indice]['precio'] = $producto['precio'];
        $carrito[$indice]['stock'] = $producto['stock'];
        $carrito[$indice]['costo'] = $producto['costo'];
        $carrito[$indice]['precio1'] = $producto['precio1'];
        $carrito[$indice]['precio2'] = $producto['precio2'];
        $carrito[$indice]['precio3'] = $producto['precio3'];
        $carrito[$indice]['tipoproducto'] = 'K';
        $carrito[$indice]['nreg'] = 0;
        $carrito[$indice]['idcliente'] = 0;
        $carrito[$indice]['activo'] = 'A';

        session()->set('carrito', $carrito);
    }

    public static function numeroItems()
    {
        $carrito = session()->get('carrito', []);
        $titems = 0;
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                $titems++;
            }
        }
        return $titems;
    }

    public static function subtotal()
    {
        $carrito = session()->get('carrito', []);
        $total = 0.00;
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                $cantidad = $item['cantidad'];
                $precio_venta = $item['precio'];
                $total += ($cantidad * $precio_venta);
            }
        }
        return $total;
    }

    public static function total()
    {
        return self::subtotal();
    }

    public static function descuento()
    {
        return session()->get('descuento', 0.00);
    }

    public static function quitar($indice)
    {
        $carrito = session()->get('carrito', []);
        foreach ($carrito as $posicion => $item) {
            if ($posicion == $indice) {
                $indice = $posicion;
                break;
            }
        }
        $carrito[$indice]['activo'] = 'I';
        session()->set('carrito', $carrito);
    }

    public static function aplicarCupon($cupon)
    {
        session()->set('cupon', $cupon);
        $subtotal = CarritoService::subtotal();
        $descuento = 0.00;
        if ($cupon->tipo == 'MONTO') {
            $descuento = $cupon->monto;
        } else if ($cupon->tipo == 'PORCENTAJE') {
            $descuento = $subtotal * $cupon->monto / 100;
        }
        session()->set('descuento', $descuento);
    }

    public static function siesta($idart)
    {
        $valor = false;
        $carrito = \session()->get('carrito', []);
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                if ($item['coda'] == $idart) {
                    $valor = true;
                    break;
                }
            }
        }
        return $valor;
    }

    public static function item($pos)
    {
        $carrito = \session()->get('carrito', []);
        $itemcarrito = array();
        foreach ($carrito as $posicion => $item) {
            if ($posicion == $pos) {
                $itemcarrito = array(
                    'coda' => $item['coda'],
                    'descri' => $item['descri'],
                    'unidad' => $item['unidad'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'stock' => $item['stock'],
                    'precio1' => $item['precio1'],
                    'precio2' => $item['precio2'],
                    'precio3' => $item['precio3'],
                    'costo' => $item['costo'],
                    'nreg' => $item['nreg'],
                    'idcliente' => $item['idcliente'],
                    'tipoproducto' => $item['tipoproducto'],
                    'textopresentacion' => $item['textopresentacion'],
                    'cantpresentacion' =>  $item['cantpresentacion'],
                    'preciopresentacion' =>  $item['preciopresentacion'],
                    'eptaidep' =>  $item['eptaidep'],
                    'activo' => 'A'
                );
                break;
            }
        }
        return $itemcarrito;
    }

    //COMPRAS
    public static function itemCompra($pos)
    {
        $carritoc = \session()->get('carritoc', []);
        $itemcompra = array();
        foreach ($carritoc as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                $itemcompra = array(
                    'coda' => $item['coda'],
                    'descri' => $item['descri'],
                    'unidad' => $item['unidad'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'stock' => $item['stock'],
                    'precio1' => $item['precio1'],
                    'precio2' => $item['precio2'],
                    'precio3' => $item['precio3'],
                    'costo' => $item['costo'],
                    'nreg' => $item['nreg'],
                    'activo' => 'A'
                );
                break;
            }
        }
        return $itemcompra;
    }

    public static function numeroItemsCompra()
    {
        $carritoc = session()->get('carritoc', []);
        $titems = 0;
        foreach ($carritoc as $item) {
            if ($item['activo'] == 'A') {
                $titems++;
            }
        }
        return $titems;
    }

    public static function generardescuento($descuento)
    {
        if (empty($descuento)) {
            $descuento = 0;
        }
        $valortotal = CarritoService::totalCompra();
        $carritoc = session()->get('carritoc', []);
        // if (floatval($descuento) > 0) {
        foreach ($carritoc as $posicion => $item) {
            $carritoc[$posicion]['precio'] = $item['preciocopia'];
        }
        // foreach ($carritoc as $posicion => $item) {
        //     $presentaciones = json_decode($item['presentaciones']);
        //     foreach ($presentaciones as $p) {
        //         if ($p->epta_idep == $carritoc[$posicion]['presseleccionada']) {
        //             $carritoc[$posicion]['precio'] = Round(floatval($p->epta_prec), 2);
        //         }
        //     }
        // }
        $calculo = (100 * $descuento) / floatval($valortotal);
        $calculo = (100 - $calculo) / 100;
        foreach ($carritoc as $posicion => $item) {
            $carritoc[$posicion]['precio'] = $item['precio'] * $calculo;
        }
        session()->set('carritoc', $carritoc);
        // }
    }

    public static function siestacompras($idart)
    {
        $valor = false;
        $carritoc = \session()->get('carritoc', []);
        foreach ($carritoc as $item) {
            if ($item['activo'] == 'A') {
                if ($item['coda'] == $idart) {
                    $valor = true;
                    break;
                }
            }
        }
        return $valor;
    }

    public static function quitarItemCompra($indice)
    {
        $carritoc = session()->get('carritoc', []);
        foreach ($carritoc as $posicion => $item) {
            if ($posicion == $indice) {
                $indice = $posicion;
                break;
            }
        }
        $carritoc[$indice]['activo'] = 'I';
        session()->set('carritoc', $carritoc);
    }

    public static function subtotalCompras()
    {
        $carritoc = session()->get('carritoc', []);
        $total = 0.00;
        foreach ($carritoc as $item) {
            if ($item['activo'] == 'A') {
                $cantidad = $item['cantidad'];
                $precio_venta = $item['precio'];
                $total += ($cantidad * $precio_venta);
            }
        }
        return $total;
    }

    public static function agregarItemCompra($producto)
    {
        $carritoc = session()->get('carritoc', []);
        $carritoc[] = [
            'coda' => $producto['coda'],
            'descri' => $producto['descri'],
            'unidad' => $producto['unidad'],
            'cantidad' => $producto['cantidad'],
            'precio' => $producto['precio'],
            'preciocopia' => $producto['precio'],
            'stock' => $producto['stock'],
            'precio1' => $producto['precio1'],
            'precio2' => $producto['precio2'],
            'precio3' => $producto['precio3'],
            'costo' => $producto['costo'],
            'nreg' => 0,
            'idcliente' => 0,
            'presentaciones' => $producto['presentaciones'],
            'presseleccionada' => $producto['presseleccionada'],
            'cantequi' => $producto['cantequi'],
            'checkafecto' => "false",
            'lote' => '',
            'fechavto' => '',
            'activo' => 'A',
            'caant' => $producto['cantidad']
        ];
        session()->set('carritoc', $carritoc);
    }

    public static function totalCompra()
    {
        return self::subtotalCompras();
    }

    public static function editarProductoCompra($producto)
    {
        $carritoc = session()->get('carritoc', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carritoc as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carritoc[$indice]['cantidad'] = $producto['cantidad'];
        $carritoc[$indice]['precio'] = $producto['precio'];
        $carritoc[$indice]['preciocopia'] = $producto['precio'];
        $carritoc[$indice]['presseleccionada'] = $producto['presseleccionada'];
        $carritoc[$indice]['cantequi'] = $producto['cantequi'];
        $carritoc[$indice]['unidad'] = $producto['unidad'];
        $carritoc[$indice]['lote'] = empty($producto['lote']) ? ' ' : $producto['lote'];
        $carritoc[$indice]['fechavto'] = empty($producto['fechavto']) ? '' : $producto['fechavto'];
        session()->set('carritoc', $carritoc);
    }
    public static function editarProductocheckafecto($producto)
    {
        $carritoc = session()->get('carritoc', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carritoc as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carritoc[$indice]['checkafecto'] = $producto['checkafecto'];
        session()->set('carritoc', $carritoc);
    }

    //////////////// VENTAS

    public static function cambiardetalledolar()
    {
        $carritov = session()->get('carritov', []);
        $i = 0;
        foreach ($carritov as $item) {
            if ($item['activo'] == 'A') {
                $preciodolar = $item['precio'] / $_SESSION['gene_dola'];
                $carritov[$i]['precio'] = Round($preciodolar, 2);
            }
            $i = $i + 1;
        }
        session()->set('carritov', $carritov);
    }

    public static function verificarvalorescarrito($producto)
    {
        $carritov = session()->get('carritov', []);
        $pos = $producto['id'];
        $indice = 0;
        foreach ($carritov as $posicion => $item) {
            if ($item['activo'] == 'A') {
                if ($posicion == $pos) {
                    $indice = $posicion;
                    break;
                }
            }
        }
        $carritov[$indice]['cantidad'] = $producto['cant'];
        $carritov[$indice]['precio'] = $producto['precio'];
        session()->set('carritov', $carritov);
    }

    public static function agregarItemVenta($producto, $moneda)
    {
        $carritov = session()->get('carritov', []);

        if ($moneda == 'D') {
            $producto['precio'] = $producto['precio'] / $_SESSION['gene_dola'];
            $producto['precio1'] = $producto['precio1'] / $_SESSION['gene_dola'];
            $producto['precio2'] = $producto['precio2'] / $_SESSION['gene_dola'];
            $producto['precio3'] = $producto['precio3'] / $_SESSION['gene_dola'];
        }
        $carritov[] = [
            'coda' => $producto['coda'],
            'descripcion' => $producto['descri'],
            'unidad' => $producto['unidad'],
            'cantidad' => $producto['cantidad'],
            'precio' => $producto['precio'],
            'stock' => $producto['stock'],
            'precio1' => $producto['precio1'],
            'precio2' => $producto['precio2'],
            'precio3' => $producto['precio3'],
            'costo' => $producto['costo'],
            'nreg' => 0,
            'caant' => $producto['caant'],
            'tipoproducto' => $producto['tipoproducto'],
            'idcliente' => 0,
            'presentaciones' => $producto['presentaciones'],
            'cantequi' => $producto['cantequi'],
            'presseleccionada' => $producto['presseleccionada'],
            'lote' => empty($producto['lote']) ? '' : $producto['lote'],
            'fechavto' => empty($producto['fechavto']) ? date('Y-m-d') : $producto['fechavto'],
            'activo' => 'A'
        ];
        session()->set('carritov', $carritov);
    }

    // public static function itemVenta($pos)
    // {
    //     $carritov = \session()->get('carritov', []);
    //     $itemventa = array();
    //     foreach ($carritov as $posicion => $item) {
    //         if ($posicion == $pos) {

    //             if ($_SESSION['moneda'] == 'D') {
    //                 $item['precio1'] = $item['precio1'] / $_SESSION['gene_dola'];
    //                 $item['precio2'] = $item['precio2'] / $_SESSION['gene_dola'];
    //                 $item['precio3'] = $item['precio3'] / $_SESSION['gene_dola'];
    //             }

    //             //$indice = $posicion;
    //             $itemventa = array(
    //                 'coda' => $item['coda'],
    //                 'descripcion' => $item['descripcion'],
    //                 'unidad' => $item['unidad'],
    //                 'cantidad' => $item['cantidad'],
    //                 'precio' => $item['precio'],
    //                 'stock' => $item['stock'],
    //                 'precio1' => $item['precio1'],
    //                 'precio2' => $item['precio2'],
    //                 'precio3' => $item['precio3'],
    //                 'costo' => $item['costo'],
    //                 'nreg' => $item['nreg'],
    //                 'tipro' => $item['tipro'],
    //                 'caant' => $item['caant'],
    //                 'activo' => 'A'
    //             );
    //             break;
    //         }
    //     }
    //     return $itemventa;
    // }

    public static function verutilidad()
    {
        $carrito = session()->get('carritov', []);
        $totalcosto = 0;
        $total = 0;
        foreach ($carrito as $item) {
            if ($item['activo'] == 'A') {
                $cantidad = $item['cantidad'];
                $costo = $item['costo'];
                $precio = $item['precio'];
                $total += $cantidad * $precio;
                $totalcosto += ($cantidad * $costo);
            }
        }
        return $total - $totalcosto;
    }
    public static function numeroItemsVenta()
    {
        $carritov = session()->get('carritov', []);
        $titems = 0;
        if (!empty($carritov)) {
            foreach ($carritov as $item) {
                if ($item['activo'] == 'A') {
                    $titems++;
                }
            }
        }
        return $titems;
    }

    public static function siestaventas($idart)
    {
        $valor = false;
        $carritov = \session()->get('carritov', []);
        foreach ($carritov as $item) {
            if ($item['activo'] == 'A') {
                if ($item['coda'] == $idart) {
                    $valor = true;
                    break;
                }
            }
        }
        return $valor;
    }

    public static function quitarItemVenta($indice)
    {
        $carritov = session()->get('carritov', []);
        foreach ($carritov as $posicion => $item) {
            if ($posicion == $indice) {
                $indice = $posicion;
                break;
            }
        }
        $carritov[$indice]['activo'] = 'I';
        session()->set('carritov', $carritov);
    }

    public static function subtotalVentas()
    {
        $carritov = session()->get('carritov', []);
        $total = 0.00;
        foreach ($carritov as $item) {
            if ($item['activo'] == 'A') {
                $cantidad = $item['cantidad'];
                $precio_venta = $item['precio'];
                $total += ($cantidad * $precio_venta);
            }
        }
        return $total;
    }

    public static function totalVenta()
    {
        return self::subtotalVentas();
    }

    public static function editarProductoVenta($producto, $cmbmoneda)
    {
        $carritov = session()->get('carritov', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carritov as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carritov[$indice]['descripcion'] = $producto['descri'];
        $carritov[$indice]['cantidad'] = $producto['cantidad'];
        $carritov[$indice]['presseleccionada'] = $producto['presseleccionada'];
        $carritov[$indice]['cantequi'] = $producto['cantequi'];
        $carritov[$indice]['unidad'] = $producto['unidad'];
        $carritov[$indice]['lote'] = empty($producto['lote']) ? ' ' : $producto['lote'];
        $carritov[$indice]['fechavto'] = empty($producto['fechavto']) ? date('Y-m-d') : $producto['fechavto'];
        if ($cmbmoneda == 'D') {
            $carritov[$indice]['precio'] = $producto['precio'] / $_SESSION['gene_dola'];
        } else {
            $carritov[$indice]['precio'] = $producto['precio'];
        }
        if (empty($_SESSION['carritov'])) {
            session()->set('carritov', []);
        } else {
            session()->set('carritov', $carritov);
        }
    }

    public static function editarProductoLoteFechavto($producto, $cmbmoneda)
    {
        $carritov = session()->get('carritov', []);
        $pos = $producto['txtidart'];
        $indice = 0;
        foreach ($carritov as $posicion => $item) {
            if ($item['activo'] == 'A') {
                if ($item['coda'] == $pos) {
                    $indice = $posicion;
                    break;
                }
            }
        }
        $carritov[$indice]['lote'] = empty($producto['lote']) ? ' ' : $producto['lote'];
        $carritov[$indice]['fechavto'] = empty($producto['fechavto']) ? date('Y-m-d') : $producto['fechavto'];
        session()->set('carritov', $carritov);
    }

    //Para guias

    public static function itemGuia($pos)
    {
        $carritov = \session()->get('carritogr', []);
        $itemcompra = array();
        foreach ($carritov as $posicion => $item) {
            if ($posicion == $pos) {
                // $indice = $posicion;
                $itemcompra = array(
                    'coda' => $item['coda'],
                    'descri' => $item['descri'],
                    'unidad' => $item['unidad'],
                    'cantidad' => $item['cantidad'],
                    'peso' => $item['peso'],
                    'stock' => $item['stock'],
                    'precio1' => $item['precio1'],
                    'precio2' => $item['precio2'],
                    'precio3' => $item['precio3'],
                    'costo' => $item['costo'],
                    'nreg' => $item['nreg'],
                    'activo' => 'A'
                );
                break;
            }
        }
        return $itemcompra;
    }

    public static function numeroItemsGuiar()
    {
        $carritov = session()->get('carritogr', []);
        $titems = 0;
        foreach ($carritov as $item) {
            if ($item['activo'] == 'A') {
                $titems++;
            }
        }
        return $titems;
    }

    public static function siestaguiasr($idart)
    {
        $valor = false;
        $carritov = \session()->get('carritogr', []);
        foreach ($carritov as $item) {
            if ($item['activo'] == 'A') {
                if ($item['coda'] == $idart) {
                    $valor = true;
                    break;
                }
            }
        }
        return $valor;
    }

    public static function quitarItemGuiar($indice)
    {
        $carritov = session()->get('carritogr', []);
        foreach ($carritov as $posicion => $item) {
            if ($posicion == $indice) {
                $indice = $posicion;
                break;
            }
        }
        $carritov[$indice]['activo'] = 'I';
        session()->set('carritogr', $carritov);
    }

    public static function subtotalGuiar()
    {
        $carritov = session()->get('carritogr', []);
        $total = 0.00;
        foreach ($carritov as $item) {
            if ($item['activo'] == 'A') {
                $cantidad = $item['cantidad'];
                $precio_venta = $item['peso'];
                $total += ($cantidad * $precio_venta);
            }
        }
        return $total;
    }

    public static function totalGuiar()
    {
        return self::subtotalGuiar();
    }

    public static function editarProductoGuiar($producto)
    {
        $carritov = session()->get('carritogr', []);
        $pos = $producto['indice'];
        $indice = 0;
        foreach ($carritov as $posicion => $item) {
            if ($posicion == $pos) {
                $indice = $posicion;
                break;
            }
        }
        $carritov[$indice]['cantidad'] = $producto['cantidad'];
        $carritov[$indice]['unidad'] = $producto['unidad'];
        $carritov[$indice]['peso'] = $producto['peso'];
        $carritov[$indice]['scop'] = $producto['scop'];
        $carritov[$indice]['cantequi'] = $producto['cantequi'];
        $carritov[$indice]['presseleccionada'] = $producto['presseleccionada'];
        session()->set('carritogr', $carritov);
    }

    public static function agregarItemGuiar($producto)
    {
        $carritogr = session()->get('carritogr', []);
        $carritogr[] = [
            'coda' => $producto['coda'],
            'descri' => $producto['descri'],
            'unidad' => $producto['unidad'],
            'cantidad' => $producto['cantidad'],
            'peso' => $producto['peso'],
            'stock' => $producto['stock'],
            'precio1' => $producto['precio1'],
            'precio2' => $producto['precio2'],
            'precio3' => $producto['precio3'],
            'costo' => $producto['costo'],
            'nreg' => 0,
            'scop' => '',
            'idcliente' => 0,
            'presentaciones' => $producto['presentaciones'],
            'presseleccionada' => $producto['presseleccionada'],
            'cantequi' => $producto['cantequi'],
            'activo' => 'A'
        ];
        session()->set('carritogr', $carritogr);
    }
}
