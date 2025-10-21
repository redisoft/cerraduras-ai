<script src="<?php echo base_url()?>js/compras/informacionCompras.js"></script>  
<script src="<?php echo base_url()?>js/compras/global/global.js"></script>  

<!--CRM DE SERVICIOS-->
<script src="<?php echo base_url()?>js/proveedores/seguimiento/detalles.js"></script>
<script src="<?php echo base_url()?>js/proveedores/seguimiento/archivos.js"></script>
<script src="<?php echo base_url()?>js/crm/proveedores/servicios/servicios.js"></script>
<script src="<?php echo base_url()?>js/crm.js"></script>





<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
<table class="toolbar" style="width:100%">	
	<tr>
    
		 <?php
	   
	   if($idProveedor>0)
	   {
		   echo '
		   <td align="center" style="border:none" width="10%" >
				<a id="btnCompras" class="toolbar" >
					<img src="'.base_url().'img/compras.png" width="30px" title="Compras" /> <br />
					Compras
				</a>
		   </td>
		   
			<td align="center" style="border:none" width="10%" >
				<a id="btnContactos" href="'.base_url().'proveedores/contactos/'.$idProveedor.'" class="toolbar" >
					<img src="'.base_url().'img/contactos.png" width="30px" title="Compras" /> <br />
					Contactos
				</a>
		   </td>
		   
		   <td align="center" style="border:none" width="10%" >
				<a class="toolbar" onclick="obtenerFichaTecnicaProveedor('.$idProveedor.')" >
					<img src="'.base_url().'img/fichaTecnica.png" width="30px" title="Ficha técnica" /> <br />
					Ficha técnica
				</a>
		   </td>';
		   
		   echo '
			<script>
				desactivarBotonSistema(\'btnCompras\');
			</script>';
			
		   if($permisoContactos[0]->activo==0)
			{
				echo '
				<script>
					desactivarBotonSistema(\'btnContactos\');
				</script>';
			}
	   }
	   ?>
       
      <td align="left" valign="middle" style="width:78%; padding-right:100px">
        <input type="text" class="busquedas" placeholder="Seleccione fecha" onchange="obtenerComprasGlobal()" style="width:110px" id="FechaDia" value="<?=date('Y-m-d')?>"/>
        <input type="text" class="busquedas" placeholder="Seleccione fecha" onchange="obtenerComprasGlobal()" style="width:110px" id="FechaDia2" value="<?=date('Y-m-d')?>" />
        
        
        <input type="text" class="busquedas" placeholder="<?php echo $idProveedor==0?'Buscar por proveedor, orden de compra':'Buscar por orden de compra'?>" style="width:450px" id="txtCriterioGlobal" />
        
        <input type="hidden"  name="txtIdServicioCrm" id="txtIdServicioCrm" value="3"/>
        <input type="hidden"  name="txtIdProveedorCrm" id="txtIdProveedorCrm" value="0"/>
        <input type="hidden"  name="txtIdProveedorGlobal" id="txtIdProveedorGlobal" value="<?php echo $idProveedor?>"/>
        
        &nbsp;
        
         <?php
		/*if($fecha!='fecha' or $idCompras!=0 or $idProveedor!=0)
		{
			echo '<img src="'.base_url().'img/quitar.png" style="width:22px; height:22px" title="Borrar busqueda" onclick="window.location.href=\''.base_url().'compras/administracion\'" />';
		}*/
      	?>         
         
        </td>
    </tr>
 </table>
 </div>
</div>

<div class="listproyectos">

<div id="obtenerComprasGlobal"></div>



<div id="ventanitaCompras" title="Detalles de compra">
<div id="errorComprita" class="ui-state-error" ></div>
<div  id="cargarComprita"></div>
</div>




<?php #$this->load->view('clientes/seguimiento/crmServicios/modalesSeguimientoServicios');?>

</div>
</div>
