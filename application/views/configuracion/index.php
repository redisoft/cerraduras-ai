<script>
function comprobarArchivo()
{
	cadena=	$('#userfile').val();
	b=0;
	extension="";
	
	for(i=0;i<cadena.length;i++)
	{
		if(b==1)
		{
			extension+=cadena[i];
		}

		if(cadena[i]==".")
		{
			b=1;
		}
	}
	
	if(extension!='png' && extension!='jpg' && extension!='gif' && extension!='bmp')
	{
		alert('Solo se permiten archivos de imagen');
		$('#userfile').val('');
	}
}

</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
    <!--<div class="seccionDiv">
    Sistema
    </div>-->

   <table class="toolbar" width="100%">
        <tr>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/"); ?>" class="escalaGrisesConfiguracion" > <span class="icon-option" title="Configuración de Sistema"> <img src="<?php print(base_url()); ?>img/configure.png" width="30" height="30" border="0" title="Configuración de Sistema" /> </span> Sistema </a> </td>
            
			<?php
            if($this->session->userdata('idLicencia')=='1')
			{
				?>
                <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/listauser"); ?>" > <span class="icon-option" title="Lista de usuarios"> <img src="<?php print(base_url()); ?>img/clientes.png"   width="30" height="30" title="Lista de usuarios" /></span> Usuarios </a> </td>
                <?php
			}
			?>
			
            
			<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/roles"); ?>" > <span class="icon-option" title="Añadir nuevo usuario"> <img src="<?php print(base_url()); ?>img/roles.png"   width="30" height="30" title="Roles" /></span> Roles </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos"); ?>" > <span class="icon-option" title="Banco"> <img src="<?php print(base_url()); ?>img/banco.png"   width="30" height="30" title="Banco" /></span> Banco </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."bancos/cuentas"); ?>" > <span class="icon-option" title="Cuentas"> <img src="<?php print(base_url()); ?>img/dinero.png"   width="30" height="30" title="Cuentas" /></span> Cuentas </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/unidades"); ?>" > <span class="icon-option" title="Unidades"> <img src="<?php print(base_url()); ?>img/bascula.png"   width="30" height="30" title="Unidades" /></span> Unida. </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/zonas"); ?>" > <span class="icon-option" > <img src="<?php print(base_url()); ?>img/zona.png" width="30" height="30" title="<?php echo $this->session->userdata('identificador')?>" /></span> <?php echo $this->session->userdata('identificador')?> </a> </td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/facturacion"); ?>"><span class="icon-option" title="FEL"><img src="<?php print(base_url()); ?>img/fel.png"  width="30" height="26" title="Facturación electronica"/></span>Emiso.</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/impuestos"); ?>" ><span class="icon-option" title="Impuestos"><img src="<?php print(base_url()); ?>img/impuestos.png"  width="30" height="30" title="Impuestos"/></span>Impuestos</a></td>
            
            <?php
            if(sistemaActivo=='IEXE')
			{
				?>
                <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/programasComisiones"); ?>" ><span class="icon-option" title="Comisiones"><img src="<?php print(base_url()); ?>img/comisiones.png"  style="max-width: 30px; max-height: 30px" title="Comisiones"  /></span>Comisiones</a></td>
                <?php
			}
			?>
            
            
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/procesos"); ?>" ><span class="icon-option" title="Estilo"><img src="<?php print(base_url()); ?>img/produccion.png"  width="30" height="34" title="Procesos"  /></span>Procesos</a></td>
			<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/divisas"); ?>" ><span class="icon-option" title="Divisas"><img src="<?php print(base_url()); ?>img/divisas.jpg"  width="30" height="34" title="Divisas"  /></span>Divisas</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/catalogosContables"); ?>" ><span class="icon-option" title="Catálogos contables"><img src="<?php print(base_url()); ?>img/engranes.png"  width="30" height="34" title="Catálogos contables"  /></span>Cat. Cont.</a></td> 
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/lineas"); ?>" ><span class="icon-option" title="Líneas"><img src="<?php print(base_url()); ?>img/lineas.png"  width="30" height="34" title="Lineas"  /></span>Líneas</a></td>      
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/servicios"); ?>" ><span class="icon-option" title="Servicios"><img src="<?php print(base_url()); ?>img/servicios.png"  width="30" height="34" title="Servicios"  /></span>Servi.</a></td>
            <td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/formasPago"); ?>" ><span class="icon-option" title="Formas de pago"><img src="<?php print(base_url()); ?>img/formas.png"  width="30" height="34" /></span>F. Pago</a></td>
            <!--td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."tiendas"); ?>" ><span class="icon-option" title="Tiendas"><img src="<?php print(base_url()); ?>img/tienda.png"  width="30" height="34" title="Tiendas"  /></span>Tiendas</a></td-->
			<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."configuracion/categorias"); ?>" ><span class="icon-option" title="Categorías"><img src="<?php print(base_url()); ?>img/categorias.png"  width="30" height="34" title="Categorías"  /></span>Categorías</a></td>
			<td width="5%" align="center" valign="middle"><a href="<?php print(base_url()."estaciones"); ?>" ><span class="icon-option" title="Estaciones"><img src="<?php print(base_url()); ?>img/estaciones.png"  width="30" height="34" title="Estaciones"  /></span>Estaciones</a></td>      
			
        </tr>
    </table>
