# Estructura de Base de Datos SACINV

## Tabla Marca

Descripción: Almacena las marcas de los productos.

| Campo | Descripción |
|-------|-------------|
| id_marca | Identificador único de la marca |
| descripcion_marca | Nombre o descripción de la marca |
| creado_por | Usuario del sistema que creó el registro (FK a usuario_sistema.id_us) |
| fecha_creacion | Fecha en la que se creó el registro |
| actualizado_por | Usuario que realizó la última actualización (FK a usuario_sistema.id_us) |
| fecha_actualizacion | Fecha en que se actualizó el registro |
| status_marca | Indica si la marca está habilitada (1) o deshabilitada (0) |

## Tabla Categoría

Descripción: Contiene las categorías generales para clasificar las subcategorias.  
Ejemplo: Tecnología, Belleza, Papeleria...

| Campo | Descripción |
|-------|-------------|
| id_categoria | Identificador único de la categoría |
| descripcion_categoria | Nombre o descripción de la categoría |
| creado_por | Usuario que creó el registro (FK a usuario_sistema.id_us) |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro (FK a usuario_sistema.id_us) |
| fecha_actualizacion | Fecha de actualización del registro |
| status_categoria | Indica si la categoría está habilitada (1) o deshabilitada (0) |

## Tabla Subcategoría

Descripción: Contiene las subcategorías a la que pertenece cada producto.  
Ejemplo: Lápiz, Tajalápiz, Borrador... 

| Campo | Descripción |
|-------|-------------|
| id_subctg | Identificador único de la subcategoría |
| descripcion_subctg | Nombre o descripción de la subcategoría |
| creado_por | Usuario que creó el registro (FK a usuario_sistema.id_us) |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro (FK a usuario_sistema.id_us) |
| fecha_actualizacion | Fecha de actualización del registro |
| fk_categoria | Fecha de actualización del registro |
| status_subctg | Indica si la categoría está habilitada (1) o deshabilitada (0) |

## Tabla Producto

Descripción: Registra la información detallada de cada producto.

| Campo | Descripción |
|-------|-------------|
| id_prod | Identificador único del producto |
| descripcion_prod | Nombre del producto |
| observaciones_prod | Información funcional para uso interno sobre el producto |
| valor_venta_prod | Valor de venta por defecto del producto |
| valor_descuento_prod | Valor de descuento aplicado durante temporadas especiales |
| cant_stock_prod | Cantidad mínima de stock para generar compras pendientes |
| creado_por | Usuario que creó el registro (FK a usuario_sistema.id_us) |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro (FK a usuario_sistema.id_us) |
| fecha_actualizacion | Fecha de actualización del registro |
| fk_marca | Referencia a la marca del producto |
| fk_subcategoria | Referencia a la subcategoría del producto |
| status_prod | Indica si el producto está habilitado (1) o deshabilitado (0) |

## Tabla Pendiente_compra

Descripción: Guarda las compras pendientes generadas por bajo stock.

| Campo | Descripción |
|-------|-------------|
| id_pend | Identificador único de la compra pendiente |
| fk_producto | Referencia al producto pendiente (FK a producto.id_prod) |
| fecha_pend | Fecha desde la cual está pendiente la compra |
| cant_pend | Cantidad que se debe comprar al proveedor |

## Tabla Producto_img_video
Descripción: Guarda los nombres de las imágenes y videos del producto.

| Campo | Descripción |
|-------|-------------|
| id_prodiv | Identificador único del registro |
| nombre_prodiv | Nombre del archivo multimedia |
| tipo_archivo_prodiv | Tipo de archivo (imagen o video) |
| fk_producto | Referencia al producto relacionado |

## Tabla Producto_codigo
Descripción: Un mismo tipo de producto puede tener varios códigos de barra y por ende varios códigos manuales, sobretodo marcas blancas

| Campo | Descripción |
|-------|-------------|
| id_cod | Identificador único del código |
| codigo_barras | Código de barras del producto (pueden existir varios) |
| codigo_manual | Últimos 6 caracteres del código de barras (usado como identificación manual) |
| fk_producto | Referencia al producto |

## Tabla Producto_oferta
Descripción: Las ofertas son aquellas que representan un valor menor del producto a partir de una cantidad mínima de venta.    
Ejemplo: 12 lápices normalmente costarían $12.000, sin embargo por la cantidad se puede realizar un descuento $10.000.

