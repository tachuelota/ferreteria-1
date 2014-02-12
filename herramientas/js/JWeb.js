(function() {


    function JWeb(ref){
        if (!(this instanceof JWeb))
            return new JWeb(ref);
        this.ref = ref;
    }

    JWeb.tableToExcel = function () {
        var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body>{table}</body></html>'
        , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
        return function (tableList, name, filename) {
            if (!tableList.length>0 && !tableList[0].nodeType) table = document.getElementById(table)
            var tables = '';
            for(var i=0; i<tableList.length; i++){
                tables += '<table>';
                tables += tableList[i].innerHTML;
                tables += '</table>';
            }
            var ctx = {worksheet: name || 'Worksheet', table: tables};
            var link = document.createElement('a');
            link.href = uri + base64(format(template, ctx));
            link.download = filename;
            link.click();
        }
    }();

    JWeb.trim = function(myString){
        var str = myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
        return str; 
    }

    JWeb.caracter_normal = function(str){
        var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç";
        var to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuuNnCc";
        var mapping = {};
        for(var i = 0, j = from.length; i < j; i++ )
          mapping[ from.charAt( i ) ] = to.charAt( i );
        var ret = [];
        for( var i = 0, j = str.length; i < j; i++ ) {
          var c = str.charAt( i );
          if( mapping.hasOwnProperty( str.charAt( i ) ) )
              ret.push( mapping[ c ] );
          else
              ret.push( c );
        }
        return ret.join( '' );
    }

    JWeb.number_format = function(number, decimals, dec_point, thousands_sep){
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
          var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
              var k = Math.pow(10, prec);
              return '' + Math.round(n * k) / k;
            };
          s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
          if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
          }
          if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
          }
          return s.join(dec);
    }

    JWeb.ajax = function(options){

        var params = typeof(options.params) != 'undefined' ? options.params : '';
        var charset = typeof(options.charset) != 'undefined' ? options.charset : 'UTF-8';
        var metodo =  typeof(options.method) != 'undefined' ? options.method : 'GET';
        var tipo = typeof(options.dataType) != 'undefined' ? options.dataType.toLowerCase() : 'string';

        if(typeof(params) != "string"){
            var arregloQuery = new Array();
            for (var key in params){
                if(params[key] instanceof Array || params[key] instanceof Object)
                    for(var indice in params[key])
                        arregloQuery.push(key+'['+indice+']='+encodeURIComponent(params[key][indice]));    
                else
                    arregloQuery.push(key+'='+encodeURIComponent(params[key]));
            }
            params = arregloQuery.join('&');
        }

        if(typeof(options.beforeSend) != 'undefined' ){
            if(options.beforeSend() === false){
                return false;
            }
        }

        if(window.XMLHttpRequest){
          xmlhttp = new XMLHttpRequest();
        }
        else{
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                var datos = tipo == 'json' ? JSON.parse(xmlhttp.responseText) : xmlhttp.responseText;
                var succes = typeof(options.succes) != 'undefined' ? options.succes(datos) : '';
            }
        }

        if(metodo.toLowerCase() == 'get'){
            xmlhttp.open("GET",options.url+"?"+params,true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded; charset="+charset);
            xmlhttp.send();
        }
        else if(metodo.toLowerCase() == 'post'){
            xmlhttp.open("POST",options.url,true);
            xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded; charset="+charset);
            xmlhttp.send(params);
        }
    };

    JWeb.fn = JWeb.prototype = {
        init: function () {}
    };

    window.JWeb = JWeb;
})();

//Extensiones

JWeb.fn.ajaxForm = function(opciones){
    var form = document.getElementById(this.ref);
    form.onsubmit = function(){
        var elementos = this.elements;
        var datos_form = new Array();
        for(var i = 0; i < elementos.length; i++){
            var tag = (elementos[i].tagName).toLowerCase();
            var type = (elementos[i].type).toLowerCase();
            var name = JWeb.trim(elementos[i].name);
            if(typeof(name) != "undefined" && tag == 'input' && (type == 'radio' || type == 'checkbox')){
                if(elementos[i].checked)
                    datos_form.push(encodeURIComponent(name) + '=' + encodeURIComponent(elementos[i].value));
            }
            else if(typeof(name) != "undefined" && name != ''){
                alert(tag);
                var value = elementos[i].value;
                if(typeof(value) != "undefined"){
                    datos_form.push(encodeURIComponent(name) + '=' + encodeURIComponent(value));
                }
            }
        }
        var datos_form = datos_form.join('&');
        opciones.params = datos_form;
        opciones.url = this.action;
        JWeb.ajax(opciones);
        return false;
    };
};

