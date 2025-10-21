
<script type="text/javascript" src="<?php echo base_url()?>js/clientes/seguimiento/seguimiento.js"></script>
<script>
    function busqueda()
    {
        div = document.getElementById('bus_id');
        filtro=div.value;
        
        if(filtro=='')
        {
            showDialog('ERROR','Escriba el nombre del cliente a buscar','error',2);
            return;
        }
        direccion="<?php echo base_url()?>clientes/prebusqueda/"+filtro;
        
        window.location.href=direccion;
    }
    
    function busquedaZona()
    {
        div = document.getElementById('zonas');
        filtro=div.value;
        
        direccion="<?php echo base_url()?>clientes/busquedaZona/"+filtro;
        
        window.location.href=direccion;
    }
    
    function copiarDireccion()
    {
        if(document.getElementById('chkConfirmar').checked==true)
        {
            $('#direccionEnvio').val($('#direccion').val());
            $('#ciudadEnvio').val($('#localidad').val());
            $('#codigoPostalEnvio').val($('#codigoPostal').val());
            $('#estadoEnvio').val($('#estado').val());
        }
        else
        {
            $('#direccionEnvio').val('');
            $('#ciudadEnvio').val('');
            $('#codigoPostalEnvio').val('');
            $('#estadoEnvio').val('');
        }
    }
    
    function esProveedor()
    {
        if(document.getElementById('proveedorcito').checked==true)
        {
            $('#proveedoraso').val('si');
        }
        else
        {
            $('#proveedoraso').val('no');
        }
    }
