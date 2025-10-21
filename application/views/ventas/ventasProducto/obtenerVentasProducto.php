<?php
/*if(!empty ($ventas))
{*/
	$sql="";
	echo'
	<div style="width:100%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagVentasProducto">'.$this->pagination->create_links().'</ul>
	</div>';
	?>
    
    <ul class="menuTabs" <?php #echo $numero>25?'style="float: left; margin-left: -115px"':''?>>
        <li style="margin-top:0px" onclick="window.location.href='<?php echo base_url()?>ventas'">Ventas</li>
        <li style="margin-top:0px" class="activado" >Ventas por producto</li>
        <li style="margin-top:0px" onclick="window.location.href='<?php echo base_url()?>ventas/ventasServicio'">Ventas por servicio</li>
    </ul>

	<table class="admintable" width="100%" >
    	<?php
        echo '
		<tr>
        	<th class="encabezadoPrincipal" colspan="9">
            	Ventas por producto
                
                <img src="'.base_url().'img/pdf.png" width="22" onclick="reporteVentasProducto()" title="PDF" />
				<img src="'.base_url().'img/excel.png" width="22" onclick="excelVentasProducto()" title="Excel" />
            </th>
        </tr>';
		?>
    	
	 	<tr>
			<th align="center" valign="middle">#</th>
            
			<?php
            echo '
            <th align="center">
            
                <select class="cajas" id="selectClientesBusqueda" style="width:120px" onchange="obtenerVentasProducto()">
                    <option value="0">Cliente</option>';
                    for($i=0;$i<count($arreglos['idCliente']);$i++)
                    {
                        if(strlen($arreglos['idCliente'][$i])>0)
                        {
                            echo '<option '.($arreglos['idCliente'][$i]==$idCliente?'selected="selected"':'').' value="'.$arreglos['idCliente'][$i].'">'.$arreglos['cliente'][$i].'</option>';
                        }
                    }
                
            echo '
                </select>
            </th>';
            
            ?>
            
			<th align="center">
            	<?php
				echo '
				<select class="cajas" id="selectVentasBusqueda" style="width:80px" onchange="obtenerVentasProducto()">
					<option value="0">Venta</option>';
					for($i=0;$i<count($arreglos['idCotizacion']);$i++)
					{
						if(strlen($arreglos['idCotizacion'][$i])>0)
						{
							echo '<option '.($arreglos['idCotizacion'][$i]==$idCotizacion?'selected="selected"':'').' value="'.$arreglos['idCotizacion'][$i].'">'.$arreglos['ordenCompra'][$i].'</option>';
						}
					}
					
				echo '</select>';
				?>
            </th>
			<th align="center" >
            	Fecha
                
                <?php
				echo '<img onclick="definirOrdenVentas('.($ordenVentas=='asc'?'\'desc\'':'\'asc\'').')" src="'.base_url().'img/'.($ordenVentas=='asc'?'ocultar':'mostrar').'.png" width="17" />';
		  		?>
            </th>
            <th align="center" >
            	<?php
				echo '
				<select class="cajas" id="selectProductosBusqueda" style="width:100px" onchange="obtenerVentasProducto()">
					<option value="0">Producto</option>';
					
					for($i=0;$i<count($arreglos['idProducto']);$i++)
					{
						if(strlen($arreglos['idProducto'][$i])>0)
						{
							echo '<option '.($arreglos['idProducto'][$i]==$idProducto?'selected="selected"':'').' value="'.$arreglos['idProducto'][$i].'">'.$arreglos['producto'][$i].'</option>';
						}
					}
				
				echo '</select>';
					
				?>
            </th>
            <th align="center">Cantidad</th>
			<th align="center">PU</th>
			<th align="center">Descuento</th>
			<th align="center">Importe</th>
		</tr>
	
	<?php
	$i=$limite+1;
	foreach ($ventas as $row)
	{
		echo'
		<tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
	 	<td align="center">'.$i.'</td>
		<td align="left">'.$row->cliente.'</td>
	 	<td align="center">';
		
		 	echo $row->ordenCompra; 
			if($row->cancelada==1)
			echo ' (Venta cancelada)';
			echo $row->idTienda>0?'('.$row->tienda.')':'';
		 
         echo'
		 </td>
		 <td align="center">'.obtenerFechaMesCortoHora($row->fechaCompra).'</td>
         <td align="left">'.$row->producto.'</td>
         
		 <td align="center">'.number_format($row->cantidad,decimales).'</td>
		 <td align="right">'."$".number_format($row->precio,decimales).'</td>
		 <td align="right">'."$".number_format($row->descuento,decimales).'</td>
		 <td align="right">'."$".number_format($row->importe,decimales).'</td>
		 </tr>';
		$i++;
	}
	
	?>
	
	</table>

	<?php
	
	if(count($ventas)>20)
	{
		echo'
		<div style="width:90%; ">
			<ul id="pagination-digg" class="ajax-pagVentasProducto">'.$this->pagination->create_links().'</ul>
		</div>';
	}
/*}
else
{
	echo 
	'<div class="Error_validar" style="margin-top:2%; width:67%; margin-left:2px; margin-bottom: 5px;">
		No hay registro de ventas.
	</div>';
}*/
?>