JWeb.fn.grid = function(estructura){
    var elem = document.getElementById(this.ref);
    $(elem).addClass('JWebGrid');
    var nombre = this.ref;
    var datos = estructura.data;
    var size = Object.keys(estructura.data).length;
    var name = typeof(estructura.name) != 'undefined' ? '<h4 class="nameJWeb">'+estructura.name+'</h4>' : '';
    var nameS = (estructura.name).replace(' ','');
    var band_btn = typeof(estructura.extends) != 'undefined' ? true : false;
    var busqueda = [];
    var visible = [];
    var cambio = true;


    ////////////////// CREACION DE LA CONFIGURAICION ////////////////////

    var tabla = '<section class="config" style="display:none;">\
                    <section class="bg" style="width:100%; z-index:10000; top:0px; left:0px; position:fixed; height:100%; background:#222; opacity:0.7;">\
                    </section>\
                    <section class="sett" style="position:absolute; z-index:10002; width:280px; height:auto; padding:10px; left:50%; top:50%; margin-left:-150px; margin-top:-150px; border-radius:10px; background:#F0F0F0; font-size:12px;">\
                        <span style="font-weight:bold">Configuración</span> <span class="JWebGridClose" style="margin-top:-4px; cursor:pointer; font-size:20px; float:right;">&times;</span><hr/>\
                        <ul style="list-style:none;">\
                            <li style="padding:10px 5px; background:#CCC; border:1px solid #444; border-bottom:none;"><a href="#" class="config_btn" style="width:100%; display:block; color:#333; text-decoration:none; font-weight:bold; font-size:15px;">Visibles <span style="float:right">&#9660;</span></a></span>\
                                <div style="background:#fff; height:auto; padding:3px; width:98%; margin-top:5px; color:#444; text-align:right; font-weight:bold; display:none;">';
    
    for(var indice in estructura.fields){
        busqueda.push(estructura.fields[indice]);
        tabla += '<label>'+estructura.fields[indice]+'<input type="checkbox" style="display:inline-block; margin:0px 5px;" data-JWebGridColumn="'+estructura.fields[indice]+'" data-JWebGrid="'+nameS+'" data-JWebGridClase="visible" checked /></label><br/>';
    }

    tabla +=                   '</div>\
                            </li>\
                            <li style="padding:10px 5px; background:#CCC; border:1px solid #444;"><a href="#" class="config_btn" style="width:100%; display:block; color:#333; text-decoration:none; font-weight:bold; font-size:15px;">Busqueda <span style="float:right">&#9660;</span></a></span>\
                                <div style="background:#fff; height:auto; padding:3px; width:98%; margin-top:5px; color:#444; text-align:right; font-weight:bold; display:none;">';

    for(var indice in estructura.fields){
        tabla += '<label>'+estructura.fields[indice]+'<input type="checkbox" style="display:inline-block; margin:0px 5px;" data-JWebGridColumn="'+estructura.fields[indice]+'" data-JWebGrid="'+nameS+'" data-JWebGridClase="busqueda" checked /></label><br/>';
    }

    tabla += '</div>';

    tabla +=        '</li>\
                     <li style="padding:10px 5px; margin-top:10px; background:#CCC; text-align:center; border:1px solid #444;"><a href="#" class="excel" style="text-align:center; width:100%; display:block; color:#333; text-decoration:none; font-weight:bold; font-size:15px;">Exportar a excel</a></span></li>\
                        </ul>\
                    </section>\
                </section>';

    ////////////////// CREACION DEL HEADER  /////////////

    tabla += '<div class="JWebGridSearch">\
                '+name+'\
                <div class="JWebGridSearching" style="float:right; max-width:300px;">\
                    <a href="#" class="settings"><hr/><hr/><hr/></a>\
                    <input type="text" class="JWebGridSearchTxt" value="" />\
                </div>\
            </div>';
    
    ////////////////// CREACION DE LA TABLA ////////////////

    tabla += '<table id="'+nameS+'">';

    //////////////////////// HEAD /////////////////////////

    tabla += '<thead><tr>';

    for(var indice in estructura.fields)
        tabla += '<th data-JWebGridTitle="'+estructura.fields[indice]+'">'+estructura.fields[indice]+'</th>';

    if(band_btn)
        tabla += '<th class="JWebGridButtons">&nbsp;</th>';

    tabla += '</tr></thead>';

    //////////////////////// BODY ////////////////////////

    tabla += '<tbody>';
    for(var i in datos){
        tabla += '<tr class="visible">';

        for(var j in estructura.fields){
            var html = $.trim(datos[i][j]) == '' ? '&nbsp;' : datos[i][j];
            tabla += '<td data-JWebGridTitle="'+estructura.fields[j]+'">'+html+'</td>';
        }

        if(band_btn){
            tabla += '<td class="JWebGridButtons">';
            for(var indice in estructura.extends){
                if(typeof(estructura.extends[indice].click) != 'undefined' || typeof(estructura.extends[indice].details) != 'undefined'){
                    var clase = typeof(estructura.extends[indice].class) != 'undefined' ? 'class="'+estructura.extends[indice].class+'"' : ''; 
                    var text = typeof(estructura.extends[indice].text) != 'undefined' ? estructura.extends[indice].text : estructura.extends[indice].name;
                    var data_boton = (estructura.extends[indice].name).replace(' ','');
                    tabla += '<a data-JWebGridBtn="'+data_boton+'" data-JWebGridAssoc="'+estructura.extends[indice].assoc+'" data-JWebGridAssocInd="'+indice+'" href="#" '+clase+'>'+text+'</a>';
                }
            }  
            tabla += '</td>';
        }
        tabla += '</tr>';
    }
    tabla += '</tbody></table>';

    tabla+= '<div class="pagination">\
                <a style="margin-left:10px;" href="#" class="firstJWeb flecha">&laquo;</a><a href="#" class="antJWeb flecha" style="margin-right:0px;">&lsaquo;</a>\
                <ul class="nums" style="display:inline; list-style:none; margin:0px; padding:0px;">\
                </ul>\
                <a href="#" class="sigJWeb flecha">&rsaquo;</a><a href="#" class="lastJWeb flecha">&raquo;</a>\
                <select class="mostrarJWeb">\
                    <option value="10">10</option>\
                    <option value="20">20</option>\
                    <option value="30">30</option>\
                    <option value="50">50</option>\
                    <option value="100">100</option>\
                    <option value="200">200</option>\
                    <option value="t">Todos</option>\
                </select>\
                <input type="text" class="JWebGridStart JWebGridNum" value=""/>\
                <input type="text" class="JWebGridEnd JWebGridNum" value=""/>\
            </div>';
    elem.innerHTML = tabla;


    ////////////////// FUNCION DE PAGINATION ////////////////////////

    function numerosJWeb(num){
        $('#'+nombre+' .nums').html('');
        var trs = $('#'+nameS+' tbody tr.visible');
        var start = parseInt($('#'+nombre+' .JWebGridStart').val());
        var end = parseInt($('#'+nombre+' .JWebGridEnd').val());
        var inicio = start > 0 && !isNaN(start) ? (start - 1) : 0;
        var fin = end > 0 && !isNaN(end) ? (end - 1) : $(trs).length;
        trs = $(trs).slice(inicio,fin); 
        var mostrar = parseInt($('#'+nombre+' .mostrarJWeb').val());
        mostrar = isNaN(mostrar) ? $(trs).length : mostrar;
        var registros = $(trs).length > 0 ? $(trs).length : 0;
        registros = end != '' && end > 0 ? end : registros;
        var paginas = Math.ceil(registros/mostrar);
        var desde = (num - 2) <= 0 ? 1 : (num-2);
        var hasta = paginas > (desde+5) ? (desde+5) : paginas;
        var visibles = (num-1) * mostrar;
        visibles = parseInt(mostrar) > parseInt(registros) ? 0 : visibles; 
        var limit = parseInt(visibles) + parseInt(mostrar);
        limit = parseInt(mostrar) > parseInt(registros) ? registros : limit;
        for(desde; desde<=hasta; desde++){
            var li = document.createElement('li');
            li.setAttribute('class','page');
            li.innerHTML = desde;
            li.onclick = function(){
                numerosJWeb(this.innerHTML);
            };
            $('#'+nombre+' .nums').append(li);
        }
        $('#'+nameS+' tbody tr').attr("style","display:none !important");
        $(trs).slice(visibles, limit).show();
        $('#'+nombre+' .nums .page:contains('+num+')').addClass('seleccionado');
        $('#'+nameS+" tbody tr.oddJWeb").removeClass("oddJWeb");
        $('#'+nameS+" tbody tr.visible:visible:odd").addClass("oddJWeb");
    }


    ////////////////////////// EVENTOS ////////////////////////
    $(document).off('click','#'+nombre+' li .config_btn');
    $(document).on('click','#'+nombre+' li .config_btn',function(e){
        e.preventDefault();
        if($(this).siblings('div').length > 0){
            $(this).siblings('div').slideToggle('slow');
        }
    });

    $(document).off('click','#'+nombre+' input[type="checkbox"]');
    $(document).on('click','#'+nombre+' input[type="checkbox"]',function(){
        var tabla = $(this).attr('data-JWebGrid');
        var header = $(this).attr('data-JWebGridColumn');
        var clase = $(this).attr('data-JWebGridClase');
        if(clase == 'visible'){
            if($(this).is(':checked')){
                $('#'+tabla+' [data-JWebGridTitle="'+header+'"]').show();
                var index = visible.indexOf(header);
                visible.splice(index,1);
            }
            else{
                $('#'+tabla+' [data-JWebGridTitle="'+header+'"]').hide();
                visible.push(header);
            }
        }
        else{
            if($(this).is(':checked')){
                busqueda.push(header);
            }
            else{
                var index = busqueda.indexOf(header);
                busqueda.splice(index,1);
            }
            $('#'+nombre+' .JWebGridSearchTxt').trigger('keyup');
        }
    });

    $(document).off('click','#'+nombre+' .settings');
    $(document).on('click','#'+nombre+' .settings', function(e){
        e.preventDefault();
        $('#'+nombre+' .config').fadeIn('slow');
        $("html, body").animate({ scrollTop: parseInt($('#'+nombre+' .config .sett').offset().top) - 50 });
    });

    $(document).off('click','#'+nombre+' .JWebGridClose');
    $(document).on('click','#'+nombre+' .JWebGridClose',function(){
        $(this).parents('.config').fadeOut('slow');
        $("html, body").animate({ scrollTop: parseInt($('#'+nombre).offset().top) - 50 });

    });

    $(document).off('click','#'+nombre+' .bg');
    $(document).on('click','#'+nombre+' .bg',function(){
        $(this).parents('.config').fadeOut('slow');
        $("html, body").animate({ scrollTop: parseInt($('#'+nombre).offset().top) - 50 });
    });

    $(document).off('keyup','#'+nombre+' .JWebGridSearchTxt');
    $(document).on('keyup','#'+nombre+' .JWebGridSearchTxt',function(){
        var valor = JWeb.caracter_normal($(this).val()).toLowerCase();
        $('#'+nameS+' tbody tr').removeClass('visible');
        for(var indice in busqueda){
            $('#'+nameS+" tbody td[data-JWebGridTitle='"+busqueda[indice]+"']").filter(function() {
                var dato = JWeb.caracter_normal($.text([this])).toLowerCase();
                if(dato.indexOf(valor) !== -1){
                   $(this).parent().addClass('visible');
                }
            });
        }
        numerosJWeb(1);
    });

    $(document).off('change','#'+nombre+' .mostrarJWeb');
    $(document).on('change','#'+nombre+' .mostrarJWeb', function(){
        numerosJWeb(1);
    });

    $(document).off('click','.sigJWeb');
    $(document).on('click','.sigJWeb',function(e){
        e.preventDefault();
        var mostrar = parseInt($('#'+nombre+' .mostrarJWeb').val());
        mostrar = isNaN(mostrar) ? $('#'+nombre+' .visible').length : mostrar;
        var registros = $('#'+nombre+' .visible').length > 0 ? $('#'+nombre+' .visible').length : 0;
        var paginas = Math.ceil(registros/mostrar);
        var num = $('.seleccionado',$('#'+nombre+' .seleccionado').parents('.pagination').parent()).html();
        num++;
        num = num > paginas ? paginas : num;
        numerosJWeb(num);
    });

    $(document).off('click','.antJWeb');
    $(document).on('click','.antJWeb',function(e){
        e.preventDefault();
        var mostrar = parseInt($('#'+nombre+' .mostrarJWeb').val());
        mostrar = isNaN(mostrar) ? $('#'+nombre+' .visible').length : mostrar;
        var registros = $('#'+nombre+' .visible').length > 0 ? $('#'+nombre+' .visible').length : 0;
        var paginas = Math.ceil(registros/mostrar);
        var num = $('.seleccionado',$('#'+nombre+' .seleccionado').parents('.pagination').parent()).html();
        num--;
        num = num < 1 ? 1 : num;
        numerosJWeb(num);
    });

    $(document).off('click','.firstJWeb');
    $(document).on('click','.firstJWeb',function(e){
        e.preventDefault();
        numerosJWeb(1);
    });

    $(document).off('click','.JWebGrid .excel');
    $(document).on('click','.JWebGrid .excel',function(e){
        e.preventDefault();
        var f = new Date();
        var archivo = 'reporte'+ f.getDate() + '-' + f.getMonth() + '-' + f.getFullYear() + '.xls';
        var table = $(this).parents('.JWebGrid').find('table').clone();
        $('.JWebGridButtons', table).remove();
        for(var indice in visible){
            $('[data-JWebGridTitle="'+visible[indice]+'"]',table).remove();
        }
        JWeb.tableToExcel(table, 'name', archivo);
    });

    $(document).off('click','.lastJWeb');
    $(document).on('click','.lastJWeb',function(e){
        e.preventDefault();
        var mostrar = parseInt($('#'+nombre+' .mostrarJWeb').val());
        mostrar = isNaN(mostrar) ? $('#'+nombre+' .visible').length : mostrar;
        var registros = $('#'+nombre+' .visible').length > 0 ? $('#'+nombre+' .visible').length : 0;
        var paginas = Math.ceil(registros/mostrar);
        numerosJWeb(paginas);
    });

    $(document).off('keyup','.JWebGridNum');
    $(document).on('keyup','.JWebGridNum',function(e){
        e.preventDefault();
        var regex = /^\d+$/;
        var valor = $(this).val();
        var padre = $(this).parent();
        if(!valor.match(regex) && valor != ''){
            var texto = valor.substring(0,valor.length-1);
            $(this).val(texto);
        }
        numerosJWeb(1);
    });

    for(var indice in estructura.extends){
        var data_boton = (estructura.extends[indice].name).replace(' ','');
        $(document).off('click','#'+nombre+' [data-JWebGridBtn="'+data_boton+'"]');

        if(typeof(estructura.extends[indice].click) != 'undefined'){
            $(document).on('click','#'+nombre+' [data-JWebGridBtn="'+data_boton+'"]',function(e){
                e.preventDefault();
                var tr = $(this).parents('.visible');
                var index = $(elem).find("table tbody tr:not('.details')").index(tr);
                var assoc = $(this).attr('data-JWebGridAssoc');
                var i = $(this).attr('data-JWebGridAssocInd');
                var data = datos[index][assoc];
                estructura.extends[i].click(data);
            });
        }
        else if(typeof(estructura.extends[indice].details) != 'undefined'){
            $(document).on('click','#'+nombre+' [data-JWebGridBtn="'+data_boton+'"]', function(e){
                e.preventDefault();
                var tr = $(this).parents('.visible');
                var clase = $(this).attr('data-JWebGridBtn');
                var index = $(elem).find("table tbody tr:not('.details')").index(tr);
                var next = $(tr).next();
                if($(tr).siblings('[data-JWebGridDetails="'+clase+''+index+'"]').length <= 0){
                    var assoc = $(this).attr('data-JWebGridAssoc');
                    var data = estructura.extends[$(this).index()].details(datos[index][assoc]);
                    var colspan = $('th').length;
                    $(tr).after('<tr class="details" data-JWebGridDetails="'+clase+''+index+'"><td colspan="'+colspan+'">'+data+'</td></tr>').hide().fadeIn(500)
                }
                else
                    $(next).remove();
            });
        }
    }

    ///////////////////////////////////////////////////////////////////////
    numerosJWeb(1);

    if(typeof(estructura.success) != 'undefined'){
        estructura.success($('#'+nombre+' table'));
    }
};

