<script type="text/javascript" src="<?php echo base_url()?>js/bibliotecas/barcode.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/produccion/produccion.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/produccion/materiales.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/produccion/importar.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/produccion/similares.js"></script>
<script src="<?php echo base_url()?>js/productos/impuestos.js"></script>
<script src="<?php echo base_url()?>js/lineas/lineas.js"></script>
<script src="<?php echo base_url()?>js/mostrar.js"></script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
 <table class="toolbar " width="100%">
   <!-- <tr>
    	<td colspan="3" class="seccion">Explosión de materiales</td>	
    </tr>-->
    <tr>
   
		<?php
        
        $inicio	= $this->uri->segment(3);
        $pagina	= $inicio;
        
        if(strlen($inicio)==0)
        {
            $inicio=0;
            $pagina=0;
        }
	
		echo '
		<td width="8%" align="left" valign="middle" style="border:none"  >
			<a onclick="formularioProduccion()" id="btnAgregarProducto">
				<img src="'.base_url().'img/productos.png" width="30px;" height="30px;" style="cursor:pointer;" title="Añadir nuevo producto">  
				<br />
				Nuevo producto    
			</a>
        </td>
		<td width="8%" align="left" valign="middle" style="border:none" >
			<a onclick="formularioSimilares()" id="btnSimilares">
            <img  src="'.base_url().'img/materiales.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Añadir productos similares">
            <br />
    		Productos similares
			</a>
        </td>
		<td class="button" width="5%">
			<a class="toolbar" onclick="accesoImportarProduccion()" id="btnImportar">
				<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Importar" alt="Importar" /><br />
				Importar  
			</a>      
		</td>
		
		<td class="button" width="5%">
			<a class="toolbar" onclick="accesoExportarProduccion()" id="btnExportar">
				<img src="'.base_url().'img/excel.png" width="30px;" height="30px;" title="Exportar" alt="Exportar" /><br />
				Exportar  
			</a>      
		</td>';
		
		if($permiso[1]->activo==0)
		{ 
			echo '
			<script>
				desactivarBotonSistema(\'btnAgregarProducto\');
				desactivarBotonSistema(\'btnImportar\');
				desactivarBotonSistema(\'btnExportar\');
			</script>';
			
			
		}
		
		if(empty($productos) or $permiso[1]->activo==0)
		{
			echo '
			<script>
				desactivarBotonSistema(\'btnSimilares\');
			</script>';
		}
    	?>

       
       <td width="74%" align="left" valign="middle" style="border:none" >
       	<input type="text"  name="txtBuscarProduccion" id="txtBuscarProduccion" class="busquedas" placeholder="Buscar producto" style="width:500px;"/>

            <?php
            if($idProducto!=0)
			{
				echo '<img class="borrarBusqueda" onclick="window.location.href=\''.base_url().'produccion/index\'" src="'.base_url().'img/quitar.png" title="Borrar busqueda" />';
			}
		?>        
        </td>
        

        <?php
	
	?>
 </table>
 </div>
</div>
<div class="listproyectos">

<?php

if(!empty($productos))
{
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagI">'.$this->pagination->create_links().'</ul>
	</div>';

	$i		= 1;
	$p		= 1; #CONTADOR PARA IDs DE PRODUCTOS
	$pagina	= 0;
	
	foreach ($productos as $pro)
	{
		?>
		<table class="admintable" width="100%" >
		 <tr>
		 <th class="encabezadoPrincipal" align="left" colspan="10" style="font-size:14px;" id="mostrars<?php echo $i?>" >
		 <?php 
		#echo $pro['nombre'] .' '.$pro['piezas'] .' piezas, Línea: '.$pro['linea'].' ';
		echo $pro['nombre'] .', Línea: '.$pro['linea'].' ';		
		if($pro['materiaPrima']==1)
		{
			echo '(Materia prima)';
		}
		
		$imagen='<img src="'.base_url().carpetaProductos.'default.png" style=" margin-top:-10px; width:40px; height:40px;  margin-left: 10px; position: absolute"  />';

		if(file_exists(carpetaProductos.$pro['idProducto'].'_'.$pro['imagen']) and strlen($pro['imagen'])>2)
		{
			$imagen='<img src="'.base_url().carpetaProductos.$pro['idProducto'].'_'.$pro['imagen'].'" style=" margin-top:-10px; width:40px; height:40px;  margin-left: 10px; position: absolute"  />';
		}
		
		echo $imagen;
		 
		 ?> 
		 </th>
		 <th class="encabezadoPrincipal" style="width:24.3%;" align="center">
		 <?php
		
			echo '
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="btnAgregarMaterial'.$i.'" src="'.base_url().'img/materiales.png" width="22px;" height="22px;" style="cursor:pointer;" title="Añadir materia prima" onclick="formularioAgregarMateriales('.$pro['idProducto'].')" >
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <img id="btnEditarProduccion'.$i.'" src="'.base_url().'img/editar.png" width="22" height="22" title="Producido" onclick="accesoEditarProduccion('.$pro['idProducto'].')" />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <img id="btnBorrarProduccion'.$i.'" onclick="borrarProductoProduccion('.$pro['idProducto'].',\'¿Realmente desea borrar este producto?\','.$pagina.')" src="'.base_url().'img/quitar.png" width="22" height="22" hspace="3" title="Borrar registro" border="0" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<img id="mostrar'.$i.'"src="'.base_url().'img/ocultar.png" width="22" height="22" title="Ocultar detalles" style="cursor:pointer;" onclick="mostrar(this,'.$i.')"/>
			<br />
			<strong><a>Materia prima </a> </strong>
			<strong><a>Editar </a></strong>
			<strong><a>Borrar </a> </strong>';
		
			if($permiso[1]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnAgregarMaterial'.$i.'\');
				</script>';
			}
			
			if($permiso[2]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnEditarProduccion'.$i.'\');
				</script>';
			}
			
			if($permiso[3]->activo==0)
			{
				 echo '
				<script>
					desactivarBotonSistema(\'btnBorrarProduccion'.$i.'\');
				</script>';
			}
		?>
	 </th>
	 </tr>
	 </table>
	 
	  <div  id="caja<?php echo $i?>"> <!-- Ocultar o mostrar el detalle de productos-->
		<table class="admintable" width="100%">
	
		<tr id="columna2<?php echo $i?>" class="cajita">
			<th style="width:5%; -moz-border-radius-bottomright: 0px;-moz-border-radius-topright: 0px;">#</th>
			<th align="center" style="width:150px; -moz-border-radius: 0px">Materia prima</th>
			<th style="width:10%; border-radius: 0px">Unidad</th>
			<th style="width:10%; border-radius: 0px">Conversión</th>
			<th style="width:10%; border-radius: 0px">Cantidad </th>
			<th style="width:60px; border-radius: 0px">PU </th>
			<th style="width:60px; border-radius: 0px">Importe </th>
			<th style="width:12%; -moz-border-radius-bottomleft: 0px;-moz-border-radius-topleft: 0px;">Acciones</th>             
		</tr>
		
		<?php
			$valorStock=$pro['stock'];
			
			$sql="select a.idMaterial, a.nombre,
			a.stock, a.costo, b.cantidad, 
			b.idMaterial as relMaterial,c.descripcion, 
			c.idUnidad, b.idConversion  
			from produccion_materiales as a
			inner join rel_producto_material as b
			on (a.idMaterial=b.idMaterial) 
			inner join unidades as c 
			on(c.idUnidad=a.idUnidad)
			where b.idProducto='".$pro['idProducto']."'";
			
			$query = $this->db->query($sql);
			  
			$m=1;
			
			foreach ($query->result() as $row)
			{
				$estilo		= 'class="sombreado"';
			
				if($m%2>0)
				{
					$estilo	= "class='sinSombra'";
				}
				
				$conversion		= "Sin conversión";
				$precioUnitario	= $row->costo;
				$importe		= $row->costo*$row->cantidad;
				#$stockTotal=$valorStock*$row->costo;
				
				if($row->idConversion>0)
				{
					$sql="select * from unidades_conversiones
					where idConversion='$row->idConversion'";
					
					$conversiones=$this->db->query($sql)->row();
					$conversion=$conversiones->nombre;
					$precioUnitario=$row->costo/$conversiones->valor;
					$importe=$precioUnitario*$row->cantidad;
				}
				?>
				   <tr <?php echo $estilo?>>
					<td align="center" valign="middle"> <?php echo $m?>  </td>
					<td align="center" valign="middle"> <?php echo $row->nombre ?></td>
					<td align="center" valign="middle"> <?php echo $row->descripcion ?> </td>
					<td align="center" valign="middle"> <?php echo $conversion ?> </td>
					<td align="center" valign="middle"> <?php echo $row->cantidad ?> </td>
					<td align="right" valign="middle">$ <?php echo number_format($precioUnitario,4) ?></td>
					<td align="right" valign="middle">$ <?php echo number_format($importe,4)?> </td>
					<td align="center"   valign="middle">
					<?php
					echo '
					<img id="btnEditarMaterialProducto'.$p.'" src="'.base_url().'img/editar.png" width="22" height="22" style="cursor:pointer;" title="Producido" onclick="accesoEditarProductoProduccion('.$pro['idProducto'].','.$row->idMaterial.')" />
					&nbsp;&nbsp;
					<img id="btnBorrarMaterialProducto'.$p.'" onclick="borrarMaterialProducto('.$row->idMaterial.','.$pro['idProducto'].',\'¿Realmente desea borrar el material?\','.$inicio.')" src="'.base_url().'img/quitar.png" width="22" height="22" title="Borrar materia prima" />
					<br />
					<a id="a-btnEditarMaterialProducto'.$p.'">Editar </a>
					<a id="a-btnBorrarMaterialProducto'.$p.'">Borrar </a>';
					
					if($permiso[2]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnEditarMaterialProducto'.$p.'\');
						</script>';
					}
					
					if($permiso[3]->activo==0)
					{
						 echo '
						<script>
							desactivarBotonSistema(\'btnBorrarMaterialProducto'.$p.'\');
						</script>';
					}
					
					?> 
					</td>
				   </tr>	
					<?php
					$m++;  //Contador de conceptos
					$p++;
			   }
			   
			
			$estilo1='class="sombreado"';
			$estilo2='class="sinSombra"';
		
			if($m%2>0)
			{
				$estilo1	= 'class="sinSombra"';
				$estilo2	= 'class="sombreado"';
			}
			
			#$gastos=$gastosAdministrativos;
			   ?>
			   <!--  bgcolor="#E8E8E8" Por si se arrepienten despues-->
			<tr <?php echo $estilo1?>>
				<td align="left" valign="middle">  </td>
				<td align="left" valign="middle"> Costo estandar de producción </td>
				<td align="right" valign="middle"> <?php //?></td>
				<td align="center" valign="middle"> <?php  ?> </td>
				<td align="right" valign="middle">  <?php //echo number_format($Inventario['costo'],2)?> </td>
				<td align="right" valign="middle"> <?php //echo number_format($row->sumaPrecios,2) ?>   </td>
				<td align="right" valign="middle">$  <?php echo number_format($pro['costo'],4)//echo number_format($precioStock,2) //print( number_format($Inventario['stock'],0)); ?> </td>
				<td align="left"   valign="middle"></td>
			</tr>
			
			<?php
			if($pro['materiaPrima']==0)
			{
				?>

				<tr <?php echo $estilo1?>>
					<td align="left" valign="middle">  </td>
					<td align="left" valign="middle"> <?php echo obtenerNombrePrecio(1)?></td>
					<td align="right" valign="middle"></td>
					<td align="center" valign="middle"> <?php  ?> </td>
					<td align="right" valign="middle"></td>
					<td align="right" valign="middle"></td>
					<td id="etiquetaUtilidad<?php echo $i?>" align="right" valign="middle">$  
						<?php echo number_format($pro['precioA'],4) ?>
					</td>
					<td align="left"   valign="middle"></td>
				</tr>
                 
				<?php
			}
			?>
			
			<tr>
				<td style="color:#FFF; border:none;">&nbsp;
				</td>
			</tr>
				
			</table>
			</div>
	
		   <?php
		   $i++;
		}
	
	echo'
	<div style="width:90%; margin-bottom:1%;">
		<ul id="pagination-digg" class="ajax-pagI">'.$this->pagination->create_links().'</ul>
	</div>';
	
}
else
{
	echo'<div class="Error_validar" style="margin-top:10px; margin-bottom: 5px;">No hay registros de productos</div>';
}
?>
	
<input type="hidden" name="pag1" id="pag1" class="cajas" style="width:160px;" value="<?php echo $inicio?>"  />

<div id="ventanaAgregarProduccion" title="Agregar nuevo producto">
<div id="agregandoProductoProduccion"></div>
<div id="formularioProduccion"></div>
</div>

<!-- Productos con sus materiales-->
<div id="ventanaProductoMateriales" title="Agregar materia prima">
    <div id="agregandoMateriaProducto"></div>
    <div id="ErrorAgregarMaterial" class="ui-state-error" ></div>
    <div id="formularioAgregarMateriales"></div>
</div>

<div id="ventanaEditarMaterial" title="Editar material">
<div id="editandoProductoMaterial"></div>
<div id="errorEditarProductoMaterial" class="ui-state-error" ></div>
<div id="obtenerMaterialEditar"></div>
</div>
    
<div id="ventanaEditarProducto" title="Editar producto">
<div id="editandoProductoProduccion"></div>
<div class="ui-state-error" ></div>
<div id="obtenerProducto"></div>
</div>
    
<!--Pues con esto sera lo de similares-->

<div id="ventanaSimilares" title="Productos similares">
<div id="agregandoSimilares"></div>
<div id="ErrorSimilares" class="ui-state-error" ></div>
	<input type="hidden" name="pag2" id="pag2" class="cajas" style="width:160px;" value="<?php echo $inicio?>" />
    <table class="admintable" width="100%;">
    <tr>
    	<td class="key">Producto base</td>
    <td>  
        <select class="cajas" style="width:300px" id="productoBase" name="productoBase">
        <option value="0">Seleccione</option>
        <?php
		foreach($productos as $row)
		{ 
			if($row['materiaPrima']==0)
			{
				echo'<option value="'.$row['idProducto'].'">'.$row['nombre'].'</option>';
			}
        	
		}
        ?>
        </select>
    </td>
    </tr>
  	<tr>  
    	<th colspan="2"> Similares </th>
    </tr>
    <tr>
    	<th width="60%">Nombre</th>
        <th>Código interno</th>
    </tr>
	<?php
	for($i=0; $i<10;$i++)
	{
		echo'
		<tr>
			<td align="center"><input type="text" style="width:300px" class="cajas" id="txtNombreSimilar'.$i.'" /></td>
			<td align="center"><input type="text" style="width:150px" class="cajas" id="txtCodigoSimilar'.$i.'" /></td>
		</tr>
		';
	}
    ?>
</table>
</div>

<div id="ventanaLineas" title="Líneas">
<div id="agregandoLinea"></div>
<div id="formularioLineas"></div>
</div>

<div id="ventanaImportarProduccion" title="Importar producción">
    <div id="importandoProducccion"></div>
    <div class="ui-state-error" ></div>
	<div id="formularioImportarProduccion"></div>
</div>

</div>
</div>
