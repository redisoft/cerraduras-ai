<?php
class Pruebas extends CI_Controller
{
    protected $_fechaActual;
	protected $_iduser;
	protected $_csstyle;
    protected $_tables;
	protected $cuota;

	function __construct()
	{
	}
	
	
	public function globalPrueba()
	{
		#exec("xsltproc media/fel/cadenaoriginal_3_3.xslt media/fel/MOSS5409219Q9/folioA1/cfd1.xml > media/fel/MOSS5409219Q9/folioA1/cadena.txt ");
		#exec("xsltproc media/fel/cadenaoriginal_3_3.xslt media/fel/MOSS5409219Q9/folioA1/cfd8.xml > media/fel/MOSS5409219Q9/folioA1/cadena2.txt ");
		
		shell_exec("xsltproc media/fel/cadenaoriginal_3_3.xslt media/fel/cfd8.xml > media/fel/cadena2.txt ");
	}
}

?>
