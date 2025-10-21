<script src="<?php echo base_url()?>js/sie/creditos/creditos.js"></script>

<script>
$(document).ready(function()
{
	obtenerCreditos(); 
});
	
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
<div class="toolbar" id="toolbar">
<!--<div class="seccionDiv">
	Contabilidad
</div>-->
 <table class="toolbar" width="100%" >
    <tr>
    
     <?php
	 	echo'
		<td align="center" valign="middle" style="border:none" width="5%">
			<a id="btnCreditos" onclick="formularioCreditos()" >
				<img src="'.base_url().'img/ingresos.png" width="30px;" height="30px;"  style="cursor:pointer;" title="Créditos" />
				<br />
				Agregar crédito  
			</a>
		</td>';
		
		if($permiso[1]->activo==0)
		{
			 echo '
			<script>
				desactivarBotonSistema(\'btnCreditos\');
			</script>';
		}
		?>
          <td width="52%"></td>
	</tr>
</table>
 </div>
</div>
<div class="listproyectos">

<div id="procesandoInformacion" style="width:100%"></div>

<div id="obtenerCreditos"></div>

<!-- INGRESOS-->
<div id="ventanaCreditos" title="Créditos">
    <div id="formularioCreditos"></div>
</div>

<div id="ventanaEditarCreditos" title="Créditos">
    <div id="obtenerCredito"></div>
</div>



</div>
</div>

