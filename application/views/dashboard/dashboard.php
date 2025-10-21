<script src="<?php echo base_url()?>js/dashboard/dashboard.js"></script> 
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/bundle.js"></script>
<script src="<?php echo base_url()?>js/bibliotecas/chartjs/utils.js"></script> 


<script>
$(document).ready(function()
{
	obtenerDashboard();
	//obtenerGraficaClientes()
	
	$("#txtInicio,#txtFin").monthpicker(
	{
		dateFormat: 'yy-mm',		
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'],
	});
});
</script>

<?php
#$fechaInicioSemana  = date('Y-m-d', strtotime(date('Y') . 'W' . str_pad(date('W') , 2, '0', STR_PAD_LEFT)));

#echo 'Fecha inicio: '.$fechaInicioSemana;
?>

<div class="derecha">
<div class="submenu" >
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
    
    <table style="width:100%">
    	<tr>
        	<td align="center">
            	De
                <input type="text" class="cajas" id="txtInicio" 	name="txtInicio" value="<?php echo date('Y-01')?>" style="width:100px" readonly="readonly" onchange="obtenerDashboard()" />
                a
                <input type="text" class="cajas" id="txtFin" 	name="txtFin" value="<?php echo date('Y-m')?>" style="width:100px" readonly="readonly" onchange="obtenerDashboard()" />
                
                <select class="cajas" id="selectCuentaIngresos" style="width:auto" onchange="obtenerDashboard()">
                    <option value="0">Seleccione cuenta</option>
                	<?php
                    foreach($cuentasBanco as $row)
                    {
                        echo '<option '.($row->dashboard=='1'?'selected="selected"':'').' value="'.$row->idCuenta.'">'.(strlen($row->cuenta)>0?$row->cuenta:$row->tarjetaCredito).', '.$row->nombre.'</option>';
                    }
                	?>
                </select>
            </td>
        </tr>
    </table>
    

</div>


<div class="listproyectos" align="center">

<div id="obtenerDashboard"></div>



</div>
</div>