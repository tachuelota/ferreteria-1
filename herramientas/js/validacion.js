function trim(s) { return s.replace(/^\s+/g,'').replace(/\s+$/g,''); }
function esNatural(s) { return /^\d+$/.test(s); }
function esFlotante(s) { return /^[-+]?[0-9]*\.?[0-9]+$/.test(s); }
function esEntero(s) { return /^-?\d+$/.test(s); }
function ucfirst(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

function validar(contenedor) {

    var campo;
    var errores;
    var correcto = true;

    var campos = $('[data-validador]').filter(function() {
        return $(this).parents("." + contenedor.className).get(0) === contenedor;
    });

    campos.each(function() {
        
        campo = this;
        errores = "";

        $.each(campo.dataset.validador.split(" "), function() {
            errores += validacion[this.toString()](campo);
            if(errores)
                return false;
        });

        if(errores) {
            correcto = false;
            $(campo).addClass("error").attr({title:errores, rel:"tooltip"});
        }
        else
            $(campo).removeClass("error").attr({title:"", rel:""});
        
    });
    
    return correcto;
}

function limpiar(contenedor, defaults) {

    $("[data-validador]", contenedor).off('focus', focus_tooltip);

    $(contenedor).find('input:text, input:password, input:file, input:hidden, select, textarea').not(':button, :submit, :reset').val('').removeClass("error");
    $(contenedor).find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected').removeClass("error");

}

var validacion = {

    "obligatorio" : function(elemento) {
        return trim(elemento.value) !== "" ? "" : "Debe especificar el campo.<br/>";
    },

    "decimal" : function(elemento) {
        return esFlotante(trim(elemento.value)) ? "" : "Debe ser decimal.<br />";
    },

    "positivo-exclusivo" : function(elemento) {
        return parseFloat(trim(elemento.value)) > 0 ? "" : "Debe ser mayor a 0.<br />";
    }
    
}