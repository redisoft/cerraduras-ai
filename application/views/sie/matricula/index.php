<script src="<?php echo base_url()?>js/sie/matricula/matricula.js"></script>
<script>
$(document).ready(function()
{
	obtenerMatriculaSie();
});
</script>
<div class="submenu">
     <table class="toolbar" width="100%">
     	<tr>
       	 	<td class="seccion" colspan="2">
            
            </td>
        </tr>
        <tr>
            <td style="border:none" width="20%" align="center" valign="middle" class="button">
				<?php
                echo'
				<a onclick="formularioMatriculaSie()" id="btnRegistrarMatriculaSie">
					<img src="'.base_url().'img/add.png" style="cursor:pointer"  /> <br />
					Agregar
				</a>';
				
				if($permiso[1]->activo==0)
				{
					 echo '
					<script>
						desactivarBotonSistema(\'btnRegistrarMatriculaSie\');
					</script>';
				}
                ?>
            </td>
            <td>
            	<!--<input type="text"  	name="txtBuscarNivel1" 			id="txtBuscarNivel1" class="busquedas" placeholder="Buscar registro"  style="width:400px; "/>-->
                <input type="hidden"  	name="txtLicenciatura" 	id="txtLicenciatura" value="<?=$licenciatura?>"/>
            </td>
        
        </tr>
    </table>
</div>

<div class="listproyectos">

<div id="procesandoMatriculaSie"></div>
<div id="obtenerMatriculaSie"></div>


<div id="ventanaRegistroMatriculaSie" title="Registrar">
    
    <div id="formularioMatriculaSie"></div>
</div>


</div>
