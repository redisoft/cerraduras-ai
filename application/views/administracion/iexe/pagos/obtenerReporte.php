<table class="admintable" width="100%">
	<?php
    echo '
	<tr>
		<td colspan="10" class="sinbordeTransparente">
			<ul id="pagination-digg" class="ajax-pagReporte">'.$this->pagination->create_links().'</ul>
		</td>
	</tr>';
	?>
    <tr>
        <th width="2%"># <br /><?=$registros?></th>
        
        <th width="15%">Fecha de asignación</th>
        <th width="15%">Fecha de inscripción</th>
         <th width="25%">
        	<select id="selectCampanasBusqueda" name="selectCampanasBusqueda" class="cajas" style="width:90%" onchange="obtenerReporte()">
                <option value="0">Campaña</option>
                
                <?php
                foreach($campanas as $row)
                {
                    echo '<option '.($row->idCampana==$idCampana?'selected="selected"':'').' value="'.$row->idCampana.'">'.$row->nombre.'</option>';
                }
                ?>
            </select>
        </th>
        <th width="25%">
        <select id="selectProgramaBusqueda" name="selectProgramaBusqueda" class="cajas" style="width:90%" onchange="obtenerReporte()">
                <option value="0">Programa</option>
                
                <?php
                foreach($programas as $row)
                {
                    echo '<option '.($row->idPrograma==$idPrograma?'selected="selected"':'').' value="'.$row->idPrograma.'">'.$row->nombre.'</option>';
                }
                ?>
            </select>
    	</th>
        <th width="20%">Alumno</th>
    </tr>
    
    <?php
	$i=$limite;
    foreach ($prospectos as $row)
    {
        echo '
        <tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
            <td align="center">'.$i.'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fechaRegistro).'</td>
			<td align="center">'.obtenerFechaMesCortoHora($row->fechaInscrito).'</td>
			<td>'.$row->campana.'</td>
			<td>'.$row->programa.'</td>
			<td>'.$row->nombre.' '.$row->paterno.' '.$row->materno.'</td>
		
        </tr>';
		
		$i++;
        
    }
    ?>
</table>