</div>
</div>

<div class="listproyectos" >
<form  name="form" id="form" enctype="multipart/form-data" action="<?php echo base_url().'configuracion/guardar'?>" method="post">
	<table class="admintable" width="100%;">
        <tr>
        	<th colspan="2" class="encabezadoPrincipal">Uso de disco</th>
        </tr>
        <tr>
            <td class="key">Detalles:</td>
            <td>
            	Comprado: <a><?php echo number_format($configuracion->cuota,2) ?> MB</a><br />
                
            	Archivos: <a><?php echo number_format($archivos+$imagenes,2) ?> MB</a><br />
            	Base de datos: <a><?php echo number_format($cuatoBase,2) ?> MB</a><br />
            </td>
        </tr>
        
        <tr>
        	<th colspan="2" class="encabezadoPrincipal">Ventas</th>
        </tr>
        
        <tr>
            <td class="key">Orden productos:</td>
            <td>
                <select id="selectOrdenProductos" name="selectOrdenProductos"  class="cajas">
                	<option value="stock">Stock</option>
                    <option <?php echo $configuracion->ordenProductos=='vendidos'?'selected="selected"':''?> value="vendidos">Vendidos</option>
                </select>
            </td>
        </tr>
        
        
        <tbody <?php echo sistemaActivo=='IEXE'?'':'style="display:none"'?>>
            <tr>
                <th colspan="2" class="encabezadoPrincipal">Variables</th>
            </tr>
            
            <tr>
                <td class="key">Variable A:</td>
                <td>
                    <input type="text" class="cajas" style="width:15%;" id="txtVariableA" name="txtVariableA" value="<?php echo $configuracion->variable1 ?>" /> 
                </td>
            </tr>
            
            <tr>
                <td class="key">Variable B:</td>
                <td>
                    <input type="text" class="cajas" style="width:15%;" id="txtVariableB" name="txtVariableB" value="<?php echo $configuracion->variable2 ?>" /> 
                </td>
            </tr>
            
            <tr>
                <td class="key">Variable C:</td>
                <td>
                    <input type="text" class="cajas" style="width:15%;" id="txtVariableC" name="txtVariableC" value="<?php echo $configuracion->variable3 ?>" /> 
                </td>
            </tr>
            
            <tr>
                <td class="key">Variable D:</td>
                <td>
                    <input type="text" class="cajas" style="width:15%;" id="txtVariableD" name="txtVariableD" value="<?php echo $configuracion->variable4 ?>" /> 
                </td>
            </tr>
            
        </tbody>
 	
    <tr>
        	<th colspan="2" class="encabezadoPrincipal">Datos empresa</th>
        </tr>
        
 <tr style="display:none">
	<input name="id" type="hidden" value="<?php echo $configuracion->id;?>" />
     <td class="key">Admin. Email:</td>
     <td>
     	<textarea rows="2" class="TextArea" id="T1" name="T1"><?php echo $configuracion->admin ?></textarea>
     </td>
</tr>

<!--<tr>
    <td class="key">Iva 1:</td>
    <td>
        <input type="text" class="cajas" style="width:8%;" id="T6" name="T6" value="<?php echo $configuracion->iva ?>" /> 
    </td>
</tr>

<tr>
    <td class="key">Iva 2:</td>
    <td>
        <input type="text" class="cajas" style="width:8%;" id="txtIva2" name="txtIva2" value="<?php echo $configuracion->iva2 ?>" /> 
    </td>
</tr>

<tr>
    <td class="key">Iva 3:</td>
    <td>
        <input type="text" class="cajas" style="width:8%;" id="txtIva3" name="txtIva3" value="<?php echo $configuracion->iva3 ?>" /> 
    </td>
</tr>-->

<tr>
     <td class="key">Email:</td>
     <td>
     	<textarea rows="2" class="TextArea" id="T4" name="T4"><?php echo $configuracion->correo ?></textarea>
        </td>
</tr>
<tr>
  <td class="key">Nombre de empresa: </td>
  <td>
  	<textarea rows="3" id="T42" name="T42" class="TextArea"><?php echo $configuracion->nombre ?></textarea>
  </td>
</tr>
<tr>
  <td class="key">RFC:</td>
  <td><input type="text" class="cajas" id="T43" name="T43" value="<?php echo $configuracion->rfc ?>"/></td>
</tr>
<tr>
  <td class="key">Calle</td>
  <td>
  	<textarea rows="3" class="TextArea" id="T44" name="T44"><?php echo $configuracion->direccion ?></textarea>
  </td>
