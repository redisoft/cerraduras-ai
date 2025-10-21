<?php
if (!defined('BASEPATH')) 
{
    exit('No direct script access allowed');
}

class Paginas
{
    var $_matchbox;
	
	function paginar($url,$registros,$numero,$links,$uri)
	{
		$Pag['first_link'] = 'Inicio';
		$Pag['first_tag_open'] = '<li>';
		$Pag['first_tag_close'] = '</li>';
		
		// Last Links
		$Pag['last_link'] = 'Fin';
		$Pag['last_tag_open'] = '<li>';
		$Pag['last_tag_close'] = '</li>';
		
		// Next Link
		$Pag['next_link'] = '&raquo;';
		$Pag['next_tag_open'] = '<li>';
		$Pag['next_tag_close'] = '</li>';
		
		// Previous Link
		$Pag['prev_link'] = '&laquo;';
		$Pag['prev_tag_open'] = '<li>';
		$Pag['prev_tag_close'] = '</li>';
		
		// Current Link
		$Pag['cur_tag_open'] = '<li class="active">';
		$Pag['cur_tag_close'] = '</li>';
		
		// Digit Link
		$Pag['num_tag_open'] = '<li>';
		$Pag['num_tag_close'] = '</li>';
		
		$Pag['uri_segment'] = $uri;
		#$Pag['page_query_string'] = TRUE;
		$Pag["base_url"]= $url;
		#$Pag["cur_page"]= 1;
		$Pag["total_rows"]=$registros;//Total de Registros
		$Pag["per_page"]=$numero;
		$Pag["num_links"]=5;
		
		return $Pag;
	}
}

?>