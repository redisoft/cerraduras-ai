<script>
$(document).ready(function()
{
	$("#txtBuscarProductoTraspaso").keyup(function() 
	{
		clearTimeout(tiempoRetraso);
		tiempoRetraso 	= setTimeout(function() 
		{
			obtenerProductosTraspaso();
		}, 700);
	});
	
	$('#txtFechaTraspaso').timepicker();
});
</script>

<form id="frmTraspasos">
    <div class="ui-state-error" ></div>
    <div id="registrandoTraspaso"></div>
    <table class="admintable" width="100%">
    	
    	<tr>
        
        	<td class="key">Folio:</td>
            <td><?php echo $folio?></td>
        	
        	<td class="key">Destino:</td>
            <td>
            	<select class="cajas" id="selectLicenciaDestino" name="selectLicenciaDestino" style="width:300px" >
                    <?php
                    foreach($licencias as $row)
                    {
                        echo '<option value="'.$row->idLicencia.'">'.$row->nombre.'</option>';
                    }
                    ?>	
                </select>
            </td>
        </tr>
        <tr>
            
            <td class="key">Fecha:</td>
            <td>
            	<input type="text" class="cajas"  id="txtFechaTraspaso" name="txtFechaTraspaso" style="width:120px"  value="<?php echo date('Y-m-d H:i')?>"/>
            </td>
            
            <td class="key">Comentarios:</td>
            <td>
            	<textarea class="TextArea" id="txtComentarios" name="txtComentarios" style="width:250px; height:50px"></textarea>
            </td>
        </tr>
        <tr>
            <td align="left" colspan="4">
                <input type="text" class="cajas"  id="txtBuscarProductoTraspaso" style="width:500px"  placeholder="Buscar producto" />
                
                &nbsp;&nbsp;
                
            </td>
        </tr>
    </table>
    <div id="obtenerProductosTraspaso" style="overflow:scroll; height:260px; overflow-x: hidden"></div>
    
    <table class="admintable" id="tablaTraspasos" width="100%">
        <tr>
            <th class="encabezadoPrincipal" width="3%">-</th>
            <th class="encabezadoPrincipal" width="17%">Código interno</th>
            <th class="encabezadoPrincipal" width="25%">Producto</th>
            <th class="encabezadoPrincipal" width="25%">Línea</th>
            <th class="encabezadoPrincipal" width="15%">Stock</th>
            <th class="encabezadoPrincipal" width="15%">Cantidad</th>
        </tr>
    </table>
</form>