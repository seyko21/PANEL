<?php
/*
 * Documento   : Labels
 * Creado      : 15-jul-2014
 * Autor       : ..... .....
 * Descripcion : todas las etiquetas del sistema
 */

/*ETIQUETAS GENERALES*/
define('H_PANEL','Panel:');
define('H_DOMI','Dominios');
define('CK_ACTIVO','Activo');
define('BTN_NUEVO','Nuevo');
define('BTN_DELETE','Eliminar');
define('BTN_CLOSE','Cerrar');
define('BTN_ADD','Agregar');
define('LABEL_T','Total');
define('LABEL_ACT','Activo');
define('LABEL_AN','Anulado');
define('LABEL_DESACT','Inactivo');
define('BTN_ACT','Activar');
define('BTN_DESACT','Desactivar');
define('LABEL_ADV','Advertencia');
define('LABEL_ADVMSN','Todas las caratulas han sido cotizadas.');
define('COPY','Copyright SmartAdmin 2014-2020.');
define('ICON_CLOSE','fa fa-ban');
define('THEME_CLOSE','btn btn-default xClose');

/*FORMULARIO LOGIN*/
define('L_TITLE','Ingresar');
define('L_EMAIL','E-mail o usuario');
define('L_PASS','Contraseña');
define('L_OLVIDE','¿Olvide mi contraseña?');
define('L_ENTRAR','Entrar');
define('L_L_EMAIL','Ingrese su email');
define('L_L_PASS','Ingrese su clave');

/*CONFIGURAR MENU*/
define('M_TITLTE_DOM','Módulos');
define('M_TITLTE_MOD','Menú Principal');
define('M_TITLTE_MEP','Opciones');
define('M_TITLTE_OPC','Opciones');
define('M_LB_DOM','Dominio:');
define('M_LB_MOD','Módulo:');
    /*formulario dominios*/
    define('M_FG_DOM_TITLTE','Nuevo Dominio');
    define('M_FG_DOM_L_DOM','Dominio');
    define('M_FG_DOM_L_ICON','Icono');
    define('M_FG_DOM_L_ORD','Orden');
    define('M_FG_DOM_H_ICON','Class css para diseño de icono de Dominio');
    define('M_FG_DOM_H_ORD','Posición del módulo');
    define('M_FE_DOM_TITLTE','Editar Dominio');
    /*formulario modulos*/
    define('M_FG_MOD_TITLTE','Nuevo Módulo');
    define('M_FG_MOD_L_MOD','Módulo');
    define('M_FG_MOD_L_ORD','Orden');
    define('M_FG_MOD_H_ORD','Posición del menú');
    
/*CONFIGURAR USUARIOS*/
define('M_TITLTE_USU','Usuarios');    
    /*formulario usuarios*/
    define('M_FG_USU_TITLTE','Nuevo Usuario');
    define('M_FG_USU_L_PASS','Clave');
    define('M_FG_USU_L_EMP','Empleado');
    define('M_FG_USU_H_EMP','Click en boton para buscar empleado.');
    define('M_FG_USU_T_DP','Datos personales');
    define('M_FG_USU_T_RO','Roles');
    define('LABEL_3','E-mail');
    define('LABEL_4','Ingrese clave.');
    define('LABEL_5','Ingrese empleado.');
    define('LABEL_USU1','Editar Usuario');
    
    /*formulario buscar empleado*/
    define('LABEL_1','Buscar Empleado');
    define('LABEL_2','Nombres');
/*----------------------TIPO DE CONCEPTOS----------------------*/
define('LABEL_6','Tipo de Concepto');
define('LABEL_7','Nuevo Tipo de Concepto');
define('LABEL_8','Descripción');
define('LABEL_9','Ingrese una descrición.');
define('LABEL_10','Editar Tipo de Concepto');

/*----------------------CONCEPTOS----------------------*/
define('LABEL_11','Conceptos');
define('LABEL_12','Nuevo Concepto');
define('LABEL_13','Importe');
define('LABEL_14','Ingrese importe');
define('LABEL_15','Editar Concepto');

