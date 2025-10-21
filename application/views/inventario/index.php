
<div class="derecha">

<div class="barra">Productos</div>

<div class="submenu">
<div class="toolbar" id="toolbar">
 <table class="toolbar admintable" width="44%">
    <tr>
       <td align="center" valign="middle" class="button">               
        <a  href="<?php print(base_url()."inventario/index/0"); ?>" class="toolbar" id="">
        <span class="icon-option" title="Lista de productos">
         <img src="<?php print(base_url()); ?>img/package.png" width="24px;" height="24px;" title="Lista de productos" alt="Lista de productos" /> </span>
         Productos        </a>      </td>
       <td align="center" valign="middle" >
              
        <a  href="<?php print(base_url()."inventario/add"); ?>" class="toolbar" id="">
        <span class="icon-option" title="Añadir nuevo producto">
         <img src="<?php print(base_url()); ?>img/package_add.png" width="30px;" height="30px;" title="Añadir nuevo producto" alt="Añadir productos" /> </span>
         Añadir nuevo producto        </a>       </td>
       
       <td align="left" valign="middle">
        Buscar
        <input type="text" id="bus_id" name="bus_id" value="" class="cajas" maxlength="20" style="width:100px; background-color:#FFF;" />
		</td valign="middle">
		<td>
<?php print('<img src="'.base_url().'img/search_32.png" width="24px;" height="24px;" id="id_buscar_link" title="Buscar producto" style="cursor:pointer;">'); ?>      </td>
    </tr>
 </table>
 </div>
</div>

<div class="listproyectos">

<div id="CargandoID" style="width:92%; float:left; margin-top:2px; margin-bottom: 5px;"></div>

<div class="Error_validar" id="registroError" style="display:none; margin-top:2px; margin-bottom: 5px;"></div>

<?php

if(!empty($Inventarios)){

?>

<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

<div id="RESPUESTACLIENTE" style="width:95%;float:left; margin-left:3%;">
    
<table class="admintable" width="99%;">

    <tr>
        <th>Clave</th>
        <!--th>Unidad</th-->
        <th align="center" style="width:120px;">Producto</th>

        <th>Costo produccion </th>
        <th>Precio venta </th>
        <th style="width:60px;">Peso por caja (kgs) </th>
	    <th style="width:60px";>Tipo de caja (pza)</th>
		<th>Color </th>
        <th>Pzs</th>
		<!--th>Empaque</th-->
        <th>Acciones</th>               
    </tr>


<?php

foreach ($Inventarios as $Inventario){

$image_Editar = array(
       'src' => base_url().'img/edit.png',
       'alt' => 'Editar producto',
       'class' => '',
       'width' => '16',
       'height' => '16',
       'title' => 'Editar producto'
  );

$Link_editar=anchor(base_url()."inventario/editar/".$Inventario['id'],img($image_Editar),'Editar proveedor');

?>

    
    <tr>
        <td align="left" valign="middle"> <?php print($Inventario['referencia']); ?> </td>
        <!--td align="left" valign="middle"> <?php print($Inventario['ubicacion']); ?> </td-->
        <td align="left" valign="middle"> <?php print($Inventario['descripcion']); ?> </td>

        <td align="center" valign="middle"> $ <?php print(number_format($Inventario['precio_costo'],2)); ?> </td>
        <td align="center" valign="middle">$ <?php print(number_format($Inventario['precio_venta'],2)); ?></td>
        <td align="center" valign="middle"> <?php print(number_format($Inventario['precio_porcaja'],2)); ?></td>
		<td align="center" valign="middle"> <?php print($Inventario['tpocaja']); ?> </td>
		<td align="center" valign="middle">
		<?php if($Inventario['tipo']!='Surtido') echo "<img src=\"".base_url()."img/productos/".$Inventario['tipo'].".PNG\">"; 
		//else echo "Surtido";
		if($Inventario['tipo']=='Surtido')
		{
			if($Inventario['color_blanco']>0) echo "<img src=\"".base_url()."img/productos/Blanco.PNG\">&nbsp;";
			if($Inventario['color_rojo']>0) echo "<img src=\"".base_url()."img/productos/Rojo.PNG\">&nbsp;";
			if($Inventario['color_azul']>0) echo "<img src=\"".base_url()."img/productos/Azul.PNG\">&nbsp;";
			if($Inventario['color_amarillo']>0) echo "<img src=\"".base_url()."img/productos/Amarillo.PNG\">&nbsp;";
			if($Inventario['color_morado']>0) echo "<img src=\"".base_url()."img/productos/Morado.PNG\">&nbsp;"; 
			if($Inventario['color_rosa']>0) echo "<img src=\"".base_url()."img/productos/Rosa.PNG\">&nbsp;";
			if($Inventario['color_verde']>0) echo "<img src=\"".base_url()."img/productos/Verde.PNG\">&nbsp;";
		}
		?>
		</td>
        <td align="center" valign="middle"> <?php print($Inventario['cexistencia']); ?> </td>
		<!--td align="center" valign="middle"> </td-->
        <td align="center"   valign="middle"><?php print($Link_editar); ?> <br />
		<a href="<?php echo base_url()."inventario/editar/".$Inventario['id']?>">Editar </a> 
</td>
    </tr>

<?php
 }//Foreach del Cliente
?>
</table>
    
</div><!--  RESPUESTACLIENTES -->

<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

<?php

}//Fin del IF de Clientes
else{
?>

<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de productos</div>
    
 <?php
   }
 ?>


</div>
<!-- Termina -->
</div>
