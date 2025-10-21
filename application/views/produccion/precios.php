<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/adm/dialog_box.css" />
<script type="text/javascript">
	var tabla=1;

	function busqueda()
	{
		div = document.getElementById('bus_id');
				filtro=div.value;

		if(filtro=='')
		{
			  showDialog('ERROR','Escriba el nombre del producto a buscar','error',2);
			//alert('Escriba el nombre del producto a buscar');
			return;
		}
		
		direccion="<?php echo base_url()?>produccion/prebusqueda/"+filtro;
		window.location.href=direccion;
	}
</script>
<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar" >

  <table class="toolbar" border="0" width="100%">
   <tr>
       	<td class="seccion">
    	Precios unitarios
   	    </td>
	</tr>
    </table>
    </div>
    </div>
<?php

if(!empty($productos))
{
?>
<table class="admintable" style="width:100%">
    <tr>
    	<th  class="encabezadoPrincipal">#</th>
        <th class="encabezadoPrincipal" align="left">Concepto</th>
        <th class="encabezadoPrincipal">Presentacion</th>
        <th class="encabezadoPrincipal">% Utilidad A</th>
        <th class="encabezadoPrincipal">% Utilidad B</th>
        <th class="encabezadoPrincipal">% Utilidad C</th>
        <th class="encabezadoPrincipal">Precio A</th>
        <th class="encabezadoPrincipal">Precio B</th>
        <th class="encabezadoPrincipal">Precio C</th>
    </tr>

<?php
$i=1;

foreach($productos as $row)
{
	$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';

	?>
    <tr <?php echo $estilo?>>
        <td align="right"><?php echo $i?></td>
        <td><?php echo $row['nombre']?></td>
        <td align="center"><?php echo $row['piezas']." pzas."?></td>
        <td align="right"><?php echo number_format($row['utilidadA'],2)?></td>
        <td align="right"><?php echo number_format($row['utilidadB'],2)?></td>
        <td align="right"><?php echo number_format($row['utilidadC'],2)?></td>
        <td align="right"><?php echo number_format($row['precioA'],2)?></td>
        <td align="right"><?php echo number_format($row['precioB'],2)?></td>
        <td align="right"><?php echo number_format($row['precioC'],2)?></td>
    </tr>
    <?php
	$i++;
}
?>
</table>
<?php

}//Fin del IF de Clientes
	else
	{
		?>
			<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">
            No hay registros de precios unitarios</div>
		<?php
	}
	?>
</div>

