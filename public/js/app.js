function isNumber(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if ((charCode < 48 || charCode > 57) && (charCode !== 8) && (charCode !== 46)) {
    return false;
  }
  return true;
}
function isNumberNdoc(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if ((charCode < 48 || charCode > 57) && (charCode !== 8)) {
    return false;
  }
  return true;
}
function isFormatSerie() {
  tdoc = $("#cmbdcto").val();
  serie = $("#cndoc1").val();
  lserie = serie.substring(0, 1);
  udserie = serie.substring(3, 4);
  formatocumple = false;
  if (tdoc == '01' && (lserie == 'F' || lserie == 'E')) {
    formatocumple = true;
  }
  if (tdoc == '03' && !(lserie == 'F')) {
    formatocumple = true;
  }
  if (tdoc = 'GI') {
    formatocumple = true;
  }
  if (formatocumple == false) {
    $("#cndoc1").val("");
    // toastr.error("Mensaje del sistema", "La serie no cumple con el tipo de documento.")
    // $("#cndoc1").focus();
    // $("#cndoc1").select();
  }
}
function isFormatSerieOcompra() {
  tdoc = $("#cmbtdoc").val();
  serie = $("#cndoc1").val();
  lserie = serie.substring(0, 1);
  udserie = serie.substring(3, 4);
  formatocumple = false;
  if (tdoc == '01' && (lserie == 'F' || lserie == 'E')) {
    formatocumple = true;
  }
  if (tdoc == '03' && !(lserie == 'F')) {
    formatocumple = true;
  }
  if (formatocumple == false) {
    $("#cndoc1").val("");
    // toastr.error("Mensaje del sistema", "La serie no cumple con el tipo de documento.")
    // $("#cndoc1").focus();
    // $("#cndoc1").select();
  }
}
function limpiarDatos(form) {
  $(form)[0].reset();
}
function limpiaErrores(idformulario) {
  const formulario = document.getElementById(idformulario);
  const campos_formulario = formulario.elements;
  for (let i = 0; i < campos_formulario.length; i++) {
    campos_formulario[i].classList.remove("is-invalid");
  }
}
function moverCursorFinalTexto(id) {
  var el = document.getElementById(id)
  el.focus()
  if (typeof el.selectionStart == "number") {
    el.selectionStart = el.selectionEnd = el.value.length;
  } else if (typeof el.createTextRange != "undefined") {
    var range = el.createTextRange();
    range.collapse(false);
    range.select();
  }
}
// function moverCursorFinalTextoTabla(id, tabla) {
//   var el = document.getElementById(id)
//   if ($('#' + tabla).find('td').length > 0) {
//   } else {
//     el.focus()
//     if (typeof el.selectionStart == "number") {
//       el.selectionStart = el.selectionEnd = el.value.length;
//     } else if (typeof el.createTextRange != "undefined") {
//       var range = el.createTextRange();
//       range.collapse(false);
//       range.select();
//     }
//   }
// }

