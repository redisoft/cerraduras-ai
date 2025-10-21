<script src="<?php echo base_url()?>js/crm/clientes/semana.js"></script> 

<?php
$mes		= substr($fecha,5,2);

switch($mes)
{
	case "01":$mes='ENERO';$dia="31";break;
	case "02":$mes='FEBRERO';$dia="31";break;
	case "03":$mes='MARZO';$dia="31";break;
	case "04":$mes='ABRIL';$dia="30";break;
	case "05":$mes='MAYO';$dia="31";break;
	case "06":$mes='JUNIO';$dia="30";break;
	case "07":$mes='JULIO';$dia="31";break;
	case "08":$mes='AGOSTO';$dia="31";break;
	case "09":$mes='SEPTIEMBRE';$dia="30";break;
	case "10":$mes='OCTUBRE';$dia="31";break;
	case "11":$mes='NOVIEMBRE';$dia="30";break;
	case "12":$mes='DICIEMBRE';$dia="31";break;
} 

#echo $mes;

$dia 	= date ( "l", strtotime ($fecha)); //Obtener el dia actual
$dias	= substr($dia,0,3);

switch($dias)
{
	case 'Sun':$dia=1;break;
	case 'Mon':$dia=2;break;
	case 'Tue':$dia=3;break;
	case 'Wed':$dia=4;break;
	case 'Thu':$dia=5;break;
	case 'Fri':$dia=6;break;
	case 'Sat':$dia=7;break;
}

$fechas	=array();

if($dia==1)
{
	$fechas[1]=$fecha;
	
	for($i=2;$i<8;$i++)
	{
		$sql		="select date_add('".$fechas[$i-1]."', interval 1 day) as fecha;";
		$fechas[$i]	=$this->db->query($sql)->row()->fecha;
	}
}

if($dia>1)
{
	$sql		="select date_sub('".$fecha."', interval ".($dia-1)." day) as fecha;";
	$fechas[1]	=$this->db->query($sql)->row()->fecha;
	
	for($i=2;$i<8;$i++)
	{
		$sql		="select date_add('".$fechas[$i-1]."', interval 1 day) as fecha;";
		$fechas[$i]	=$this->db->query($sql)->row()->fecha;
	}
}

$sql		="select date_sub('".$fechas[1]."', interval 7 day) as fecha;";
$atras		=$this->db->query($sql)->row()->fecha;

$sql		="select date_add('".$fechas[7]."', interval 1 day) as fecha;";
$adelante	=$this->db->query($sql)->row()->fecha;
?>
    
