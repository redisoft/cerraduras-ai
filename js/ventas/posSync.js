(function(window){
	'use strict';

	if(!window.posCache)
	{
		return;
	}

	const API_PRODUCTOS = base_url + 'api_catalogo/productos';
	const API_CLIENTES  = base_url + 'api_catalogo/clientes';
	const CONTEXTO_CACHE_KEY = 'cerradurasOfflineSession';

	function obtenerContextoSincronizacion()
	{
		var contexto = {};

		try
		{
			var almacenado = localStorage.getItem(CONTEXTO_CACHE_KEY);
			if(almacenado)
			{
				var data = JSON.parse(almacenado);
				if(data && typeof data === 'object')
				{
					if(data.idLicencia)
					{
						contexto.idLicencia = data.idLicencia;
					}
					if(data.idEstacion)
					{
						contexto.idEstacion = data.idEstacion;
					}
				}
			}
		}
		catch(error)
		{
			console.warn('No fue posible recuperar el contexto offline', error);
		}

		var licenciaInput = document.getElementById('selectSucursal');
		if(licenciaInput && licenciaInput.value)
		{
			contexto.idLicencia = licenciaInput.value;
		}

		return contexto;
	}

	function getModuloActual()
	{
		var path = (window.location && window.location.pathname ? window.location.pathname : '').toLowerCase();

		if(path.indexOf('/clientes') !== -1)
		{
			return 'clientes';
		}
		if(path.indexOf('/inventarioproductos') !== -1)
		{
			return 'productos';
		}
		if(path.indexOf('/ventas') !== -1)
		{
			return 'ventas';
		}
		return 'general';
	}

	function fetchJson(url, params)
	{
		if(params)
		{
			const query = Object.keys(params).map(function(key)
			{
				return encodeURIComponent(key) + '=' + encodeURIComponent(params[key]);
			}).join('&');

			url += (url.indexOf('?') === -1 ? '?' : '&') + query;
		}

		return fetch(url, {
			credentials: 'same-origin'
		}).then(function(response)
		{
			if(!response.ok)
			{
				throw new Error('Error de sincronización: ' + response.status);
			}
			return response.json();
		});
	}

	function transformProducto(registro)
	{
		return {
			idProducto: registro.idProducto,
			nombre: registro.nombre,
			codigoInterno: registro.codigoInterno,
			codigoBarras: registro.codigoBarras,
			descripcion: registro.descripcion,
			servicio: registro.servicio,
			idLinea: registro.idLinea,
			precioImpuestos: registro.precioImpuestos,
			precioA: registro.precios ? registro.precios.a : 0,
			precioB: registro.precios ? registro.precios.b : 0,
			precioC: registro.precios ? registro.precios.c : 0,
			precioD: registro.precios ? registro.precios.d : 0,
			precioE: registro.precios ? registro.precios.e : 0,
			preciosLista: registro.precios ? [registro.precios.a, registro.precios.b, registro.precios.c, registro.precios.d, registro.precios.e] : [],
			precioCliente: 1,
			precioTarjeta: registro.precios && registro.precios.a ? registro.precios.a * 1.025 : 0,
			cantidadMayoreo: registro.cantidadMayoreo || 0,
			stock: registro.stock || 0,
			unidad: registro.unidad,
			impuestos: {
				id: registro.impuestos ? registro.impuestos.id : 0,
				nombre: registro.impuestos ? registro.impuestos.nombre : null,
				tipo: registro.impuestos ? registro.impuestos.tipo : null,
				tasa: registro.impuestos ? registro.impuestos.tasa : 0,
				total: 0
			},
			ultimaActualizacion: registro.ultimaActualizacion ? new Date(registro.ultimaActualizacion).getTime() : Date.now()
		};
	}

	function syncProductos()
	{
	return window.posCache.getLastSync('productos').then(function(ultimoSync)
	{
		var limite = 500;
		var baseParams = { limite: limite };
		var contexto = obtenerContextoSincronizacion();

		if(contexto.idLicencia)
		{
			baseParams.idLicencia = contexto.idLicencia;
		}
		if(contexto.idEstacion)
		{
			baseParams.idEstacion = contexto.idEstacion;
		}

		if(ultimoSync)
		{
			baseParams.desde = new Date(parseInt(ultimoSync, 10)).toISOString();
		}

		var totalGuardado = 0;

		function cargarPagina(offset)
		{
			var params = Object.assign({ offset: offset }, baseParams);
			return fetchJson(API_PRODUCTOS, params).then(function(respuesta)
			{
				
				if(!respuesta || !Array.isArray(respuesta.data) || respuesta.data.length === 0)
				{
					return;
				}

				var productos = respuesta.data.map(transformProducto);
				return window.posCache.saveProductos(productos).then(function()
				{
					totalGuardado += productos.length;
					if(respuesta.data.length === limite)
					{
						return cargarPagina(offset + respuesta.data.length);
					}
				});
			});
		}

		return cargarPagina(0).then(function()
		{
			return window.posCache.setLastSync('productos', Date.now());
		});
	});
}

	function transformCliente(registro)
	{
		return {
			idCliente: registro.idCliente,
			empresa: registro.empresa,
			razonSocial: registro.razonSocial,
			nombre: registro.nombre,
			paterno: registro.paterno,
			materno: registro.materno,
			email: registro.email,
			telefono: registro.telefono,
			precio: registro.precio,
			ultimaActualizacion: registro.ultimaActualizacion ? new Date(registro.ultimaActualizacion).getTime() : Date.now()
		};
	}

	function syncClientes()
	{
	return window.posCache.getLastSync('clientes').then(function(ultimoSync)
	{
		var limite = 500;
		var baseParams = { limite: limite };
		var contexto = obtenerContextoSincronizacion();

		if(contexto.idLicencia)
		{
			baseParams.idLicencia = contexto.idLicencia;
		}
		if(contexto.idEstacion)
		{
			baseParams.idEstacion = contexto.idEstacion;
		}

		if(ultimoSync)
		{
			baseParams.desde = new Date(parseInt(ultimoSync, 10)).toISOString();
		}

		function cargarPagina(offset)
		{
			var params = Object.assign({ offset: offset }, baseParams);
			return fetchJson(API_CLIENTES, params).then(function(respuesta)
			{
				
				if(!respuesta || !Array.isArray(respuesta.data) || respuesta.data.length === 0)
				{
					return;
				}

				var clientes = respuesta.data.map(transformCliente);
				return window.posCache.saveClientes(clientes).then(function()
				{
					if(respuesta.data.length === limite)
					{
						return cargarPagina(offset + respuesta.data.length);
					}
				});
			});
		}

		return cargarPagina(0).then(function()
		{
			return window.posCache.setLastSync('clientes', Date.now());
		});
	});
}

window.posSync = {
	syncProductos: syncProductos,
	syncClientes: syncClientes,
	getModuloActual: getModuloActual
};

window.obtenerModuloPOSActual = getModuloActual;

window.sincronizarPOS = function(opciones)
{
	var modulo = opciones && opciones.modulo ? opciones.modulo : getModuloActual();
	var tareas = [];

	function agregarTarea(nombre, disponible, ejecutar)
	{
		if(!disponible)
		{
			return;
		}
		tareas.push(Promise.resolve().then(ejecutar).catch(function(error){
			console.warn('Sync '+nombre+' falló', error);
		}));
	}

	var puedeSyncProductos = window.posSync && typeof window.posSync.syncProductos === 'function';
	var puedeSyncClientes = window.posSync && typeof window.posSync.syncClientes === 'function';
	var puedeSyncVentas = typeof window.syncVentasPendientes === 'function';

	agregarTarea('productos', (modulo === 'productos' || modulo === 'general') && puedeSyncProductos, function(){
		return window.posSync.syncProductos();
	});
	agregarTarea('clientes', (modulo === 'clientes' || modulo === 'general') && puedeSyncClientes, function(){
		return window.posSync.syncClientes();
	});
	agregarTarea('ventas pendientes', (modulo === 'ventas' || modulo === 'general') && puedeSyncVentas, function(){
		return window.syncVentasPendientes();
	});

	return Promise.all(tareas);
};

window.addEventListener('online', function(){
	window.sincronizarPOS().finally(function(){
		if(typeof window.actualizarEstadoConexion === 'function')
		{
			window.actualizarEstadoConexion();
		}
	});
});

})(window);
