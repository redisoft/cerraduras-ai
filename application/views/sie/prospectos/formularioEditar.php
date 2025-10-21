<script>
$(document).ready(function()
{
	//$('#txtFechaInicialSie,#txtFechaFinalSie').datepicker({});
	
	
	
	var startDate;
    var endDate;

    var selectCurrentWeek = function() 
	{
        window.setTimeout(function () 
		{
            $('#txtFechaInicialSie').find('.ui-datepicker-current-day a').addClass('ui-state-active')
        }, 1);
    }

    $('#txtFechaInicialSie,#txtFechaFinalSie').datepicker( 
	{
        showOtherMonths: true,
        selectOtherMonths: true,
        onSelect: function(dateText, inst)
		 { 
            var date = $(this).datepicker('getDate');
            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 2);
            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 8);
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            
			$('#txtFechaInicialSie').val($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            $('#txtFechaFinalSie').val($.datepicker.formatDate( dateFormat, endDate, inst.settings ));

            selectCurrentWeek();
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
    });
	
	
	//$('.week-picker .ui-datepicker-calendar tr').live('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
    //$('.week-picker .ui-datepicker-calendar tr').live('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });
	
});
</script>

<div id="registrandoProspectoSie"></div>

<form id="frmRegistroProspectoSie">
	<input type="hidden"  id="txtIdMeta" name="txtIdMeta" value="<?=$registro->idMeta?>" />
    <table class="admintable" width="100%">
    	<tr>
            <td class="key">Tipo:</td>
            <td>
               <select id="selectTiposSie" name="selectTiposSie" class="cajas" style="width:150px">
                   
                   	<?php
                    foreach($tipos as $row)
					{
						echo '<option '.($row->idTipo==$registro->idTipo?'selected="selected"':'').' value="'.$row->idTipo.'">'.$row->nombre.'</option>';
					}
					?>
                </select>
            </td>
        </tr>
        
        <tr>
            <td class="key">Grado:</td>
            <td>
               <select id="selectGradosSie" name="selectGradosSie" class="cajas" style="width:150px">
                   
                   	<?php
                    foreach($grados as $row)
					{
						echo '<option '.($row->idGrado==$registro->idGrado?'selected="selected"':'').'  value="'.$row->idGrado.'">'.$row->nombre.'</option>';
					}
					?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="key">Semana:</td>
            <td>
               De <input type="text" 	class="cajas" 	id="txtFechaInicialSie" 	name="txtFechaInicialSie" 	style="width:100px" value="<?=$registro->fechaInicial?>"/>
               a <input type="text" 	class="cajas" 	id="txtFechaFinalSie"	 	name="txtFechaFinalSie" 	style="width:100px" value="<?=$registro->fechaFinal?>"/>
            </td>
        </tr>
        
         <tr>
            <td class="key">Meta:</td>
            <td>
                <input type="text" class="cajas" id="txtMetaSie" name="txtMetaSie" style="width:100px" onkeypress="return soloNumerico(event)" maxlength="5" value="<?=$registro->meta?>" />
            </td>
        </tr>
    </table>
</form>