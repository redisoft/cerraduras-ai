<script src="<?php echo base_url()?>js/ventas/catalogo/ventas.js"></script>
<script src="<?php echo base_url()?>js/administracion.js"></script>

<script type="text/javascript">
	function busquedaCliente()
	{
		cliente=document.getElementById('selectClientes').value;
		
		direccion="http://"+base_url+"reportes/busquedaClienteVentasCobranza/"+cliente;
		
		window.location.href=direccion;
	}
	
	function busquedaZona()
	{
		zona=document.getElementById('selectZonas').value;
		
		direccion="http://"+base_url+"reportes/busquedaZonaVentasCobranza/"+zona;
		
		window.location.href=direccion;
	}
	
	function validando()
	{
		if(document.form.FechaDia.value==""|| document.form.FechaDia2.value=="" )
		{
			alert("Debe seleccionar fecha de inicio y fecha fin");
			return false;
		}
		else 
		if(document.form.FechaDia.value > document.form.FechaDia2.value)
		{
			alert('La fecha de inicio no puede ser mayor a la final.');
			return false;
		} 
		else
		return true;
	}
</script>

<div class="derecha">
<div class="submenu">
<div class="toolbar">
<div class="seccionDiv">
Reporte de cobranza
</div>
<form id="form" name="form" onSubmit="return validando()" method="post" action="<?php echo base_url().'reportes/busquedaCobranzaFechas/'?>">
 <table class="toolbar" width="100%">
    <tr>
      <td>
        <input name="FechaDia" type="text" title="Inicio" style="width:150px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
        <input name="FechaDia2" type="text" title="Fin" id="FechaDia2" style="width:150px" class="busquedas" placeholder="Fecha fin" />
        <input type="submit" name="Submit" class="btn" value="Buscar"  />    
     </td>
     <td width="30%" style="padding-right:60px">
     	 <input type="text"  name="txtBusquedas" id="txtBusquedas" class="busquedas" placeholder="Seleccione cliente"
                onkeyup="buscarDato(this.value,'cobranza');" onblur="datoEncontrado();" style="width:300px;"/>
        <div align="left" class="suggestionsBox" id="listaInformacion" 
            style="display: none; position:absolute; margin-left:52%; width:300px">
                <img src="<?php echo base_url()?>img/upArrow.png" style="position: relative; top: -12px; left: 30px;" />
            <div class="suggestionList" id="autoListaInformacion">
             &nbsp;
            </div>
         </div>
         
          <?php
        if($this->session->userdata('idClienteCobranza')!="" or $this->session->userdata('idZonaCobranza')!="" 
			or $this->session->userdata('fechaInicioCobranza')!="")
        {
			echo 
			'<br />
			<a href="'.base_url().'reportes/busquedaIdentificadorCobranza/todas" class="toolbar" style="margin-left:90px">
			<span class="icon-option" 
			title="Añadir cliente"><img src="'.base_url().'img/quitar.png" width="30px;" 
			height="30px;" title="Borrar busqueda" />
			</span>
			Borrar busqueda</a>';
        }
        ?>        
         
         
     </td>
     <td>
     <input type="text"  name="txtBusquedas" id="txtBusquedas" class="busquedas" placeholder="Seleccione <?php echo $this->session->userdata('identificador')?>" onkeyup="buscarDato(this.value,'identificadorCobranza');" onblur="datoEncontrado();" style="width:300px;"/>
     </td>
	</tr>
</table>
</form>	
</div>
</div>

