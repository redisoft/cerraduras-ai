<?php
$cantidadInscripcion	= $academicos!=null?$academicos->cantidadInscripcion:0;
$cantidadColegiatura	= $academicos!=null?$academicos->cantidadColegiatura:0;
$cantidadReinscripcion	= $academicos!=null?$academicos->cantidadReinscripcion:0;

if($programa!=null)
{
	if($cantidadInscripcion==0) $cantidadInscripcion=$programa->cantidadInscripcion;
	if($cantidadColegiatura==0) $cantidadColegiatura=$programa->cantidadColegiatura;
	if($cantidadReinscripcion==0) $cantidadReinscripcion=$programa->cantidadReinscripcion;
}

$totalInscripcion		= $cantidadInscripcion*($academicos!=null?$academicos->inscripcion:0);
$totalColegiatura		= $cantidadColegiatura*($academicos!=null?$academicos->colegiatura:0);
$totalReinscripcion		= $cantidadReinscripcion*($academicos!=null?$academicos->reinscripcion:0);

$total					= $totalInscripcion+$totalColegiatura+$totalReinscripcion;


	echo'
	<table class="admintable" width="100%">
		
		<input  type="hidden" id="txtCantidadInscripcion" value="'.$cantidadInscripcion.'"  />
		<input  type="hidden" id="txtCantidadColegiatura" value="'.$cantidadColegiatura.'"  />
		<input  type="hidden" id="txtCantidadReinscripcion" value="'.$cantidadReinscripcion.'"  />
		<input  type="hidden" id="txtIdVentaProspecto" value="'.($venta!=null?$venta->idVenta:0).'"  />
		<input  type="hidden" id="txtIdClienteProspecto" value="'.($cliente->idCliente).'"  />
		
		<tr>
			<td class="key">Programa:</td>
			<td align="left">       
				<select id="selectProgramas" name="selectProgramas" class="cajas" style="width:500px" >
					';
				
				foreach($programas as $row)
				{
					echo '<option '.($row->idPrograma==$cliente->idPrograma?'selected="selected"':'').' value="'.$row->idPrograma.'">'.$row->nombre.'</option>';
				}
				
				echo'
				</select>
			</td>
		</tr>
		
		<tr>
			<td class="key">Campana:</td>
			<td align="left">       
				<select id="selectCampanas" name="selectCampanas" class="cajas" style="width:500px" >
					';
				
				foreach($campanas as $row)
				{
					echo '<option '.($row->idCampana==$cliente->idCampana?'selected="selected"':'').' value="'.$row->idCampana.'">'.$row->nombre.'</option>';
				}
				
				echo'
				</select>
			</td>
		</tr>

		<tr>
			<td class="key">Incripción:</td>
			<td align="left">       
				<input  type="text" onchange="calcularTotalesAcademicosProspecto()" id="txtInscripcion" name="txtInscripcion"  class="cajas" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="10" value="'.($academicos!=null?round($academicos->inscripcion,decimales):'').'"  />
				
				<input  type="hidden"  id="txtIdAcademico" name="txtIdAcademico"  value="'.($academicos!=null?$academicos->idAcademico:'0').'"  />
			</td>
		</tr>
		
		<tr>
			<td class="key">Colegiatura:</td>
			<td align="left">       
				<input  type="text" onchange="calcularTotalesAcademicosProspecto()" id="txtColegiatura" name="txtColegiatura"  class="cajas" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="10" value="'.($academicos!=null?round($academicos->colegiatura,decimales):'').'"  />
			</td>
		</tr>
		
		<tr>
			<td class="key">Reinscripción:</td>
			<td align="left">       
				<input  type="text" onchange="calcularTotalesAcademicosProspecto()" id="txtReinscripcion" name="txtReinscripcion"  class="cajas" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="10" value="'.($academicos!=null?round($academicos->reinscripcion,decimales):'').'" />
			</td>
		</tr>	
		
		<tr>
			<td class="key">Titulación:</td>
			<td align="left">       
				<input  type="text" onchange="registrarTotalesAcademicosProspecto()" id="txtTitulacion" name="txtTitulacion"  class="cajas" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="10" value="'.($academicos!=null?round($academicos->titulacion,decimales):'').'" />
			</td>
		</tr>	
		
		<tr>
			<td class="key">Venta:</td>
			<td align="left">       
				<input  type="text" id="txtVenta" name="txtVenta"  class="cajas" style="width:100px" onkeypress="return soloDecimales(event)" maxlength="10" value="'.round($venta!=null?$venta->venta:$total,decimales).'"  />
			</td>
		</tr>
	</table>';