function showtoastrerrors(e) {
  errors = Object.entries(e)
  errors.forEach((element) => toastr.error("Mensaje del Sistema", element[1]));
}
function mostrarErrores(idformulario, errores) {
  const campos_con_errores = Object.keys(errores);
  const formulario = document.getElementById(idformulario);
  const campos_formulario = formulario.elements;

  for (let i = 0; i < campos_formulario.length; i++) {
    campos_formulario[i].classList.remove("is-invalid");
  }

  campos_con_errores.map(function (item) {
    const campo = $("#" + idformulario + " [name=" + item + "]");
    if (campo.length) {
      campo.addClass("is-invalid");
      let span_error = $("#span_error_" + item);
      if (span_error.length) {
        span_error.html(errores[item][0]);
      } else {
        span_error = '<span id="span_error_' + item + '" class="invalid-feedback">' + errores[item][0] + "</span>";
        campo.parent().append(span_error);
      }
    }
  });
}
function vendedorseleccionado(vendedor) {
  // let sleTex = vendedor.options[vendedor.selectedIndex].innerHTML;
  let selVal = vendedor.value;
  localStorage.setItem('codv', selVal);
}
function empresaseleccionada() {
  var select = document.getElementById("cmbAlmacen");
  select.addEventListener("change", function () {
    var selectedOption = this.options[select.selectedIndex];
    //console.log(selectedOption.value + ': ' + selectedOption.text);
  });
  const sele = select.options[select.selectedIndex];
  const empresasel = sele.value;
  return empresasel;
}
function mayusculas(e) {
  e.value = e.value.toUpperCase();
}
function obtenertipobusquedacliente() {
  let vdvto = 0;

  if (document.getElementsByName("optradios")[0].checked) {
    document.getElementById("txtbuscar").focus()
    vdvto = 0;
  }
  if (document.getElementsByName("optradios")[1].checked) {
    vdvto = 1;
    document.getElementById("txtbuscar").focus()
  }
  if (document.getElementsByName("optradios")[2].checked) {
    vdvto = 2;
    document.getElementById("txtbuscar").focus()
  }
  if (document.getElementsByName("optradios")[3].checked) {
    document.getElementById("txtbuscar").focus()
    vdvto = 3;
  }
  return vdvto;
}
function consultarclientes() {
  var noption;
  var abuscar = document.querySelector("#txtbuscar").value;
  if (abuscar.length == 0) {
    toastr.info("Ingrese dato a buscar");
    return;
  }
  noption = obtenertipobusquedacliente();
  // console.log(noption);
  var cmodo = "S";
  axios.get("/cliente/buscar", {
    params: {
      cbuscar: abuscar,
      option: noption,
      modo: cmodo,
    }
  }).then(function (respuesta) {
    // 100, 200, 300
    const contenido_tabla = respuesta.data;
    // console.log(respuesta.data);
    $("#search").html(contenido_tabla);
    $("#cmdbuscar").attr('disabled', true);

    var nombre = "N", ciudad = "-", direccion = "-", ubigeo = "-";
    const tblcl = $("#iniciar").val();
    if ((tblcl == null) && (noption == '1' || noption == '2')) {
      switch (noption) {
        case 1:
          if (abuscar.length == 11) {
            axios.get('/empresa/importarucydni', {
              "params": {
                "ruc": abuscar
              }
            }).then(function (respuesta) {
              nombre = respuesta.data.nombre_o_razon_social;
              if (abuscar.substring(0, 1) == '2') {
                direccion = respuesta.data.direccion;
                ciudad = respuesta.data.distrito.trimEnd() + ' ' + respuesta.data.provincia.trimEnd() + ' ' + respuesta.data.departamento.trimEnd();
                ubigeo = respuesta.data.ubigeo.trimEnd();
              }
              if (nombre !== undefined) {
                Swal.fire({
                  title: "Cliente no registrado en el sistema, ¿Desea agregarlo?",
                  text: nombre,
                  icon: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Sí, deseo registrarlo.',
                  cancelButtonText: 'No, volver atras.'
                }).then(function (respuesta) {
                  if (respuesta.isConfirmed) {
                    const data = new FormData();
                    data.append("txtRUC", abuscar);
                    data.append("txtDNI", "");
                    data.append("txtNombre", nombre);
                    data.append("txtDireccion", direccion);
                    data.append("txtCiudad", ciudad);
                    data.append("cmbUbigeo", ubigeo);
                    axios.post('/cliente/store', data)
                      .then(function (respuesta) {
                        axios.get("/cliente/buscar", {
                          params: {
                            cbuscar: abuscar,
                            option: noption,
                            modo: cmodo,
                          }
                        }).then(function (rp) {
                          const contenido_tabla = rp.data;
                          $("#search").html(contenido_tabla);
                          $("#cmdbuscar").attr('disabled', true);
                          btnagregar = $("#iniciar").find("button");
                          $(btnagregar).click();
                        });
                      }).catch(function (error) {
                        if (error.hasOwnProperty('response')) {
                          if (error.response.status === 422) {
                            const respuesta_servidor = error.response.data;
                            const errores = respuesta_servidor.errors;
                            mostrarErrores('formulario-crear', errores);
                          }
                        }
                      })
                  }
                });
              }
            }).catch(function (error) {
              toastr.error(error, 'Mensaje del Sistema')
            });
          }
          break;
        case 2:
          if (abuscar.length == 8) {
            axios.get('/empresa/importarucydni', {
              "params": {
                "ruc": abuscar
              }
            }).then(function (respuesta) {
              nombre = respuesta.data.nombre;
              if (nombre !== undefined) {
                Swal.fire({
                  title: "Cliente no registrado en el sistema, ¿Desea agregarlo?",
                  text: nombre,
                  icon: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Sí, deseo registrarlo.',
                  cancelButtonText: 'No, volver atras.'
                }).then(function (respuesta) {
                  if (respuesta.isConfirmed) {
                    if (respuesta.isConfirmed) {
                      const data = new FormData();
                      data.append("txtRUC", "");
                      data.append("txtDNI", abuscar);
                      data.append("txtNombre", nombre);
                      data.append("txtDireccion", direccion);
                      data.append("txtCiudad", ciudad);
                      data.append("cmbUbigeo", ubigeo);
                      axios.post('/cliente/store', data)
                        .then(function (respuesta) {
                          axios.get("/cliente/buscar", {
                            params: {
                              cbuscar: abuscar,
                              option: noption,
                              modo: cmodo,
                            }
                          }).then(function (rp) {
                            const contenido_tabla = rp.data;
                            $("#search").html(contenido_tabla);
                            $("#cmdbuscar").attr('disabled', true);
                            btnagregar = $("#iniciar").find("button");
                            $(btnagregar).click();
                          });
                        }).catch(function (error) {
                          if (error.hasOwnProperty('response')) {
                            if (error.response.status === 422) {
                              const respuesta_servidor = error.response.data;
                              const errores = respuesta_servidor.errors;
                              mostrarErrores('formulario-crear', errores);
                            }
                          }
                        })
                    }
                  }
                });
              }
            }).catch(function (error) {
              toastr.error(error, 'Mensaje del Sistema')
            });
          }
          break;
        default:
          console.log("Tipo de busqueda invalido");
      }
    }

  }).catch(function (error) {
    // 400, 500
    console.log(error);
    toastr.error("Error al cargar el listado", 'Mensaje del Sistema');
  });
}
function consultarDestinatarios() {
  var noption;
  // console.log(window.location.href);
  var abuscar = document.querySelector("#txtbuscar").value;

  if (abuscar.length == 0) {
    toastr.info("Ingrese Dato a buscar");
    return;
  }
  noption = obtenertipobusquedacliente();
  // console.log(noption);
  var cmodo = "S";
  axios
    .get("/destinatario/lista", {
      params: {
        cbuscar: abuscar,
        option: noption,
        modo: cmodo,
      },
    }).then(function (respuesta) {
      // 100, 200, 300
      const contenido_tabla = respuesta.data;
      $("#search").html(contenido_tabla);
    }).catch(function (error) {
      toastr.error("Error al cargar el listado" + error);
    });
}
function pulsarenterbuscarclientes(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    e.preventDefault();
    var valorbusqueda = document.getElementById("txtbuscar").value;
    if (valorbusqueda.length == 0) {
      toastr.info("Ingrese Dato a Buscar", 'Mensaje del Sistema');
      return;
    }
    consultarclientes();
  }
}
function pulsarenterbuscardestinatarios(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    e.preventDefault();
    var valorbusqueda = document.getElementById("txtbuscar").value;
    if (valorbusqueda.length == 0) {
      toastr.info("Ingrese Dato a Buscar", 'Mensaje del Sistema');
      return;
    }
    consultarDestinatarios();
  }
}
function pulsarenterbuscarremitentes(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    e.preventDefault();
    var valorbusqueda = document.getElementById("txtbuscarProv").value;
    if (valorbusqueda.length == 0) {
      toastr.info("Ingrese dato a buscar", 'Mensaje del Sistema');
      return;
    }
    buscarRemitente();
  }
}
function pulsarentertransportista(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    e.preventDefault();
    var valorbusqueda = document.getElementById("txtbuscarTr").value;
    if (valorbusqueda.length == 0) {
      toastr.info("Ingrese Dato a Buscar", 'Mensaje del Sistema');
      return;
    }
    var cmdbuscar = document.getElementById('cmdbuscartra');
    if (cmdbuscar.disabled == false) {
      buscarTransportista();
    }
  }
}
function pulsarenterbuscarproveedor(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    e.preventDefault();
    var valorbusqueda = document.getElementById("txtbuscarprov").value;
    if (valorbusqueda.length == 0) {
      toastr.info("Ingrese dato a buscar",'Mensaje del Sistema');
      return;
    }
    buscarProveedor();
  }
}
function pulsarenterbuscarproductos(e) {
  if (e.keyCode === 13 && !e.shiftKey) {
    // e.preventDefault();
    var valorbusqueda = document.getElementById("txtbuscarProducto").value;
    if (valorbusqueda.length == 0) {
      toastr.info("Ingrese Dato a Buscar", 'Mensaje del Sistema');
      return;
    }
    buscarProducto();
  }
}
function activarydesactivar() {
  if ($('#flexCheckDefault').is(':checked')) {
    $('#form :input').attr('disabled', true);
  } else {
    $('#form :input').removeAttr('disabled');
  }
}
function reporteTabla(tbl) {
  $(document).ready(function () {
    $(tbl).DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "dom": 'Bfrtip',
      "keys": true,
      "buttons": [{
        //Botón para Excel
        extend: 'excelHtml5',
        footer: true,
        title: 'Reporte de Productos Personalizados',
        filename: 'Sysven-Reporte',
        //Aquí es donde generas el botón personalizado
        text: '<span class="badge badge-success"><i class="fas fa-file-excel"></i></span>'
      },
      //Botón para PDF
      {
        extend: 'pdfHtml5',
        download: 'open',
        title: 'Reporte de Productos Personalizados',
        filename: 'Sysven-Reporte',
        text: '<span class="badge  badge-danger"><i class="fas fa-file-pdf"></i></span>'
      },
      //Botón para copiar
      {
        extend: 'copyHtml5',
        footer: true,
        title: 'Reporte de Productos Personalizados',
        filename: 'Sysven-Reporte',
        text: '<span class="badge  badge-primary"><i class="fas fa-copy"></i></span>',
        exportOptions: {
          columns: [0, ':visible']
        }
      },
      // //Botón para print
      // {
      //     extend: 'print',
      //     footer: true,
      //     filename: 'Export_File_print',
      //     text: '<span class="badge badge-light"><i class="fas fa-print"></i></span>'
      // },
      //Botón para cvs
      {
        extend: 'csvHtml5',
        footer: true,
        filename: 'Export_File_csv',
        text: '<span class="badge  badge-success"><i class="fas fa-file-csv"></i></span>'
      }
        // {
        //     extend: 'colvis',
        //     text: '<span class="badge  badge-info"><i class="fas fa-columns"></i></span>',
        //     postfixButtons: ['colvisRestore']
        // }
      ],
      "footerCallback": function (row, data, start, end, display) {
        var api = this.api();

        // Remove the formatting to get integer data for summation
        var intVal = function (i) {
          return typeof i === 'string' ?
            i.replace(/[\$,]/g, '') * 1 :
            typeof i === 'number' ?
              i : 0;
        };

        // Total over all pages
        total = api
          .column(8)
          .data()
          .reduce(function (a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Total over this page
        pageTotal = api
          .column(8, {
            page: 'current'
          })
          .data()
          .reduce(function (a, b) {
            return intVal(a) + intVal(b);
          }, 0);

        // Update footer
        $(api.column(9).footer()).html(addCommas(total.toFixed(2)));
      }
    });

    function addCommas(nStr) {
      nStr += '';
      x = nStr.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
      }
      return x1 + x2;
    }
  });
}
function focustabla(tbl) {
  $(document).ready(function () {
    var table = $(tbl).DataTable({
      paging: true,
      lengthChange: false,
      searching: false,
      info: false,
      keys: true,
      autoWidth: false,
      responsive: true,
      columnDefs: [
        {
          targets: 2,
          orderable: false,
          searchable: false,
        },
        { className: "dt-head-center" },
      ],
      fnCreatedRow: function (rowEl, data) {
        $(rowEl).attr("id", data[0]);
      },
    });
    $('thead').css({
      color: "white",
      "background-color": "#03326a"
      // 006CA7
    });
    $(table.row().node()).addClass("selected");
    $("#iniciar").trigger("click");
    $(tbl).on("key-focus.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).addClass("selected");
    });
    $(tbl).on("key-blur.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).removeClass("selected");
    });
    $(tbl).on("key.dt", function (e, datatable, key, cell, originalEvent) {
      if (key === 13) {
        var data = table.row(cell.index().row).data();
        arr = data[2].split('"');
        const cmd = "#" + arr[1];
        document.querySelector(cmd).click();
      }
    });
  });
}
function focustablalotefvto(tbl) {
  $(document).ready(function () {
    var table = $(tbl).DataTable({
      paging: true,
      lengthChange: false,
      searching: false,
      info: false,
      keys: true,
      autoWidth: false,
      responsive: true,
      columnDefs: [
        {
          targets: 2,
          orderable: false,
          searchable: false,
        },
        { className: "dt-head-center" },
      ],
      fnCreatedRow: function (rowEl, data) {
        $(rowEl).attr("id", data[0]);
      },
    });
    $('thead').css({
      color: "white",
      "background-color": "#03326a"
    });
    $(table.row().node()).addClass("selected");
    $("#iniciarlotefvto").click()
    $(tbl).on("key-focus.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).addClass("selected");
    });
    $(tbl).on("key-blur.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).removeClass("selected");
    });
    $(tbl).on("key.dt", function (e, datatable, key, cell, originalEvent) {
      if (key === 13) {
        var data = table.row(cell.index().row).data();
        arr = data[3].split('"');
        const cmd = "#" + arr[1];
        document.querySelector(cmd).click();
      }
    });
  });
}
function focustablatransportista(tbl) {
  $(document).ready(function () {
    var table = $(tbl).DataTable({
      paging: true,
      lengthChange: false,
      searching: false,
      info: false,
      keys: true,
      autoWidth: false,
      responsive: true,
      columnDefs: [
        {
          targets: 2,
          orderable: false,
          searchable: false,
        },
        { className: "dt-head-center" },
      ],
      fnCreatedRow: function (rowEl, data) {
        $(rowEl).attr("id", data[0]);
      },
    });
    $('thead').css({
      color: "white",
      "background-color": "#03326a"
    });
    $(table.row().node()).addClass("selected");
    $("#iniciartra").trigger("click");
    $(tbl).on("key-focus.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).addClass("selected");
    });
    $(tbl).on("key-blur.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).removeClass("selected");
    });
    $(tbl).on("key.dt", function (e, datatable, key, cell, originalEvent) {
      if (key === 13) {
        var data = table.row(cell.index().row).data();
        arr = data[3].split('"');
        const cmd = "#" + arr[1];
        document.querySelector(cmd).click();
      }
    });
  });
}
function focustablacliente(tbl) {
  $(document).ready(function () {
    var table = $(tbl).DataTable({
      paging: true,
      lengthChange: false,
      searching: false,
      info: false,
      keys: true,
      autoWidth: false,
      responsive: true,
      columnDefs: [
        {
          targets: 2,
          orderable: false,
          searchable: false,
        },
        { className: "dt-head-center" },
      ],
      fnCreatedRow: function (rowEl, data) {
        $(rowEl).attr("id", data[0]);
      },
    });
    $('thead').css({
      color: "white",
      "background-color": "#03326a"
      // 006CA7
    });
    $(table.row().node()).addClass("selected");
    $("#iniciar").trigger("click");
    $(tbl).on("key-focus.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).addClass("selected");
    });
    $(tbl).on("key-blur.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).removeClass("selected");
    });
    $(tbl).on("key.dt", function (e, datatable, key, cell, originalEvent) {
      if (key === 13) {
        var data = table.row(cell.index().row).data();
        arr = data[3].split('"');
        // console.log(arr);
        const cmd = "#" + arr[1];
        document.querySelector(cmd).click();
      }
    });
  });
}
function focustablaproducto(tbl) {

  var w = screen.width;
  responsividad = true;

  if (w >= 768) {
    responsividad = false;
  }

  $(document).ready(function () {
    var table = $(tbl).DataTable({
      paging: true,
      lengthChange: false,
      searching: false,
      info: false,
      keys: true,
      autoWidth: false,
      responsive: responsividad,
      columnDefs: [
        {
          targets: 2,
          orderable: false,
          searchable: false,
        },
        { className: "dt-head-center" },
      ],
      fnCreatedRow: function (rowEl, data) {
        $(rowEl).attr("id", data[0]);
      },
    });
    $('thead').css({
      color: "white",
      "background-color": "#03326a"
    });
    $(table.row().node()).addClass("selected");
    $("#iniciarp").trigger("click");
    $(tbl).on("key-focus.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).addClass("selected");
    });
    $(tbl).on("key-blur.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).removeClass("selected");
    });
    $(tbl).on("key.dt", function (e, datatable, key, cell, originalEvent) {
      if (key === 13) {
        var data = table.row(cell.index().row).data(); // obtenemos todas las columnas, con [0] accedemos a la primera 
        totalcolumns = data.length;
        arr = data[totalcolumns - 1].split('"'); //entramos al boton guardar
        const cmd = "#" + arr[5]; //asignamos el ID al boton
        document.querySelector(cmd).click(); //simulamos un clic en el boton
        $(cmd).attr("disabled", "disabled"); //desactivamos el boton para que no se agregado dos veces

        // A partir de acá son pruebas
        // $("#modal_productos").modal('hide');
        // $(".selected").removeClass('selected');
        // $("#iniciarp").removeClass("focus");
        // $("#iniciarp").blur();
      }
    });
  });
  $("#txtbuscarProducto").on("click", function () {
    $("#txtbuscarProducto").select();
  });
}
function focustablacelular(tbl) {
  $(document).ready(function () {
    var table = $(tbl).DataTable({
      paging: false,
      lengthChange: false,
      searching: false,
      info: false,
      keys: true,
      autoWidth: true,
      responsive: true,
      columnDefs: [
        {
          targets: 2,
          orderable: false,
          searchable: false,
        },
        { className: "dt-head-center" },
      ],
      fnCreatedRow: function (rowEl, data) {
        $(rowEl).attr("id", data[0]);
      },
    });
    $('thead').css({
      color: "white",
      "background-color": "#03326a"
      // 006CA7
    });
    $(table.row().node()).addClass("selected");
    $("#iniciar").trigger("click");
    $(tbl).on("key-focus.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).addClass("selected");
    });
    $(tbl).on("key-blur.dt", function (e, datatable, cell) {
      $(table.row(cell.index().row).node()).removeClass("selected");
    });
    $(tbl).on("key.dt", function (e, datatable, key, cell, originalEvent) {
      if (key === 13) {
        var data = table.row(cell.index().row).data();
        arr = data[2].split('"');
        const cmd = "#" + arr[1];
        document.querySelector(cmd).click();
      }
    });
  });
}
function cerrarventana(formulario, modal) {
  limpiaErrores(formulario);
  $(modal).modal("hide");
}
function titulo(titulo) {
  $("#titulo").html(titulo);
}
function obtenerTipoIGV() {
  let vdvto = 'I';
  if (document.getElementsByName("igv")[0].checked) {
    vdvto = 'I';
  }
  if (document.getElementsByName("igv")[1].checked) {
    vdvto = 'N';
  }
  return vdvto;
}
function validarNumeros(evt) {
  // code is the decimal ASCII representation of the pressed key.
  var code = (evt.which) ? evt.which : evt.keyCode;

  if (code == 8) { // backspace.
    return true;
  } else if (code >= 48 && code <= 57) { // is a number.
    return true;
  } else { // other keys.
    return false;
  }
}
//VALIDAR RUC Y/O DNI
function validarDNI() {
  txtdni = document.querySelector("#txtdni").value;
  if (txtdni.length != '') {
    if (txtdni.length < 8) {
      toastr.error("Dígite correctamente el DNI");
      return false;
    }
  } else {
    return true;
  }
}
function validarRUC() {
  txtruc = document.querySelector("#txtruc").value;
  if (txtruc.length != '') {
    if (txtruc.length < 11) {
      toastr.error("Dígite correctamente el RUC");
      return false;
    }
  } else {
    return true;
  }
}
function buscaruc() {
  var ruc;
  ruc = document.querySelector('.txtruc').value;
  if (ruc.length == 11) {
    axios.get('/empresa/importarucydni', {
      "params": {
        "ruc": ruc
      }
    }).then(function (respuesta) {
      $(".txtnombre").val(respuesta.data.nombre_o_razon_social)
      if (ruc.substring(0, 1) == '2') {
        $(".txtdireccion").val(respuesta.data.direccion)
        $(".txtciudad").val(respuesta.data.distrito.trimEnd() + ' ' + respuesta.data.provincia.trimEnd() + ' ' + respuesta.data.departamento.trimEnd())
      }
    }).catch(function (error) {
      console.log(error);
    })
  }
}
function buscadni() {
  var ruc;
  ruc = document.querySelector('.txtdni').value;
  if (ruc.length == 8) {
    axios.get('/empresa/importarucydni', {
      "params": {
        "ruc": ruc
      }
    }).then(function (respuesta) {
      // console.log(respuesta.data);
      $(".txtnombre").val(respuesta.data.nombre)
      // console.log(ruc.substring(0, 1))
    }).catch(function (error) {
      console.log(error);
    })
  }
}
function calcularfechavto() {
  // var df = $("#txtfecha").val();
  // fa = df.substr(-2, 10) + '/ ' + df.substr(5, 2) + '/' + df.substr(0, 4)
  // fa = df.substr(5, 2) + '/' + df.substr(-2, 10) + '/ ' + '/' + df.substr(0, 4)

  // var TuFecha = new Date(fa);
  // var dias = document.getElementById("txtdias").value;
  // TuFecha.setDate(TuFecha.getDate() + parseInt(dias));
  // dia = String(TuFecha.getDate())

  // if (dia.length == 1) {
  //   dia = "0" + dia
  // }
  // // console.log(dia)
  // mes = String(TuFecha.getMonth() + 1)
  // // console.log(mes.length)

  // if (mes.length == 1) {
  //   mes = "0" + mes
  // }
  // // console.log(mes)
  // //formato de salida para la fecha
  // dfvto = TuFecha.getFullYear() + '-' + mes + '-' + dia;
  // if (isNaN(dia)) {
  //   var fecha = new Date();
  //   var mes = fecha.getMonth() + 1;
  //   var dia = fecha.getDate();
  //   var ano = fecha.getFullYear();
  //   if (dia < 10)
  //     dia = '0' + dia;
  //   if (mes < 10)
  //     mes = '0' + mes
  //   document.getElementById('txtfechavto').value = ano + "-" + mes + "-" + dia;
  // } else {
  //   $("#txtfechavto").val(dfvto);
  // }
  var txtfecha = document.getElementById("txtfecha").value;
  var txtdias = document.getElementById("txtdias").value;
  axios.get('/calcularfechavto', {
    "params": {
      "txtfecha": txtfecha,
      'txtdias': txtdias
    }
  }).then(function (respuesta) {
    $("#txtfechavto").val(respuesta.data);
  }).catch(function (error) {
    toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
  });
}
function mostrarerroresvalidacion(error) {
  if (error.hasOwnProperty("response")) {
    if (error.response.status === 422) {
      e = error['response']['data']
      result = []
      for (var i in e) {
        result.push([i, e[i]]);
      }
      result.forEach(function (numero) {
        toastr.error(numero[1])
      });
    }
  }
}
function obtenerdetalletabla(idtabla) {
  const detalle = []
  $("#" + idtabla + " tbody tr").each(function () {
    json = "";
    $(this).find("td input").each(function () {
      $this = $(this);
      json += ',"' + $this.attr("class") + '":"' + $this.val() + '"'
    })
    obj = JSON.parse('{' + json.substr(1) + '}');
    detalle.push(obj)
  });
  return detalle;
}
function rellenaNumero() {
  valor = document.getElementById("cndoc2").value;
  cndoc = "00000000" + valor.trim();
  document.getElementById("cndoc2").value = cndoc.substr(cndoc.length - 8);
}
function obtenerFechas() {
  var fecha = new Date(); //Fecha actual
  var mes = fecha.getMonth() + 1; //obteniendo mes
  var dia = fecha.getDate(); //obteniendo dia
  var ano = fecha.getFullYear(); //obteniendo año
  if (dia < 10)
    dia = '0' + dia; //agrega cero si el menor de 10
  if (mes < 10)
    mes = '0' + mes //agrega cero si el menor de 10
  document.getElementById('txtfechai').value = ano + "-" + mes + "-" + dia;
  document.getElementById('txtfechaf').value = ano + "-" + mes + "-" + dia;
}
function reporteTablaLyG(tbl) {
  $(document).ready(function () {
    $(tbl).DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "dom": 'Bfrtip',
      "keys": true,
      "buttons": [{
        //Botón para Excel
        extend: 'excelHtml5',
        footer: true,
        title: 'Reporte de Sysven',
        filename: 'Sysven-Reporte',
        //Aquí es donde generas el botón personalizado
        text: '<span class="badge badge-success"><i class="fas fa-file-excel"></i></span>'
      },
      //Botón para PDF
      {
        extend: 'pdfHtml5',
        download: 'open',
        title: 'Reporte de Sysven',
        filename: 'Sysven-Reporte',
        text: '<span class="badge badge-danger"><i class="fas fa-file-pdf"></i></span>'
      },
      //Botón para copiar
      {
        extend: 'copyHtml5',
        footer: true,
        title: 'Reporte de Sysven',
        filename: 'Sysven-Reporte',
        text: '<span class="badge badge-primary"><i class="fas fa-copy"></i></span>',
        exportOptions: {
          columns: [0, ':visible']
        }
      },
      //Botón para cvs
      {
        extend: 'csvHtml5',
        footer: true,
        filename: 'Export_File_csv',
        text: '<span class="badge badge-success"><i class="fas fa-file-csv"></i></span>'
      }
      ]
    })
  })
}
function verdetallecombo(idart) {
  axios.get('/productos/verdetallecombo', {
    "params": {
      "txtidart": idart
    }
  }).then(function (respuesta) {
    // $('#detallecombo').html(respuesta.data)
    // $('#modaldetallecombo').modal('show');
    Swal.fire({
      title: respuesta.data,
      showDenyButton: true,
      confirmButtonText: "Aceptar"
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      // if (result.isConfirmed) {
      //   Swal.fire("Saved!", "", "success");
      // } else if (result.isDenied) {
      //   Swal.fire("Changes are not saved", "", "info");
      // }
    });
  }).catch(function (error) {
    toastr.error('Error al cargar el modal ' + error, 'Mensaje del sistema')
  });
}
// function hidemodal(idmodal) {
//   $(idmodal).removeClass("in");
//   $(".modal-backdrop").remove();
//   $(idmodal).hide();
// }
// tdoc = $("#cmbdcto").val();
// serie = $("#cndoc1").val();
// lserie = serie.substring(0, 1);
// udserie = serie.substring(3, 4);
// console.log(udserie)
// formatocumple = false;
// if (tdoc == '01' && (lserie == 'F' || lserie == 'E')) {
//   formatocumple = true;
// }
// if (tdoc == '03') {
//   formatocumple = true;
// }
// if (formatocumple == false) {
//   $("#cndoc1").val("");
//   // toastr.error("Mensaje del sistema", "La serie no cumple con el tipo de documento.")
//   // $("#cndoc1").focus();
//   // $("#cndoc1").select();
// }

// function test1(ms) {
//   return new Promise(resolve => setTimeout(resolve, ms));
// }
$('#exampleModal').on('shown.bs.modal', function (e) {
  $("#pdfguia").get(0).contentWindow.print();
});
var w = screen.width;
if (w >= 768) {
  $("#pushmenu").click();
}
