<div>
	<div style="float:left; border:none;" class="divDatosNomina">
    	<strong style="font-size:24px">CFDI</strong> <span style="font-size:13px">Comprobante Fiscal Digital a través de Internet</span>
    </div>
    <div style="float:left; border:none; text-align:right; font-size:20px" class="divDatosNomina">
    	RECIBO DE NÓMINA
    </div>
    
    <div style="float:left;" class="divDatosNominaResaltado">
    <table width="100%" class="tablaNomina">
        <tr>
            <td class="negrita">Folio Fiscal</td>
            <td align="right"><?php echo $factura->UUID?></td>
        </tr>
        
        <tr>
            <td class="negrita">Certificado SAT</td>
            <td align="right"><?php echo $factura->certificadoSat?></td>
        </tr>
        <tr>
            <td class="negrita">Certificado del emisor</td>
            <td align="right"><?php echo $emisor->numeroCertificado?></td>
        </tr>
        
        <tr>
            <td class="negrita">Fecha y hora de certificación</td>
            <td align="right"><?php echo $factura->fechaTimbrado?></td>
        </tr>
        <tr>
            <td class="negrita">Régimen fiscal</td>
            <td align="right"><?php echo $emisor->regimenFiscal?></td>
        </tr>
        <tr>
            <td class="negrita">Expedición</td>
            <td align="right"><?php echo $emisor->municipio.', '.$emisor->estado?></td>
        </tr>
        <tr>
            <td class="negrita">Tipo de comprobante</td>
            <td align="right"><?php echo $factura->documento?></td>
        </tr>
        <tr>
            <td class="negrita">Folio/Serie</td>
            <td align="right"><?php echo $factura->serie.$factura->folio?></td>
        </tr>
        <tr>
            <td class="negrita">Fecha y hora de emisión</td>
            <td align="right"><?php echo $factura->fecha?></td>
        </tr>
        <tr>
            <td class="negrita">Método de pago</td>
            <td align="right"><?php echo $factura->metodoPago?></td>
        </tr>
        <tr>
            <td class="negrita">No. de cuenta de pago</td>
            <td align="right"><?php echo $emisor->numeroCuenta?></td>
        </tr>
        <tr>
            <td class="negrita">Moneda</td>
            <td align="right"><?php echo $factura->divisa?></td>
        </tr>
        <tr>
            <td class="negrita">Tipo de cambio</td>
            <td align="right"><?php echo number_format($factura->tipoCambio,2)?></td>
        </tr>
    </table>
    </div>
    
    <div style="float:right;" class="divDatosNominaResaltado">
    <table width="100%" class="tablaNomina">
        <tr>
            <td class="negrita">Registro Patronal</td>
            <td align="right"><?php echo $empleado->registroPatronal?></td>
        </tr>
        
        <tr>
            <td class="negrita">Número de empleado</td>
            <td align="right"><?php echo $empleado->numeroEmpleado?></td>
        </tr>
        <tr>
            <td class="negrita">Departamento</td>
            <td align="right"><?php echo $empleado->departamento?></td>
        </tr>
        
        <tr>
            <td class="negrita">Puesto</td>
            <td align="right"><?php echo $empleado->puesto?></td>
        </tr>
        <tr>
            <td class="negrita">Riesgo de puesto</td>
            <td align="right"><?php echo $empleado->riesgo?></td>
        </tr>
        <tr>
            <td class="negrita">Tipo de contrato</td>
            <td align="right"><?php echo $empleado->tipoContrato?></td>
        </tr>
        <tr>
            <td class="negrita">Tipo de jornada</td>
            <td align="right"><?php echo $empleado->tipoJornada?></td>
        </tr>
        <tr>
            <td class="negrita">Antigüedad</td>
            <td align="right"><?php echo $empleado->antiguedad?></td>
        </tr>
        <tr>
            <td class="negrita">Inicio de la relación laboral</td>
            <td align="right"><?php echo $empleado->fechaInicio?></td>
        </tr>
        <tr>
            <td class="negrita">Periodo de pago</td>
            <td align="right"><?php echo $empleado->periodicidadPago?></td>
        </tr>
        <tr>
            <td class="negrita">Salario Base de Cotización</td>
            <td align="right"><?php echo number_format($empleado->salarioBase,2)?></td>
        </tr>
        <tr>
            <td class="negrita">Salario Diario Integrado</td>
            <td align="right"><?php echo number_format($empleado->salarioDiario,2)?></td>
        </tr>
        <tr>
            <td class="negrita">Fecha de pago</td>
            <td align="right"><?php echo $empleado->fechaPago?></td>
        </tr>
        <tr>
            <td class="negrita">Fecha inicial de pago</td>
            <td align="right"><?php echo $empleado->fechaInicialPago?></td>
        </tr>
        <tr>
            <td class="negrita">Fecha final de pago</td>
            <td align="right"><?php echo $empleado->fechaFinalPago?></td>
        </tr>
        <tr>
            <td class="negrita">Número de días pagados</td>
            <td align="right"><?php echo $empleado->diasTrabajados?></td>
        </tr>
        <tr>
            <td class="negrita">Banco</td>
            <td align="right"><?php echo $empleado->banco?></td>
        </tr>
        <tr>
            <td class="negrita">Clabe</td>
            <td align="right"><?php echo $empleado->clabe?></td>
        </tr>
    </table>
    </div>
    
    <div style="float:left;" class="divDatosNomina">
   
		<?php
		echo '<strong>Empleador</strong> <br /><br />';
        echo '<strong>'.$emisor->rfc.'</strong> '.$emisor->nombre.'<br /><br />';
        echo $emisor->calle.' '. $emisor->numeroExterior.' <br />
		COL. '.$emisor->colonia.' '.$emisor->localidad.'C.P'.$emisor->codigoPostal.'<br />
		'. $emisor->municipio.', '.$emisor->estado.', '.$emisor->pais.'<br /><br />';
		
		echo '<strong>Expedido en</strong> <br />';
		echo $emisor->calle.' '. $emisor->numeroExterior.' <br />
		COL. '.$emisor->colonia.' '.$emisor->localidad.'C.P'.$emisor->codigoPostal.'<br />
		'. $emisor->municipio.', '.$emisor->estado.', '.$emisor->pais;
        ?>
         
    </div>
    
     <div style="float:right; height:72px" class="divDatosNomina">
		<?php
        echo '<strong>Empleado</strong> <br /><br />
		
		<strong>'.$empleado->rfc.'</strong> '.$empleado->nombre.'<br />
		C.U.R.P. '.$empleado->curp.'<br />
		N.S.S. '.$empleado->numeroSeguridad;

        ?>
    </div>
    
