<?php
class PDF extends CI_Controller
{
    protected $_fechaActual;
    protected $_iduser;
    protected $_csstyle;
    protected $_tables;
    protected $_role;
	protected $cuota;
	protected $idLicencia;

    function __construct()
	{
		 parent::__construct();
	
		if( ! $this->redux_auth->logged_in() )
		{//verificar si el el usuario ha iniciado sesion
			#redirect(base_url().'login');
		}
        $datestring   		= "%Y-%m-%d %H:%i:%s";
		$this->_fechaActual = mdate($datestring,now());

         $this->_iduser	 	= $this->session->userdata('id');
         $this->_role 		= $this->session->userdata('role');
		 $this->idLicencia 	= $this->session->userdata('idLicencia');

         $this->config->load('datatables', TRUE);
         $this->_tables = $this->config->item('datatables');

        $this->load->model("modelousuario","modelousuario");
        $this->load->model("modeloclientes","clientes");
        $this->load->model("inventario_model","modeloinventario");        
        $this->load->model('proveedores_model','modeloproveedores');
        $this->load->model("modelo_configuracion","configuracion");
		$this->load->model("compras_modelo","compras");
		$this->load->model("ventas_model","ventas");
		$this->load->model("facturacion_modelo","facturacion");
		$this->load->model("reportes_model","reportes");
		$this->load->model("nomina_modelo","nomina");
		$this->load->model("tiendas_modelo","tiendas");
		$this->load->model("pagos_modelo","pagos");
		$this->load->model('facturaglobal_modelo','facturaGlobal');
		
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$this->configuracion->accesoUsuario(); //CONTROL DE ACCESOS
		$this->cuota	= $this->configuracion->comprobarCuota(); //COMPROBAR CUOTA DE DISCO
	}
	
	public function crearFactura($idFactura,$imprimir='0')
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->helper('qrlib');
		
		$data['factura'] 		= $this->facturacion->obtenerFactura($idFactura);
		$data['cliente'] 		= $this->clientes->obtenerDatosCliente($data['factura']->idCliente);
		$data['retencion'] 		= $this->facturacion->obtenerRetenciones($idFactura);
		$data['productos'] 		= $this->facturacion->obtenerProductosFacturados($idFactura,$data['factura']->pago);
		$data['configuracion'] 	= $this->facturacion->obtenerEmisor($data['factura']->idEmisor);
		$data['cuentas']		= $this->configuracion->obtenerCuentasReportes();	
		$data['relaciones']		= $this->facturacion->obtenerRelaciones($idFactura);
		$data['canceladas']		= $this->facturaGlobal->obtenerFoliosVentaFacturaCanceladas($data['factura']->inicio,$data['factura']->fin,$data['factura']->prefacturas);
		$data['reporte']		= 'facturacion/factura';	
		
		if($data['factura']->pago=='1')
		{
			$data['pago'] 		= $this->pagos->obtenerPago($idFactura);
			$data['relacion'] 	= $this->pagos->obtenerFacturaRelacion($idFactura);
			$data['reporte']	= 'facturacion/pagos/pago';	
		}