/*----------------------REGISTRAR VENDEDORES----------------------*/
define('LABEL_RV1','Vendedores');
define('LABEL_RV2','Nuevo Vendedor');
define('LABEL_RV3','Nombres');
define('LABEL_RV4','Apellido paterno');
define('LABEL_RV5','Apellido materno');
define('LABEL_RV6','Ingrese nombres');
define('LABEL_RV7','Ingrese apellido paterno');
define('LABEL_RV8','Ingrese apellido materno');
define('LABEL_RV9','Dirección');
define('LABEL_RV10','E-mail');
define('LABEL_RV11','Ingresar dirección');
define('LABEL_RV12','ingresar e-mail');
define('LABEL_RV13','Sexo');
define('LABEL_RV14','Teléfonos');
define('LABEL_RV15','Ingresar teléfonos');
define('LABEL_RV16','Nro. de RUC');
define('LABEL_RV17','Ingresar nro. de RUC');
define('LABEL_RV18','Departamento');
define('LABEL_RV19','Provincia');
define('LABEL_RV20','Distrito');
define('LABEL_RV21','Editar Vendedor');
define('LABEL_RV22','Nro. de Documento');
define('LABEL_RV23','DNI');
define('LABEL_RV24','Ingresar nro. de DNI');
define('LABEL_RV25','Adjuntar Documento');

/*----------------------    GENERAR COTIZACION----------------------*/
define('LABEL_GNC1','Cotizaciones');
define('LABEL_GNC2','Generar Cotización');
define('LABEL_GNC3','Cliente');
define('LABEL_GNC4','Meses de contrato');
define('LABEL_GNC5','Días de oferta');
define('LABEL_GNC6','IGV');
define('LABEL_GNC7','Producto');
define('LABEL_GNC8','Buscar Producto');
define('LABEL_GNC9','Escriba ubicación para buscar');
define('LABEL_GNC10','Agregar Producto');
define('LABEL_GNC11','Abre ventana para buscar y agregar productos');
define('LABEL_GNC12','Buscar Cliente');
define('LABEL_GNC13','Validez de oferta (días)');
define('LABEL_GNC14','Costo de producción de banner');
define('LABEL_GNC15','Generar cotización');
define('LABEL_GNC16','Incluye IGV');
define('LABEL_GNC17','Si');
define('LABEL_GNC18','No');
define('LABEL_GNC19','Paso 1');
define('LABEL_GNC20',' - Información general');
define('LABEL_GNC21','Paso 2');
define('LABEL_GNC22',' - Productos');
define('LABEL_GNC23','Paso 3');
define('LABEL_GNC24',' - Finalizar');
define('LABEL_GNC25','Observaciones');
define('LABEL_GNC26','Campaña');
define('LABEL_GNC27','Click aquí para generar cotización');

/*----------------------    ASIGNAR CUENTAS----------------------*/
define('LABEL_ASC1','Cuentas');
define('LABEL_ASC2','Nueva Asignación');
define('LABEL_ASC3','Comisión');
define('LABEL_ASC4','Empleado');

/*----------------------    CLIENTE  ----------------------*/
define('LABEL_RC1','Nuevo Cliente');
define('LABEL_RC2','Tipo de documento');
define('LABEL_RC3','Editar Cliente');
define('LABEL_RC4','Razón Social');
define('LABEL_RC5','Representantes');
define('LABEL_RC6','Cliente:');
define('LABEL_RC7','Nuevo Representante');
define('LABEL_RC8','Editar Representante');

/*----------------------    SEGUIMIENTO DE COTIZACION  ----------------------*/
define('SEGCO_1','Seguimiento de Cotización');
define('SEGCO_2','Observación - Cotización Nro.: ');
define('SEGCO_3','Ingrese una observación para el cambio de estado');
define('SEGCO_4','Todos');
define('SEGCO_5','Emitido');
define('SEGCO_6','Procesado');
define('SEGCO_7','Orden de servicio');
define('SEGCO_8','Rechazado');
define('SEGCO_9','Estado');
define('SEGCO_10','Observación');

/*----------------------    ORDEN DE SERVICIO  ----------------------*/
define('GNOSE_1','Ordenes de Servicio');
define('GNOSE_2','Cronograma');
define('GNOSE_3','Cronograma de Pagos - Orden Nro.: ');
define('GNOSE_4','Monto');
define('GNOSE_5','Ingrese monto de la cuota a programar');
define('GNOSE_6','Fecha de pago');
define('GNOSE_7','Ingrese la fecha de pago de la cuota');
define('GNOSE_8','Fecha de orden');
define('GNOSE_9','Ingrese la fecha de la orden de servicio');
define('GNOSE_10','Descuento');
define('GNOSE_11','Ingrese monto a descontar');
define('GNOSE_12','Editar Orden');
define('GNOSE_13','Fecha de contrato');
define('GNOSE_14','Ingrese la fecha del contrato');
define('GNOSE_15','Contrato');
define('GNOSE_16','Enviar accesos a email');
define('GNOSE_17','Exportar contrato');

/*---------------------- SEGUIMIENTO DE PAGOS----------------*/
define('SEGPA_1','Seguimiento de Pagos');
define('SEGPA_2','Cuota');
define('SEGPA_3','Monto');
define('SEGPA_4','Fecha Programada');
define('SEGPA_5','Fecha de Pago');
?>