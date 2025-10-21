<script>
$(document).ready(function()
{
	$("#txtBuscarProductoLista").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerProductosLista();
		}, 500);
	});
	
	$('#txtFechaInicialRegistro,#txtFechaFinalRegistro').datepicker();
});
</script>

<form id="frmListas">
    <div class="ui-state-error" ></div>
    <div id="registrandoLista"></div>
    <table class="admintable" width="100%">
    	 <tr>
            <td class="key">Nombre:</td>
            <td>
            	<input type="text" class="cajas"  id="txtNombreLista" name="txtNombreLista" style="width:300px" value="<?php echo $lista->nombre?>"  />
            </td>
        </tr>
        
    	<tr>
            <td class="key">Fecha inicial:</td>
            <td>
            	<input type="text" class="cajas"  id="txtFechaInicialRegistro" name="txtFechaInicialRegistro" style="width:100px"  value="<?php echo $lista->fechaInicial?>" />
                &nbsp;&nbsp;
                <input type="checkbox" id="chkVigencia" name="chkVigencia" value="1"  onchange="configurarFechaFinal()" <?php echo $lista->vigencia=='1'?'checked="checked"':''?>  />
                <label>Vigencia</label>
            </td>
        </tr>
        
        <tr <?php echo $lista->vigencia=='0'?'style="display:none"':''?> id="filaFechaFinal">
            <td class="key">Fecha final:</td>
            <td>
            	<input type="text" class="cajas"  id="txtFechaFinalRegistro" name="txtFechaFinalRegistro" style="width:100px"  value="<?php echo $lista->fechaFinal?>" />
            </td>
        </tr>

        <tr>
            <td align="center" colspan="2">
                <input type="text" class="cajas"  id="txtBuscarProductoLista" style="width:500px"  placeholder="Buscar producto" />
                <input type="hidden" id="txtNumeroProductosLista" name="txtNumeroProductosLista" value="<?php echo count($productos)?>" />
                <input type="hidden" id="txtIdLista" name="txtIdLista" value="<?php echo $lista->idLista?>" />
                &nbsp;&nbsp;
            </td>
        </tr>
    </table>
    <div id="obtenerProductosLista" style="overflow:scroll; height:260px; overflow-x: hidden; overflow-x: auto"></div>
    
    <table class="admintable" id="tablaLista" width="100%">
        <tr>
            <th class="encabezadoPrincipal" width="3%">-</th>
            <th class="encabezadoPrincipal" width="17%">Código interno</th>
            <th class="encabezadoPrincipal" width="25%">Producto</th>
            <th class="encabezadoPrincipal" width="25%">Línea</th>
            <th class="encabezadoPrincipal" width="15%">Precio</th>
            <th class="encabezadoPrincipal" width="15%">Precio nuevo</th>
        </tr>
        
        <?php
		$i=0;
        foreach($productos as $row)
		{
			echo '
			<tr id="filaLista'.$i.'">
				<td><img src="'.base_url().'img/borrar.png" width="18" onclick="quitarProductoLista('.$i.')" /></td>
				<td>'.$row->codigoInterno.'</td>
				<td>'.$row->producto.'</td>
				<td>'.$row->linea.'</td>
				<td align="right">$'.number_format($row->precioPasado,decimales).'</td>
				<td align="center"><input type="text" class="cajas" style="text-align: right; width:80px" id="txtPrecioNuevo'.$i.'" name="txtPrecioNuevo'.$i.'" value="'.round($row->precioNuevo,decimales).'" /> </td>
				<input type="hidden" id="txtPrecioProducto'.$i.'" name="txtPrecioProducto'.$i.'" value="'.$row->precioPasado.'" />
				<input type="hidden" id="txtIdProducto'.$i.'" name="txtIdProducto'.$i.'" value="'.$row->idProducto.'" />
			</tr>';
			
			$i++;
		}
		?>
        
    </table>
</form>