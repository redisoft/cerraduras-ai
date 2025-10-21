<div class="row">
	<?php
	
	$ingresosMaestria		= 0;
	$actualMaestria			= 0;
	$metaMaestria			= 0;
	$desercionMaestria		= 0;
	
	foreach($maestrias as $row)
	{
		$ingresosMaestria		+= $row->ingresos;
		$actualMaestria			+= $row->actual;
		$metaMaestria			+= $row->meta;
		
		$desercion				= (1-($row->actual/$row->ingresos))*100;
		$desercionMaestria		+= $desercion;
	}
	
    echo'
	<div class="col-md-7">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr onclick="obtenerDetallesMatricula(\'\',1)">
                            <th colspan="5" class="encabezadoPrincipal">
                                Licenciaturas
                            </th>
                        </tr>
                        <tr>
                            <th width="20%">Cuatrimestre</th>
                            <th width="20%"># Ingresos</th>
                            <th width="20%"># Actual</th>
                            <th width="20%">Resultados</th>
                            <th width="20%">Meta</th>
                        </tr>';
                    
                    $i=1;
                    
                    $ingresosLicenciatura		= 0;
                    $actualLicenciatura			= 0;
                    $metaLicenciatura			= 0;
                    $desercionLicenciatura		= 0;
                    
                    foreach($licenciaturas as $row)
                    {
                        $ingresosLicenciatura		+= $row->ingresos;
                        $actualLicenciatura			+= $row->actual;
                        $metaLicenciatura			+= $row->meta;
                        $desercion					= (1-($row->actual/$row->ingresos))*100;
                        $desercionLicenciatura		+= $desercion;
                    
                        echo'
                        <tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').' onclick="obtenerDetallesMatricula('.$row->cuatrimestre.',1)">
                            <td align="center">'.$row->cuatrimestre.'</td>
                            <td align="center">'.$row->ingresos.'</td>
                            <td align="center">'.$row->actual.'</td>
                            <td align="center">'.round($desercion,decimales).'%</td>
                            <td align="center">'.round($row->meta,decimales).'%</td>
                        </tr>';
                    
                        $i++;
                    }
                    
                    echo '
                        <tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').' onclick="obtenerDetallesMatricula(\'\',1)">
                            <td class="totales" align="center">Total</td>
                            <td class="totales" align="center">'.$ingresosLicenciatura.'</td>
                            <td class="totales" align="center">'.$actualLicenciatura.'</td>
                            <td class="totales" align="center">'.round($desercionLicenciatura/count($licenciaturas),decimales).'%</td>
                            <td class="totales" align="center">'.round($metaLicenciatura/count($licenciaturas),decimales).'%</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
	</div>';
	
	$ingresosTotales	= $ingresosMaestria+$ingresosLicenciatura;
	$actualesTotales	= $actualMaestria+$actualLicenciatura;
	$desercion			= (1-($actualesTotales/$ingresosTotales))*100;
        
    echo'
	<div class="col-md-5">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">

                    <table class="table table-striped" onclick="obtenerDetallesMatricula(\'\',\'\')">
                        <tr>
                            <th colspan="3" class="encabezadoPrincipal">
                                Totales
                            </th>
                        </tr>
                        <tr>
                            <th width="28%">Ingresos</th>
                            <th width="28%">Activos</th>
                            <th width="44%">% Total de deserción</th>
                        </tr>
                        
                        <tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').'>
                            <td align="center">'.$ingresosTotales.'</td>
                            <td align="center">'.$actualesTotales.'</td>
                            <td align="center">'.round($desercion,decimales).'%</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
	</div>';
	
	
	echo'
	<div class="col-md-7">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr onclick="obtenerDetallesMatricula(\'\',0)">
                            <th colspan="5" class="encabezadoPrincipal">
                                Maestrías
                            </th>
                        </tr>
                        <tr>
                            <th width="20%">Cuatrimestre</th>
                            <th width="20%"># Ingresos</th>
                            <th width="20%"># Actual</th>
                            <th width="20%">Resultados</th>
                            <th width="20%">Meta</th>
                        </tr>';
                    
                    $i=1;
                    
                    $ingresosMaestria		= 0;
                    $actualMaestria			= 0;
                    $metaMaestria			= 0;
                    $desercionMaestria		= 0;
                    
                    foreach($maestrias as $row)
                    {
                        $ingresosMaestria		+= $row->ingresos;
                        $actualMaestria			+= $row->actual;
                        $metaMaestria			+= $row->meta;
                        
                        $desercion				= (1-($row->actual/$row->ingresos))*100;
                        $desercionMaestria		+= $desercion;
                        
                        echo'
                        <tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').' onclick="obtenerDetallesMatricula('.$row->cuatrimestre.',0)">
                            <td align="center">'.$row->cuatrimestre.'</td>
                            <td align="center">'.$row->ingresos.'</td>
                            <td align="center">'.$row->actual.'</td>
                            <td align="center">'.round($desercion,decimales).'%</td>
                            <td align="center">'.round($row->meta,decimales).'%</td>
                        </tr>';
                    
                        $i++;
                    }
                    
                    echo '
                        <tr '.($i%2>0?'class="sinSombra"':'class="sombreado"').' onclick="obtenerDetallesMatricula(\'\',0)">
                            <td class="totales" align="center">Total</td>
                            <td class="totales" align="center">'.$ingresosMaestria.'</td>
                            <td class="totales" align="center">'.$actualMaestria.'</td>
                            <td class="totales" align="center">'.round($desercionMaestria/count($maestrias),decimales).'%</td>
                            <td class="totales" align="center">'.round($metaMaestria/count($maestrias),decimales).'%</td>
                        </tr>
                    </table>
                </div>
            </div>
		</div>
		<div class="col-5">&nbsp;</div>';

	
        ?>
    

</div>
