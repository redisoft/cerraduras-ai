<script language="javascript" type="text/javascript" src="<?php echo base_url()?>js/nomina/percepciones.js"></script>
<script>
$(document).ready(function()
{
	obtenerPercepciones();
});
</script>
<div class="derecha">
<div class="submenu">
	<div class="breadcumb"><?php echo isset($breadcumb)?$breadcumb:''?></div>
     <table class="toolbar" width="100%">
     	<tr>
        <td class="seccion" colspan="2">
            Percepciones
            </td>
        </tr>
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
				<?php
				echo'
				<a id="btnRegistrarPercepcion" onclick="formularioPercepciones()" >
					<img src="'.base_url().'img/add.png" title="Registrar percepción"/> <br />
					Registrar
				</a>';
				
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarPercepcion\');
					</script>';
				}
                ?>
            </td>
            <td>
            	<input onkeyup="obtenerPercepciones()" type="text"  name="txtBuscarPercepcion" id="txtBuscarPercepcion" class="busquedas" placeholder="Buscar percepcion"  style="width:600px; "/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="obtenerPercepciones" style="margin-top:20px"></div>
<input type="hidden" id="txtAgregarPercepciones" value="0" />

<div id="ventanaRegistrarPercepcion" title="Registrar percepción">
<div id="registrandoPercepcion"></div>
<div id="formularioPercepciones"></div>
</div>

<div id="ventanaEditarPercepcion" title="Editar Percepción">
<div id="editandoPercepcion"></div>
<div id="obtenerPercepcion"></div>
</div>

</div>
</div>