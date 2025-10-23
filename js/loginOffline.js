(function(window, document){
	'use strict';

	var baseUrl = window.base_url || '/';
	var form = document.getElementById('acceso');
	if(!form)
	{
		return;
	}

	var statusNode = document.getElementById('offlineLoginStatus');
	var submitButton = form.querySelector('button[type="submit"]');
	var originalButtonText = submitButton ? submitButton.textContent : '';
	var dbReadyPromise = null;

	function setStatus(message, type)
	{
		if(!statusNode)
		{
			return;
		}
		statusNode.textContent = message || '';
		statusNode.className = 'offline-login-status' + (type ? ' ' + type : '');
		statusNode.style.display = message ? 'block' : 'none';
	}

	function toggleLoading(loading)
	{
		if(!submitButton)
		{
			return;
		}
		submitButton.disabled = !!loading;
		submitButton.textContent = loading ? 'Ingresando...' : originalButtonText;
	}

	function withFinally(promise, handler)
	{
		if(promise && typeof promise.finally === 'function')
		{
			return promise.finally(handler);
		}
		return promise.then(function(resultado)
		{
			if(typeof handler === 'function')
			{
				handler();
			}
			return resultado;
		}, function(error)
		{
			if(typeof handler === 'function')
			{
				handler();
			}
			throw error;
		});
	}

	function ensureDatabase()
	{
		if(!window.posCache || typeof window.posCache.openDatabase !== 'function')
		{
			return Promise.reject(new Error('IndexedDB no disponible'));
		}
		if(!dbReadyPromise)
		{
			dbReadyPromise = window.posCache.openDatabase().catch(function(error){
				console.warn('No fue posible abrir la base offline', error);
				throw error;
			});
		}
		return dbReadyPromise;
	}

	function hashCredenciales(usuario, password)
	{
		var texto = (usuario || '') + '|' + (password || '');
		if(typeof hex_sha1 === 'function')
		{
			return hex_sha1(texto);
		}
		try
		{
			return window.btoa(unescape(encodeURIComponent(texto)));
		}
		catch(error)
		{
			return texto;
		}
	}

	function guardarCredenciales(usuario, password, metadata)
	{
		if(!window.posCache || typeof window.posCache.saveUsuarioCredential !== 'function')
		{
			return Promise.resolve();
		}
		return ensureDatabase().then(function()
		{
			return window.posCache.saveUsuarioCredential(Object.assign({}, metadata || {}, {
				username: usuario,
				hash: hashCredenciales(usuario, password)
			}));
		}).catch(function(error)
		{
			console.warn('No se pudieron guardar las credenciales offline', error);
		});
	}

	function iniciarSesionOffline(usuario, password)
	{
		if(!window.posCache || typeof window.posCache.getUsuarioCredential !== 'function')
		{
			return Promise.reject(new Error('Modo offline no disponible en este navegador.'));
		}
		return ensureDatabase().then(function()
		{
			return window.posCache.getUsuarioCredential(usuario);
		}).then(function(registro)
		{
			if(!registro || !registro.hash)
			{
				throw new Error('Este usuario no está habilitado para modo offline.');
			}
			var hash = hashCredenciales(usuario, password);
			if(registro.hash !== hash)
			{
				throw new Error('Contraseña incorrecta para modo offline.');
			}

			try
			{
				localStorage.setItem('cerradurasOfflineSession', JSON.stringify({
					username: usuario,
					idUsuario: registro.idUsuario || null,
					idLicencia: registro.idLicencia || null,
					idEstacion: registro.idEstacion || null,
					offline: true,
					inicio: Date.now()
				}));
			}
			catch(error)
			{
				console.warn('No se pudo persistir la sesión offline', error);
			}
			return registro;
		});
	}

	function redirigirOffline()
	{
		window.location.href = baseUrl + 'ventas/puntoVenta/0';
	}

	function mostrarSugerenciaOffline()
	{
		if(!navigator.onLine)
		{
			setStatus('Sin conexión. Si ya guardaste tus credenciales, puedes acceder en modo offline.', 'info');
		}
		else if(statusNode && statusNode.className.indexOf('error') === -1)
		{
			setStatus('', '');
		}
	}

	window.addEventListener('offline', mostrarSugerenciaOffline);
	window.addEventListener('online', mostrarSugerenciaOffline);

	mostrarSugerenciaOffline();
	ensureDatabase().catch(function(){});

	var botonSync = document.getElementById('btnSyncOffline');
	if(botonSync)
	{
		botonSync.addEventListener('click', function()
		{
			if(botonSync.disabled)
			{
				return;
			}
			if(!navigator.onLine)
			{
				setStatus('Necesitas conexión para sincronizar catálogos.', 'error');
				return;
			}
			setStatus('Sincronizando catálogos para uso offline...', 'info');
			botonSync.disabled = true;

			var tareas = [];
			if(window.posSync && typeof window.posSync.syncProductos === 'function')
			{
				tareas.push(window.posSync.syncProductos());
			}
			if(window.posSync && typeof window.posSync.syncClientes === 'function')
			{
				tareas.push(window.posSync.syncClientes());
			}

			if(!tareas.length)
			{
				setStatus('Sincronización offline no disponible en este módulo.', 'error');
				botonSync.disabled = false;
				return;
			}

			withFinally(
				Promise.all(tareas).then(function()
				{
					setStatus('Catálogos sincronizados. Ya puedes trabajar sin conexión.', 'success');
				}).catch(function(error)
				{
					console.warn('Fallo al sincronizar catálogos offline', error);
					setStatus('No se pudo completar la sincronización. Intenta nuevamente.', 'error');
				}),
				function(){ botonSync.disabled = false; }
			);
		});
	}

	form.addEventListener('submit', function(event)
	{
		event.preventDefault();

		var usuario = (form.username ? form.username.value : '').trim();
		var password = form.password ? form.password.value : '';

		if(!usuario || !password)
		{
			setStatus('Ingresa usuario y contraseña.', 'error');
			return;
		}

		toggleLoading(true);

		var datos = new FormData(form);
		datos.append('ajax', '1');

		function finalizar()
		{
			toggleLoading(false);
		}

		function manejarOffline()
		{
			withFinally(
				iniciarSesionOffline(usuario, password).then(function()
				{
					setStatus('Acceso offline exitoso. Cargando punto de venta...', 'success');
					setTimeout(redirigirOffline, 400);
				}).catch(function(error)
				{
					setStatus(error.message || 'No fue posible iniciar sesión sin conexión.', 'error');
				}),
				finalizar
			);
		}

		if(!navigator.onLine)
		{
			manejarOffline();
			return;
		}

		fetch(baseUrl + 'login/acceso', {
			method: 'POST',
			body: datos,
			credentials: 'same-origin',
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		}).then(function(response)
		{
			if(!response.ok)
			{
				throw response;
			}
			return response.json();
		}).then(function(json)
		{
			if(!json || json.success !== true)
			{
				throw new Error(json && json.message ? json.message : 'No fue posible iniciar sesión.');
			}

			return guardarCredenciales(usuario, password, json.usuario || {}).then(function()
			{
				try
				{
					localStorage.setItem('cerradurasOfflineSession', JSON.stringify(Object.assign({}, json.usuario || {}, {
						username: usuario,
						offline: false,
						inicio: Date.now()
					})));
				}
				catch(error)
				{
					console.warn('No se pudo registrar la sesión local', error);
				}
				setStatus('Acceso exitoso. Redireccionando...', 'success');
				window.location.href = json.redirect || (baseUrl + 'principal/index/');
			});
		}).catch(function(error)
		{
			if(window.Response && error instanceof window.Response)
			{
				if(error.status === 0 || error.type === 'opaque')
				{
					manejarOffline();
					return;
				}
				withFinally(
					error.text().then(function(texto)
					{
						try
						{
							var parsed = JSON.parse(texto);
							setStatus(parsed.message || 'No se pudo iniciar sesión.', 'error');
						}
						catch(e)
						{
							setStatus('No se pudo iniciar sesión. Verifica tu conexión.', 'error');
						}
					}),
					finalizar
				);
			}
			else if(!navigator.onLine)
			{
				manejarOffline();
			}
			else
			{
				setStatus(error && error.message ? error.message : 'No se pudo iniciar sesión.', 'error');
				finalizar();
			}
		});
	});

})(window, document);
