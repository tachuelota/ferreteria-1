function Entidad(entidad) {

	var elemento = this.elemento = null;
	var elementos = this.elementos = [];
	var defaults = this.defaults = entidad.defaults || {};
	var forma = this.forma = entidad.forma;
	var carrito = this.carrito = entidad.carrito || null;
	var botonGuardar = this.botonGuardar = entidad.botonGuardar || null;
	var botonNuevo = this.botonNuevo = entidad.botonNuevo || null;
	var objeto = this.objeto = entidad.objeto || {};
	var hijos = this.hijos = entidad.hijos || [];
	var success = this.success = entidad.success || (function() {});

	var inicializar = this.inicializar = function() {

		for(var campo in defaults)
	        document.getElementById(campo).value = defaults[campo];

		if(hijos.length)
			for(var i in hijos)
				hijos[i].inicializar();

	}

	var guardar = this.guardar = carrito ?
	
	function() {

		if(elemento) {

			$(":input:not(:button)", forma).each(function() {
				elemento[this.id] = this.value;
			});

			$(".elemento:eq("+ elementos.indexOf(elemento) +")", carrito).get(0).outerHTML = elemento;
			
		}
		else {

			elemento = new objeto();
			elemento["id"] = 0;

			$(":input:not(:button)", forma).each(function() {
				elemento[this.id] = this.value;
			});

			elementos.push(elemento);
			carrito.innerHTML += elemento;
			
		}

		elemento = null;
		limpiar(forma);
		inicializar();
		success();

	} :

	function() {

		forma.submit();

	};

	$(botonGuardar).click(function() {

		if(validar(forma))
			guardar();
		else
			tooltip(forma);

    });

    $(botonNuevo).click(function() {

		elemento = null;
		limpiar(forma);
		inicializar();

	});
		
	if(carrito) {

		$(document).on("click", ".editar", function(e) {

			if($(this).parents("." + forma.className).get(0) === forma) {

    			elemento = elementos[$(".editar", carrito).index(this)];

    			limpiar(forma);
    			inicializar();

    			$(":input:not(:button)", forma)

	    			.filter(function() {
        				return $(this).parents("." + forma.className).get(0) === forma;
    				})

	    			.each(function() {
	    				this.value = elemento[this.id];
	    			});
			}

		});

		$(document).on("click", ".eliminar", function(e) {
			
			if($(this).parents("." + forma.className).get(0) === forma) {

				elementos.splice($(".eliminar", carrito).index(this), 1);

			    $(this).parents(".elemento").first().hide('slow', function() {
			    	this.remove();
			    });
			}

		});
	}
}