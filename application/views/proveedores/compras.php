<script  language="javascript" type="text/javascript"/>
	function busqueda()
	{
		mes=document.getElementById('mes').value;
		anio=document.getElementById('anio').value;
		
		direccion="http://"+base_url+"compras/prebusqueda/"+mes+"/"+anio;
		
		window.location.href=direccion;
	}
	
	function busquedaFecha()
	{
		if($('#FechaDia').val()=="")
		{
			alert('Por favor seleccione una fecha');
			return;
		}
		
		direccion="http://"+base_url+"proveedores/prebusquedaFecha/"+$('#FechaDia').val();
		
		window.location.href=direccion;
	}
	
	function busquedaProveedor()
	{
		proveedor=document.getElementById('idProveedor').value;
		
		direccion="http://"+base_url+"compras/busquedaProveedor/"+proveedor;
		
		window.location.href=direccion;
	}
	
	function buscarCuentas()
	{
		div = document.getElementById('listaBancos');
		idBanco=div.value;
		
		$("#cargarCuenta").load("http://"+base_url+"ficha/obtenerCuentas/"+idBanco);
	}
	
	function obtenerTotal()
	{
		numero=document.getElementById('indice').value;
		total=0;
		
		for(i=1;i<numero;i++)
		{
			valor=parseFloat(document.getElementById('pagar_'+i).value);
			if(isNaN(valor))    
			{
				alert('La cantidad es incorrecta');
				document.getElementById('pagar_'+i).value=0;  
				
				return;
			}
			total+=valor;
		}
		
		$("#totales").val(total);
    }
</script>
<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar">
 <table class="toolbar" style="width:100%">	
    <tr>
     <!--td align="center" valign="middle" style="width:10%" >
        <?php print('<img src="'.base_url().'img/compras.png" width="30px;" height="30px;" class="productos" id="productos" style="cursor:pointer;" title="Nueva compra">'); ?>  <br />
		Nueva compra		 </td-->
      <td align="left" valign="middle" style="width:40%">
        <input type="text" class="busquedas" style="width:130px" id="FechaDia" placeholder="Seleccione fecha" />
        <?php print('<img src="'.base_url().'img/search_32.png" width="30px;" height="30px;" id="buscame" title="Buscar compras" style="cursor:pointer;" onclick="busquedaFecha()">'); ?>      
        </td>

        <?php 
		if($this->session->userdata('fechaCompras')!="" or $this->session->userdata('idProveedor')!="" )
		{
			?>
            <td style="width:15%">
             <a href="<?php echo base_url()?>proveedores/prebusquedaFecha/nada"> Borrar busqueda
              <img src="<?php echo base_url()?>img/borrar.png" width="30px;" height="30px;" 
			 title="Borrar busqueda" style="cursor:pointer;"> 
             </a>
            </td>
            <?php
		}
		?>
    </tr>
 </table>
 </div>
</div>

<div class="listproyectos">

<?php

