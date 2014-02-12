	/*

			
			function calcularTotal() {

				var subtotal = 0;
				for(var i in elementos["articulo"])
					subtotal += parseFloat(elementos["articulo"][i]["importe"]);

				document.getElementById("subtotal").value = subtotal.toFixed(2);
				document.getElementById("iva").value = (subtotal * .16).toFixed(2);
				document.getElementById("total").value = (subtotal * 1.16).toFixed(2);

			}

			var articulos = <?php
				
				$articulos = Articulo::buscar(array(
					"campos" => "id, nombre, unidad, codigo",
					"condiciones" => array("articulo.activo", "=", 1)));

				echo json_encode(Articulo::getRelacion($articulos));

			?>;

			function buscarArticulo(id) {
				for(var i in articulos)
					if(articulos[i].id === id)
						return articulos[i];
				return null;
			}

			$(document).on("blur", "#cantidad", function() {
		
				var cantidad = this.value;
				var precio = document.getElementById("precio").value;
				var idarticulo = document.getElementById("idarticulo").value;

				document.getElementById("importe").value = idarticulo ? esFlotante(cantidad) && parseFloat(cantidad) > 0 ?
				(parseFloat(precio) * parseFloat(cantidad)).toFixed(2) : (0).toFixed(2) : (0).toFixed(2);

			});

			$(document).on("change", "#idarticulo", function() {

				var codigo = document.getElementById("codigo");
				var unidad = document.getElementById("unidad");
				var precio = document.getElementById("precio");
				var importe = document.getElementById("importe");

				if(this.value) {
					var articulo = buscarArticulo(this.value);
					var cantidad = document.getElementById("cantidad").value;
					codigo.value = articulo.codigo;
					unidad.value = articulo.unidad;
					precio.value = parseFloat(articulo.precios[0]["precio"]).toFixed(2);
					
					importe.value =	esFlotante(cantidad) && parseFloat(cantidad) > 0 ? 
					(parseFloat(articulo.precios[0]["precio"]) * parseFloat(cantidad)).toFixed(2) : (0).toFixed(2);
				}
				else {
					codigo.value = unidad.value = precio.value = "";
					importe.value = (0).toFixed(2);
				}

			});

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
                    	var listado = "";
                    	for(var i in coincidencias)
                    		listado += "<li id='" + coincidencias[i].id + "'>" + coincidencias[i].razon_social + "</li>";
                    	$("#clientes").html(listado);
                    }
                });
            });

			$("#clientes").click(function(e) {
				
				var cliente = null;
				var id = e.target.id;


				for(var i in clientes)
					if(clientes[i].id == id) {
						cliente = clientes[i];
						break;
					}
				
				if(cliente)
					$("#cliente :input").each(function() {
						this.value = cliente[this.id];
					});
				

			});

			$("#formaPedido").submit(function() {
				var correcto = validar(this, "pedido");
            	tooltip(this, "pedido");
            	return correcto;
			});
			*/


			<div class="header">
							<span class="idarticulo">Artículo</span><span class="cantidad">Cantidad</span><span class="codigo">Código</span><span class="unidad">Unidad</span><span class="precio">Precio</span><span class="importe">Importe</span>
						</div>




						<div id="panel">
							<div style="float: left;">
								<div style="float: left;"><input class="tipo_cambio" name="moneda" type="radio" value="MXN" checked="checked" /> MXN</div>
								<div style="float: left;"><input class="tipo_cambio" name="moneda" type="radio" value="USD" /> USD</div>
								<div style="float: left;"><input class="tipo_cambio" name="moneda" type="radio" value="EUR" /> EUR</div>
								<div style="float: left;"><input id="tipo_cambio" name="tipo_cambio" value="1.00" readonly="readonly" class="cantidad" /></div>
								<div class="clear"></div>
							</div>
							<div style="float: right;">
								<div style="float: right;">Total: <input id="total" class="cantidad" value="0.00" readonly="readonly" /></div>
								<div style="float: right;">IVA: <input id="iva" class="cantidad" value="0.00" readonly="readonly" /></div>
								<div style="float: right;">Subtotal: <input id="subtotal" class="cantidad" value="0.00" readonly="readonly"/></div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
							
							<span class="campo">Sucursal</span>
							<select name="sucursal">
								<option value="">Única</option>
							</select>
							<br />

							<span class="campo">Método de pago</span>
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

						<!-- Botones -->

						<button class="button derecha" type="submit" name="envioPedido">Crear</button>
						<div class="clear"></div>
					</form>

                        <div id="buscador">

                            <span>Razón social: </span>
                            <input name="razon_social" type="text" autocomplete="off"/>

                            <span>Email: </span>
                            <input name="email" type="text" autocomplete="off"/>

                            <span>RFC: </span>
                            <input name="rfc" type="text" autocomplete="off"/>

                            <span>Nombre de contacto: </span>
                            <input name="nombre_contacto" type="text" autocomplete="off"/>

                        </div>

                        <ul id="clientes" class="lista"></ul>
						<div id="cliente">
							<span class="campo">RFC:</span>
							<input readonly="readonly" id="rfc" />
							<br />
							<span class="campo">Razón social:</span>
							<input readonly="readonly" id="razon_social" />
							<br />
							<span class="campo">Calle:</span>
							<input readonly="readonly" id="calle" />
							<br />
							<span class="campo">Número exterior:</span>
							<input readonly="readonly" id="numero_exterior" />
							<br />
							<span class="campo">Número interior:</span>
							<input readonly="readonly" id="numero_interior" />
							<br />
							<span class="campo">Código postal:</span>
							<input readonly="readonly" id="codigo_postal" />
							<br />
							<span class="campo">Colonia:</span>
							<input readonly="readonly" id="colonia" />
							<br />
							<span class="campo">Estado:</span>
							<input readonly="readonly" id="estado" />
							<br />
							<span class="campo">Municipio:</span>
							<input readonly="readonly" id="municipio" />
							<br />
							<span class="campo">Nombre de contacto:</span>
							<input readonly="readonly" id="nombre_contacto" />
							<br />
							<span class="campo">Teléfono:</span>
							<input readonly="readonly" id="telefono" />
							<br />
							<span class="campo">Email:</span>
							<input readonly="readonly" id="email" />
							<br />
						</div>

						<div id="pedidos"></div>