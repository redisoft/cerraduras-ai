<script type="text/javascript" src="<?php echo base_url()?>js/globales/globales.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/globales/facturaGlobal.js"></script>
<script src="<?php echo base_url()?>js/facturacion/folios.js"></script> 

<script>
$(document).ready(function()
{
	obtenerVentasGlobal();
});
</script>

<div class="derecha">
<div class="submenu">
<div class="breadcumb">Facturación global</div>
<div class="toolbar">

 <table class="toolbar" width="100%" <?php echo $permiso->escribir==0?'style="display:none"':''?>>
    <tr>
    	<?php
        echo '
		<td class="button" width="18%">
            <a  onclick="formularioSucursales()">
                <img src="'.base_url().'img/banco.png" width="30px;" height="30px;" title="Sucursales" /><br />
                Sucursales  
            </a>      
			
			 <a  onclick="formularioFacturaGlobal()">
                <img src="'.base_url().'img/xml.png" width="30px;" height="30px;" title="Globales" /><br />
                Factura global  
            </a>      
        </td>';
		?>
        
        <td width="95%">
        	<select class="cajas" id="selectLicencias" name="selectLicencias" style="width:300px" onchange="obtenerVentasGlobal()">
            	<?php
                foreach($licencias as $row)
				{
					echo '<option value="'.$row->idLicencia.'">'.$row->nombre.'</option>';
				}
				
				?>
            </select>
            <input onchange="obtenerVentasGlobal()" readonly="readonly" value="<?php echo date('Y-m-d')?>" type="text" title="Inicio" style="width:90px" id="FechaDia" class="busquedas" placeholder="Fecha inicio" />
			&nbsp;
            <input onchange="obtenerVentasGlobal()" readonly="readonly" value="<?php echo date('Y-m-d')?>" type="text" title="Fin" id="FechaDia2" style="width:90px" class="busquedas" placeholder="Fecha fin" />

            <input type="text" id="txtCriterio" style="width:500px" class="busquedas" placeholder="Buscar folio venta" />
        </td>
</tr>
</table>
</div>
</div>

<div class="listproyectos" style="margin-top:20px" >
	<div id="obtenerVentasGlobal"></div>
</div>



<div id="ventanaSucursales" title="Sucursales">
    <div class="ui-state-error" ></div>
	<div id="formularioSucursales"></div>
</div>

<div id="ventanaFacturaGlobal" title="Factura global">
    <div id="registrandoFacturaGlobal"></div>
    <div class="ui-state-error" ></div>
    <div id="formularioFacturaGlobal"></div>
</div>

</div>
