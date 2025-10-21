<script type="text/javascript">
	function validando()
	{
		if(document.form.FechaDia.value==""|| document.form.FechaDia2.value=="" )
		{
			alert("Debe llenar ambos campos");
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

	$(document).ready(function()
	{
		$("#borraBusqueda").click(function(e)
		{
			direccion="http://"+base_url+"reportes/busquedaEgresoFechas/";
		
			window.location.href=direccion;
		})
	});
	
	function buscarCuentas()
	{
		div = document.getElementById('selectBancos');
		idBanco=div.value;
		
		$("#cargarCuenta").load("http://"+base_url+"ficha/obtenerCuentas/"+idBanco);
	}
</script>

<div class="derecha">
<div class="submenu">
<div class="toolbar">
<div class="seccionDiv">
Reporte de Egresos
</div>

<form id="form" name="form" onSubmit="return validando()" method="post" action="<?php echo base_url().'reportes/busquedaEgresoFechas/'?>">
 <table class="toolbar" width="100%">
    <tr>
        <td width="40%">
            <input name="FechaDia" type="text" title="Inicio" style="width:150px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
			&nbsp;
            <input name="FechaDia2" type="text" title="Fin" id="FechaDia2" style="width:150px" class="busquedas" placeholder="Fecha fin" />
        </td>
        <td width="20%">
            <select class="busquedas" id="selectBancos" name="selectBancos" style="width: auto"  onchange="buscarCuentas()">
            <option value="0">Seleccione banco</option>
            <?php
            foreach($bancos as $row)
            {
                echo '<option value="'.$row->id.'">'.$row->nombre.'</option>';
            }
            ?>
            </select>  
            
             <?php
			if($this->session->userdata('egresoInicio')!="")
			{
				echo 
				'<br />
				<a id="borraBusqueda" class="toolbar" style="margin-left:55px">
				<span class="icon-option" 
				title="Añadir cliente"><img src="'.base_url().'img/quitar.png" width="30px;" 
				height="30px;" title="Borrar busqueda" />
				</span>
				Borrar busqueda</a>';
			}
			?>        
            
        </td>
        <td width="20%">
        <div id="cargarCuenta">
         <select  id="cuentasBanco" name="cuentasBanco" class="busquedas" style="width:auto;" >
            <option value="0">Seleccione cuenta</option>
         </select>
         </div>
        </td>
        <td>
         <input type="submit" name="Submit" class="btn" value="Buscar"  />  
        </td>
        
       
</tr>
</table>
</form>
</div>
</div>

<div class="listproyectos" style="margin-top:20px">

<?php
if($egresos!=null)
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>';
	?>

  <table class="admintable" style="width:99%">
  <tr>
  <th class="encabezadoPrincipal" colspan="7">
  Egresos 
  <?php
	if($this->session->userdata('egresoInicio')!="")
	{
		print('del '.$this->session->userdata('egresoInicio')." al ".$this->session->userdata('egresoFin'));
	}
  ?>
  </th>
  </tr>
  <tr>
      <th class="encabezadoPrincipal" align="right">#</th>
      <th class="encabezadoPrincipal">Fecha</th>
      <th class="encabezadoPrincipal">Concepto</th>
      <th class="encabezadoPrincipal">Banco</th>
      <th class="encabezadoPrincipal">Cuenta</th>
      <th class="encabezadoPrincipal">Forma de pago</th>
      <th class="encabezadoPrincipal">Monto</th>
  </tr>
    
    <?php
    $i=1;
    $total=0;
	
	foreach($egresos as $egreso)
	{
		$estilo		=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		$total+=$egreso->pago;
		?>
		<tr <?php echo $estilo?>>
		<td align="right"><?php echo $i ?></td>
		<td align="center"><?php echo $egreso->fecha?></td>
        <td align="center"><?php echo $egreso->producto?></td>
		<td align="center"><?php echo $egreso->banco?></td>
		<td align="center"><?php echo $egreso->cuenta?></td>
		<td align="center"><?php echo $egreso->formaPago?></td>
		<td align="right">$ <?php echo number_format($egreso->pago,2)?></td>
		</tr>
		<?php
		$i++;   
	}
	
	?>
	<tr>
        <th align="right" colspan="7">
        <a onclick="window.open('<?php echo base_url()?>reportes/egresosPDF')" >
        <img src="<?php echo base_url()?>img/pdf.png" width="20" title="Generar PDF" 
        style="cursor:pointer" />
        </a>
        &nbsp; Total $ <?php echo number_format($total,2)?>
		</th>
	</tr>
	</table>
	<?php
	
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pag">'.$this->pagination->create_links().'</ul>
	</div>';
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de egresos</div>';
}
  ?>
</div>
<!-- Termina -->
</div>