</tr>
<tr>
  <td class="key">Número</td>
  <td><input type="text" class="cajas" id="numero" name="numero" value="<?php echo $configuracion->numero ?>"/></td>
</tr>
<tr>
  <td class="key">Colonia:</td>
  <td><input type="text" class="cajas" id="txtColonia" name="txtColonia" value="<?php echo $configuracion->colonia ?>"/></td>
</tr>
<tr>
  <td class="key">Localidad:</td>
  <td><input type="text" class="cajas" id="txtLocalidad" name="txtLocalidad" value="<?php echo $configuracion->localidad ?>"/></td>
</tr>
<tr>
  <td class="key">Municipio:</td>
  <td><input type="text" class="cajas" id="txtMunicipio" name="txtMunicipio" value="<?php echo $configuracion->municipio ?>"/></td>
</tr>

<tr>
  <td class="key">Estado:</td>
  <td><input type="text" class="cajas" id="T49" name="T49" value="<?php echo $configuracion->estado ?>"/></td>
</tr>
<tr>
  <td class="key">País:</td>
  <td>
  <input id="pais" name="pais" value="<?php echo $configuracion->pais?>" class="cajas" />
  </td>
</tr>

<tr>
  <td class="key">Código postal: </td>
  <td><input type="text" class="cajas" id="T47" name="T47" value="<?php echo $configuracion->codigoPostal ?>"/></td>
</tr>

<tr>
  <td class="key">Teléfono</td>
  <td><input type="text" class="cajas" id="T45" name="T45" value="<?php echo $configuracion->telefono?>"/></td>
</tr>
<tr>
  <td class="key">Contacto:</td>
  <td><input type="text" class="cajas" id="T46" name="T46" value="<?php echo $configuracion->contacto ?>"/></td>
</tr>

<tr>
  <td class="key">Identificador: </td>
  <td><input type="text" class="cajas" id="identificador" name="identificador" value="<?php echo $configuracion->identificador ?>"/></td>
</tr>

<tr>
  <td class="key">Logotipo: </td>
  <td>
    <?php
	if(file_exists('img/logos/'.$configuracion->id.'_'.$configuracion->logotipo) and strlen($configuracion->logotipo)>4)
	{
		echo '<img src="'.base_url().'img/logos/'.$configuracion->id.'_'.$configuracion->logotipo.'" style="margin-top:0px; max-width:120px; max-height:60px"  />';
	}
	?>
	
    <input onchange="comprobarArchivo()" type="file" id="userfile" class="cajas"  name="userfile" style="height:30px"/>
  </td>
</tr>

<tr>
  	<td class="key">Clave precio 1: </td>
  	<td><input type="password" class="cajas" id="txtCodigoBorrado" name="txtCodigoBorrado" value=""/></td>
</tr>

<tr>
    <td class="key">Código para editar: </td>
    <td><input type="password" class="cajas" id="txtCodigoEditar" name="txtCodigoEditar" value=""/></td>
</tr>

<tr>
    <td class="key">Código para Exportar/Importar: </td>
    <td><input type="password" class="cajas" id="txtCodigoImportar" name="txtCodigoImportar" value=""/></td>
</tr>

<tr>
    <td class="key">Mostrar notificaciones: </td>
    <td><input type="checkbox" id="chkNotificaciones" name="chkNotificaciones" value="1" <?php echo $configuracion->notificaciones=='1'?'checked="checked"':''?> /></td>
</tr>
		
	<tr>
		<td class="key">Imprimir con el agente: </td>
		<td><input type="checkbox" id="chkAgente" name="chkAgente" value="1" <?php echo $configuracion->impresoraLocal=='1'?'checked="checked"':''?> /></td>
	</tr>


    <tr>
        <th colspan="2" class="encabezadoPrincipal">Configuración estaciones</th>
    </tr>
    
    <tr>
        <td class="key">Usuario:</td>
        <td>
            <input type="text" class="cajas" id="txtUsuarioTiendas" name="txtUsuarioTiendas" value="<?php echo $configuracion->usuarioTiendas ?>" /> 
        </td>
    </tr>
    
    <tr>
        <td class="key">Password:</td>
        <td>
            <input type="text" class="cajas"  id="txtPasswordTiendas" name="txtPasswordTiendas" value="" /> 
        </td>
    </tr>

<tr>
  <td class="key">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
 <tr>
     <td colspan="2" >
         <div style="width:78%" align="center">
        <?php
		if($permiso[2]->activo==1)
		{
			echo '<input type="submit" name="id_guarda" id="id_guardar" value="Guardar" class="btn"  />';
		}
		?>
         </div>     
     </td>
 </tr>
 </table> 
 <!-- Configuacion -->
</form>



 <!--  Lista -->
 <!-- Termina ListProyectos-->
</div>
<!-- Termina derecha-->
</div>


