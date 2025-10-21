//PARA SELECCIONAR LA SEMANA

$(function() 
{
    var startDate;
    var endDate;
    
    var selectCurrentWeek = function() 
	{
        window.setTimeout(function () 
		{
            $('#week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
        }, 2000);
    }
    
    $('#week-picker').datepicker( 
	{
        showOtherMonths: true,
        selectOtherMonths: true,
        onSelect: function(dateText, inst) 
		{ 
            var date 	= $(this).datepicker('getDate');
            startDate 	= new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
            endDate 	= new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            $('#week-picker').val($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            //$('#endDate').text($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
            //alert($.datepicker.formatDate( dateFormat, startDate, inst.settings))
			//window.location.href=base_url+"principal/tableroControl/"+$('#week-picker').val();
			definirFechaTablero($('#week-picker').val());
            selectCurrentWeek();
        },
        beforeShowDay: function(date) 
		{
            var cssClass = '';
            if(date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
        },
        onChangeMonthYear: function(year, month, inst) 
		{
            selectCurrentWeek();
        }
    });
	
	//$('#week-picker .ui-datepicker-calendar tr').live('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
    //$('#week-picker .ui-datepicker-calendar tr').live('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });
});