| Campo | Descripción |
|-------|-------------|
| id_po | Identificador único de la oferta |
| fk_producto | Referencia al producto |
| cant_po | Cantidad mínima para aplicar el precio en oferta |
| valor_venta_po | Precio de venta unitario durante la oferta |
| status_po | Indica si la oferta está habilitada (1) o deshabilitada (0) |

## Tabla Ubicacion
Descripción: El mismo tipo de producto puede estar almacenados en diferentes lugares.   
Ejemplo: Bodega 1, Bodega 2, Almacén

| Campo | Descripción |
|-------|-------------|
| id_ub | Identificador único de ubicación |
| nombre_ub | Nombre del lugar donde se almacena el producto |
| creado_por | Usuario que creó el registro |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro |
| fecha_actualizacion | Fecha de actualización del registro |
| status_ub | Indica si la ubicación está habilitada (1) o deshabilitada (0) |

## Tabla Producto_ubicacion
Descripción: Relaciona el tipo de producto y en cuantas ubicaciones se encuentra así como la cantidad almacenada.  
Ejemplo: 200 Lapices Mirado en Bodega 1, 25 Lapices Mirado en Almacén  

| Campo | Descripción |
|-------|-------------|
| id_produb | Identificador único |
| fk_producto | Referencia al producto |
| fk_ubicacion | Referencia al lugar de ubicación |
| cant | Cantidad del producto en esa ubicación |

## Tabla Departamento
Descripción: Almacena los nombres de los departamentos de Colombia

| Campo | Descripción |
|-------|-------------|
| id_depart | Identificador del departamento |
| nombre_depart | Nombre del departamento |

## Tabla Ciudad
Descripción: Almacena los nombres de los municipios y ciudades que pertenencen a los departamentos de Colombia.

| Campo | Descripción |
|-------|-------------|
| id_ciudad | Identificador de la ciudad |
| nombre_ciudad | Nombre de la ciudad |
| fk_depart | Referencia al departamento |

## Tabla Proveedor

| Campo | Descripción |
|-------|-------------|
| id_prov | Identificador del proveedor |
| nit_prov | NIT del proveedor |
| razon_social_prov | Razón social del proveedor |
| tel_prov | Teléfono del proveedor |
| email_prov | Correo electrónico del proveedor |
| direccion_prov | Dirección del proveedor |
| status_prov | Indica si el proveedor está habilitado (1) o deshabilitado (0) |
| fk_ciudad | Referencia a la ciudad del proveedor |

## Tabla Metodo_pago

| Campo | Descripción |
|-------|-------------|
| id_mp | Identificador del método de pago |
| descipcion_mp | Descripción del método de pago |
| status_mp | Indica si el método está habilitado (1) o deshabilitado (0) |

## Tabla Compra_master

| Campo | Descripción |
|-------|-------------|
| id_cm | Identificador de la compra |
| fk_proveedor | Referencia al proveedor |
| nombre_adjunto_cm | Nombre del archivo adjunto guardado |
| fecha_cm | Fecha de la compra |
| total_descuento | Monto total del descuento aplicable |
| total_cm | Total general de la compra |
| observaciones_cm | Observaciones sobre la compra |
| cancela_cm | Indicador si la compra fue cancelada |
| creado_por | Usuario que creó el registro |
| fecha_creacion | Fecha de creación de la compra |
| actualizado_por | Usuario que actualizó el registro |
| fecha_actualizacion | Fecha de actualización de la compra |
| estado_cm | Estado de la compra (Registrada, Pagada, Anulada) |

## Tabla Compra_detalle

| Campo | Descripción |
|-------|-------------|
| id_cd | Identificador del detalle de compra |
| fk_cm | Referencia a compra_master |
| fk_producto | Referencia al producto comprado |
| cant_cd | Cantidad de producto comprada |
| valor_unit_cd | Valor unitario del producto |
| xje_desc_cd | Porcentaje de descuento ofrecido por el proveedor |
| descuento_unit_cd | Valor del descuento generado |
| total_cd | Total del detalle (cant_cd * valor_unit_cd) |
| valor_venta_cd | Valor de venta calculado del producto |
| fecha_vencimiento_cd | Fecha de vencimiento del producto (si aplica) |

## Tabla Pago_compra

| Campo | Descripción |
|-------|-------------|
| id_pc | Identificador del pago |
| estado_pago_pc | Estado del pago (Debe o Pagó) |
| nombre_comprobante_pc | Nombre del archivo de comprobante de pago |
| fecha_pc | Fecha del pago |
| fk_mp | Referencia al método de pago |
| fk_cm | Referencia a la compra_master |
| observaciones_pc | Observaciones del pago |

