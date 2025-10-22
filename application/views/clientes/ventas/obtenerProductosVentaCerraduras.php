<script>
 var table=null;
 
$(document).ready(function ()
{
	 table = $('#example').DataTable(
	{

        keys: 
		{
			keys: [ 13 /* ENTER */, 38 /* UP */, 40 /* DOWN */ ],
        },
		"tabIndex": 3,
		pageLength: 8,
		deferRender: true,
		/*pageLength: 10,
		scrollY:        200,*/
		
		
		
		/*colReorder: false,
		rowReorder: false,
		pageLength: 10,
		deferRender:    true,
		
		"bPaginate": false,
		
		"bFilter": false,
		"bInfo": false,
		"bAutoWidth": false,*/
		/*scroller:       true*/
		
		/*deferRender:    true,
		scrollY:        200,
		scrollCollapse: true,
		scroller:       true,*/
    });
    
    // Handle event when cell gains focus
    $('#example').on('key-focus.dt', function(e, datatable, cell)
	{
        // Select highlighted row
        $(table.row(cell.index().row).node()).addClass('selected');
    });

    // Handle event when cell looses focus
    $('#example').on('key-blur.dt', function(e, datatable, cell)
	{
        // Deselect highlighted row
        $(table.row(cell.index().row).node()).removeClass('selected');
    });
	
	//$('#example').on('click', 'tbody td', function(e){
	$('#example').on('dblclick', 'tbody td', function(e){
        e.stopPropagation();
        
		 rowIdx = table.cell(this).index().row;
		 
		agregarProductoVenta(rowIdx+1,0,'si','0');
		
		//table.cell.blur();
        // Get index of the clicked row
       
        
        // Select row
        //table.row(rowIdx).select();
    });
        
    // Handle key event that hasn't been handled by KeyTable
    $('#example').on('key.dt', function(e, datatable, key, cell, originalEvent)
	{
        // If ENTER key is pressed
        if(key === 13)
		{
            // Get highlighted row data
            var data = table.row(cell.index().row).data();
            
			
            // FOR DEMONSTRATION ONLY
           // $("#example-console").html(data.join(', '));
			
			
			
			setTimeout(function() 
			{
				if(agregarProductoVenta(data[0],0,'si','0'))
				{
					table.cell.blur();
				}
			}, 100);
			
        }
    });       
	
	$('#example_info').fadeOut();
	$('#example_paginate').fadeOut();
	$('#example_length').fadeOut();
	$('#example_filter').fadeOut();
	
	
	<?php
	
	if(count($productos)==1)
	{
		?>
		agregarProductoVenta(1,0,'si','0')
		<?php
	}
	
	?>
});
</script>

<div id="example-console"></div>

<?php
$idCliente		= $this->input->post('idCliente');
$precio			= $this->clientes->obtenerPrecioCliente($idCliente);
$precioCliente	= $precio!=null?$precio->precio:''; 

echo '<input type="hidden" id="txtNumeroTotalProductos" 		value="'.count($productos).'"/>';
		
