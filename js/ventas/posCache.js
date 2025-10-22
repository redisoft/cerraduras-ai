(function(window){
	'use strict';

	const DB_NAME = 'cerradurasPOS';
	const DB_VERSION = 1;

	const STORE_PRODUCTOS = 'productos';
	const STORE_STOCKS = 'stocks';
	const STORE_CLIENTES = 'clientes';
	const STORE_VENTAS = 'ventasPendientes';
	const STORE_META = 'metadatos';

	let dbPromise = null;

	function openDatabase()
	{
		if(dbPromise)
		{
			return dbPromise;
		}

		dbPromise = new Promise(function(resolve, reject)
		{
			const request = indexedDB.open(DB_NAME, DB_VERSION);

			request.onerror = function(event)
			{
				reject(event.target.error);
			};

			request.onsuccess = function(event)
			{
				resolve(event.target.result);
			};

			request.onupgradeneeded = function(event)
			{
				const db = event.target.result;

				if(!db.objectStoreNames.contains(STORE_PRODUCTOS))
				{
					const productos = db.createObjectStore(STORE_PRODUCTOS, { keyPath: 'idProducto' });
					productos.createIndex('codigoInterno', 'codigoInterno', { unique: false });
					productos.createIndex('codigoBarras', 'codigoBarras', { unique: false });
					productos.createIndex('nombre', 'nombre', { unique: false });
					productos.createIndex('ultimaActualizacion', 'ultimaActualizacion', { unique: false });
				}

				if(!db.objectStoreNames.contains(STORE_STOCKS))
				{
					const stocks = db.createObjectStore(STORE_STOCKS, { keyPath: ['idProducto', 'idSucursal'] });
					stocks.createIndex('idSucursal', 'idSucursal', { unique: false });
				}

				if(!db.objectStoreNames.contains(STORE_CLIENTES))
				{
					const clientes = db.createObjectStore(STORE_CLIENTES, { keyPath: 'idCliente' });
					clientes.createIndex('nombre', 'nombre', { unique: false });
					clientes.createIndex('rfc', 'rfc', { unique: false });
				}

				if(!db.objectStoreNames.contains(STORE_VENTAS))
				{
					db.createObjectStore(STORE_VENTAS, { keyPath: 'id', autoIncrement: true });
				}

				if(!db.objectStoreNames.contains(STORE_META))
				{
					db.createObjectStore(STORE_META, { keyPath: 'clave' });
				}
			};
		});

		return dbPromise;
	}

	function withStore(storeName, mode, handler)
	{
		return openDatabase().then(function(db)
		{
			return new Promise(function(resolve, reject)
			{
				const tx = db.transaction(storeName, mode);
				const store = tx.objectStore(storeName);
				let result;

				try
				{
					result = handler(store, tx);
				}
				catch(error)
				{
					reject(error);
					return;
				}

				tx.oncomplete = function()
				{
					resolve(result);
				};

				tx.onerror = function(event)
				{
					reject(event.target.error);
				};
			});
		});
	}

	function putMany(storeName, registros)
	{
		if(!Array.isArray(registros) || registros.length === 0)
		{
			return Promise.resolve();
		}

		return withStore(storeName, 'readwrite', function(store)
		{
			registros.forEach(function(item)
			{
				store.put(item);
			});
		});
	}

	function getAll(storeName, indexName, query)
	{
		return withStore(storeName, 'readonly', function(store)
		{
			return new Promise(function(resolve, reject)
			{
				let source = store;

				if(indexName)
				{
					source = store.index(indexName);
				}

				const request = source.getAll(query);

				request.onsuccess = function(event)
				{
					resolve(event.target.result || []);
				};

				request.onerror = function(event)
				{
					reject(event.target.error);
				};
			});
		});
	}

	function searchProductos(filtros)
	{
		filtros = filtros || {};
		var limite = filtros.limite || 40;
		var texto = (filtros.texto || '').toLowerCase();
		var codigo = (filtros.codigo || '').toLowerCase();

		return getAll(STORE_PRODUCTOS).then(function(lista)
		{
			if(!lista.length)
			{
				return [];
			}

			var resultados = lista.filter(function(producto)
			{
				var coincideTexto = true;
				var coincideCodigo = true;

				if(texto)
				{
					var nombre = (producto.nombre || '').toLowerCase();
					coincideTexto = nombre.indexOf(texto) !== -1;
				}

				if(codigo)
				{
					var codigoInterno = (producto.codigoInterno || '').toLowerCase();
					coincideCodigo = codigoInterno.indexOf(codigo) !== -1;
				}

				return coincideTexto && coincideCodigo;
			});

			return resultados.slice(0, limite);
		});
	}

	function getMetadata(clave)
	{
		return withStore(STORE_META, 'readonly', function(store)
		{
			return new Promise(function(resolve, reject)
			{
				const request = store.get(clave);

				request.onsuccess = function(event)
				{
					const registro = event.target.result;
					resolve(registro ? registro.valor : null);
				};

				request.onerror = function(event)
				{
					reject(event.target.error);
				};
			});
		});
	}

	function setMetadata(clave, valor)
	{
		return withStore(STORE_META, 'readwrite', function(store)
		{
			store.put({ clave: clave, valor: valor });
		});
	}

	function clearStore(storeName)
	{
		return withStore(storeName, 'readwrite', function(store)
		{
			store.clear();
		});
	}

	function getLastSync(key)
	{
		return getMetadata('lastSync_' + key).then(function(valor)
		{
			return valor;
		});
	}

	function setLastSync(key, value)
	{
		return setMetadata('lastSync_' + key, value);
	}

	function needsSync(key, minutos)
	{
		minutos = minutos || 5;
		return getLastSync(key).then(function(timestamp)
		{
			if(!timestamp)
			{
				return true;
			}
			
			var ultimo = parseInt(timestamp, 10);
			if(isNaN(ultimo))
			{
				return true;
			}
			var diferencia = Date.now() - ultimo;
			return diferencia > minutos * 60 * 1000;
		});
	}

function saveProductosLote(registros)
{
	return putMany(STORE_PRODUCTOS, registros).then(function()
	{
		return setLastSync('productos', Date.now());
	});
}

	function addVentaPendiente(venta)
	{
		venta = Object.assign({}, venta, { fecha: venta.fecha || new Date().toISOString() });
		return withStore(STORE_VENTAS, 'readwrite', function(store)
		{
			return new Promise(function(resolve, reject)
			{
				const request = store.add(venta);
				request.onsuccess = function(event)
				{
					resolve(event.target.result);
				};
				request.onerror = function(event)
				{
					reject(event.target.error);
				};
			});
		});
	}

	function removeVentaPendiente(id)
	{
		return withStore(STORE_VENTAS, 'readwrite', function(store)
		{
			store.delete(id);
		});
	}

	function countVentasPendientes()
	{
		return withStore(STORE_VENTAS, 'readonly', function(store)
		{
			return new Promise(function(resolve, reject)
			{
				const request = store.count();
				request.onsuccess = function(event)
				{
					resolve(event.target.result || 0);
				};
				request.onerror = function(event)
				{
					reject(event.target.error);
				};
			});
		});
	}

	const api =
	{
		openDatabase: openDatabase,
		saveProductos: function(productos){ return putMany(STORE_PRODUCTOS, productos); },
		saveProductosLote: saveProductosLote,
		getProductos: function(){ return getAll(STORE_PRODUCTOS); },
		searchProductos: searchProductos,
		saveStocks: function(stocks){ return putMany(STORE_STOCKS, stocks); },
		getStocks: function(){ return getAll(STORE_STOCKS); },
		saveClientes: function(clientes){ return putMany(STORE_CLIENTES, clientes); },
		getClientes: function(){ return getAll(STORE_CLIENTES); },
	addVentaPendiente: addVentaPendiente,
	removeVentaPendiente: removeVentaPendiente,
	countVentasPendientes: countVentasPendientes,
	getVentasPendientes: function(){ return getAll(STORE_VENTAS); },
		clearStore: clearStore,
		getMetadata: getMetadata,
		setMetadata: setMetadata,
		getLastSync: getLastSync,
		setLastSync: setLastSync,
		needsSync: needsSync
	};

	window.posCache = api;

})(window);
