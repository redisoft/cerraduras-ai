<?php
class Arreglos_modelo extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function obtenerCodigos($productos)
	{
		$data	= array('idLinea'=>array(),'unidad'=>array(),'linea'=>array(),'cliente'=>array(),'idCliente'=>array(),'cliente'=>array(),'idCliente'=>array(),'idCotizacion'=>array(),'ordenCompra'=>array(),'idProducto'=>array(),'producto'=>array());
		$i		= 0;
		
		foreach($productos as $row)
		{
			if(isset($row->idProducto))
			{
				if($this->comprobarElemento($data['idProducto'],$row->idProducto))
				{
					$data['idProducto'][$i]			= $row->idProducto;
					$data['producto'][$i]			= $row->producto;
				}
			}
			
			if(isset($row->idLinea))
			{
				if($this->comprobarElemento($data['idLinea'],$row->idLinea))
				{
					$data['idLinea'][$i]			= $row->idLinea;
					$data['linea'][$i]				= $row->linea;
				}
			}
			
			if(isset($row->idCliente))
			{
				if($this->comprobarElemento($data['idCliente'],$row->idCliente))
				{
					$data['idCliente'][$i]			= $row->idCliente;
					$data['cliente'][$i]			= $row->cliente;
				}
			}

			if(isset($row->unidad))
			{
				if($this->comprobarElemento($data['unidad'],$row->unidad))
				{
					$data['unidad'][$i]	=$row->unidad;
				}
			}
			
			if(isset($row->idCotizacion))
			{
				if($this->comprobarElemento($data['idCotizacion'],$row->idCotizacion))
				{
					$data['idCotizacion'][$i]	= $row->idCotizacion;
					$data['ordenCompra'][$i]	= $row->folio;
				}
			}


			$i++;
		}

		if(count($productos)>0)
		{
			$data['idLinea']			= array_values($data['idLinea']);
			$data['linea']				= array_values($data['linea']);
			$data['unidad']				= array_values($data['unidad']);
			$data['cliente']			= array_values($data['cliente']);
			$data['idCliente']			= array_values($data['idCliente']);
			$data['idCotizacion']		= array_values($data['idCotizacion']);
			$data['ordenCompra']		= array_values($data['ordenCompra']);
			$data['idProducto']			= array_values($data['idProducto']);
			$data['producto']			= array_values($data['producto']);

		}

		return $data;
	}
	
	public function comprobarElemento($arreglo,$elemento)
	{
		if(count($arreglo)>1)
		{
			$arreglo	= array_values($arreglo);
		}
		
		for($i=0;$i<count($arreglo);$i++)
		{
			if(isset($arreglo[$i]))
			{
				if($arreglo[$i]==$elemento)
				{	
					return false;
				}
			}
		}
		
		return true;
	}
}