<table class="admintable" style="width:100%">
	<tr>
    	<th class="encabezadoTablero" colspan="2"><label style="font-size:16px;"><?php echo $mes.' '.substr($fecha,0,4)?></label></th>
        <th class="encabezadoTablero" style="border: none; font-size:16px; text-align:right" colspan="6">
        
        <input class="cajas" id="week-picker" type="text" style="width:120px" placeholder="Seleccione fecha" />

        <!--<a title="Semana anterior" style="font-size:20px; font-weight:600;" href="<?php echo base_url()?>principal/tableroControl/<?php echo $atras?>">◄</a>-->
        <a title="Semana anterior" style="font-size:20px; font-weight:600;" onclick="definirFechaTablero('<?php echo $atras?>')" >◄</a>
        &nbsp;&nbsp;&nbsp;
        <label style="font-size:16px; color:#000">Reporte del <?php echo $fechas[1].' al '.$fechas[7]?></label>
        &nbsp;&nbsp;&nbsp;
        <!--<a title="Siguiente semana" style="font-size:20px; font-weight:600; " href="<?php echo base_url()?>principal/tableroControl/<?php echo $adelante?>">►</a>-->
        <a title="Siguiente semana" style="font-size:20px; font-weight:600; " onclick="definirFechaTablero('<?php echo $adelante?>')">►</a>
        </th>
    </tr>
	<tr>
        <th class="encabezadoPrincipal" width="6%">Hora</th>
        <th width="13.2%" class="encabezadoPrincipal">Domingo<br />
		<label><?php echo substr($fechas[1],8,2)?></label></th>
        <th width="13.2%" class="encabezadoPrincipal">Lunes<br />
		<label><?php echo substr($fechas[2],8,2)?></label></th>
        <th width="13.2%" class="encabezadoPrincipal">Martes<br />
		<label><?php echo substr($fechas[3],8,2)?></label></th>
        <th width="13.2%" class="encabezadoPrincipal">Miércoles<br />
		<label><?php echo substr($fechas[4],8,2)?></label></th>
        <th width="13.2%" class="encabezadoPrincipal">Jueves<br />
		<label><?php echo substr($fechas[5],8,2)?></label></th>
        <th width="13.2%" class="encabezadoPrincipal">Viernes<br />
		<label><?php echo substr($fechas[6],8,2)?></label></th>
        <th width="13.2%" class="encabezadoPrincipal">Sábado<br />
		<label><?php echo substr($fechas[7],8,2)?></label></th>
	</tr>
    
    <?php
	$cot=1;
	$ven=1;
	$com=1;
	$cob=1;
	$pag=1;
	$fac=1;
	$seg=1;
	
	for($h=0;$h<24;$h++)
	{
		$estilo='class="sombreado"';
	
		if($h%2>0)
		{
			$estilo="class='sinSombra'";
		}
	
		echo'<tr '.$estilo.'>';
		echo'
		<td align="right">
			'.$h.':00
		</td>';
		
		for($i=1;$i<8;$i++)
		{
			$fecha	=$fechas[$i];
			$h1		="";
			$h2		="";
			
			//Horas para el between con lambda
			$h1.=strlen($h)<2 ? '0'.$h:$h; //Hora de inicio
			$h2.=strlen(($h+1))<2 ? '0'.($h+1):($h+1); //Hora de fin
			
			echo '<td class="semanal" '.($permiso[1]->activo==1?'onclick="formularioCrmClientes(\''.$fecha.'\','.$h1.','.$h2.')"':'').' title="Click para registrar acción">';
			#--------------------------------------------COTIZACIONES--------------------------------------------------#
				
			$cotizaciones	= $this->tablero->obtenerCotizaciones($fecha,$h1,$h2,$permisoCotizaciones[4]->activo);

			foreach($cotizaciones as $row)
			{
				echo'
				<a title="Click para ver cotización '.$row->serie.'">
					<label class="crm" onclick="obtenerCotizacionInformacion('.$row->idCotizacion.')" style="cursor:pointer; color: #666">COTIZACIÓN: '.$row->serie.'</label>
				</a>
				<br />';
						
				$cot++;
			}
			
			#------------------------------------------------VENTAS----------------------------------------------------#
			
			#echo $sql;
			#if($this->session->userdata('rol')==1)
			{
				$ventas	= $this->tablero->obtenerVentas($fecha,$h1,$h2,$permisoVentas[4]->activo);
				
				foreach($ventas as $row)
				{
					$facturar	=$row->facturar==1?'Facturar, ':'';
					
					echo'
					<a onclick="obtenerVentaInformacion('.$row->idCotizacion.')"  title="Click para ver venta '.$row->ordenCompra.'">
						<label class="crm" style="cursor:pointer;  color: #FF6633">VENTA: '.$row->ordenCompra.($row->facturar=='1'?'<i style="font-weight:100">('.$facturar.')</i>':'').'</label>
						
					</a>
					<br />';
							
					$ven++;
				}
			}
			
			#-----------------------------------------------COMPRAS----------------------------------------------------#
			if($this->session->userdata('rol')==1)
			{
				$compras	= $this->tablero->obtenerCompras($fecha,$h1,$h2,$permiso);
				
				foreach($compras as $row)
				{
					$pagado	=$this->tablero->obtenerPagadoCompra($row->idCompras);
					$pago	=$pagado==0?'(Pagar)':'(Pago parcial)';
					$pago	=$pagado==$row->total?'(Pagado)':$pago;
					$color	=$pago=="(Pagado)"?"color: purple":'';	

					echo '
					<a title="Click para ver '.$row->nombre.'" >
						<label class="crm" onclick="obtenerComprita('.$row->idCompras.')" style="cursor:pointer; '.$color.'">COMPRA: '.$row->nombre.$pago.'</label>
					</a>
					<br />';
					$com++;
				}
			}
			#----------------------------------------------COBROS------------------------------------------------------#
			if($this->session->userdata('rol')==1)
			{
				$cobros	= $this->tablero->obtenerCobros($fecha,$h1,$h2,$permiso);
				
				foreach($cobros as $row)
				{
					echo '
					<a title="Click para ver cobro de venta '.$row->ordenCompra.'">
						<label class="crm" onclick="obtenerCobrosClientesTablero('.$row->idCotizacion.')" style="cursor:pointer; color: green">COBRO: '.$row->ordenCompra.'</label>
					</a>
					<br />';
					$cob++;
				}
			}
				
			#-------------------------------------------------PAGOS----------------------------------------------------#
			if($this->session->userdata('rol')==1)
			{
				$pagos	= $this->tablero->obtenerPagos($fecha,$h1,$h2,$permiso);
				
				foreach($pagos as $row)
				{
					echo '
					<a title="Click para ver pago de compra '.$row->nombre.'">
						<label class="crm" onclick="obtenerPagosComprasProveedor('.$row->idCompras.')" style="cursor:pointer; color: red">PAGO: '.$row->nombre.'</label>
					</a>
					<br />';
					$pag++;
				}
			}
			
			#--------------------------------------------FACTURACIÓN--------------------------------------------------#
			if($this->session->userdata('rol')==1)
			{
				$facturas	= $this->tablero->obtenerFacturas($fecha,$h1,$h2,$permiso);
				
				foreach($facturas as $row)
				{
					echo'
					<a title="Click para ver CFDI '.$row->serie.$row->folio.'">
						<label class="crm" id="facturasTablero'.$fac.'" onclick="obtenerDetallesFactura('.$row->idFactura.')" style="cursor:pointer; color: #666">CFDI: '.$row->serie.$row->folio.'</label>
					</a>
					<br />';
							
					$fac++;
				}
			}
			
			#--------------------------------------------SEGUIMIENTO A CLIENTES--------------------------------------------------#
			$seguimiento	= $this->tablero->obtenerSeguimiento($fecha,$h1,$h2,$permisoCrm[4]->activo);
			$tipo			= "CRM";
			
			foreach($seguimiento as $row)
			{
				#$bolita='<img src="'.base_url().'img/crm/'.($row->idStatus==3?'Cliente':$row->status).'.png" title="'.$row->status.'" width="20" />';
				
				echo'
				<a class="crm" title="Click para ver seguimiento cliente '.$row->empresa.'" onclick="obtenerSeguimientoEditar('.$row->idSeguimiento.')" style="cursor:pointer; color: #666">
					'.$tipo.': '.substr($row->empresa,0,8).'('.substr($row->responsable,0,6).')
					
					<div style="background-color: '.$row->color.'" class="circuloStatus"></div>
					'.$row->status.'
				</a>
				<br />';
						
				$seg++;
			}
			
			#--------------------------------------------SEGUIMIENTO A PROVEEDORES--------------------------------------------------#
			$seguimiento	= $this->tablero->obtenerSeguimientoProveedor($fecha,$h1,$h2,$permiso);
			$tipo			= "CRM";
			
			foreach($seguimiento as $row)
			{
				#$bolita='<img src="'.base_url().'img/crm/'.($row->idStatus==3?'Cliente':$row->status).'.png" title="'.$row->status.'" width="20" />';
				
				echo'
				<a class="crm" title="Click para ver seguimiento proveedor '.$row->empresa.'" onclick="obtenerSeguimientoEditarProveedor('.$row->idSeguimiento.')" style="cursor:pointer; color: #666">
					'.$tipo.': '.substr($row->empresa,0,8).'('.substr($row->responsable,0,6).')
					
					<div style="background-color: '.$row->color.'" class="circuloStatus"></div>
					'.$row->status.'
				</a>
				<br />';
						
				$seg++;
			}
				
			echo'</td>';
		}
		
		echo'</tr>';
	}
    ?>
</table>