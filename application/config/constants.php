<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('carpetaProductos',		'img/productos/');
define('folioOrdenes',			'OP-');


//CONSTANTES GLOBALES PARA EL SISTEMA

define('empresa',								'Redisoftsystems');
define('logotipo',								'redisoft.png');

define('semilla',								'fr351t4l1nd4');
#define('semilla',								'fresita');
define('carpetaJs',								'js/');
define('version',								'1.1');
define('carpetaFacturacion',					'ficheros/facturacion/');
define('integrador',							'2b3a8764-d586-4543-9b7e-82834443f219');
define('token',									'dfaa32ec02637c73f4a01e81461c541476bd79c3');
define('SPARKPATH',		'');

define('carpetaCfdi',							'media/fel/');
define('carpetaXml',							'media/fel/xml/');
define('carpetaBancos',							'media/bancos/');
define('carpetaImportar',						'media/ficheros/importar/');
define('carpetaFicheros',						'media/ficheros/');
define('carpetaMedia',							'media/');

define('carpetaIngresos',						'media/ficheros/comprobantes/');
define('carpetaEgresos',						'media/ficheros/comprobantesEgresos/');
define('carpetaCompras',						'media/ficheros/comprobantesCompras/');
define('carpetaClientes',						'media/ficheros/clientes/');
define('carpetaClientesDocumentos',				'media/ficheros/clientes/documentos/');
define('carpetaSeguimientoClientes',			'media/ficheros/seguimientoClientes/');
define('carpetaSeguimientoProveedores',			'media/ficheros/seguimientoProveedores/');
define('carpetaProveedores',					'media/ficheros/proveedores/');
define('carpetaPersonal',						'media/ficheros/personal/');

define('carpetaPlantillas',						'media/ficheros/clientes/plantillas/');

define('condicionesPago',						'PAGO POR ADELANTADO');

define('decimales',								2);
define('mensajeCuota',							'Se ha superado el espacio de almacenamiento, por favor contacte con el administrador');
define('errorRegistro',							'Error en el registro, no hubo ningun cambio');
define('registroCorrecto',						'El registro ha sido exitoso');
define('registroDuplicado',						'Error al guardar la información, el registro esta duplicado');

define('errorBorrado',							'Error en el borrado, no hubo ningun cambio');
define('borradoCorrecto',						'El registro se ha borrado correctamente');

define('requisicion',							'REQ-');
define('salidas',								'SAL-');
define('pedidos',								'PED-');
define('frances',								'PEDF-');
define('bizcocho',								'PEDB-');
define('conteos',								'CN-');

define('manoObra',								10);
define('cuotaSindical',							4);
define('primaDominical',						25);
define('costoKg',								80);

#define('tipoUsuario',							'pinata');
define('tipoUsuario',							'demo');

define('sistemaActivo',							'cerraduras');
#define('sistemaActivo',							'pinata');
#define('sistemaActivo',							'olyess');
#define('sistemaActivo',								'demo');
#define('sistemaActivo',							'IEXE');


define('baseRedisoft',							'http://redisofterp.com/');

define('duracion',								86400 * 365);
define('siteKey',								'6LcGAVsUAAAAAMKqItW3mcgUkeIbmqvuOXeJ6jVP');
define('secret',								'6LcGAVsUAAAAALbOzcg0i9fkmFX7n6DWlmWBLCae');