</script>
<div class="derecha">
<div class="submenu">
<div class="toolbar" id="toolbar" >
  <table class="toolbar" width="100%">
  	<tr>
     	<td class="seccion">
    	Clientes
   	    </td>
    </tr>
   <tr>
   <?php
   if($permiso->escribir=='1')
   { 
	   ?>
		  <td class="button" width="10%">
			<a class="toolbar" id="agregarCliente">
			<span class="icon-option" title="Añadir cliente">
			  <img src="<?php print(base_url()); ?>img/nuevo.png" width="30px;" height="30px;" 
              title="Añadir nuevo prospecto" alt="Añadir nuevo cliente" /></span>
			 Añadir prospecto        
			 </a>      
			 </td> 
		  <td  width="10%" >
		  <a href="<?php echo base_url()?>ficha/cotizacion/zz" class="toolbar">
		  <span class="icon-option" 
		  title="Añadir cliente"><img src="<?php print(base_url()); ?>img/remision.png" width="30px;" 
		  height="30px;" title="Cotizaci&oacute;n" />
		  </span>
		  Cotización</a>
		  </td>
      
      <?php
       }
    ?>
	  <td width="80%" align="left" valign="middle" style="padding-right:100px">
        <input type="text"  name="txtBusquedas" id="txtBusquedas" class="busquedas" placeholder="Buscar cliente"   
        onkeyup="buscarDato(this.value,'clientes');" onblur="datoEncontrado();" style="width:300px;"/>
        <div align="left" class="suggestionsBox" id="listaInformacion" style="display: none; position:absolute; margin-left:43%; width:300px">
        		<img src="<?php echo base_url()?>img/upArrow.png" style="position: relative; top: -12px; left: 30px;" />
        	<div class="suggestionList" id="autoListaInformacion">
       		 &nbsp;
        	</div>
        </div>
         <?php
		if($this->session->userdata('idClienteBusqueda')!="")
		{
			echo 
			'<br />
			 <a href="'.base_url().'clientes/prebusqueda/nada" class="toolbar" style="margin-left:350px">
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
       
<div class="listproyectos">
<?php

if(!empty($clientes))
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
        <th class="encabezadoPrincipal" align="center" >ID</th>
        <th class="encabezadoPrincipal" align="left">Contacto</th>
        <th class="encabezadoPrincipal" align="left">
        Empresa
         <?php
		  if($this->session->userdata('criterioClientes')=='a')
		  {
			echo '<a href="'.base_url().'clientes/ordenamiento/z">
			<img src="'.base_url().'img/ocultar.png" width="17" /></a>';	
		  }
		  else
		  {
			  echo '<a href="'.base_url().'clientes/ordenamiento/a">
			<img src="'.base_url().'img/mostrar.png" width="17" /></a>';
		  }
	  ?>
        </th>
        <th class="encabezadoPrincipal" >Tipo</th>
		<th class="encabezadoPrincipal" >Dirección</th>
        <th class="encabezadoPrincipal" >Teléfono</th>
        <th class="encabezadoPrincipal" width="25%">Acciones</th>
    </tr>

<?php
$i=1;
foreach ($clientes as $row)
{
	$estilo=$i%2>0?'class="sinSombra"':'class="sombreado"';
	
	$idcli=$row->id;
	
	$contacto='Sin contactos';
	
	$ww=$this->db->query("select nombre from clientes_contactos where id_cliente = '$idcli' order by fechadd desc limit 0,1");
	
	foreach ($ww->result() as $con)
	{
		$contacto=$con->nombre;
	}
	?>
		<tr <?php echo $estilo?>>
			<td align="left"> <?php echo $i?> </td>
			<td align="left"> <?php print($contacto) ?> </td>
			<td align="left"> <?php echo '<a href="'.base_url().'clientes/busquedaClienteFicha/'.$row->id.'">'.$row->empresa.'</a>';?> </td>
			<td align="left"> 
			<?php
			if($row->prospecto=="1")
			{
				echo 'Prospecto';
			}
			else
			{
				echo 'Cliente';
			}
			?> 
			</td>
			<td align="left"> <?php echo $row->direccion ?> </td>
			<td align="left">  <?php echo $row->telefono ?> </td>
			<td align="left" valign="middle">
			
			 <?php
		   if($permiso->escribir=='1')
		   { 
		   ?>
				&nbsp;&nbsp;
				<img style="cursor:pointer" src="<?php echo base_url()?>img/edit.png" width="22" height="22" 
				id="editarCliente<?php echo $i?>" border="0" title="Editar cliente" 
				alt="Editar cliente" onclick="obtenerCliente('<?php echo $row->id?>')" />
				
				 &nbsp; &nbsp;&nbsp;
				<a onclick="return confirm('¿Realmente desea borrar el cliente seleccionado?')" href="<?php echo base_url()?>clientes/borrarCliente/<?php echo $row->id?>">
			   <img src="<?php echo base_url()?>img/borrar.png" width="22" height="22" 
			   border="0" title="Borrar Cliente" alt="Borrar Cliente" />
			   </a>
			   &nbsp;&nbsp;
			   <?php
		   }
		   ?>
		   &nbsp;&nbsp;
			<a href="<?php echo base_url()?>facturacion/facturasCliente/<?php echo $row->id?>">
		   <img src="<?php echo base_url()?>img/pdf.png" width="22" height="22" 
		   border="0" title="Facturas" />
		   </a>
		   
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img src="<?php echo base_url()?>img/clientes.png" id="fichaTecnica<?php echo $i?>"
		 	width="22" height="22" border="0" title="Ficha tecnica" 
		 	style="cursor:pointer" onclick="fichaTecnicaCliente('<?php echo $row->id?>')" />
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img src="<?php echo base_url()?>img/seguimiento.png" id="btnSeguimiento<?php echo $i?>"
		 	width="22" height="22" border="0" title="Seguimiento" 
		 	style="cursor:pointer" onclick="obtenerSeguimientoCliente('<?php echo $row->id?>')" />
            
		   	<br />
			&nbsp;
		 <?php
	   if($permiso->escribir=='1')
	   { 
		   ?>
			<a>Editar</a>&nbsp;&nbsp;
			<a href="<?php echo base_url()?>clientes/borrarCliente/<?php echo $row->id?>">Borrar</a>&nbsp;
			<?php
	   }
		?>
        
		<a>Facturas</a>&nbsp;
		<a>Ficha</a>&nbsp;
        <a>Seguimiento</a>
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
else{
?>

<div class="Error_validar" style=" width:95%;">No hay registros de clientes</div>
    
 <?php
   }
 ?>
 
<div id="ventanaClientes" title="Añadir cliente">
<div style="width:99%;" id="cargandoClientes"></div>
<div id="ErrorClientes" class="ui-state-error" ></div>
    <table class="admintable" width="99%;">
     <tr>
    	<td class="key">¿Es proveedor?</td>
    	<td>
        Confirmar &nbsp;
        <input type="checkbox" onchange="esProveedor()" id="proveedorcito" name="proveedorcito"/>
        <input type="hidden" id="proveedoraso" name="proveedoraso" value="no" />
        </td>
    </tr>
    
     <tr>
    	<td class="key">¿Es cliente?</td>
    	<td>
        Confirmar &nbsp;
        <input type="checkbox" id="esCliente" name="esCliente"/>
        <input type="hidden" id="proveedorasos" name="proveedorasos" value="no" />
        </td>
    </tr>
    
    <tr>
    	<td class="key">Empresa</td>
    	<td>
        <textarea class="TextArea" style="width:200px" name="empresa" id="empresa"></textarea>
        
        </td>
    </tr>
    <tr >
    	<td class="key">Tipo de precio</td>
    	<td>
        	<select name="txtPrecioCliente" id="txtPrecioCliente" class="cajas" style="width:200px">
            	<option value="1">A</option>
                <option value="2">B</option>
                <option value="3">C</option>
                <option value="4">D</option>
                <option value="5">E</option>
                
            </select>
        
        </td>
    </tr>
    <tr>
    	<td class="key" align="right"><?php echo $this->session->userdata('identificador')?>:</td>
    	<td>
    	<input type="hidden" id="txtIdentificador" value="<?php echo $this->session->userdata('identificador')?>" />
     <select name="zona" id="zona" class="cajasSelect" style="width:200px">
    	<option value="0">Seleccione</option>
	   <?php 
	   if(count($zonas) > 0)
	   { 
			foreach($zonas as $zona) 
		   { 
		    ?>
			  <option value="<?php echo $zona['idZona'];?>"><?php echo $zona['descripcion'];?></option>
			<?php 
			} 
        } 
		?>
   </select> 
	</td>
   </tr>
   	 <tr>
    	<td class="key">RFC</td>
    	<td>
        <input type="text" class="cajas" name="rfc" id="rfc" style="width:200px" />
        </td>
    </tr>
     <tr>
    	<td class="key">Calle</td>
    	<td>
        	<textarea class="TextArea" name="direccion" id="direccion" style="width:200px"></textarea>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Número</td>
    	<td>
        <input type="text" class="cajas" name="numero" id="numero" style="width:200px"/>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Colonia</td>
    	<td>
        <input type="text" class="cajas" name="colonia" id="colonia" style="width:200px"/>
        </td>
    </tr>
    
    <tr>
    	<td class="key">Localidad</td>
    	<td>
        <input type="text" class="cajas" name="localidad" id="localidad" style="width:200px" />
        </td>
    </tr>
    
     <tr>
    	<td class="key">Municipio</td>
    	<td>
        <input type="text" class="cajas" name="txtMunicipio" id="txtMunicipio" style="width:200px" />
        </td>
    </tr>
    
     <tr>
    	<td class="key">Estado</td>
    	<td>
        <input type="text" class="cajas" name="estado" id="estado" style="width:200px"/>
        </td>
    </tr>
     <tr>
    	<td class="key">Código Postal</td>
    	<td>
        <input type="text" class="cajas" name="codigoPostal" id="codigoPostal" style="width:200px"/>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Teléfono</td>
    	<td>
        <input type="text" class="cajas" name="telefono" id="telefono" style="width:200px"/>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Fax</td>
    	<td>
        <input type="text" class="cajas" name="fax" id="fax" style="width:200px"/>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Email</td>
    	<td>
        <input type="text" class="cajas" name="email" id="email" style="width:200px"/>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Pagina web</td>
    	<td>
        <input type="text" class="cajas" name="pagina" id="pagina" style="width:200px"/>
        </td>
    </tr>
     <tr>
    	<td class="key">Días de crédito</td>
    	<td>
        <input type="text" class="cajas" name="limiteCredito" value="0" id="limiteCredito" style="width:200px"/>
        </td>
    </tr>
    <!--tr>
    	<td class="key">Vendedor</td>
    	<td>
        <input type="text"  name="txtUsuarios" id="txtUsuarios" class="cajas"   
        onkeyup="buscarDato(this.value,'usuarios');" onblur="datoEncontrado();" style="width:200px;"/>
        
        <div align="left" class="suggestionsBox" id="listaInformacionBodega" style="display: none; position:absolute; margin-left:30%">
        		<img src="<?php echo base_url()?>img/upArrow.png" style="position: relative; top: -12px; left: 30px;" />
        	<div class="suggestionList" id="autoListaInformacionBodega">
       		 &nbsp;
        	</div>
        </div>
        
            <input type="hidden" class="cajas" name="nombreVendedor" id="nombreVendedor" style="width:200px"/>
        </td>
    </tr>
     <tr>
    	<td class="key">Límite de crédito</td>
    	<td>
        <input type="text" class="cajas" name="limiteCredito" id="limiteCredito" style="width:200px"/>
        </td>
    </tr>
      <tr>
    	<td class="key">Plazos</td>
    	<td>
        <input type="text" class="cajas" name="plazos" id="plazos" style="width:200px"/>
        </td>
    </tr-->
    
    <tr>
    	<th colspan="2" align="center" class="key">Dirección de envio</th>
     </tr>
     
     <tr>
    	<td class="key">¿Es la misma que la anterior?</td>
    	<td>
        Confirmar
        <input type="checkbox"  id="chkConfirmar" onchange="copiarDireccion()" />
        </td>
    </tr>
    
      <tr>
    	<td class="key">Dirección</td>
    	<td>
        <textarea class="TextArea" name="direccionEnvio" id="direccionEnvio" style="width:200px"></textarea>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Código postal</td>
    	<td>
        <input type="text" class="cajas" name="codigoPostalEnvio" id="codigoPostalEnvio" style="width:200px"/>
        </td>
    </tr>
    
     <tr>
    	<td class="key">Estado</td>
    	<td>
        <input type="text" class="cajas" name="estadoEnvio" id="estadoEnvio" style="width:200px"/>
        </td>
    </tr>
     <tr>
    	<td class="key">Ciudad</td>
    	<td>
        <input type="text" class="cajas" name="ciudadEnvio" id="ciudadEnvio" style="width:200px"/>
        </td>
    </tr>
    </table>
  </div>

<div id="ventanaFichaCliente" title="Ficha técnica del cliente:">
<div id="errorInformacionCliente" class="ui-state-error" ></div>
	<div id="cargarFichaCliente"></div>
	</div>
</div>


<div id="ventanaEditarClientes" title="Editar cliente">
<div style="width:99%;" id="cargandoEditarClientes"></div>
<div id="ErrorEditarClientes" class="ui-state-error" ></div>
<div id="cargarClientes"></div>
</div>

<div id="ventanaSeguimientoClientes" title="Seguimiento cliente">
<div style="width:99%;" id="siguiendoClientes"></div>
<div id="errorSeguimientoClientes" class="ui-state-error" ></div>
<div id="cargarSeguimiento"></div>
</div>

<div id="ventanaDetallesSeguimiento" title="Detalles de seguimiento">
<div id="errorDetallesSeguimiento" class="ui-state-error" ></div>
<div id="detallesSeguimiento"></div>
</div>

<div id="ventanaSeguimiento" title="Seguimiento">
<div style="width:99%;" id="cargandoSeguimiento"></div>
<div id="errorSeguimiento" class="ui-state-error" ></div>
<table class="admintable" width="99%;">
    <tr>
    <td class="key">Fecha:</td>
    <td><input type="text" name="FechaDia" id="FechaDia" class="cajas" style="width:160px;" value=""  /> </td>
    </tr>
    
    <tr>
    <td class="key">Comentarios:</td>
    <td>
    	<textarea id="txtComentarios" name="txtComentarios" rows="3" style="width:300px"class="TextArea"></textarea>
    </td>
    </tr>
</table>
</div>

<div id="ventanaConfirmarSeguimiento" title="Confirmar seguimiento">
<div style="width:99%;" id="cargandoConfirmacion"></div>
<div id="errorConfirmacion" class="ui-state-error" ></div>
<table class="admintable" width="99%;">
    <tr>
        <td class="key">Observaciones:</td>
        <td>
            <textarea id="txtObservaciones" name="txtObservaciones" rows="3" style="width:300px" class="TextArea"></textarea>
        </td>
    </tr>
</table>
</div>

</div>

