<script>
$(document).ready(function()
{
	$("#txtNombre").autocomplete(
	{
		source:base_url+'configuracion/obtenerRolesRepetidos',
		
		select:function( event, ui)
		{
			notify("El rol ya esta registrado",500,5000,"error",5,5);
			document.getElementById("txtNombre").reset();
		}
	});
});
</script>

<form name="frmRoles" id="frmRoles">
    <table class="admintable" width="100%">
        <tr>
            <td colspan="2" class="key">Nombre:</td>
            <td>
                <input name="txtNombre" id="txtNombre" type="text"  style="width:300px" class="cajas" />
            </td>
        </tr>
        <tr>
            <th colspan="3">Permisos</th>
        </tr>
        <tr>
            <th style="width:2%">#</th>
            <th style="width:30%">Descripción</th>
            <th >
            	Opciones 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 <input type="checkbox" id="chkTodo" onchange="seleccionarTodosArchivos()" /> Seleccionar todo
            </th>
        </tr>
        <?php
        $i	=1;
        $b	=1; //Para contar los botones
		$c	= 0;
        foreach($permisos as $row)
        {
			if($row->activo=='1') $c++;
			
            $estilo		= $c%2>0?'class="sinSombra"':'class="sombreado"';
            $botones	= $this->configuracion->obtenerPermisosBotones($row->idPermiso);
            
            echo 
            '<tr '.$estilo.' '.($row->activo=='0'?'style="display:none"':'').'>
                <td align="right">'.$c.'</td>
                <td>'.$row->descripcion.'</td>
                <td align="left">';
                
                foreach($botones as $boton)
                {
                    echo '<label>'.$boton->nombre.'</label>
                    <input class="check" type="checkbox" id="chkBoton'.$b.'" name="chkBoton'.$b.'" value="'.$boton->idBoton.'" />
                    <input type="hidden" id="txtBoton'.$b.'" name="txtBoton'.$b.'" value="'.$boton->idBoton.'" /> ';
                    
                    $b++;
                }
               echo' 
               </td>
            </tr>';
            
            
            $i++;
        }
        ?>
        
        <input type="hidden" value="<?php echo $b?>" id="txtIndice" name="txtIndice"/>
    </table>
</form>