## Tabla Usuario

| Campo | Descripción |
|-------|-------------|
| id_us | Identificador del usuario |
| nombre_us | Nombre del usuario |
| nickname_us | Apodo o usuario de acceso |
| password_us | Contraseña de acceso |
| tel_us | Teléfono del usuario |
| dir_us | Dirección del usuario |
| email_us | Correo electrónico del usuario |
| fk_ciudad | Ciudad del usuario |
| status_us | Indica si el usuario está habilitado (1) o deshabilitado (0) |

## Tabla Cliente

| Campo | Descripción |
|-------|-------------|
| id_cli | Identificador del cliente |
| cedula_nit_cli | Cédula o NIT del cliente |
| razon_social_cli | Nombre o razón social del cliente |
| tel_cli | Teléfono del cliente |
| direccion_cli | Dirección del cliente |
| email_cli | Correo electrónico del cliente |
| fk_ciudad | Ciudad del cliente |

## Tabla Empleado_cliente

| Campo | Descripción |
|-------|-------------|
| id_ec | Identificador del empleado del cliente |
| fk_cli | Referencia al cliente empresarial |
| nombre_ec | Nombre del empleado |
| tel_ec | Teléfono del empleado |

## Tabla Venta_master

| Campo | Descripción |
|-------|-------------|
| id_vm | Identificador de la venta |
| fk_cli | Referencia al cliente |
| fecha_vm | Fecha de la factura |
| subtotal_vm | Suma total de los registros vendidos |
| total_descuento_vm | Suma total de descuentos |
| total_venta_vm | Valor total después del descuento |
| observaciones_vm | Observaciones de la venta |
| creado_por | Usuario que creó el registro |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro |
| fecha_actualizacion | Fecha de actualización del registro |
| estado_venta | Estado de la venta (Registrada, Pagada, Anulada) |

## Tabla Venta_detalle

| Campo | Descripción |
|-------|-------------|
| id_vd | Identificador del detalle de venta |
| numero_venta | Número de la venta |
| fk_vm | Referencia a venta_master |
| fk_producto | Referencia al producto vendido |
| fk_ec | Referencia al empleado del cliente |
| fk_us | Usuario que realizó la venta |
| cant_vd | Cantidad vendida |
| valor_unit_vd | Valor unitario vendido |
| total_vd | Total del detalle |
| fecha_vd | Fecha de la venta |
| hora_vd | Hora de la venta |

## Tabla Pago_venta

| Campo | Descripción |
|-------|-------------|
| id_pv | Identificador del pago |
| estado_pago_pv | Estado del pago (Debe o Pagó) |
| nombre_comprobante_pv | Nombre del comprobante de pago |
| fecha_pv | Fecha del pago |
| fk_mp | Referencia al método de pago |
| fk_vm | Referencia a la venta |
| observaciones_pv | Observaciones del pago |

## Tabla Movimiento_inventario

| Campo | Descripción |
|-------|-------------|
| id_mov | Identificador del movimiento |
| fk_producto | Referencia al producto |
| tipo_mov | Tipo de movimiento (COMPRA, VENTA, DEV_CLI, DEV_PROV, AJUSTE_POS, AJUSTE_NEG, INV_INICIAL) |
| cant_mov | Cantidad del movimiento |
| valor_unit_mov | Valor unitario (solo para entradas) |
| fecha_mov | Fecha del movimiento |
| fk_cd | Referencia al detalle de compra |
| fk_vd | Referencia al detalle de venta |
| observaciones | Observaciones del movimiento |

## Tabla Stock_actual

| Campo | Descripción |
|-------|-------------|
| id_st | Identificador del registro |
| fk_producto | Referencia al producto |
| cant_st | Cantidad actual del producto |

## Tabla Tabla

| Campo | Descripción |
|-------|-------------|
| id_tabla | Identificador de la tabla |
| nombre_tabla | Nombre de la tabla |

## Tabla Permisos_Usuario_Tabla

| Campo | Descripción |
|-------|-------------|
| id_tabla | Identificador del registro |
| fk_usuario | Referencia al usuario |
| fk_tabla | Referencia a la tabla |
| lectura_registro | Permiso para leer registros |
| crear_registro | Permiso para crear registros |
| actualizar_registro | Permiso para actualizar registros |
| eliminar_registro | Permiso para eliminar registros |

