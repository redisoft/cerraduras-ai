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
            <td align="right"><?php #echo $factura->UUID?></td>
        </tr>
        
        <tr>
            <td class="negrita">Certificado SAT</td>
            <td align="right"><?php #echo $factura->certificadoSat?></td>
        </tr>
        <tr>
            <td class="negrita">Certificado del emisor</td>
            <td align="right"><?php echo $emisor->numeroCertificado?></td>
        </tr>
        
        <tr>
            <td class="negrita">Fecha y hora de certificación</td>
            <td align="right"><?php #echo $factura->fechaTimbrado?></td>
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
            <td align="right">Recibo de nómina</td>
        </tr>
        <tr>
            <td class="negrita">Folio/Serie</td>
            <td align="right"><?php echo $this->input->post('txtFolioRecibo')?></td>
        </tr>
        <tr>
            <td class="negrita">Fecha y hora de emisión</td>
            <td align="right"><?php echo date('Y-m-d')?></td>
        </tr>
        <tr>
            <td class="negrita">Método de pago</td>
            <td align="right"><?php echo $this->input->post('txtMetodoPagoTexto')?></td>
        </tr>
        <tr>
            <td class="negrita">No. de cuenta de pago</td>
            <td align="right"><?php echo $emisor->numeroCuenta?></td>
        </tr>
        <tr>
            <td class="negrita">Moneda</td>
            <td align="right">Pesos</td>
        </tr>
        <tr>
            <td class="negrita">Tipo de cambio</td>
            <td align="right">1.0</td>
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
            <td align="right"><?php echo $antiguedad?></td>
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
            <td align="right"><?php echo $this->input->post('txtFechaInicialPago')?></td>
        </tr>
        <tr>
            <td class="negrita">Fecha final de pago</td>
            <td align="right"><?php echo $this->input->post('txtFechaFinalPago')?></td>
        </tr>
        <tr>
            <td class="negrita">Número de días pagados</td>
            <td align="right"><?php echo $this->input->post('txtDiasTrabajados')?></td>
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
	<img src="<?php echo base_url().'img/sinValor.png'?>" style="position:absolute; margin-top:-40%; margin-left:25%; width:300px; height:300px"/>
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
				
				$numeroPercepciones	=$this->input->post('txtNumeroPercepciones');
				
                for($p=0;$p<$numeroPercepciones;$p++)
				{
					if(strlen($this->input->post('txtTipoPercepcion'.$p))>1)
					{
						$i++;
						$gravadoPercepcion	+=$this->input->post('txtImporteGravadoPercepcion'.$p);
						$exentoPercepcion	+=$this->input->post('txtImporteExentoPercepcion'.$p);
						
						echo'
						<tr>
							<td class="conceptosNomina">'.$this->input->post('txtTipoPercepcion'.$p).'</td>
							<td>'.$this->input->post('txtClavePercepcion'.$p).'</td>
							<td>'.$this->input->post('txtConceptoPercepcion'.$p).'</td>
							<td align="right">'.number_format($this->input->post('txtImporteGravadoPercepcion'.$p),2).'</td>
							<td align="right">'.number_format($this->input->post('txtImporteExentoPercepcion'.$p),2).'</td>
						</tr>';
					}
					
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
				
				$numeroDeducciones	=$this->input->post('txtNumeroDeducciones');
				
                for($d=0;$d<$numeroDeducciones;$d++)
				{
					if(strlen($this->input->post('txtTipoDeduccion'.$d))>1)
					{
						$i++;
						$gravadoDeduccion	+=$this->input->post('txtImporteGravadoDeduccion'.$d);
						$exentoDeduccion	+=$this->input->post('txtImporteExentoDeduccion'.$d);
						
						echo'
						<tr>
							<td class="conceptosNomina">'.$this->input->post('txtTipoDeduccion'.$d).'</td>
							<td>'.$this->input->post('txtClaveDeduccion'.$d).'</td>
							<td>'.$this->input->post('txtConceptoDeduccion'.$d).'</td>
							<td align="right">'.number_format($this->input->post('txtImporteGravadoDeduccion'.$d),2).'</td>
							<td align="right">'.number_format($this->input->post('txtImporteExentoDeduccion'.$d),2).'</td>
						</tr>';
					}
					
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
                    <td style="width:13%; text-align:right">$<?php echo number_format($gravadoDeduccion+$exentoDeduccion-$this->input->post('txtTotalIsr'),2)?></td>
                </tr>
                
                <tr>
                	<td align="right" class="negrita">ISR</td>
                    <td style="width:13%; text-align:right">$<?php echo number_format($this->input->post('txtTotalIsr'),2)?></td>
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
			<?php echo $this->input->post('txtFormaPago')?><br />

			<strong style="color:#666">Cadena Original del Complemento de Certificación Digital del SAT</strong><br />
			<?php
           
		   ?>
            
            <strong style="color:#666">Sello Digital del SAT</strong><br />
            
            <?php 
		   	
		   ?>
        </td>
        <td align="right">
        	
        </td>
    </tr>
</table>
</div>


