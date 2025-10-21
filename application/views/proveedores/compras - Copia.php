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
		
		direccion="http://"+base_url+"compras/prebusquedaFecha/"+$('#FechaDia').val();
		
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
<div class="barra">Compras del proveedor <?php echo $proveedor->empresa?></div>

<div class="submenu">
<div class="toolbar" id="toolbar">
 <table class="toolbar" style="width:99%">	
    <tr>
     <!--td align="center" valign="middle" style="width:10%" >
        <?php print('<img src="'.base_url().'img/compras.png" width="30px;" height="30px;" class="productos" id="productos" style="cursor:pointer;" title="Nueva compra">'); ?>  <br />
		Nueva compra		 </td-->
        
      <td align="left" valign="middle" style="width:40%">
        Seleccione fecha:  &nbsp;&nbsp; 
       
        <input type="text" class="cajas" style="width:100px" id="FechaDia" />
        <?php print('<img src="'.base_url().'img/search_32.png" width="30px;" height="30px;" id="buscame" title="Buscar compras" style="cursor:pointer;" onclick="busquedaFecha()">'); ?>      
        </td>

        <?php 
		if($this->session->userdata('fechaCompras')!="" or $this->session->userdata('idProveedor')!="" )
		{
			?>
            <td style="width:15%">
             <a href="<?php echo base_url()?>compras/prebusquedaFecha/nada"> Borrar busqueda
             
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

<div id="RESPUESTACLIENTE" style="width:99%;float:left; margin-left:1.5%;">
    
<table class="admintable" width="100%">
    <tr>
	    <th style="-moz-border-radius-bottomright: 0px;-moz-border-radius-topright: 0px;">#</th>
		<th style="-moz-border-radius: 0px;">Fecha</th>
		<th style="-moz-border-radius: 0px;">Proveeedor</th>
        <th style="-moz-border-radius: 0px;">Descripcion</th>
        <th style="-moz-border-radius: 0px;">Precio</th>
		<th style="-moz-border-radius: 0px;">Pago</th>
		<th style="-moz-border-radius: 0px;">Saldo</th>
		<th  style="width:25%; -moz-border-radius-bottomleft: 0px;-moz-border-radius-topleft: 0px">Acciones</th>             
    </tr>
<?php
$i=1;
foreach ($compras as $compra)
{
$image_Editar = array(
       'src' => base_url().'img/edit.png',
       'alt' => 'Editar producto',
       'class' => '',
       'width' => '16',
       'height' => '16',
       'title' => 'Editar producto'
  );

$Link_editar=anchor(base_url()."compras/editar/".$compra['idCompras'],img($image_Editar),'Editar producto');

$image_Borrar = array(
       'src' => base_url().'img/borrar.png',
       'alt' => 'Borrar producto',
       'class' => '',
       'width' => '16',
       'height' => '16',
       'title' => 'Borrar producto'
	  // 'onClick'=>'return confirm(Esta seguro que desea eliminar el producto)'
  );

$Link_borrar=anchor(base_url()."compras/borrarProducto/".$compra['idCompras'],img($image_Borrar),'Borrar producto');
?>

	<?php 
		 $sql="select sum(a.cantidad) AS pagado 
		  from pagos_proveedor_detalles AS a
			inner join compras AS b 
			on(a.idCompras=b.idCompras)
	 	  where a.idCompras='".$compra['idCompras']."'";
	 
		 $query = $this->db->query($sql);
		 $row=$query->row();
		 $saldo=$compra['total']-$row->pagado;
	?>

    <tr>
	    <td align="left" valign="middle"> <?php print($i); ?> </td>
		<td align="center" valign="middle"><?php print(substr($compra['fechaCompra'],0,11)); ?></td>
		<td align="center" valign="middle">  <?php print($compra['empresa']); ?> </td>
        <td align="center" valign="middle"> <?php print($compra['nombre']); ?> </td>
		<!--td align="right" valign="middle"> <?php //print($compra['cantidad']); ?>  </td-->
		<!--td align="center" valign="middle"> <?php #print($Inventario['descripcion']); ?> </td-->
        <td align="right" valign="middle">  $<?php print(number_format($compra['total'],2)); ?> </td>
        <td align="right" valign="middle">$<?php print(number_format($row->pagado,2))?></td>
		<td align="right" valign="middle">$<?php print(number_format($saldo,2))?></td>
        <td align="left"   valign="middle"> 
		<?php 
		//print("&nbsp;&nbsp;".$Link_editar) 
		
		?>
			  &nbsp;
              
	    <a href="<?php echo base_url()."compras/borrarCompra/".$compra['idCompras']?>" 
        onclick="return confirm('Esta seguro que desea eliminar la compra de: <?php echo $compra['nombre']?>')">
            <img src="<?php echo base_url()."img/quitar.png"?>" width="22" 
            height="22" hspace="3" title="Borrar compra" border="0"/></a> 
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img src="<?php echo base_url()."img/success.png"?>" 
    width="22" height="22" hspace="3" title="Recibido" border="0" 
    onclick="recibiendoCompras('<?php echo $compra['idCompras']?>');"style="cursor:pointer;"/>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    <a href="<?php echo base_url()."compras/pagos/".$compra['idCompras']?>">
    <img id="<?php echo $compra['idCompras']?>"src="<?php echo base_url()."img/pagos.png"?>" 
    width="22" height="22" hspace="3" title="Pagos a proveedor" border="0" style="cursor:pointer;"/></a>
	
    &nbsp;&nbsp;&nbsp;&nbsp;
    <a href="<?php echo base_url()."compras/comprasPDF/".$compra['idCompras']?>">
    <img src="<?php echo base_url()."img/pdf.png"?>" 
    width="22" height="22" hspace="3" title="PDF" border="0" style="cursor:pointer;"/></a>
    
   	<br />
    	
		<a href="<?php echo base_url()."compras/borrarCompra/".$compra['idCompras']?>" 
        onclick="return confirm('Esta seguro que desea eliminar la orden de: <?php echo $compra['nombre']?>')">Borrar </a>
		&nbsp;&nbsp; 
		<a onclick="recibiendoCompras('<?php echo $compra['idCompras']?>'); ">Recibido</a>
		&nbsp;&nbsp; 
		<a href="<?php echo base_url()."compras/pagos/".$compra['idCompras']?>">Pagos</a>
        &nbsp;&nbsp;
        <a href="<?php echo base_url()."compras/comprasPDF/".$compra['idCompras']?>">PDF</a>
</td>
		
    </tr>
	<?php
	$i++;
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
<!--tr>
	<td class="key">ID</td>
	<td><input type="text" name="IDCOM" id="IDCOM" class="cajas" style="width:160px;" value=""  /> </td>
</tr-->
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

<!--tr>
	<td class="key">Unidad:</td>
	<td><input type="text" name="UNI" id="UNI" class="cajas" style="width:160px; border:none; background-color:#FFF;" readonly="readonly"  /> </td>
	<td>
	</td>
</tr-->

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
<div id="dialog-pagosGlobales" title="Pagos por proveedor:">
<div style="width:99%;" id="id_CargandoPagosProveedor"></div>
<div id="Error-pagosGlobales" class="ui-state-error" ></div>

<table class="admintable" width="99%">
<tr>
    <td class="key">Seleccionar forma de pago:</td>
    <td>
     <select id="TipoPago" name="TipoPago" class="cajas" style="width:auto;" onchange="mostrarDatos()">
            <option value="1" >Efectivo</option>
            <option value="2" >Cheque</option>
            <option value="3" >Transferencia</option>
        </select>   
         </td>
</tr>

<tr style="display:none;" id="mostrarCheques">
    <td class="key">Numero cheque:</td>
    <td>
    <input type="text" class="cajas" id="numeroCheque" name="numeroCheque" />    </td>
</tr>

<tr style="display:none;" id="mostrarTransferencia">
    <td class="key">Numero Transferencia:</td>
    <td>
    <input type="text" class="cajas" id="numeroTransferencia" name="numeroTransferencia" />    </td>
</tr>
<tr>
    <td class="key">Bancos:</td>
    <td> 
     <select id="listaBancos" name="listaBancos" class="cajas" style="width:auto;" onchange="buscarCuentas()" >
        <option value="0">Seleccione</option>
        <?php
		   foreach($Bancos as $Banco)
		   {
		   	   print('<option value="'.$Banco['id'].'" >'.$Banco['nombre'].'</option>');
		   }//Foreach
         ?>
        </select>
        </td>
        </tr>
        <tr>
        <td class="key">Cuentas</td>
     <td id="cargarCuenta">
      <select id="cuentasBanco" name="cuentasBanco" class="cajas" style="width:auto;" >
         <option value="0">Seleccione</option>
        </select>
	</td>     

</tr>
</table>
	<div id="InformacionProveedores"></div>

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
<div style="width:99%;" id="id_CargandoListaProductos"></div>

<div id="busquedas" style="float:left; width:25%" class="" >
<label>Buscar:</label> 
<input type="text" class="cajas" style="width:200px" id="buscarNombre" 
name="buscarNombre"  onkeyup="listaProductosServicios()"/>
</div>

<div id="listaProveedores" style="float:left; width:48">
<label>Proveedor:</label> 

<select class="cajas" style="width:auto" id="proveedores" onchange="confirmarProveedor()">
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

<div id="ventanaProveedores" title="Añadir proveedor">
<div style="width:99%;" id="cargandoProveedores"></div>
<div id="ErrorProveedores" class="ui-state-error" ></div>
    <table class="admintable" width="99%;">
    <tr>
    	<td class="key">Empresa</td>
    	<td>
        <input type="text" class="cajas" name="empresa" id="empresa" style="width:90%"/>
        </td>
    </tr>
    
      <tr>
    	<td class="key">Domicilio</td>
    	<td>
        <input type="text" class="cajas" name="domicilio" id="domicilio" style="width:70%"/>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Telefono</td>
    	<td>
        <input type="text" class="cajas" name="telefono" id="telefono" style="width:25%"/>
        </td>
    </tr>

     <tr>
    	<td class="key">Email</td>
    	<td>
        <input type="text" class="cajas" name="email" id="email" style="width:40%"/>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Pais</td>
    	<td>
        <input type="text" class="cajas" name="pais" id="pais" style="width:40%"/>
        </td>
    </tr>
    
    <tr>
    	<td class="key">Estado</td>
    	<td>
        <input type="text" class="cajas" name="estado" id="estado" style="width:40%" />
        </td>
    </tr>
    
      <tr>
    	<td class="key">RFC</td>
    	<td>
        <input type="text" class="cajas" name="rfc" id="rfc" style="width:30%"/>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Pagina web</td>
    	<td>
        <input type="text" class="cajas" name="pagina" id="pagina" style="width:40%"/>
        </td>
    </tr>
    </table>
    </div>
</div>
<!-- Termina -->
</div>
