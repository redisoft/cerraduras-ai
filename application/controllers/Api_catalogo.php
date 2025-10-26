<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_catalogo extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventarioproductos_modelo', 'productos');
		$this->load->model('modeloclientes', 'clientes');
		$this->output->set_content_type('application/json', 'UTF-8');

		$this->inicializarContexto();
	}

	protected function inicializarContexto()
	{
		$idLicencia = $this->input->get_post('idLicencia');
		if(empty($idLicencia))
		{
			$idLicencia = $this->input->get_post('licencia');
		}

		if(empty($idLicencia))
		{
			$idLicencia = $this->session->userdata('idLicencia');
		}

		$idEstacion = $this->input->get_post('idEstacion');
		if(empty($idEstacion))
		{
			$idEstacion = $this->session->userdata('idEstacion');
		}

		$contexto = array();

		if(!empty($idLicencia))
		{
			$contexto['idLicencia'] = $idLicencia;
			$this->session->set_userdata('idLicencia', $idLicencia);
		}

		if(!empty($idEstacion))
		{
			$contexto['idEstacion'] = $idEstacion;
			$this->session->set_userdata('idEstacion', $idEstacion);
		}

		if(!empty($contexto))
		{
			if(method_exists($this->productos, 'establecerContexto'))
			{
				$this->productos->establecerContexto($contexto);
			}
			if(method_exists($this->clientes, 'establecerContexto'))
			{
				$this->clientes->establecerContexto($contexto);
			}
		}
	}

	protected function respond($data, $status = 200)
	{
		$this->output->set_status_header($status);
		$this->output->set_output(json_encode($data, JSON_UNESCAPED_UNICODE));
	}

	private function parseFecha($valor)
	{
		if(empty($valor))
		{
			return null;
		}

		$timestamp = strtotime($valor);
		if($timestamp === false)
		{
			return null;
		}

		return date('Y-m-d H:i:s', $timestamp);
	}

	public function productos()
	{
		$limite = (int) $this->input->get('limite');
		$offset = (int) $this->input->get('offset');
		$desde  = $this->parseFecha($this->input->get('desde'));

		if(method_exists($this->productos, 'obtenerIdLicencia') && empty($this->productos->obtenerIdLicencia()))
		{
			return $this->respond(array(
				'success' => false,
				'message' => 'No se proporcionó la licencia para la sincronización.'
			), 400);
		}

		$limite = $limite > 0 ? min($limite, 500) : 100;
		$offset = $offset >= 0 ? $offset : 0;

		try
		{
			$registros = $this->productos->obtenerProductosSync($desde, $limite, $offset);
		}
		catch(Exception $ex)
		{
			log_message('error', 'Error al sincronizar productos: '.$ex->getMessage());
			return $this->respond(array(
				'success' => false,
				'message' => 'No fue posible obtener el catálogo de productos.'
			), 500);
		}

		$payload = array();

		foreach($registros as $row)
		{
			$productoActualizacion = !empty($row->productoActualizacion) ? strtotime($row->productoActualizacion) : 0;
			$inventarioActualizacion = !empty($row->inventarioActualizacion) ? strtotime($row->inventarioActualizacion) : 0;
			$ultimaActualizacion = max($productoActualizacion, $inventarioActualizacion);

			$payload[] = array(
				'idProducto' => (int) $row->idProducto,
				'nombre' => $row->nombre,
				'codigoInterno' => $row->codigoInterno,
				'codigoBarras' => $row->codigoBarras,
				'descripcion' => $row->descripcion,
				'idLinea' => isset($row->idLinea) ? (int) $row->idLinea : 0,
				'servicio' => (int) $row->servicio,
				'precioImpuestos' => (float) $row->precioImpuestos,
				'precios' => array(
					'a' => (float) $row->precioA,
					'b' => (float) $row->precioB,
					'c' => (float) $row->precioC,
					'd' => (float) $row->precioD,
					'e' => (float) $row->precioE,
				),
				'cantidadMayoreo' => isset($row->cantidadMayoreo) ? (float) $row->cantidadMayoreo : 0,
				'stock' => (float) $row->stock,
				'unidad' => $row->unidad,
				'impuestos' => array(
					'id' => isset($row->idImpuesto) ? (int) $row->idImpuesto : 0,
					'nombre' => isset($row->impuestoNombre) ? $row->impuestoNombre : null,
					'tipo' => isset($row->impuestoTipo) ? $row->impuestoTipo : null,
					'tasa' => isset($row->tasa) ? (float) $row->tasa : 0,
				),
				'ultimaActualizacion' => $ultimaActualizacion > 0 ? date('c', $ultimaActualizacion) : null
			);
		}

		$this->respond(array(
			'count' => count($payload),
			'offset' => $offset,
			'limite' => $limite,
			'fechaRespuesta' => date('c'),
			'data' => $payload
		));
	}

	public function clientes()
	{
		$limite = (int) $this->input->get('limite');
		$offset = (int) $this->input->get('offset');
		$desde  = $this->parseFecha($this->input->get('desde'));

		if(method_exists($this->clientes, 'obtenerIdLicencia') && empty($this->clientes->obtenerIdLicencia()))
		{
			return $this->respond(array(
				'success' => false,
				'message' => 'No se proporcionó la licencia para la sincronización.'
			), 400);
		}

		$limite = $limite > 0 ? min($limite, 500) : 100;
		$offset = $offset >= 0 ? $offset : 0;

		try
		{
			$registros = $this->clientes->obtenerClientesSync($desde, $limite, $offset);
		}
		catch(Exception $ex)
		{
			log_message('error', 'Error al sincronizar clientes: '.$ex->getMessage());
			return $this->respond(array(
				'success' => false,
				'message' => 'No fue posible obtener el catálogo de clientes.'
			), 500);
		}

		$payload = array();
		foreach($registros as $row)
		{
			$fechaRegistro = !empty($row->fechaRegistro) ? strtotime($row->fechaRegistro) : 0;

			$payload[] = array(
				'idCliente' => (int) $row->idCliente,
				'empresa' => $row->empresa,
				'razonSocial' => isset($row->razonSocial) ? $row->razonSocial : null,
				'nombre' => isset($row->nombre) ? $row->nombre : null,
				'paterno' => isset($row->paterno) ? $row->paterno : null,
				'materno' => isset($row->materno) ? $row->materno : null,
				'email' => isset($row->email) ? $row->email : null,
				'telefono' => isset($row->telefono) ? $row->telefono : null,
				'precio' => isset($row->precio) ? (float) $row->precio : 0,
				'ultimaActualizacion' => $fechaRegistro > 0 ? date('c', $fechaRegistro) : null
			);
		}

		$this->respond(array(
			'count' => count($payload),
			'offset' => $offset,
			'limite' => $limite,
			'fechaRespuesta' => date('c'),
			'data' => $payload
		));
	}
}