</div>

<div style="margin-top:0px">
<table class="tablaNomina" style="width:100%; background-color:#FFF; border:none">
	<tr>
		<th width="50%" align="left" style="border:none">Percepciones</th>
        <th width="50%" align="left" style="border:none">Deducciones</th>
    </tr>
    <tr>
    	<td valign="top">
            <table class="tablaNomina" style="width:100%; background-color:#FFF">
                <tr>
                    <th class="titulosTablas" width="20%">Tipo de percepción</th>
                    <th class="titulosTablas">Clave</th>
                    <th class="titulosTablas">Concepto</th>
                    <th class="titulosTablas" width="20%">Importe gravado</th>
                    <th class="titulosTablas" width="20%">Importe exento</th>
                </tr>
                <?php
				$gravadoPercepcion	=0;
				$exentoPercepcion	=0;
				$max				=6;
				$i=1;
                foreach($percepciones as $row)
				{
					$gravadoPercepcion	+=$row->importeGravado;
					$exentoPercepcion	+=$row->importeExento;
					
					echo'
					<tr>
						<td class="conceptosNomina">'.$row->tipoPercepcion.'</td>
						<td>'.$row->clave.'</td>
						<td>'.$row->concepto.'</td>
						<td align="right">'.number_format($row->importeGravado,2).'</td>
						<td align="right">'.number_format($row->importeExento,2).'</td>
					</tr>';
					
					$i++;
				}
				
				for($a=$i;$a<=$max;$a++)
				{
					echo'
					<tr>
						<td class="conceptosNomina"></td>
						<td></td>
						<td></td>
						<td align="right"></td>
						<td align="right"></td>
					</tr>';
				}
				
				echo '
				<tr>
					<td class="negritaBorde" colspan="3">Suma percepciones</td>
					<td class="negritaBorde" class="negritaBorde" align="right">'.number_format($gravadoPercepcion,2).'</td>
					<td class="negritaBorde" align="right">'.number_format($exentoPercepcion,2).'</td>
				</tr>';
				?>
                
            </table>
        </td>
        <td valign="top">
        	<table class="tablaNomina" style="width:100%; ; background-color:#FFF; ">
                <tr>
                    <th class="titulosTablas"  width="20%">Tipo de deducción</th>
                    <th class="titulosTablas">Clave</th>
                    <th class="titulosTablas">Concepto</th>
                    <th class="titulosTablas" width="20%">Importe gravado</th>
                    <th class="titulosTablas" width="20%" align="right">Importe exento</th>
                </tr>
                <?php
				$gravadoDeduccion	=0;
				$exentoDeduccion	=0;
				$i=1;
                foreach($deducciones as $row)
				{
					$gravadoDeduccion	+=$row->importeGravado;
					$exentoDeduccion	+=$row->importeExento;
					
					echo'
					<tr>
						<td class="conceptosNomina">'.$row->tipoDeduccion.'</td>
						<td>'.$row->clave.'</td>
						<td>'.$row->concepto.'</td>
						<td align="right">'.number_format($row->importeGravado,2).'</td>
						<td align="right">'.number_format($row->importeExento,2).'</td>
					</tr>';
					
					$i++;
				}
				
				for($a=$i;$a<=$max;$a++)
				{
					echo'
					<tr>
						<td class="conceptosNomina"></td>
						<td></td>
						<td></td>
						<td align="right"></td>
						<td align="right"></td>
					</tr>';
				}
				
				echo '
				<tr>
					<td class="negritaBorde" colspan="3">Suma percepciones</td>
					<td class="negritaBorde" align="right">'.number_format($gravadoDeduccion,2).'</td>
					<td class="negritaBorde" align="right">'.number_format($exentoDeduccion,2).'</td>
				</tr>';
				
				?>
            </table>
        </td>
    </tr>
    
    <tr>
    	<td align="center">
        	<strong style="color:#666">Importe con letra</strong> *** <?php echo $cantidadLetra?> ***
            <br /><br /><br />
			
            __________________________________<br />
					Firma del empleado
        </td>
        <td width="50%">
        	<table class="tablaNomina" style="width:100%; background-color:#FFF; border:none">
            	<tr>
                	<td align="right" class="negrita">Subtotal</td>
                    <td style="width:13%; text-align:right">$<?php echo number_format($gravadoPercepcion+$exentoPercepcion,2)?></td>
                </tr>
                
                <tr>
                	<td align="right" class="negrita">Descuentos</td>
                    <td style="width:13%; text-align:right">$<?php echo number_format($gravadoDeduccion+$exentoDeduccion-$factura->retencionIsr,2)?></td>
                </tr>
                
                <tr>
                	<td align="right" class="negrita">ISR</td>
                    <td style="width:13%; text-align:right">$<?php echo number_format($factura->retencionIsr,2)?></td>
                </tr>
                
                <tr>
                	<td align="right" class="negrita" style="font-size:12px">Neto a pagar</td>
                    <td style="width:13%; text-align:right; font-size:12px">$<?php echo number_format(($gravadoPercepcion+$exentoPercepcion)-($gravadoDeduccion+$exentoDeduccion),2)?></td>
                </tr>
                
            </table>
        </td>
    </tr>
    