if(!empty($compras))
{
?>
<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

<table class="admintable" width="100%">
    <tr>
	    <th class="encabezadoPrincipal">#</th>
		<th class="encabezadoPrincipal">Fecha</th>
		<th class="encabezadoPrincipal">Proveeedor</th>
        <th class="encabezadoPrincipal">Descripcion</th>
        <th class="encabezadoPrincipal">Precio</th>
		<th class="encabezadoPrincipal">Pago</th>
		<th class="encabezadoPrincipal">Saldo</th>
		<th class="encabezadoPrincipal">Acciones</th>             
    </tr>
<?php
$i=1;
foreach ($compras as $compra)
{
	 $sql="select sum(a.cantidad) AS pagado 
	  from pagos_proveedor_detalles AS a
		inner join compras AS b 
		on(a.idCompras=b.idCompras)
	  where a.idCompras='".$compra['idCompras']."'";
 
	 $query = $this->db->query($sql);
	 $row=$query->row();
	 $saldo=$compra['total']-$row->pagado;
	 
	 $estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
	?>

    <tr <?php echo $estilo?>>
	    <td align="left" valign="middle"> <?php print($i); ?> </td>
		<td align="center" valign="middle"><?php print(substr($compra['fechaCompra'],0,11)); ?></td>
		<td align="center" valign="middle">  <?php print($compra['empresa']); ?> </td>
        <td align="center" valign="middle"> <?php print($compra['nombre']); ?> </td>
        <td align="right" valign="middle">  $<?php print(number_format($compra['total'],2)); ?> </td>
        <td id="tdPagado<?php echo $compra['idCompras']?>" align="right" valign="middle">$<?php print(number_format($row->pagado,2))?></td>
		<td id="tdSaldo<?php echo $compra['idCompras']?>" align="right" valign="middle">$<?php print(number_format($saldo,2))?></td>
        <td align="left"   valign="middle"> 
		&nbsp;
         
          <?php
		if($permiso->escribir=='1')
		{ 
			?>
           <a href="<?php echo base_url()."compras/borrarCompra/".$compra['idCompras']?>/proveedores" 
            onclick="return confirm('Borrar la compra de <?php echo $compra['nombre']?> borrara tambien sus pagos, ¿Desea continuar?')">
                <img src="<?php echo base_url()."img/quitar.png"?>" width="22" 
                height="22" hspace="3" title="Borrar compra" border="0"/></a> 
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 
                 <?php
                 $imagen=base_url()."img/success.png";
                 
                 $sql="select recibido from compra_detalles
                 where idCompra='".$compra['idCompras']."'";
                 
                 $query=$this->db->query($sql);
                 
                 foreach($query->result() as $row)
                 {
                     if($row->recibido=="0")
                     {
                       $imagen=base_url()."img/Cerrar.png";
                         break;
                     }
                 }
                 
                 ?>
                 
                <img src="<?php echo $imagen?>" 
                width="22" height="22" hspace="3" title="Recibido" border="0" 
                onclick="recibiendoCompras('<?php echo $compra['idCompras']?>');"style="cursor:pointer;"/>
                
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
             <img id="pagosCompras<?php echo $i?>" onclick="obtenerPagosCompras('<?php echo $compra['idCompras']?>')" 
            src="<?php echo base_url()."img/pagos.png"?>" width="22" height="20" hspace="22" 
            title="Pagos a proveedor" border="0" style="cursor:pointer;"/>
        
            
            &nbsp;&nbsp;&nbsp;&nbsp;
            
            <?php
		}
        ?>
        <a href="<?php echo base_url()."compras/comprasPDF/".$compra['idCompras']?>">
        <img src="<?php echo base_url()."img/pdf.png"?>" 
        width="22" height="22" hspace="3" title="PDF" border="0" style="cursor:pointer;"/></a>
        
        <br />
    	
         <?php
		if($permiso->escribir=='1')
		{
			echo '
			<a>Borrar </a>
			&nbsp;&nbsp; 
			<a>Recibido</a>
			&nbsp;&nbsp; 
			<a>Pagos</a>
        	&nbsp;';
		}
		?>
		&nbsp;
        <a>PDF</a>
		</td>
    </tr>
	<?php
	$i++;
 }//Foreach del Cliente
?>
</table>
<div style="width:90%; margin-bottom:1%;">
 <?php
 print("<ul id='pagination-digg' class='ajax-pag'>");
 print($this->pagination->create_links());
 print("</ul>");
 ?>
</div>

<?php

}//Fin del IF de Clientes
else
{
	?>
		<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de compras</div>
	<?php
}
    ?>
	
<div style="visibility:hidden">

<div id="dialog-Compra" title="Compras">
<div style="width:99%;" id="id_CargandoCompra"></div>

<div id="ErrorCompra" class="ui-state-error" ></div>

<table class="admintable" width="99%;">
<form id="form1" name="form1" method="post" action="">
<tr>
	<td class="key">Concepto:</td>
    
		<td style="position:absolute; width:50%; border:none"> 
	<input type="text" style="width:90%; margin-bottom:8px"  name="inputString" id="inputString" class="cajas"   onkeyup="lookup(this.value);" onblur="fill();"/>
	<input type="hidden"  name="idPro" id="idPro"  />
    
	<div align="left" class="suggestionsBox" id="suggestions" style="display: none; ">
				<!--img src="<?php echo base_url()?>img/upArrow.png" style="position: relative; top: -12px; left: 30px;" /-->
				<div class="suggestionList" id="autoSuggestionsList">
					&nbsp;
				</div>
			</div>
	</td>
</tr>
<tr>
	<td class="key">Proveedor:</td>
	<td>
    <input type="text" name="proveedor" id="proveedor" 
    class="cajas" style="width:95%; border:none; background-color:#FFF;" value="" readonly="readonly"  /> </td>
</tr>
</form>

<tr>
	<td class="key">Precio Unitario:</td>
	<td>
    <input type="text" name="unitario" id="unitario" class="cajas" style="width:80px; border:none; background-color:#FFF"  /> 
	<td>
	</td>
</tr>


<tr>
	<td class="key">Cantidad:</td>
	<td><input type="text" name="cantidad" id="cantidad"  class="cajas" onkeyup="obtenerUnitario()" style="width:80px;" value=""  />  </td>
</tr>

