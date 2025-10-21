<script type="text/JavaScript" src="<?php echo base_url()?>js/puntoVenta.js"></script>

<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar">
<div class="seccionDiv">
Envios a tiendas
</div>

    <table class="toolbar" width="99%">
    	<tr>
        	<td>
            	<select id="selectTiendas" class="busquedas" style="width:200px" onchange="busquedaTiendas()">
                <option value="0">Todas</option>
                <?php
				
				foreach($tiendas as $row)
				{
					$seleccionado="";
					if($this->session->userdata('idTiendaBusquedaEnvio')==$row->idTienda) $seleccionado='selected="selected"';
					
					echo '<option value="'.$row->idTienda.'" '.$seleccionado.'>'.$row->nombre.'</option>';
				}
                ?>
                </select>
            </td>
            <td width="70%" style="padding-right:250px">
            <input placeholder="Seleccionar fecha" type="text" class="busquedas" id="FechaDia" 
            	style="width:150px" onchange="busquedaFechasTienda()"  />
                
                 <?php
				if($this->session->userdata('idTiendaBusquedaEnvio')!="" or $this->session->userdata('idTiendaBusquedaEnvioFecha')!="")
				{
					echo 
					'<br />
					<a href="'.base_url().'tiendas/prebusquedaTiendaFecha/0" class="toolbar" style="margin-left:218px">
					<span class="icon-option" 
					title="Añadir cliente"><img src="'.base_url().'img/quitar.png" width="30px;" 
					height="30px;" title="Borrar busqueda" />
					</span>
					Borrar busqueda</a>';
				}
				?>        
                </td>
        </tr>
    </table>
</div>
</div>

<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pagin'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

<?php
$i=1;
if(!empty($envios))
{
	echo'
	<div class="listproyectos">
	<table class="admintable" width="100%"  >
	<tr>
		<th class="encabezadoPrincipal">#</th>
		<th class="encabezadoPrincipal">Fecha</th>
		<th class="encabezadoPrincipal">Tienda</th>
		<th class="encabezadoPrincipal">Producto</th>
		<th class="encabezadoPrincipal" width="15%">Cantidad</th>
	</tr>';
	
	foreach($envios as $row)
	{
		$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
		
		echo'
		<tr '.$estilo.'>
			<td align="right">'.$i.'</td>
			<td align="center">'.$row->fecha.'</td>
			<td align="center">'.$row->tienda.'</td>
			<td>'.$row->producto.'</td>
			<td align="right">'.number_format($row->cantidad,2).'</td>
		</tr>';
		
		$i++;
	}
	
	echo '</table>
	</div>';
}
else
{
	echo '<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de envios</div>';
}
?>


<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pagin'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>




</div>