if($productos!=null)
{
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagVen">'.$this->pagination->create_links().'</ul>
	</div>
	<table class="admintable display" cellspacing="0" width="100%" id="example" >
		<thead>
			<tr>
				<th>#</th>
				<th width="12%">Código</th>
				<th width="20%">Nombre</th>
				<!--<th width="20%">Proveedor</th>-->
				<th>Unidad</th>
				<th>Stock</th>
				<th>'.obtenerNombrePrecio(1).'</th>
				<th>Precio tarjeta</th>
				<th '.($precio1=='0'?'style="display:none"':'').'>Precio 1</th>
				<th>Mayoreo</th>
			</tr>
		</thead>';
	
	$i=1;
	foreach($productos as $row)
	{
		$idPeriodo			= 0;
		$onclick			= 'onclick="agregarProductoVenta('.$i.','.$row->servicio.',\'si\',\'0\')"';
		$stock				= $row->stock;
		#$stockSucursales	= $this->inventarioProductos->obtenerStockSucursales($row->idProducto);

		$precioA	= $row->precioA;
		$precioB	= $row->precioB;
		$precioC	= $row->precioC;
		$precioD	= $row->precioD;
		$precioE	= $row->precioE;
		
		$impuestoA	= $precioA - $row->precioA / (1+($row->tasa/100));
		$impuestoB	= $precioB - $row->precioB / (1+($row->tasa/100));
		$impuestoC	= $precioC - $row->precioC / (1+($row->tasa/100));
		$impuestoD	= $precioD - $row->precioD / (1+($row->tasa/100));
		$impuestoE	= $precioE - $row->precioE / (1+($row->tasa/100));

		$precio		= $row->precioImpuestos;
		

		if($stock==0) $onclick='';
		
		$onclick='';
		
		#'.$onclick.' '.($i%2>0?'class="sinSombra"':'class="sombreado"').'
		
		echo'		
		<tr '.$onclick.' '.($i%2>0?'class="sinSombra"':'class="sombreado"').' id="tab'.$i.'" >
			<td style="font-size:11px">'.$i.'</td>
			<td style="font-size:11px">';

			echo"<input type='hidden' id='txtNombre".$i."' value='".$row->nombre."' />";
			echo $row->codigoInterno;
			
			echo'
			</td>
			<td style="font-size:11px" >'.$row->nombre.'</td>

			<td style="font-size:11px">'.$row->unidad.'</td>
			<td style="font-size:11px" align="center" '.($stock==0?'style="color: red"':'').'>
			'.number_format($stock,decimales).'<br>
			<label style="cursor: pointer" onclick="obtenerStockSucursales('.$row->idProducto.')">Sucursales</label>
			</td>
			
			<td style="font-size:11px" align="center">
			$'.number_format($precioA,decimales);
			
				
				
				//DE MOMENTO DEJAR EL PRECIO DEFINIDO
				$precioCliente=1;	
				echo'
				<select id="selectPrecios'.$i.'" class="cajasPrecios" style="height: 23px; width:100px; display: none" >
					<option '.($precioCliente==1?'selected="selected"':'').' value="'.($precioA).'">$'.number_format($precioA,decimales).'</option>
					<option '.($precioCliente==2?'selected="selected"':'').' value="'.($precioB).'">$'.number_format($precioB,decimales).'</option>
					<option '.($precioCliente==3?'selected="selected"':'').' value="'.($precioC).'">$'.number_format($precioC,decimales).'</option>
					<option '.($precioCliente==4?'selected="selected"':'').' value="'.($precioD).'">$'.number_format($precioD,decimales).'</option>
					<option '.($precioCliente==5?'selected="selected"':'').' value="'.($precioE).'">$'.number_format($precioE,decimales).'</option>
				</select>
				
				
				<input type="hidden" id="txtActualPrecio'.$i.'" 	name="txtActualPrecio'.$i.'" value="'.$precioA.'"/>
		
				<input type="hidden" id="txtCodigoProducto'.$i.'" 	value="'.$row->codigoInterno.'" />
				<input type="hidden" id="txtCantidadTotal'.$i.'" 	value="'.($row->servicio==0?$stock:100000).'" />
				<input type="hidden" id="txtIDProducto'.$i.'" 		value="'.$row->idProducto.'" />
				<input type="hidden" id="idPeriodo'.$i.'" 			name="idPeriodo'.$i.'" value="'.$idPeriodo.'"/>
				<input type="hidden" id="txtPeriodo'.$i.'" 			name="txtPeriodo'.$i.'" value=""/>
				<input type="hidden" id="txtUnidad'.$i.'" 			name="txtUnidad'.$i.'" value="'.$row->unidad.'"/>
				
				<input type="hidden" id="txtMayoreoCantidad'.$i.'" 		name="txtMayoreoCantidad'.$i.'" 			value="'.$row->cantidadMayoreo.'"/>
		
				
				<input type="hidden" id="txtImpuestoNombre'.$i.'" 	name="txtImpuestoNombre'.$i.'" 		value="'.$row->impuesto.'"/>
				<input type="hidden" id="txtImpuestoTasa'.$i.'" 	name="txtImpuestoTasa'.$i.'" 		value="'.$row->tasa.'"/>
				<input type="hidden" id="txtImpuestoTipo'.$i.'" 	name="txtImpuestoTipo'.$i.'" 		value="'.$row->tipoImpuesto.'"/>
				<input type="hidden" id="txtImpuestoTotal'.$i.'" 	name="txtImpuestoTotal'.$i.'" 		value="'.$impuestoA.'"/>
				<input type="hidden" id="txtImpuestoId'.$i.'" 		name="txtImpuestoId'.$i.'" 			value="'.$row->idImpuesto.'"/>
				
				
				<input type="hidden" id="txtPrecio3'.$i.'" 		name="txtPrecio3'.$i.'" 			value="'.$row->precioC.'"/>
				
			</td>
			
			<td style="font-size:11px" align="center">
				$'.number_format($precioA*1.025,decimales).'
			</td>
			
			<td style="font-size:11px; '.($precio1=='0'?'display:none':'').'" align="center">
				$'.number_format($row->precioC,decimales).'
			</td>
			
			<td style="font-size:11px" align="center">
				'.round($row->cantidadMayoreo,decimales).'
			</td>
		</tr>';
		
		
		
		$i++;
	}
	
	#echo'</table>';
}
else
{
	echo '<div class="Error_validar">Sin registro de productos</div>';
}
