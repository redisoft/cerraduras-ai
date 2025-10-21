//MATRICULA
//>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$(document).ready(function()
{
	$('#txtInicioSieBusqueda').daterangepicker(
	{
		singleDatePicker: true,
		locale: 
		{
		  format: 'YYYY-MM-DD'
		}
	});
	
	/*var startDate;
    var endDate;

    var selectCurrentWeek = function() 
	{
        window.setTimeout(function () 
		{
            $('#txtInicioSieBusqueda').find('.ui-datepicker-current-day a').addClass('ui-state-active')
        }, 1);
    }

    $('#txtInicioSieBusqueda,#txtFinSieBusqueda').datepicker( 
	{
        showOtherMonths: true,
        selectOtherMonths: true,
        onSelect: function(dateText, inst)
		 { 
            var date = $(this).datepicker('getDate');
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            
			$('#txtInicioSieBusqueda').val($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            $('#txtFinSieBusqueda').val($.datepicker.formatDate( dateFormat, endDate, inst.settings ));

            selectCurrentWeek();
			obtenerProspectos()
        },
        beforeShowDay: function(date) 
		{
            var cssClass = '';
            if(date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
        },
        onChangeMonthYear: function(year, month, inst) {
            selectCurrentWeek();
        }
	});*/

	obtenerProspectos();
});

function obtenerProspectos()
{
	$.ajax(
	{
		async:true,
		beforeSend:function(objeto)
		{
			$('#obtenerProspectos').html('<img src="'+base_url+'img/ajax-loader.gif"/> Obteniendo registros');
		},
		type:"POST",
		url:base_url+'prospectos/obtenerProspectos',
		data:
		{
			inicio: 	$('#txtInicioSieBusqueda').val(),
			fin:	 	$('#txtFinSieBusqueda').val(),
		},
		datatype:"html",
		success:function(data, textStatus)
		{
			$("#obtenerProspectos").html(data);
		},
		error:function(datos)
		{
			notify('Error al obtener los registros',500,5000,'error',30,3);
			$("#obtenerProspectos").html('');
		}
	});		
}
