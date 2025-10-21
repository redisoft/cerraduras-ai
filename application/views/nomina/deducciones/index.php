<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/nomina/deducciones.js"></script>
<script>
$(document).ready(function()
{
	obtenerDeducciones();
});
</script>
<div class="derecha">
<div class="submenu">
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
     <table class="toolbar" width="100%">
     	<tr>
       	 	<td class="seccion" colspan="2">
            Deducciones
            </td>
        </tr>
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
				<?php
				echo'
				<a id="btnRegistrarDeduccion" onclick="formularioDeducciones()">
					<img src="'.base_url().'img/add.png" title="Registrar deducción" /><br />
					Registrar
				</a> ';
				
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarDeduccion\');
					</script>';
				}
                ?>
            </td>
            <td>
            	<input onkeyup="obtenerDeducciones()" type="text"  name="txtBuscarDeduccion" id="txtBuscarDeduccion" class="busquedas" placeholder="Buscar deduccion"  style="width:600px; "/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="obtenerDeducciones" style="margin-top:20px"></div>
<input type="hidden" id="txtAgregarDeducciones" value="0" />

<div id="ventanaRegistrarDeduccion" title="Registrar deducción">
<div id="registrandoDeduccion"></div>
<div id="formularioDeducciones"></div>
</div>

<div id="ventanaEditarDeduccion" title="Editar deducción">
<div id="editandoDeduccion"></div>
<div id="obtenerDeduccion"></div>
</div>

</div>
</div>