<div class="listproyectos" style="margin-top:25px">
<table class="admintable" width="100%" >
<tr>
    <th class="encabezadoPrincipal">#</th>
    <th class="encabezadoPrincipal">
    Fecha Venta
    <?php
		  if($this->session->userdata('criterioCobranza')=='a')
		  {
			echo '<a href="'.base_url().'reportes/ordenamientoCobranza/z">
			<img src="'.base_url().'img/ocultar.png" width="17" /></a>';	
		  }
		  else
		  {
			  echo '<a href="'.base_url().'reportes/ordenamientoCobranza/a">
			<img src="'.base_url().'img/mostrar.png" width="17" /></a>';
		  }
	  ?>
    </th>
    <th class="encabezadoPrincipal" align="left">
		<?php echo $this->session->userdata('identificador')?>
    </th>
    <th class="encabezadoPrincipal" align="left">Cliente</th>
    <th class="encabezadoPrincipal" align="left">Venta</th>
    <th class="encabezadoPrincipal" align="center">Fecha de vencimiento</th>
    <th class="encabezadoPrincipal" align="center">Días de vencimiento</th>
    <th class="encabezadoPrincipal" align="right">Saldo</th>
    <th class="encabezadoPrincipal" align="right">Acciones</th>
</tr>
<?php
if($ventas!=null)
{
	$i=1;
	$total=0;
	foreach($ventas as $row)
	{
		
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		
		$cancelada=0;
		
		if($row->idFactura!=0)
		{
			$sql="select cancelada from facturas
			where idFactura='$row->idFactura' 
			and cancelada='1'";
			
			if($this->db->query($sql)->num_rows()>0) 
			{
				$cancelada=1;
			}
		}
		
		$fecha="Pendiente de facturación";
		$dias=0;
		
		if($row->idFactura>0)
		{
			$sql="select fecha from facturas
			where idFactura='$row->idFactura'";
			
			$factura=$this->db->query($sql)->row();
			
			$sql="SELECT date_add('".substr($factura->fecha,0,10)."',interval ".$row->diasCredito." day) as fechaFin";
			
			$fecha=$this->db->query($sql)->row()->fechaFin;
			
			$sql="SELECT DATEDIFF('".$fecha."','".date('Y-m-d')."') as diasRestantes";
			
			#echo $sql;
			#return;
			$dias=$this->db->query($sql)->row()->diasRestantes;
		}
		
		if($cancelada==0)
		{
			$sql="select sum(pago) as pago
			from catalogos_ingresos
			where idVenta='$row->idVenta'";
			
			$query=$this->db->query($sql);
			$query=$query->row();
			
			$saldo=$row->precioventa-$query->pago;
			
			if($saldo>0)
			{
				$total+=$row->precioventa-$query->pago;
				?>
				<tr <?php echo $estilo?>>
					<td><?php echo $i?></td>
					<td align="center"><?php echo substr($row->fechadd,0,10)?></td>
                    <td align="left"><?php echo $row->identificador?></td>
					<td align="left"><?php echo $row->empresa?></td>
					<td align="left"><?php echo $row->ordenCompra?></td>
					<td align="center"><?php echo $fecha?></td>
                    <td align="center">
					<?php 
					
					if($dias<0)
					{
						echo '<a>'.$dias.'</a>';
					}
					else
					{
						echo $dias;
					}
					?>
                    </td>
					<td align="right">$ <?php echo number_format($saldo,2)?></td>
                    <td align="center">
                        <img id="pagosClientes<?php echo $i?>" onclick="obtenerPagosClientes('<?php echo $row->idVenta?>')" 
                        src="<?php echo base_url()."img/pagos.png"?>" width="20" height="20"	
                        title="Cobros a clientes" style="cursor:pointer;"/>
                        <br />
                        <a>Cobros</a>
                    </td>
				</tr>

				<?php
				$i++;
			}
		}
	}
	
	echo'
	<tr>
		<td colspan="8" style="font-weight:bold" align="right">Total $ '.number_format($total,2).'</td>
		<td></td>
	</tr>';
}
?>
</table>
</div>
</div>

<div id="ventanaPagosClientes" title="Cobros a clientes">
<div id="cargandoPagosClientes"></div>
<div id="cargarPagosClientes"></div>
</div>

