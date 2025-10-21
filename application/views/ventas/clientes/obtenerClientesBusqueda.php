<?php 
if(!empty($clientes))
{
	echo'
	<div style="width:90%; margin-top:1%;">
		<ul id="pagination-digg" class="ajax-pagClientesBusqueda">'.$this->pagination->create_links().'</ul>
	</div>';
	
	?>
	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" style="width:3%;">#</th>
			<th class="encabezadoPrincipal" align="center" width="20%" >Cliente</th>
			<th class="encabezadoPrincipal" width="15%">Dirección </th>
			<th class="encabezadoPrincipal" width="10%">Municipio</th>
			<th class="encabezadoPrincipal" width="15%">Teléfono</th>
			<th class="encabezadoPrincipal" width="20%">Email</th>
			<th class="encabezadoPrincipal" style="width:20%;">Agregar</th>               
		</tr>
	
	<?php
	$i=$inicio;
	foreach($clientes as $row)
	{
		$estilo	= $i%2>0?'class="sinSombra"':'class="sombreado"';
	
		?>
		<tr <?php echo $estilo?> >
			<input type="hidden" id="txtEmpresaCliente<?=$row->idCliente?>" value="<?=$row->empresa?>" />
			<input type="hidden" id="txtCreditoCliente<?=$row->idCliente?>" value="<?=round($row->limiteCredito,0)?>" />
			<input type="hidden" id="txtIdSucursal<?=$row->idCliente?>" value="<?=strlen($row->idSucursal)>0?$row->idSucursal:0?>" />
			
			<input type="hidden" id="txtDireccionCliente<?=$row->idCliente?>" value="<?=$row->calle.' '.$row->numero.' '.$row->colonia.' '.$row->municipio?>" />
			
			<td align="right" valign="middle"> <?php print($i); ?> </td>	
			<td align="left" valign="middle"> <?php echo $row->empresa ?> </td>
            <td align="center" valign="middle"> <?php echo $row->calle.' '.$row->numero.' '.$row->colonia ?> </td>
			<td align="left" valign="middle"> <?php echo $row->municipio ?> </td>
			<td align="center" valign="middle"> 
				<?=$tiendaLocal==0?'<input type="text" id="txtTelefonoCliente'.$row->idCliente.'" value="'.$row->telefono.'" class="cajas" style="width: 98%; margin-left: 0px" />':$row->telefono?>
			</td>
			
			<td align="center" valign="middle"> 
				<?=$tiendaLocal==0?'<input type="text" id="txtEmailCliente'.$row->idCliente.'" value="'.$row->email.'" class="cajas" style="width: 98%; margin-left: 0px" />':$row->email?>
			</td>
          
			<td align="center"   valign="middle">
                
				<?php
				if($tiendaLocal=='0')
				{
					echo '
					<img id="btnEditarCliente'.$i.'"  onclick="editarClienteVenta('.$row->idCliente.')" src="'.base_url().'img/editar.png" style="width:22px; height:22px;" title="Editar cliente" />
					&nbsp;&nbsp;&nbsp;';
				}
				?>
				
				
                <img id="btnAgregar<?php echo $i?>"  onclick="agregarClienteVenta('<?php echo $row->idCliente?>')" src="<?php echo base_url()?>img/add.png" style="width:22px; height:22px;" title="Agregar a venta" />
				
				<?php
				if($tiendaLocal=='0')
				{
					echo '
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="'.base_url().'img/rastreo.png" id="btnDirecciones'.$i.'" width="22" height="22" title="Direcciones fiscales y de envío" style="cursor:pointer" onclick="obtenerCatalogoDirecciones('.$row->idCliente.')" />
					&nbsp;&nbsp;&nbsp;';
				}
				?>
				
				
               
				
                <br /> 
                
			<?php
		
			if($tiendaLocal=='0')
			{
				echo'
				<a id="a-btnEditarCliente'.$i.'">Editar</a>';
			}
		
			
			echo'
			<a id="a-btnAgregar'.$i.'">Agregar</a>';
		
			if($tiendaLocal=='0')
			{
				echo'
				<a id="a-btnDirecciones'.$i.'">Direcciones</a>';
			}
			
			
		  ?>
		  </td>
		</tr>
	
	<?php
	$i++;
	 }
	?>
	</table>
	<?php
	
	echo'
	<div style="width:90%; margin-top:0%;">
		<ul id="pagination-digg" class="ajax-pagClientesBusqueda">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">Sin registros</div>';
}
?>