</table>
</div>

<div>
<table class="tablaNomina" style="background-color:#FFF; border:none; line-height:14px;">
	<tr>
    	<td style="font-size:12px">
        	<strong style="color:#666">Forma de pago</strong>
            <br />
			<?php echo $factura->formaPago?><br />

			<strong style="color:#666">Cadena Original del Complemento de Certificación Digital del SAT</strong><br />
			<?php
            $tamano	=strlen($factura->cadenaTimbre);
			$n		=$tamano/90;
			
			if($tamano%90>0)
			{
				$n++;
			}
			
			$inicio	=0;
			for($i=1;$i<$n;$i++)
			{
				echo substr($factura->cadenaTimbre,$inicio,90).'<br />';
				$inicio	=$inicio+90;
			}
			?>
            
            <strong style="color:#666">Sello Digital del CFDI</strong><br />
            <?php 
		    $tamano	=strlen($factura->selloDigital);
			$n		=$tamano/110;
			
			if($tamano%110>0)
			{
				$n++;
			}
			
			$inicio	=0;
			for($i=1;$i<$n;$i++)
			{
				echo substr($factura->selloDigital,$inicio,110).'<br />';
				$inicio=$inicio+110;
			}
		   ?>
            
            <strong style="color:#666">Sello Digital del SAT</strong><br />
            
            <?php 
		   	$tamano	=strlen($factura->selloSat);
			$n		=$tamano/110;
			
			if($tamano%110>0)
			{
				$n++;
			}
			
			$inicio	=0;
			for($i=1;$i<$n;$i++)
			{
				echo substr($factura->selloSat,$inicio,110).'<br />';
				$inicio=$inicio+110;
			}
		   ?>
        </td>
        <td align="right">
        	<img src="<?php echo base_url().'media/fel/'.$emisor->rfc.'/folio'.$factura->serie.$factura->folio.'/codigo'.$factura->folio.'.png'?>"/>
        </td>
    </tr>
</table>
</div>


