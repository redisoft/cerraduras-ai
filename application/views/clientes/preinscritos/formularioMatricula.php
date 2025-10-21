<?php
echo'
<div class="ui-state-error" ></div>
<form id="frmMatricula">
	<table class="admintable" width="100%">
		<tr>
			<td class="key">Matrícula:</td>
			<td>
				<input type="text" style="width:280px" class="cajas" id="txtMatricula" name="txtMatricula" value="'.$academico->matricula.'" />
				<input type="hidden"  id="txtIdAcademico" name="txtIdAcademico" value="'.$academico->idAcademico.'" />
				<input type="hidden"  id="txtIdRelacion" name="txtIdRelacion" value="'.$academico->idRelacion.'" />
			</td>
		</tr>
		<tr>
			<td class="key">Mes:</td>
			<td>
				<select id="selectMesRegistro" name="selectMesRegistro" style="width:200px" class="cajas">';
				
				foreach($meses as $row)
                {
                    echo '<option '.($academico->mes==$row->nombre?'selected="selected"':'').' >'.$row->nombre.'</option>';
                }
				
			echo'
			</select>
			</td>
		</tr>
		
		<tr>
			<td class="key">Periodo:</td>
			<td>
				<select id="selectPeriodoRegistro" name="selectPeriodoRegistro" style="width:300px" class="cajas">';
				
				foreach($periodos as $row)
                {
                    echo '<option '.($academico->idPeriodo==$row->idPeriodo?'selected="selected"':'').' value="'.$row->idPeriodo.'" >'.$row->nombre.' ('.obtenerFechaMesCorto($row->fechaInicial).' | '.obtenerFechaMesCorto($row->fechaFinal).')</option>';
                }
				
			echo'
			</select>
			</td>
		</tr>
	</table>
</form>';
?>