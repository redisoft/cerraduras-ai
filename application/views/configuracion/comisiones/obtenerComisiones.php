<?php
#if(!empty ($comisiones))
error_reporting(0);
$totalVentas		= 0;
$totalComisiones	= 0;

foreach ($comisionesTotal as $row)
{
	$comision	= 0;
	
	if($row->numeroPagos>1) 
	{
		$comision		= $row->comision;
		
		if($row->venta!=$row->importe)
		{
			$base			= $row->venta/$row->importe;
			$comision		= $row->comision*$base;
		}
	}
	
	$totalVentas		+= $row->venta;
	$totalComisiones	+= $comision;
}


{
	echo'
	<div style="width:90%;">
		<ul id="pagination-digg" class="ajax-pagComisiones">'.$this->pagination->create_links().'</ul>
	</div>
	
	<table width="100%" class="admintable" >
		<tr>
			<th colspan="10">
				<img src="'.base_url().'img/excel.png" width="22" height="22" onclick="excelComisiones()" />
				<br />
				Exportar
			</th> 
		</tr>
		<tr>
			<th class="encabezadoPrincipal" width="3%" align="center" valign="middle"># <br /> '.$registros.'</th>
			<th class="encabezadoPrincipal" align="center">
				<select id="selectPromotoresComisiones" name="selectPromotoresComisiones"  style="width:130px" class="cajas" onchange="obtenerComisiones()">
					<option value="0">Promotor</option>';
					foreach($usuarios as $row)
					{
						echo '<option '.($row->idUsuario==$idPromotor?'selected="selected"':'').' value="'.$row->idUsuario.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
			</th>
			<th class="encabezadoPrincipal" align="center">
				<select id="selectCampanasComisiones" name="selectCampanasComisiones"  style="width:130px" class="cajas" onchange="obtenerComisiones()">
					<option value="0">Campaña</option>';
					foreach($campanas as $row)
					{
						echo '<option '.($row->idCampana==$idCampana?'selected="selected"':'').' value="'.$row->idCampana.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
				
			</th>
			
			<th class="encabezadoPrincipal" align="center">
				<select id="selectProgramasComisiones" name="selectProgramasComisiones"  style="width:130px" class="cajas" onchange="obtenerComisiones()">
					<option value="0">Programa</option>';
					foreach($programas as $row)
					{
						echo '<option '.($row->idPrograma==$idPrograma?'selected="selected"':'').' value="'.$row->idPrograma.'">'.$row->nombre.'</option>';
					}
				echo'
				</select>
				
			</th>
			
			<th class="encabezadoPrincipal" align="center" width="15%">Alumno</th>
			<th class="encabezadoPrincipal" align="center" >Última conexión</th>
			<th class="encabezadoPrincipal" align="right" width="15%">Venta <br /> $'.number_format($totalVentas,decimales).'</th>
			<th class="encabezadoPrincipal" align="right" width="10%">Comisión <br /> $'.number_format($totalComisiones,decimales).'</th>
			<th class="encabezadoPrincipal" align="center" width="10%">Pagos</th>
			<th class="encabezadoPrincipal" align="center">Editar</th>
		</tr>';

	$i	= $limite;
	foreach ($comisiones as $row)
	{
		$estilo		= $i%2>0?' class="sinSombra" ':' class="sombreado" ';
		$comision	= 0;
		
		if($row->numeroPagos>1) 
		{
			$comision=$row->comision;
			
			if($row->venta!=$row->importe)
			{
				$base			= $row->venta/$row->importe;
				$comision		= $row->comision*$base;
			}
		}
		?>
        <tr <?php echo $estilo?> id="filaComisiones<?php echo $row->idVenta?>">
            <td align="center"> <?php echo $i?> </td>
            <td align="center" valign="middle"><?php echo $row->promotor ?></td>
            <td align="center" valign="middle"><?php echo $row->campana ?></td>
            <td align="center" valign="middle"><?php echo $row->programa ?></td>
            <td align="center" valign="middle"><?php echo $row->alumno ?></td>
            
            <td align="center" valign="middle" >
			 <?php 
				#$ultima	= $this->crm->obtenerUltimaConexionPreinscrito($row->matricula);
				
				#echo strlen($ultima)>0?obtenerFechaMesCortoHora($ultima):'';
				echo strlen($row->ultimaConexion)>2? obtenerFechaMesCortoHora($row->ultimaConexion):''
			 ?>
             </td>
            
            <td align="right" valign="middle">$<?php echo number_format($row->venta,decimales) ?></td>
            <td align="right" valign="middle">$<?php echo number_format($comision,decimales) ?></td>
            <td align="center" valign="middle"><?php echo $row->numeroPagos ?></td>
            
             <td align="center" valign="middle">
			 	<img src="<?php echo base_url()?>img/editar.png" onclick="accesoEditarComisionProspecto(<?php echo $row->idCliente?>)" width="22" height="22" />
                <br />
				<a>Editar</a>
             </td>
			<?php 
			echo '
		</tr>';
		
		$i++;
	}
	
	echo '</table>';
	
	echo'
	<div style="width:90%;">
		<ul id="pagination-digg" class="ajax-pagComisiones">'.$this->pagination->create_links().'</ul>
	</div>';
}
/*else
{
	echo
	'<div class="Error_validar" style="width:95%; margin-bottom: 5px;">
		No se encontraron registros.
	</div>';
}*/
?>