		generarCodigoBidimensional($data['factura'],$data['configuracion']);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['factura']->total);
		$this->ccantidadletras->setMoneda($data['factura']->divisa);//

		$data['cantidadLetra']	= $this->ccantidadletras->PrimeraMayuscula();

		/*$html					= $this->load->view('facturacion/facturaCapymet',$data,true);
		$pie 					= $this->load->view('facturacion/pieCapymet',$data,true);*/
		
		$html					= $this->load->view('facturacion/principal',$data,true);
		
		if($data['factura']->pago=='0')
		{
			$pie 					= $this->load->view('facturacion/pie',$data,true);
		}
		else
		{
			$pie 					= $this->load->view('facturacion/pagos/pie',$data,true);
		}
		
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,78,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		if($imprimir=='0')
		{
			#$this->mpdf->Output();
			$this->mpdf->Output($data['configuracion']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.pdf','D');
		}
		
		if($imprimir=='1')
		{
			$this->mpdf->Output(carpetaCfdi.$data['configuracion']->rfc.'/folio'.$data['factura']->serie.$data['factura']->folio.'/'.$data['configuracion']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.pdf','F');
		}
		
		if($imprimir=='2')
		{
			$this->mpdf->Output($data['configuracion']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.pdf','D');
		}
	}
	
	public function crearFacturaPrueba($idFactura,$imprimir='0')
	{
		ini_set("memory_limit","1500M");
		set_time_limit(0); 
		
		$this->load->helper('qrlib');
		
		$data['factura'] 		= $this->facturacion->obtenerFactura($idFactura);
		$data['cliente'] 		= $this->clientes->obtenerDatosCliente($data['factura']->idCliente);
		$data['retencion'] 		= $this->facturacion->obtenerRetenciones($idFactura);
		$data['productos'] 		= $this->facturacion->obtenerProductosFacturados($idFactura,$data['factura']->pago);
		$data['configuracion'] 	= $this->facturacion->obtenerEmisor($data['factura']->idEmisor);
		$data['cuentas']		= $this->configuracion->obtenerCuentasReportes();	
		$data['relaciones']		= $this->facturacion->obtenerRelaciones($idFactura);
		$data['canceladas']		= $this->facturaGlobal->obtenerFoliosVentaFacturaCanceladas($data['factura']->inicio,$data['factura']->fin,$data['factura']->prefacturas);
		$data['reporte']		= 'facturacion/factura';	
		
		if($data['factura']->pago=='1')
		{
			$data['pago'] 		= $this->pagos->obtenerPago($idFactura);
			$data['relacion'] 	= $this->pagos->obtenerFacturaRelacion($idFactura);
			$data['reporte']	= 'facturacion/pagos/pago';	
		}

		generarCodigoBidimensional($data['factura'],$data['configuracion']);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['factura']->total);
		$this->ccantidadletras->setMoneda($data['factura']->divisa);//

		$data['cantidadLetra']	= $this->ccantidadletras->PrimeraMayuscula();

		/*$html					= $this->load->view('facturacion/facturaCapymet',$data,true);
		$pie 					= $this->load->view('facturacion/pieCapymet',$data,true);*/
		
		$html					= $this->load->view('facturacion/principal',$data,true);
		
		if($data['factura']->pago=='0')
		{
			$pie 					= $this->load->view('facturacion/pie',$data,true);
		}
		else
		{
			$pie 					= $this->load->view('facturacion/pagos/pie',$data,true);
		}
		
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,78,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		$this->mpdf->Output();
	}
	
	public function reciboNomina($idFactura,$imprimir='0')
	{
		$this->load->helper('qrlib');
		
		$data['factura'] 		= $this->facturacion->obtenerFactura($idFactura);
		$data['percepciones'] 	= $this->reportes->obtenerPercepciones($idFactura);
		$data['deducciones'] 	= $this->reportes->obtenerDeducciones($idFactura);
		$data['empleado'] 		= $this->reportes->obtenerEmpleado($idFactura);
		$data['emisor']			= $this->configuracion->obtenerEmisor($data['factura']->idEmisor);
		$data['reporte']		= 'nomina/reciboNomina';
		
		generarCodigoBidimensional($data['factura'],$data['emisor']);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['factura']->total);
		$this->ccantidadletras->setMoneda("pesos");//
		$data['cantidadLetra']	=$this->ccantidadletras->PrimeraMayuscula();

		$html					= $this->load->view('nomina/principal',$data,true);
		$pie 					= $this->load->view('nomina/pieNomina',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,10,10,2,1);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		if($imprimir=='0')
		{
			$this->mpdf->Output();
		}
		
		if($imprimir=='1')
		{
			$this->mpdf->Output(carpetaCfdi.$data['emisor']->rfc.'/folio'.$data['factura']->serie.$data['factura']->folio.'/'.$data['emisor']->rfc.'_'.$data['factura']->serie.$data['factura']->folio.'.pdf','F');
		}
	}
	
	public function previaReciboNomina($idEmpleado)
	{
		if(!empty($_POST))
		{
			$this->load->library('ccantidadletras');
			$this->load->library('mpdf/mpdf');
			$this->load->helper('qrlib');
			
			$data['empleado'] 		=$this->nomina->obtenerEmpleado($idEmpleado);
			$data['emisor']			=$this->configuracion->obtenerEmisor($this->input->post('selectEmisores'));
			$data['antiguedad'] 	=$this->nomina->obtenerAntiguedad($data['empleado']->fechaInicio,date('Y-m-d'));
			$data['reporte']		='nomina/reciboNominaPrevia';
			
			$this->ccantidadletras->setIdioma("ES");
			$this->ccantidadletras->setNumero($this->input->post('txtTotales'));
			$this->ccantidadletras->setMoneda("pesos");//
			$data['cantidadLetra']	=$this->ccantidadletras->PrimeraMayuscula();
	
			$html	= $this->load->view('nomina/principal',$data,true);
			$pie 	= $this->load->view('nomina/pieNomina',$data,true);
			
			$this->mpdf->mPDF('en-x','Letter','','',10,10,10,10,2,1);
			$this->mpdf->SetHTMLFooter($pie);
			$this->mpdf->SetHTMLFooter($pie,'E');
			
			$this->mpdf->mirrorMargins = 1;
			$this->mpdf->WriteHTML($html);
			$this->mpdf->Output(carpetaCfdi.'previaRecibo.pdf','F');
		}
		else
		{
			echo 'Genere la previa desde el formulario';
		}
	}
	
	public function vistaPrevia()
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['factura'] 		= $this->facturacion->obtenerFacturaVista();
		$data['cliente'] 		= $this->clientes->obtenerDatosCliente($data['factura']->idCliente);
		$data['retencion'] 		= $this->facturacion->obtenerRetencionVista($data['factura']->idFactura);
		$data['productos'] 		= $this->facturacion->obtenerProductosFacturadosVista($data['factura']->idFactura);
		$data['configuracion'] 	= $this->facturacion->obtenerEmisor($data['factura']->idEmisor);
		
		#$this->generarCodigoBidimensional($data['factura'],$data['configuracion']);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['factura']->total);
		$this->ccantidadletras->setMoneda($data['factura']->divisa);//
		
		$CantidadLetras			= $this->ccantidadletras->PrimeraMayuscula();
		$data['cantidadLetra']	= $CantidadLetras;

		$html	=$this->load->view('facturacion/facturaPrevia',$data,true);
		$pie 	= $this->load->view('facturacion/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,62,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}

	public function comprasPDF($idCompra,$idLicencia)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');

		$data['compra'] =$this->compras->obtenerCompra($idCompra);
		#$data['cliente'] =$this->clientes->obtenerDatosCliente($data['factura']->cliente);
		$data['productos'] =$this->compras->obtenerProductosPDF($idCompra);
		$data['empresa'] =$this->configuracion->obtenerConfiguraciones($idLicencia);
		 
		#print($data['compra']->total);
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['compra']->total);
		$this->ccantidadletras->setMoneda("pesos");//
		$CantidadLetras=$this->ccantidadletras->PrimeraMayuscula();
		
		$data['cantidadLetra']=$CantidadLetras;
		
		$html=$this->load->view('compras/pdfCompras',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	

	public function remisiones($remision)
	{
		$remisiones=$this->clientes->remisiones($remision);
		
		redirect('clientes');
	}
	
	public function generarRemision($idCotizacion)
	{
		$this->load->library('ccantidadletras');
		
		$data['factura'] =$this->ventas->obtenerFactura($idFactura);
		$data['cliente'] =$this->clientes->obtenerDatosCliente($data['factura']->cliente);
		$data['productos'] =$this->ventas->obtenerProductosFacturados($idFactura);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['factura']->total);
		$this->ccantidadletras->setMoneda("pesos");//
		$CantidadLetras=$this->ccantidadletras->PrimeraMayuscula();
		
		$data['cantidadLetra']=$CantidadLetras;
		
		$this ->load->plugin('to_pdf');
		$html=$this->load->view('factura_ventas/pdfFactura',$data,true);
		
		pdf_create ($html,'Remision');
	}
	
	public function cotizacionPdf($idCotizacion,$desglose=0,$opcion=0)
	{
		$this->reportes->cotizacionPdf($idCotizacion,$desglose,$opcion);
	}
	
	public function nuevaVenta($idCotizacion,$idLicencia)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$remision 			= $this->ventas->obtenerRemision($idCotizacion);
		$data['cotizacion'] = $this->ventas->obtenerRemision($idCotizacion);
		$data['cliente'] 	= $this->ventas->obtenerCliente($remision->idCliente);
		$data['productos'] 	= $this->ventas->obtenerProductos($remision->idCotizacion);
		$data['empresa'] 	= $this->configuracion->obtenerConfiguraciones($idLicencia);
		$data['tienda'] 	= $this->tiendas->obtenerTienda($data['cotizacion']->idTienda);
		$data['cuentas']	= $this->configuracion->obtenerCuentasReportes();
		$data['reporte'] 	= 'clientes/venta/remision';
		$data['titulo'] 	= 'Venta';
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($remision->total);
		$this->ccantidadletras->setMoneda($remision->divisa);//
		$CantidadLetras		= $this->ccantidadletras->PrimeraMayuscula();
		
		$data['cantidadLetra']=$CantidadLetras;

		$html				= $this->load->view('reportes/principal',$data,true);
		$pie				= $this->load->view('reportes/pieCotizacion',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,10,47,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function nuevaRemision($idCotizacion=0)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$remision 			= $this->ventas->obtenerRemision($idCotizacion);
		$data['cotizacion'] = $this->ventas->obtenerRemision($idCotizacion);
		$data['cliente'] 	= $this->ventas->obtenerCliente($remision->idCliente);
		$data['productos'] 	= $this->ventas->obtenerProductos($remision->idCotizacion);
		$data['empresa'] 	= $this->configuracion->obtenerConfiguraciones(1);
		$data['tienda'] 	= $this->tiendas->obtenerTienda($data['cotizacion']->idTienda);
		$data['reporte'] 	= 'clientes/venta/remisionHigienica';
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($remision->total);
		$this->ccantidadletras->setMoneda($remision->divisa);//
		$CantidadLetras		=$this->ccantidadletras->PrimeraMayuscula();
		
		$data['cantidadLetra']=$CantidadLetras;

		$html	=$this->load->view('reportes/principal',$data,true);
		$pie	=$this->load->view('reportes/pie',$data,true);

		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,0);
		#$this->mpdf->SetHTMLFooter($pie);
		#$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function nuevaRemisionFormato($idCotizacion=0)
	{
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');

		$data['cotizacion'] 	= $this->ventas->obtenerRemision($idCotizacion);
		$data['cliente'] 		= $this->ventas->obtenerCliente($data['cotizacion']->idCliente);
		$data['productos'] 		= $this->ventas->obtenerProductos($data['cotizacion']->idCotizacion);
		$data['empresa'] 		= $this->configuracion->obtenerConfiguraciones(1);
		$data['tienda'] 		= $this->tiendas->obtenerTienda($data['cotizacion']->idTienda);
		$data['reporte'] 		= 'clientes/venta/remisionHigienicaFormato';
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['cotizacion']->total);
		$this->ccantidadletras->setMoneda($data['cotizacion']->divisa);//

		$data['cantidadLetra']=$this->ccantidadletras->PrimeraMayuscula();

		$this->load->view('clientes/venta/principal',$data);
		
	}
	
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	//PARA LA FACTURACIÓN DEL SAT
	//->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->->
	
	public function crearFacturaSat($idFactura,$criterio=0)
	{
		$this->load->helper('xml');
		
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['factura'] 		= $this->reportes->obtenerFacturaSat($idFactura);
		
		$carpeta				= "media/sat/";
		$fichero				= $data['factura']->rfcEmisor.'_'.obtenerFechaMesCorto($data['factura']->fecha).'_'.$data['factura']->serie.$data['factura']->folio.'.xml';
		
		guardarFichero($carpeta.$fichero,$data['factura']->xml);
		
		
		$data['xml'] 			= procesarXmlCfdi($carpeta.$fichero);

		$this->generarCodigoBidimensionalSat($data['xml'],$data['factura']);
		
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['xml'][4]);
		$this->ccantidadletras->setMoneda($data['xml'][14]);//
		
		$CantidadLetras			=$this->ccantidadletras->PrimeraMayuscula();
		$data['cantidadLetra']	=$CantidadLetras;

		$html	=$this->load->view('reportes/facturacionSat/pdf/factura',$data,true);
		$pie 	= $this->load->view('reportes/facturacionSat/pdf/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		
		if($criterio==0)
		{
			$this->mpdf->Output();
		}
		else
		{
			$this->mpdf->Output('media/sat/'.$data['factura']->rfcEmisor.'_'.obtenerFechaMesCorto($data['factura']->fecha).'_'.$data['factura']->serie.$data['factura']->folio.'.pdf','F');
		}
	}
	
	public function generarCodigoBidimensionalSat($xml,$factura)
	{
		$this->load->helper('qrlib');

		$partes			= explode(".",$xml[4]);
		
		$entero			= $partes[0];
		$decimal		= $partes[1];
		
		$valor			= strlen($entero);
		$ceros			= 10-$valor;
		$ceroEntero		= "";
		
		for($i=1;$i<=$ceros;$i++)
		{
			$ceroEntero.="0";
		}
		
		$ceroEntero.=$entero;
		
		$valor			= strlen($decimal);
		$ceros			= 6-$valor;
		$ceroDecimal	= "";
		
		for($i=1;$i<=$ceros;$i++)
		{
			$ceroDecimal.="0";
		}
		
		$ceroDecimal=$decimal.$ceroDecimal;
		
		$codigoBidimensional = "?re=".$xml[15]."&rr=".$xml[24]."&tt=".$ceroEntero.".".$ceroDecimal."&id=".$xml[40]."";

		$codigo='media/sat/'.$xml[15].'_'.obtenerFechaMesCorto($factura->fecha).'_'.$xml[11].$xml[12].'.png';
		
		#if(!file_exists($codigo))
		#{
			QRcode::png($codigoBidimensional, $codigo, 'L', 3, 2);
		#}
	}
	
	/*public function crearFacturaSat($idFactura)
	{
		$this->load->helper('xml');
		
		$this->load->library('ccantidadletras');
		$this->load->library('mpdf/mpdf');
		
		$data['factura'] 		=$this->facturacion->obtenerFacturaSat($idFactura);
		
		$carpeta	= "media/sat/";
		$fichero	= $data['factura']->serie.$data['factura']->folio.'.xml';
		
		guardarFichero($carpeta.$fichero,$data['factura']->xml);
		
		
		$data['xml'] 			= procesarXmlCfdi($carpeta.$fichero);

		$this->generarCodigoBidimensionalSat($data['xml']);
		
		$this->ccantidadletras->setIdioma("ES");
        $this->ccantidadletras->setNumero($data['xml'][4]);
		$this->ccantidadletras->setMoneda($data['xml'][14]);//
		
		$CantidadLetras			=$this->ccantidadletras->PrimeraMayuscula();
		$data['cantidadLetra']	=$CantidadLetras;

		$html	=$this->load->view('reportes/facturacionSat/pdf/factura',$data,true);
		$pie 	= $this->load->view('reportes/facturacionSat/pdf/pie',$data,true);
		
		$this->mpdf->mPDF('en-x','Letter','','',10,10,5,47,2,0);
		$this->mpdf->SetHTMLFooter($pie);
		$this->mpdf->SetHTMLFooter($pie,'E');
		$this->mpdf->mirrorMargins = 1;
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();
	}
	
	public function generarCodigoBidimensionalSat($xml)
	{
		$this->load->helper('qrlib');

		$partes			= explode(".",$xml[4]);
		
		$entero			= $partes[0];
		$decimal		= $partes[1];
		
		$valor			= strlen($entero);
		$ceros			= 10-$valor;
		$ceroEntero		= "";
		
		for($i=1;$i<=$ceros;$i++)
		{
			$ceroEntero.="0";
		}
		
		$ceroEntero.=$entero;
		
		$valor			= strlen($decimal);
		$ceros			= 6-$valor;
		$ceroDecimal	= "";
		
		for($i=1;$i<=$ceros;$i++)
		{
			$ceroDecimal.="0";
		}
		
		$ceroDecimal=$decimal.$ceroDecimal;
		
		$codigoBidimensional = "?re=".$xml[15]."&rr=".$xml[24]."&tt=".$ceroEntero.".".$ceroDecimal."&id=".$xml[40]."";

		$codigo='media/sat/'.$xml[11].$xml[12].'.png';
		
		#if(!file_exists($codigo))
		#{
			QRcode::png($codigoBidimensional, $codigo, 'L', 3, 2);
		#}
	}*/
}
?>
