<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/ferreteria/utilidades/configuracion.php";

    if(isset($_POST['familia'])) {
        
        $familia = new Familia();
        $familia->setNombre($_POST['nombre']);

        if($_POST['id']) {
            $familia->setId($_POST['id']);
            $familia->setActivo(1);
            $familia->actualizar();
        }
        else
            $familia->insertar();
        
        header("Location: ./familias.php");
        exit();
    }
    else if(isset($_POST['inhabilitar'])) {
        
        $familias = Familia::buscar(array(
            "condiciones" => array(
                array("id", "=", $_POST['inhabilitar']),
                array("activo", "=", 1))
                ), "Familia");
        $familia = $familias ? $familias[0] : null;

        if($familia) {
            $familia->setActivo(0);
            $familia->actualizar();
        }

        header("Location: ./familias.php");
        exit();
    }
    else if(isset($_POST['habilitar'])) {

        $familias = Familia::buscar(array(
            "condiciones" => array(
                array("id", "=", $_POST['habilitar']),
                array("activo", "=", 0))
                ), "Familia");
        $familia = $familias ? $familias[0] : null;

        if($familia) {
            $familia->setActivo(1);
            $familia->actualizar();
        }

        header("Location: ./familias.php");
        exit();
    }
    else {
        
        $familias = 
        isset($_GET['id']) && is_numeric($_GET['id']) ?
        Familia::buscar(array(
            "condiciones" => array(
                array("id", "=", $_GET['id']),
                array("activo", "=", 1))), "Familia") : array();
        $familia = $familias ? $familias[0] : null;

    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include RAIZ."/html/herramientas.php"; ?>
        <title>Familias</title>
        <style>
            
            .campo {
                display: inline-block;
                font-style: italic;
                color: #333;
            }

            #familias, #familias_inactivas {
                margin-top: 20px;
            }

            .derecha {
                float: right;
            }

        </style>
        <script>

            var validacion = {
                obligatorio : function(elemento) {
                    var campo = trim(elemento.value);
                    var error = campo !== "" ? "" : "Debe especificar el campo.";
                    return error;
                }
            }

            $(document).ready(function() {

                $("#nuevo").click(function(e) { window.location.replace("./familias.php"); });

                var familias = {
                    name: 'Familias',
                    fields : {
                        nombre : 'Nombre'
                    },
                    extends:[
                        {
                            name: 'Editar',
                            text: '<i class="fa fa-pencil-square-o icono"></i>Editar',
                            clase: 'button',
                            assoc: 'id',
                            click: function(data) {
                                window.open("familias.php?id="+data, '_self');
                            }
                        },
                        {
                            name: 'Inhabilitar',
                            text: '<i class="fa fa-times icono"></i>Inhabilitar',
                            clase: 'button',
                            assoc: 'id',
                            click: function(data) {
                                
                                $("<div class='dialogo'>¿Desea inhabilitar el elemento?</div>").dialog({
                                    resizable: false,
                                    modal: true,
                                    width: 400,
                                    closeOnEscape: true,
                                    hide: "explode",
                                    show: {
                                        effect: "drop",
                                        direction: "top",
                                        duration: 500,
                                        distance: 500
                                    },
                                    buttons: {
                                        "Inhabilitar": function() {
                                            var form = document.createElement('form');
                                            form.setAttribute('action','');
                                            form.setAttribute('method','post');
                                            var input = document.createElement('input');
                                            input.setAttribute('type','hidden');
                                            input.setAttribute('name',"inhabilitar");
                                            input.setAttribute('value',data);
                                            form.appendChild(input);
                                            document.body.appendChild(form);
                                            form.submit();              
                                        },
                                        "Cancelar": function() {
                                            $(this).dialog("destroy");
                                        }
                                    }
                                });

                            }
                        }
                    ],
                    data: <?php echo json_encode(Familia::buscar(array("condiciones" => array("activo", "=", 1)))); ?>
                };

                var familias_inactivas = {
                    name: 'Familias inactivas',
                    fields : {
                        nombre : 'Nombre'
                    },
                    extends:[
                        {
                            name: 'Habilitar',
                            text: '<i class="fa fa-ambulance icono"></i>Habilitar',
                            clase: 'button',
                            assoc: 'id',
                            click: function(data) {
                                
                                $("<div class='dialogo'>¿Desea habilitar el elemento?</div>").dialog({
                                    resizable: false,
                                    modal: true,
                                    width: 400,
                                    closeOnEscape: true,
                                    hide: "explode",
                                    show: {
                                        effect: "drop",
                                        direction: "top",
                                        duration: 500,
                                        distance: 500
                                    },
                                    buttons: {
                                        "Habilitar": function() {
                                            var form = document.createElement('form');
                                            form.setAttribute('action','');
                                            form.setAttribute('method','post');
                                            var input = document.createElement('input');
                                            input.setAttribute('type','hidden');
                                            input.setAttribute('name',"habilitar");
                                            input.setAttribute('value',data);
                                            form.appendChild(input);
                                            document.body.appendChild(form);
                                            form.submit();              
                                        },
                                        "Cancelar": function() {
                                            $(this).dialog("destroy");
                                        }
                                    }
                                });

                            }
                        }
                    ],
                    data: <?php echo json_encode(Familia::buscar(array("condiciones" => array("activo", "=", 0)))); ?>
                };

                JWeb('familias').grid(familias);
                JWeb('familias_inactivas').grid(familias_inactivas);

                $('#formaFamilia').submit(function() {
                    var correcto = validar(this, "familia");
                    console.log(correcto);
                    tooltip(this, "familia");
                    return correcto;
                });

            });
        </script>
    </head>
    <body>
        <div id="wrapper">
            <?php require_once RAIZ."/html/header.php"; ?>
            <br /><br /><br /><br /><br /><br />
            <div class="forma">

                <!-- Titulo -->

                <div class="tituloForma">
                    <b>Familia</b>
                </div>
                
                <!-- Cuerpo -->
                <div class="cuerpoForma">
                        
                    <form id="formaFamilia" method="post">
                                
                        <input type="hidden" name="id" value="<?php echo $familia ? $familia->getId() : ''; ?>"/>
                        
                        <!-- Nombre -->

                        <span class="campo">Nombre:</span>
                        <input data-validador="obligatorio" data-scope="familia" name="nombre" value="<?php echo $familia ? $familia->getNombre() : ''; ?>"/>
                        <br />
                        
                        <!-- Botones -->

                        <button class="button derecha" type="submit" name="familia">Guardar</button>
                        <button class="button derecha" type="button" id="nuevo">Limpiar formulario</button>
                        <div class="clear"></div>
                                
                    </form>

                    <div id="familias"></div>
                    <div id="familias_inactivas"></div>
                    
                </div>

            </div>
    </body>
</html> 