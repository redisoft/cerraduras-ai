<form name="frmRoles" id="frmRoles">
    <table class="admintable" width="100%">
        <tr>
            <td colspan="2" class="key">Nombre:</td>
            <td>
                <input name="txtNombre" id="txtNombre" type="text"  style="width:300px" class="cajas" value="<?php echo $rol->nombre?>" />
                <input name="txtIdRol" id="txtIdRol" type="hidden" value="<?php echo $rol->idRol?>" />
            </td>
        </tr>
        <tr>
            <th colspan="3">Permisos</th>
        </tr>
        <tr>
            <th style="width:2%">#</th>
            <th style="width:30%">Descripción</th>
            <th>
            	Opciones
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 <input type="checkbox" id="chkTodo" onchange="seleccionarTodosArchivos()" /> Seleccionar todo
            </th>
        </tr>
        <?php
        $i	= 1;
        $b	= 1; //Para contar los botones
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
                    $activo	= $this->configuracion->obtenerRolBoton($boton->idBoton,$rol->idRol);
                    $activo	= $activo==1?'checked="checked"':'';
                    
                    echo '<label>'.$boton->nombre.'</label>
                    <input class="check" '.$activo.' type="checkbox" id="chkBoton'.$b.'" name="chkBoton'.$b.'" value="'.$boton->idBoton.'" />
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