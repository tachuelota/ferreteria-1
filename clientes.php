<?php
	require_once "utilidades/configuracion.php";
	$cliente = isset($_GET['idcliente']) && is_numeric($_GET['idcliente']) ? Cliente::buscar($_GET['idcliente']) : null;
?>
<!DOCTYPE html>
<html>
    <head>
        <style type="text/css">
        
		</style>
		<script type="text/javascript">
			$(document).ready(function(){
				var validacion = {
		            obligatorio : function(campo) {
		                var error = campo !== "" ? "" : "Debe especificar el campo.";
		                return error;
		            },
		            flotante : function(campo) {
		                var error = esFlotante(campo) ? "" : "Debes especificar una cantidad";
		                return error;
		            }
		        }
				$.datepicker.regional['es'] = {
	                closeText: 'Cerrar',
	                prevText: 'Anterior',
	                nextText: 'Siguiente',
	                currentText: 'Hoy',
	                monthNames: ['Ene','Feb','Mar','Abr','May','Jun',
	                'Jul','Ago','Sep','Oct','Nov','Dic'],
	                monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
	                'Jul','Ago','Sep','Oct','Nov','Dic'],
	                dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
	                dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
	                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
	                weekHeader: 'Sm',
	                dateFormat: 'dd-MM-yy',
	                firstDay: 1,
	                isRTL: false,
	                showMonthAfterYear: false,
	                yearSuffix: '',
	               	changeMonth: true,
	                changeYear: true
	            };
        		$.datepicker.setDefaults($.datepicker.regional['es']);

	    		$("#fecha").datepicker({
	    			altField: "#fechaBD",
	    			altFormat: 'yy-mm-dd'
	    		});

	    		<?php echo 'var clientes = '.json_encode(array()).';'; ?>
			    var estructura = { 
					name: 'clientes en cheque',
					fields:{ 
						importe:'Importe',
						fecha: "Fecha",
						concepto:'Concepto'
					}, 
					extends:[  
						{
							name: 'Editar',
							clase: 'button',
							text: '<i class="icon-edit"></i>Editar',
							assoc: 'idcliente',
							click: function(data){
								window.location.replace("?idcliente="+data);
							}				
						},
						{
							name: 'Inhabilitar',
							clase: 'button',
							text: '<i class="icon-remove"></i>Inhabilitar',
							assoc: 'idcliente',
							click: function(data){

								$("#dialog").dialog({
							      	resizable: false,
							      	modal: true,
							      	closeOnEscape: true,
							      	hide: "explode",
							      	buttons: {
								        Eliminar: function() {
				    						var form = document.createElement('form');
								            form.setAttribute('action','');
								            form.setAttribute('method','post');
								            var input = document.createElement('input');
								            input.setAttribute('type','hidden');
								            input.setAttribute('name',"eliminar_cliente");
								            input.setAttribute('value',data);
								            form.appendChild(input);
								            document.body.appendChild(form);
								            form.submit();				
								        },
								        Cancelar: function() {
								          $(this).dialog("close");
								        }
							      	}
						    	});
							}
						},
					],
					data: clientes
				};
		    	//JWeb('clientes').grid(estructura);

				$("#agregar").on("click",function(e){
					e.preventDefault();
					$("#formulario").slideToggle();
				});

				$("#cancelar").on("click",function(e){
					e.preventDefault();
					$("#formulario :input").val("");
					$(".mal").html("");
					$("#formulario").slideToggle();
				});

				$("[name='estado']").on("change",function(){
	                var select = "<option value=''>-Selecciona una ciudad-</option>";
	                if(this.value != ""){
	                    var idestado = this.value;
	                    $.ajax({
	                        url: "herramientas/ajax/ajax_clientes.php", 
	                        dataType: "json", 
	                        data: { "estado" : idestado },
	                        type: 'post', 
	                        success: function(ciudades) {
	                            for(var i in ciudades)
	                                select += "<option value='"+ciudades[i].id_ciudad+"'>"+ciudades[i].nombre+"</option>";
	                            $("[name='ciudad']").html(select);
	                        }
	                    });
	                }
	                else
	                    $("[name='ciudad']").html(select);
	            })

				$("#buscador").on("keyup",function(){
	        	    $.ajax({
	            		url: "herramientas/ajax/ajax_ingresos.php", 
	            		dataType: "json", 
	            		data: { "busqueda_deposito_cheque" : obtenerValores($(':input', this)) },
	            		type: 'post',
	            		success: function(datos){
	            		 	estructura.data = datos;
            		 		JWeb('clientes').grid(estructura);
	            		} 
	            	});
	        	});

		        function obtenerValores(elementos){
				    var datos = {};
	                elementos.each(function() {
	                    datos[this.id] = $(this).val();
	                });
	                return datos;
				}
			});
		</script>
    </head>
    <body>
    	<div id="wrapper">
			<?php include RAIZ."/utilidades/header.php";?>
			<div class="clear"></div>

			<div class="forma">

				<div class="tituloForma">
					<span class="titulo">Clientes</span>
				</div>
				<div class="cuerpoForma">
					<div class="campo">
	                    <div class="espacio">
	                        <span class='seccion'>Rfc</span>
	                    </div>
	                    <input type="text" class="entrada" data-validador="obligatorio" name="rfc" value="<?= $cliente ? $cliente->getRfc() : ''?>">
	                </div>
	                <div class="campo">
	                    <div class="espacio">
	                        <span class='seccion'>Razon social</span>
	                    </div>
	                    <input type="text" class="entrada" data-validador="obligatorio" name="razon_social" value="<?= $cliente ? $cliente->getRazonSocial() : ''?>">
	                </div>
	                <div class="campo">
	                    <div class="espacio">
	                        <span class='seccion'>Calle y numero</span>
	                    </div>
	                    <input type="text" class="entrada" data-validador="obligatorio" name="domicilio" value="<?= $cliente ? $cliente->getDomicilio() : ''?>">
	                </div>
	                <div class="campo">
	                    <div class="espacio">
	                        <span class='seccion'>Codigo postal</span>
	                    </div>
	                    <input type="text" class="entrada chico" data-validador="obligatorio" name="domicilio" value="<?= $cliente ? $cliente->getCodigoPostal() : ''?>">
	                </div>
	                <div class="campo">
	                    <div class="espacio">
	                        <span class='seccion'>Colonia</span>
	                    </div>
	                    <input type="text" class="entrada" data-validador="obligatorio" name="colonia" value="<?= $cliente ? $cliente->getColonia() : ''?>">
	                </div>
	                <div class="campo">
	                    <div class="espacio">
	                        <span class='seccion'>Estado</span>
	                    </div>
	                    <select class="entrada" data-validador="obligatorio" name="estado">
	                    	 <option value=''>-Selecciona un estado-</option>;
                            <?php
                                foreach(Localidad::obtenerEstados() as $estado)
                                    echo '<option value="'.$estado['id_estado'].'" ' . ($user && ($estado['id_estado'] == $user->getEstado()) ? "selected" : "") .'>'.$estado['nombre'].'</option>';    
                            ?>
	                    </select>
	                </div>
	                <div class="campo">
	                	<div class="espacio">
	                		<span class="seccion">Ciudad</span>
	                	</div>
	                	<select class="entrada" data-validador="obligatorio" name="ciudad">
	                		<option value="">-Selecciona una ciudad-</option>
	                	</select>
	                </div>
				</div>

			</div>
		</div>
    </body>
</html>