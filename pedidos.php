<?php

	require_once $_SERVER['DOCUMENT_ROOT'] . "/ferreteria/utilidades/configuracion.php";

	if(isset($_POST["envioPedido"]))
		var_dump($_POST["envioPedido"]);
	
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once RAIZ . "/html/herramientas.php"; ?>
		<script src="<?php echo PATH; ?>/herramientas/js/entidad.js"></script>
		<title>Compras</title>
	    <style>

 			table tbody tr td { border: 1px solid #ffffff !important;}
 			table thead tr th { border: 1px solid #ffffff !important; }
 			table th { background-color: #fafafa !important; }
 			table tbody tr td input { width: 95% !important; }
 			table tbody tr td select { width: 95% !important; }
 			table tbody tr td { word-wrap: break-word; }

 			.botonera {
 				width: 100%;
 				padding: 10px 0px;
				background-color: #ffffff;
				border-radius: 5px;
				text-align: right;
				margin: 5px 0px;
 			}

 			.botonera button {
 				margin-right: 5px;
 			}

 			.contenedor {
 				min-height: 300px;
 				background-color: #fff;
 			}

 			.contenedor button {
 				width: 100%;
 			}

			.lista {
				min-height:190px;
				background-color: #fff;
				overflow-y: auto;
				list-style: none;
			}

			.lista li {
				background-color: #fff;
				padding: 10px;
				color: #6f6f6f;
				border-bottom: 1px solid #E8E8E8;
				cursor:pointer;
			}

			.lista li:hover {
				background: #0075ba;
				color:#FFFFFF;
			}

			#panel > div {
				width: 50%;
			}

			#panel div div {
				margin: 10px;
			}

			#cliente td { padding: 2px; }
			@media screen and (max-width: 768px) {
				#cliente td {
					padding-left: 0px;
					text-align: center !important;
				}
			}

			#search_bar {
				width: 100%;
				height: 100px;
				background-color: #fff;
				background-image: url(./herramientas/imagenes/search_1.png);
				background-repeat: no-repeat;
				background-position: center right;
			}
			

		</style>
		<script>

			$(document).ready(function() {

				var articulo = new Entidad({

					"objeto" : function() {

		    			this.toString = function() {
							
							return "<tr class='normal elemento'>" + 
								"<td data-jwebgridtitle='Articulo'>" + $("option[value='" + this.idarticulo + "']", document.getElementById("idarticulo")).html() + "</td>" +
								"<td data-jwebgridtitle='Cantidad'>" + this.cantidad +"</td>" +
								"<td data-jwebgridtitle='Tipo de precio'>" + $("option[value='" + this.tipo_precio + "']", document.getElementById("tipo_precio")).html() +"</td>" +
								"<td data-jwebgridtitle='Código'>" + this.codigo + "</td>" +
								"<td data-jwebgridtitle='Unidad'>" + this.unidad + "</td>" +
								"<td data-jwebgridtitle='Precio'>" + this.precio + "</td>" +
								"<td data-jwebgridtitle='Importe'>" + this.importe + "</td>" +
			        			"<td data-jwebgridtitle='Eliminar'><button type='button' class='eliminar btn button' tabindex='-1'>Elimiar</button></td>" +
								"<td data-jwebgridtitle='Editar'><button type='button' class='editar btn button' tabindex='-1'>Editar</button></td>" +
							"</tr>";

		    			}	

		    		},

					"forma" : document.getElementById("formaArticulo"),
					"carrito" : document.getElementById("contenedor_articulos"),
					"botonGuardar" : document.getElementById("guardarArticulo"),
					"botonNuevo" : document.getElementById("nuevoArticulo"),
					"defaults" : {
						"importe" : (0).toFixed(2),
						"tipo_precio" : "precio_publico"
					},
					"success" : function() {

						var subtotal = 0;
						for(var i in articulo.elementos)
							subtotal += parseFloat(articulo.elementos[i]["importe"]);

						document.getElementById("subtotal").value = subtotal.toFixed(2);
						document.getElementById("iva").value = (subtotal * .16).toFixed(2);
						document.getElementById("total").value = (subtotal * 1.16).toFixed(2);
					}

				});

				var compra = new Entidad({

					"forma" : document.getElementById("formaCompra"),
					"botonGuardar" : document.getElementById("guardarCompra"),
					"hijos" : [
						articulo
					]

				});

				/******************************************************************************** Articulos ***********************************************************************/

				var articulos = <?php
					
					$articulos = Articulo::buscar(array(
						"condiciones" => array("articulo.activo", "=", 1),
					));

					echo json_encode(Proveedor::getRelacion($articulos));

				?>;

				/****************** Filtro de articulos por proveedor ********************/

				$(document).on("change", "#idproveedor", function() {

					var idproveedor = this.value;
					var html = "<option value=''>Seleccione el artículo</option>";

					if(idproveedor)
						for(var i in articulos)
							for(var j in articulos[i]["proveedores"]) {
								if(articulos[i]["proveedores"][j].idproveedor === idproveedor)
									html += "<option value='" + articulos[i].id + "'>" + articulos[i].nombre + "</option>";
							}
					else
						for(var i in articulos)
							html += "<option value='" + articulos[i].id + "'>" + articulos[i].nombre + "</option>";
					
					var idarticulo = document.getElementById("idarticulo");
					idarticulo.innerHTML = html;
					idarticulo.value = "";
					$(idarticulo).change();

				});

				/***************** Calculo de importe por articulo *********************/

				function buscarArticulo(id) {
					for(var i in articulos)
						if(articulos[i].id === id)
							return articulos[i];
					return null;
				}

				$(document).on("change", "#idarticulo", function() {

					var codigo = document.getElementById("codigo");
					var unidad = document.getElementById("unidad");
					var precio = document.getElementById("precio");
					var importe = document.getElementById("importe");

					if(this.value) {
						var articulo = buscarArticulo(this.value);
						var cantidad = document.getElementById("cantidad").value;
						var precio_seleccionado = articulo[document.getElementById("tipo_precio").value];
						codigo.value = articulo.codigo;
						unidad.value = articulo.unidad;
						precio.value = parseFloat(precio_seleccionado).toFixed(2);
						importe.value =	esFlotante(cantidad) && parseFloat(cantidad) > 0 ?
						(parseFloat(precio_seleccionado) * parseFloat(cantidad)).toFixed(2) : (0).toFixed(2);
					}
					else {
						codigo.value = unidad.value = precio.value = "";
						importe.value = (0).toFixed(2);
					}

				});

				/***************** Calculo de importe por cantidad *********************/

				$(document).on("blur", "#cantidad", function() {
		
					var cantidad = this.value;
					var precio = document.getElementById("precio").value;
					var idarticulo = document.getElementById("idarticulo").value;

					document.getElementById("importe").value = idarticulo ? esFlotante(cantidad) && parseFloat(cantidad) > 0 ?
					(parseFloat(precio) * parseFloat(cantidad)).toFixed(2) : (0).toFixed(2) : (0).toFixed(2);

				});

				/***************** Calculo de importe por cantidad *********************/

				$(document).on("change", "#tipo_precio", function() {
					
					var idarticulo = document.getElementById("idarticulo").value;
					var importe = document.getElementById("importe");

					if(idarticulo) {

						var articulo = buscarArticulo(idarticulo);
						var precio_seleccionado = articulo[this.value];
						var cantidad = document.getElementById("cantidad").value;
						var precio = document.getElementById("precio");

						precio.value = parseFloat(precio_seleccionado).toFixed(2);
						importe.value =	esFlotante(cantidad) && parseFloat(cantidad) > 0 ?
						(parseFloat(precio_seleccionado) * parseFloat(cantidad)).toFixed(2) : (0).toFixed(2);
					}
					else
						importe.value = (0).toFixed(2);

				});


				/******************************************************************************** Compra ***********************************************************************/
				
				/******************** Tipo de cambio *************************/

				$(".tipo_cambio").change(function() {

					var tipo_cambio = document.getElementById("tipo_cambio");

					switch(this.value) {

						case "MXN" :
							tipo_cambio.value = (1).toFixed(2);
							tipo_cambio.setAttribute("readonly", "readonly");
							break;

						case "USD" :
						case "EUR" :
							tipo_cambio.removeAttribute("readonly");
							break;
					}

				});

				/******************** Buscador de clientes *************************/

				var clientes = [];

				$('#buscador').on('keyup', function() {
	                var campos = $(':input', this).serializeArray();
	                $.ajax({
	                    url: "./herramientas/ajax/ajax_buscador.php",
	                    type: "post",
	                    data: { "busquedaClientes" : campos },
	                    dataType: "json",
	                    success: function(coincidencias) {
	                    	clientes = coincidencias;
	                    	var html = "";
	                    	for(var i in coincidencias)
	                    		html += "<li id='" + coincidencias[i].id + "'>" +
	                    			"<b>" + coincidencias[i].razon_social + "</b><br />" +
		                    		"<i class='fa fa-phone'></i><i> " + coincidencias[i].telefono + ", <i class='fa fa-envelope-o'></i> " + coincidencias[i].email + "</i><br />" +
		                    		"<b>" + coincidencias[i].rfc + "</b></li>";
	                    	$("#clientes").html(html);
	                    }
	                });
	            });

				/******************** Seleccion de cliente *************************/

				$("#clientes").click(function(e) {
					
					var cliente = null;
					var id = e.target.id;

					for(var i in clientes)
						if(clientes[i].id == id) {
							cliente = clientes[i];
							break;
						}
					
					if(cliente)
						$("#cliente td[id]").each(function() {
							this.innerHTML = cliente[this.id];
						});
					
				});
				
				/************************ Compras realizadas ************************/

				var compras = {
	                name : 'Compras',
	                fields : {
	                    fecha : 'Fecha',
	                    encargado : 'Encargado',
	                    observaciones : 'Observaciones',
	                },
	                extends:[
	                    {
	                        name : 'Articulos',
	                        text : '<i class="fa fa-dropbox"></i> Artículos',
	                        clase : 'button',
	                        assoc : 'articulos',
	                        details: function(articulos) {

	                        	return "<table class='articulos'>" +
				            		"<thead>" +
				            			"<tr>" +
					            			"<th>Artículo</th>" +
					            			"<th>Cantidad</th>" +
				            			"</tr>" +
				            		"</thead>" +
		            				"<tbody>" +
		            				
		            				(function() {
		                        		var html = "";
		                        		for(var i in articulos)
			                        		html += "<tr>" +
				                        		"<td>" + articulos[i].articulo + "</td>" + 
				                        		"<td>" + articulos[i].cantidad + "</td>" +
											"</tr>";
										return html;
		                        	})() +

									"</tbody>" +
								"</table>";
	                        }
	                    }
	                ],
	                data: <?php echo json_encode(Articulo::getRelacion()); ?>
	            };

	            JWeb('compras').grid(compras);

			});

		</script>
	</head>
	<body>
		<div id="wrapper">
			<?php require_once RAIZ . "/html/header.php"; ?>

			<div class="forma">

				<!-- Titulo -->

				<div class="tituloForma">
					<b>Compra</b>
				</div>
				
				<!-- Cuerpo -->
				<div class="cuerpoForma">

					<form id="formaCompra" class="formulario" method="post">

						<select id="idproveedor">
							<option value="">Filtrar sin proveedor</option>
						<?php
							$proveedores = Proveedor::buscar(array(
								"campos" => "id, razon_social",
								"condiciones" => array("activo", "=", 1),
								"orden" => array("razon_social", "ASC")));
							foreach($proveedores as $proveedor)
								echo "<option value='{$proveedor['id']}'>{$proveedor['razon_social']}</option>";
						?>
						</select>

						<div id="formaArticulo" class="formulario">

							<div class="JWebGrid">
								<table>
									<thead>
										<tr>
											<th class="header">Articulo</th>
											<th class="header">Cantidad</th>
											<th class="header">Tipo de precio</th>
											<th class="header">Código</th>
											<th class="header">Unidad</th>
											<th class="header">Precio</th>
											<th class="header">Importe</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td data-jwebgridtitle="Articulo">
												<select id="idarticulo" data-validador="obligatorio">
													<option value="">Seleccione el artículo</option>
													<?php
														$articulos = Articulo::buscar(array(
															"campos" => "id, nombre",
															"condiciones" => array("activo", "=", 1),
															"orden" => array("nombre", "ASC")));
														foreach($articulos as $articulo)
															echo "<option value='{$articulo['id']}'>{$articulo['nombre']}</option>";
													?>
												</select>
											</td>
											<td data-jwebgridtitle="Cantidad"><input id="cantidad" class="cantidad" data-validador="obligatorio decimal positivo-exclusivo"/></td>
											<td data-jwebgridtitle="Tipo de precio">
												<select id="tipo_precio">
													<option value="precio_publico" selected="selected">Público</option>
													<option value="precio_mayoreo">Mayoreo</option>
													<option value="precio_distribuidor">Distribuidor</option>
												</select>
											</td>
											<td data-jwebgridtitle="Código"><input id="codigo" readonly="readonly" /></td>
											<td data-jwebgridtitle="Unidad"><input id="unidad" readonly="readonly" /></td>
											<td data-jwebgridtitle="Precio"><input id="precio" readonly="readonly" /></td>
											<td data-jwebgridtitle="Importe"><input id="importe" readonly="readonly" value="0.00" /></td>
										</tr>
									</tbody>							
								</table>
							</div>

							<div class="botonera">
								<button type="button" id="nuevoArticulo" class="button"><i class="fa fa-eraser"></i>&nbsp;&nbsp;&nbsp;Limpiar formulario</button>
								<button type="button" id="guardarArticulo" class="button"><i class="fa fa-plus"></i>&nbsp;<i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;&nbsp;Añadir a carrito</button>
							</div>

							<div class="JWebGrid contenedor">
								<table>
									<thead>
										<tr>
											<th class="header">Articulo</th>
											<th class="header">Cantidad</th>
											<th class="header">Tipo de precio</th>
											<th class="header">Código</th>
											<th class="header">Unidad</th>
											<th class="header">Precio</th>
											<th class="header">Importe</th>
											<th class="header">Editar</th>
											<th class="header">Eliminar</th>
										</tr>
									</thead>
									<tbody id="contenedor_articulos"></tbody>
								</table>
							</div>
						</div>


	                    <div id="search_bar"><h1>Búsqueda de clientes</h1></div>
						<div id="buscador" class="JWebGrid">
							<table>
								<thead>
									<tr>
										<th class="header">Razón social</th>
										<th class="header">Email</th>
										<th class="header">RFC</th>
										<th class="header">Contacto</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td data-jwebgridtitle="Razón social"><input name="razon_social" type="text" autocomplete="off"/></td>
										<td data-jwebgridtitle="Email"><input name="email" type="text" autocomplete="off"/></td>
										<td data-jwebgridtitle="RFC"><input name="rfc" type="text" autocomplete="off"/></td>
										<td data-jwebgridtitle="Contacto"><input name="nombre_contacto" type="text" autocomplete="off"/></td>
									</tr>
								</tbody>
							</table>
	                    </div>
	                    <ul id="clientes" class="lista"></ul>


						<div id="cliente" class="JWebGrid">
							<table>
								<tbody>
									<tr>
										<td rowspan="12" style="text-align: center;"><img src="<?php echo PATH . '/herramientas/imagenes/user.png'; ?>" style="width: 100%; max-width: 110px;"/></td>
										<td><b>RFC</b></td>
										<td id="rfc"></td>
									</tr>
									<tr><td><b>Razón social</b></td><td id="razon_social"></td></tr>
									<tr><td><b>Calle</b></td><td id="calle"></td></tr>
									<tr><td><b>Número exterior</b></td><td id="numero_exterior"></td></tr>
									<tr><td><b>Número interior</b></td><td id="numero_interior"></td></tr>
									<tr><td><b>Código postal</b></td><td id="codigo_postal"></td></tr>
									<tr><td><b>Colonia</b></td><td id="colonia"></td></tr>
									<tr><td><b>Estado</b></td><td id="estado"></td></tr>
									<tr><td><b>Municipio</b></td><td id="municipio"></td></tr>
									<tr><td><b>Contacto</b></td><td id="nombre_contacto"></td></tr>
									<tr><td><b>Teléfono</b></td><td id="telefono"></td></tr>
									<tr><td><b>Email</b></td><td id="email"></td></tr>
								</tbody>
							</table>
						</div>
							
						<div id="panel">
						
							<div style="float: left;">
								<div style="float: left;"><input class="tipo_cambio" name="moneda" type="radio" value="MXN" checked="checked" /> MXN</div>
								<div style="float: left;"><input class="tipo_cambio" name="moneda" type="radio" value="USD" /> USD</div>
								<div style="float: left;"><input class="tipo_cambio" name="moneda" type="radio" value="EUR" /> EUR</div>
								<div style="float: left;"><input id="tipo_cambio" name="tipo_cambio" value="1.00" readonly="readonly" class="cantidad" /></div>
								<div class="clear"></div>
							</div>
							<div style="float: right; text-align: right;">

								Subtotal: <input id="subtotal" class="cantidad" value="0.00" readonly="readonly"/><br />
								IVA: <input id="iva" class="cantidad" value="0.00" readonly="readonly" /><br />
								Total: <input id="total" class="cantidad" value="0.00" readonly="readonly" /><br />
								Sucursal:
								<select name="sucursal">
									<option value="">Única</option>
								</select><br />
								Método de pago: 
								<select name="metodoDePago">
									<option value="">No establecido</option>
									<option value="Efectivo">Efectivo</option> 
									<option value="Cheque">Cheque</option> 
									<option value="Tarjeta crédito">Tarjeta crédito</option> 
									<option value="Tarjeta debito">Tarjeta debito</option> 
									<option value="Transferencia">Transferencia</option> 
									<option value="Deposito">Depósito</option> 
									<option value="Monedero electrónico">Monedero electrónico</option>
								</select>

							</div>
							<div class="clear"></div>
						</div>

						<div class="botonera">
							<button id="guardarCompra" class="button" type="submit" name="envioPedido"><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;&nbsp;Realizar compra</button>
						</div>

					</form>

					

					<div id="compras"></div>

				</div>
			</div>
		</div>
	</body>
</html>