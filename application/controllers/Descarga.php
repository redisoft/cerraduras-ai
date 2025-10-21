<?php
class Descarga extends CI_Controller
{
	protected $_fechaActual;
	protected $_iduser;
	protected $_csstyle;
    protected $_tables;
    protected $_role;
	protected $idTienda;
	protected $cuota;

	function __construct()
	{
		parent::__construct();

		
	}

	#========================================================================================================#
	#=============================================  COTIZACIONES ============================================#
	#========================================================================================================#
	
	public function index()
	{
		$this->load->helper('download');
			
		ini_set('max_execution_time', 500000);
		$resultCFDILinks		= null;
		$cfdiXMLLinks[]			= null;
		$rawArrayInvoiceBuffer	= null;
		$tempCFDILinks			= "";
		//Open file invoices.txt (Considering <div class='DivPaginas'... saved in all file) and then we buffer content
		if($rawBodyInvoice		= fopen('media/invoice.txt','r'))
		{
			while(!feof($rawBodyInvoice)) 
			{
				$rawArrayInvoiceBuffer	.=	fgets($rawBodyInvoice);
			}
			//Searching all paterns that we are looking
			preg_match_all('/AccionCfdi([^<]*)Recuperacion/', $rawArrayInvoiceBuffer, $resultCFDILinks);
			//Reasign matching parameters links
			$resultCFDILinks = $resultCFDILinks[0];
			//Form all links using the parameters and webpage
			foreach($resultCFDILinks as $CFDILinks)
			{
				$cfdiXMLLinks[] = 'https://portalcfdi.facturaelectronica.sat.gob.mx/' . substr(substr($CFDILinks,12),0,-15);
			}
			//Open all links to download .xml files
			foreach($cfdiXMLLinks as $links)
			{
				if(strlen($links) === 0)
				{
					//Empty link
				}
				else
				{
					#echo $links;
					/*echo "<script language='javascript'>window.open('" . $links . "')</script>";*/
					
					$data 		= file_get_contents($links,true); 
					#echo $data;
					#force_download(rand().'.xml', $data); 
					var_dump($data);
					
					sleep(0.5);
				}
			}
		}
		else{
			echo "File invoices.txt not found, this file contain the <div class='DivPaginas' that have the parameters of file";
		}
	}
	
	public function wichi()
	{
		
	}
	
	public function wichiaa()
	{
		$data 		= file_get_contents('media/invoice.txt'); 
		
		var_dump($data);
	}
	
}
?>
