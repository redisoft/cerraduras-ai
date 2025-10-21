<?php
if(!empty($listas))
{
	echo'
	<div style="width:90%; margin-top:2%;">
		<ul id="pagination-digg" class="ajax-pagListas">'.$this->pagination->create_links().'</ul>
	</div>';
	?>

	<table class="admintable" width="100%">
		<tr>
			<th class="encabezadoPrincipal" style="width:2%; ">#</th>
			<th class="encabezadoPrincipal" style="text-align:center"  >Fecha</th>
			<th class="encabezadoPrincipal" style="border-radius: 0px" width="20%" >Nombre</th>
			
			<!--th class="encabezadoPrincipal" style="text-align:center" align="center">Fecha final</th-->
			<th class="encabezadoPrincipal" style="width:20%;">Acciones </th>
		</tr>
	<?php
	$i	= $inicio+1;
	
	foreach($listas as $row)
	{
		?>
		<tr <?php echo $i%2>0?'class="sinSombra"':'class="sombreado"'?> id="filaListas<?php echo $row->idLista?>" >
			<td align="left" valign="middle"> <?php echo $i; ?> </td>
			<td align="center" valign="middle"> <?php  echo obtenerFechaMesCorto($row->fechaInicial)?> </td>
            <td align="left" valign="middle"><?php echo $row->nombre ?></td>
            
            
            <!--td align="center" valign="middle"> <?php  echo $row->vigencia=='1'? obtenerFechaMesCorto($row->fechaFinal):''?> </td-->
			<td align="center"   valign="middle"> 
			<?php
			echo'
			<img src="'.base_url().'img/print.png" width="22" style="cursor:pointer" onclick="listasPdf(\''.$row->idLista.'\')" />

			<!--&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnAutorizarLista'.$i.'" src="'.base_url().'img/autorizar.png" width="22" style="cursor:pointer" onclick="accesoAutorizarLista(\''.$row->idLista.'\')" />
			&nbsp;&nbsp;
			
			&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnEditarLista'.$i.'" src="'.base_url().'img/editar.png" width="22" style="cursor:pointer" onclick="accesoEditarLista('.$row->idLista.')" />
			
			<img id="btnBorrarLista'.$i.'" src="'.base_url().'img/borrar.png"'.'width="22" height="22" title="Borrar Producto" border="0" onclick="accesoBorrarLista('.$row->idLista.')" /-->
			
			
			
			<br />
			
			<a>Imprimir</a>
			<!--a id="a-btnAutorizarLista'.$i.'">Autorizar</a>
			<a id="a-btnEditarLista'.$i.'">Editar</a>
			<a id="a-btnBorrarLista'.$i.'">Borrar</a-->';
			
			if($row->autorizada=='1')
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnAutorizarLista'.$i.'\');
				</script>';
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
	
	if(count($listas)>10)
	{
		echo'
		<div style="width:90%; margin-top:0%;">
			<ul id="pagination-digg" class="ajax-pagListas">'.$this->pagination->create_links().'</ul>
		</div>';
	}
	
}
else
{
	echo '<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de descuentos</div>';
}