<tr>
	<td class="key">Total:</td>
	<td><input type="text" name="totalCompra" id="totalCompra" onkeyup="obtenerUnitario()" class="cajas" style="width:100px;"  /> </td>
	<td>
	</td>
</tr>


</table>

</div>
</div>
<!-- Productos con sus materiales-->

<div style="visibility:hidden">
<div id="dialog-Recibido" title="Compras:">
<div style="width:99%;" id="id_CargandoRecibido"></div>
<div id="Error-Recibido" class="ui-state-error" ></div>
	<div id="carga"></div>
</div>
</div>

<div style="visibility:hidden">
<div id="dialogoDescuentos" title="Agregar descuento adicional">
<div style="width:99%;" id="cargandoDescuentos"></div>
<div id="ErrorDescuentos" class="ui-state-error" ></div>
<div id="cargarDescuentos"></div>
</div>
</div>

<div id="ventanaProducto" title="Nueva compra por proveedor">
<div id="errorInformacionCliente" class="ui-state-error" ></div>
<div style="width:99%;" id="id_CargandoListaProductos"></div>

<div id="busquedas" style="float:left; width:25%" class="" >
<label>Buscar:</label> 
<input type="text" class="cajas" style="width:200px" id="buscarNombre" 
name="buscarNombre"  onkeyup="listaProductosServicios()"/>
</div>

<div id="listaProveedores" style="float:left; width:48">
<label>Proveedor:</label> 

<select class="cajas" style="width:auto" id="proveedores" onchange="confirmarProveedor()">
<option value="0">Todos</option>
<?php
foreach($proveedores as $row)
{
	print('<option value="'.$row->id.'">'.$row->empresa.'</option>');
}
?>
</select>
</div>

<div id="listaOpciones" style="float:right; width:20%">
&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
<img src="<?php echo base_url()?>img/materiales.png" title="Agregar materia prima" 
style="width:26px; height:26px; cursor: pointer" id="addMaterial" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<img src="<?php echo base_url()?>img/agregarProveedor.png" title="Agregar proveedor" 
style="width:30px; height:30px; cursor: pointer" id="agregarProveedor" />
<br />

<a>Materia prima </a>&nbsp;
<a>Proveedores</a>
</div>


<div id="productosKit" style="float:left; vertical-align:top; width:100%;" >
</div> 
<table class="admintable" style="width:99%">
<tr>
<th style="font-size:16px" colspan="5">
<input type="hidden" id="paginaActiva" value="0" />
<input type="hidden" id="paginaActivada" value="compras" />
Descripcion de la compra &nbsp; | 
&nbsp; Nombre <input type="text" id="nombreKit" class="cajas" style="width:300px" />  
&nbsp; | &nbsp;
Precio: 
$
<input type="text" id="kitTotal" style="width:100px; border:none" 
readonly="readonly" name="kitTotal" class="cajas" value="0" /></th>
</tr>
</table>
<table class="admintable" style="width:99%" id="armarKit">
<tr>
    <th>#</th>
    <th>Nombre</th>
    <th>Precio unitario</th>
    <th>Cantidad</th>
    <th>Total</th>
</tr>
</table>
</div>

<!--Es para agregar materia prima -->
<div id="dialog-Materiales" title="Materia prima">
<div style="width:99%;" id="id_CargandoMM"></div>
<div id="ErrorEnMaterial" class="ui-state-error" ></div>

<table class="admintable" width="99%;">
<td class="key">Concepto:</td>
<td>
<input type="text" name="T2" id="T2" class="cajas" style="width:160px;" value=""  /> 
</td>
</tr>

<tr>
<td class="key">Unidad:</td>
<td>
<select name="UNII" id="UNII" class="cajas">
  <?php 
	if(count($unidades) > 0)
	{ 
		foreach($unidades as $unidad) 
		{ 
			echo '<option value="'.$unidad['idUnidad'].'">'.$unidad['descripcion'].'</option>';
		} 
	} 
		 ?>
</select>
 </td>
</tr>

<tr>
	<td class="key">Costo:</td>
	<td><input name="T4" style="width:160px;" type="text" class="cajas" id="T4" value="" /> </td>
</tr>
<tr>
	<td class="key">Cantidad Minima:</td>
	<td><input name="CMINIMA" style="width:160px;" type="text" class="cajas" id="CMINIMA" value="" /> </td>
</tr>

</table>
</div>


    
<div id="ventanaPagos" title="Pagos a proveedores">
<div id="errorPagosProveedores" class="ui-state-error" ></div>
<div id="cargandoPagos"></div>
<div id="cargarPagos"></div>
</div>

</div>
<!-- Termina -->
</div>

