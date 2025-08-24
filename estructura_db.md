# Estructura de Base de Datos SACINV

## Tabla Marca

**Descripción:** Almacena las marcas de los productos.

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

**Descripción:** Contiene las categorías generales para clasificar las subcategorias.  
**Ejemplo:** Tecnología, Belleza, Papeleria...

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

**Descripción:** Contiene las subcategorías a la que pertenece cada producto.  
**Ejemplo:** Lápiz, Tajalápiz, Borrador... 

| Campo | Descripción |
|-------|-------------|
| id_subctg | Identificador único de la subcategoría |
| descripcion_subctg | Nombre o descripción de la subcategoría |
| creado_por | Usuario que creó el registro (FK a usuario_sistema.id_us) |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro (FK a usuario_sistema.id_us) |
| fecha_actualizacion | Fecha de actualización del registro |
| fk_categoria | Referencia a la categoría principal |
| status_subctg | Indica si la categoría está habilitada (1) o deshabilitada (0) |

## Tabla Producto

**Descripción:** Registra la información detallada de cada producto.

| Campo | Descripción |
|-------|-------------|
| id_producto | Identificador único del producto |
| descripcion_producto | Nombre del producto |
| observaciones_producto | Información funcional para uso interno sobre el producto |
| valor_venta_producto | Valor de venta por defecto del producto |
| valor_descuento_producto | Valor de descuento aplicado durante temporadas especiales |
| stock_minimo | Cantidad mínima de stock para generar compras pendientes |
| creado_por | Usuario que creó el registro (FK a usuario_sistema.id_us) |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro (FK a usuario_sistema.id_us) |
| fecha_actualizacion | Fecha de actualización del registro |
| fk_marca | Referencia a la marca del producto |
| fk_subcategoria | Referencia a la subcategoría del producto |
| status_producto | Indica si el producto está habilitado (1) o deshabilitado (0) |

## Tabla Pendiente_compra

**Descripción:** Guarda las compras pendientes generadas por bajo stock.

| Campo | Descripción |
|-------|-------------|
| id_pend | Identificador único de la compra pendiente |
| fk_producto | Referencia al producto pendiente (FK a producto.id_prod) |
| fecha_pend | Fecha desde la cual está pendiente la compra |
| cant_pend | Cantidad que se debe comprar al proveedor |

## Tabla Producto_img_video
**Descripción:** Guarda los nombres de las imágenes y videos del producto.

| Campo | Descripción |
|-------|-------------|
| id_prodiv | Identificador único del registro |
| nombre_prodiv | Nombre del archivo multimedia |
| tipo_archivo_prodiv | Tipo de archivo (imagen o video) |
| fk_producto | Referencia al producto relacionado |

## Tabla Producto_codigo
**Descripción:** Un mismo tipo de producto puede tener varios códigos de barra y por ende varios códigos manuales, sobretodo marcas blancas. El software mostrará en pantalla las opciones encontradas con el mismo código en caso de repetirse con la de algún otro producto. Ya que varios proveedores manejan su propio sistema de códigos de barra.

| Campo | Descripción |
|-------|-------------|
| id_codigo | Identificador único del código |
| codigo_barras | Código de barras del producto (pueden existir varios) |
| codigo_manual | Últimos 6 caracteres del código de barras (usado como identificación manual) |
| fk_producto | Referencia al producto |

## Tabla Producto_oferta
**Descripción:** Las ofertas son aquellas que representan un valor menor del producto a partir de una cantidad mínima de venta.    
**Ejemplo:** 12 lápices normalmente se vendería en $12.000, sin embargo por la cantidad se puede realizar un pequeño descuento, y cuya venta puede darse en $10.000.

| Campo | Descripción |
|-------|-------------|
| id_oferta | Identificador único de la oferta |
| fk_producto | Referencia al producto |
| cant_oferta | Cantidad mínima para aplicar el precio en oferta |
| valor_venta_oferta | Precio de venta unitario durante la oferta |
| status_oferta | Indica si la oferta está habilitada (1) o deshabilitada (0) |

## Tabla Ubicacion
**Descripción:** El mismo tipo de producto puede estar almacenados en diferentes lugares.   
**Ejemplo:** Bodega 1, Bodega 2, Almacén

| Campo | Descripción |
|-------|-------------|
| id_ubicacion | Identificador único de ubicación |
| nombre_ubicacion | Nombre del lugar donde se almacena el producto |
| creado_por | Usuario que creó el registro |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro |
| fecha_actualizacion | Fecha de actualización del registro |
| status_ubicacion | Indica si la ubicación está habilitada (1) o deshabilitada (0) |

## Tabla Producto_ubicacion
**Descripción:** Relaciona el tipo de producto y en cuantas ubicaciones se encuentra así como la cantidad almacenada.  
**Ejemplo:** 200 Lapices Mirado en Bodega 1, 25 Lapices Mirado en Almacén  

| Campo | Descripción |
|-------|-------------|
| id_produb | Identificador único |
| fk_producto | Referencia al producto |
| fk_ubicacion | Referencia al lugar de ubicación |
| cant_produb | Cantidad del producto en esa ubicación |

## Tabla Departamento
**Descripción:** Almacena los nombres de los departamentos de Colombia

| Campo | Descripción |
|-------|-------------|
| id_depart | Identificador del departamento |
| nombre_depart | Nombre del departamento |

## Tabla Ciudad
**Descripción:** Almacena los nombres de los municipios y ciudades que pertenencen a los departamentos de Colombia.

| Campo | Descripción |
|-------|-------------|
| id_ciudad | Identificador de la ciudad |
| nombre_ciudad | Nombre de la ciudad |
| fk_depart | Referencia al departamento |

## Tabla Proveedor

| Campo | Descripción |
|-------|-------------|
| id_proveedor | Identificador del proveedor |
| nit_proveedor | NIT del proveedor |
| razon_social_proveedor | Razón social del proveedor |
| email_proveedor | Correo electrónico del proveedor |
| direccion_proveedor | Dirección del proveedor |
| status_proveedor | Indica si el proveedor está habilitado (1) o deshabilitado (0) |
| fk_ciudad | Referencia a la ciudad del proveedor |

## Tabla Telefono_proveedor

**Descripción:** Por lo general un proveedor tiene varios teléfonos y es necesario guardarlos todos

| Campo | Descripción |
|-------|-------------|
| id_tel_proveedor | Identificador del telefono |
| numero_tel_proveedor | Número de teléfono |
| whatsapp_tel_proveedor | Si el número está registrado en whatsapp (1) sino (0) |
| fk_proveedor | Referencia a proveedor |
| status_tel_proveedor | Indica si el numero teléfónico está habilitado (1) o deshabilitado (0) |

## Tabla Metodo_pago

| Campo | Descripción |
|-------|-------------|
| id_metodo_pago | Identificador del método de pago |
| descipcion_metodo_pago | Descripción del método de pago |
| status_metodo_pago | Indica si el método está habilitado (1) o deshabilitado (0) |

## Tabla Compra_master

**Descripción:** Almacena los datos generales de una compra, pero la relación de productos comprados, se realiza en otra tabla llamada *Compra_detalle*

| Campo | Descripción |
|-------|-------------|
| id_compra_master | Identificador de la compra |
| fk_proveedor | Referencia al proveedor |
| nombre_adjunto_compra_master | Nombre del archivo adjunto guardado (factura) |
| fecha_compra_master | Fecha de la compra |
| total_descuento | Monto total del descuento aplicable |
| total_compra_master | Total general de la compra |
| observaciones_compra_master | Observaciones sobre la compra |
| creado_por | Usuario que creó el registro |
| fecha_creacion | Fecha de creación de la compra |
| actualizado_por | Usuario que actualizó el registro |
| fecha_actualizacion | Fecha de actualización de la compra |
| estado_compra_master | Estado de la compra (Registrada, Pagada, Anulada) |

## Tabla Compra_detalle

**Descripción:** Relaciona todos los productos comprados que pertenezcan a la tabla *Compra_Master*

| Campo | Descripción |
|-------|-------------|
| id_compra_detalle | Identificador del detalle de compra |
| fk_compra_master | Referencia a compra_master |
| fk_producto | Referencia al producto comprado |
| cant_compra_detalle | Cantidad de producto comprado |
| valor_unit_compra_detalle | Valor unitario del producto |
| xje_desc_compra_detalle | Porcentaje de descuento ofrecido por el proveedor |
| descuento_unit_compra_detalle | Valor del descuento generado |
| total_compra_detalle | Total del detalle (cant_compra_detalle * valor_unit_compra_detalle) |
| valor_venta_compra_detalle | Valor de venta calculado del producto |
| fecha_vencimiento_compra_detalle | Fecha de vencimiento del producto (si aplica) |

## Tabla Abono_compra

**Descripción:** Esta tabla almacena los diferentes nombres de los archivos adjuntos que son comprobantes de abonos realizados o pago total. Según esta tabla se puede saber la cantidad de veces que ha abonado al proveedor sobre la misma factura 

| Campo | Descripción |
|-------|-------------|
| id_abono_compra | Identificador del abono |
| movimiento_abono_compra | Movimiento realizado (Abonó(A) - Devolución(D) - Cancelado(C)) |
| monto_abono_compra | Valor abonado o pagado |
| nombre_comprobante_abono_compra | Nombre del archivo de comprobante de pago (.jpg) |
| fecha_hora_abono_compra | Fecha y hora (Diligenciado por el usuario, sin embargo el sistema le sugiere la fecha y hora actual del tiempo real)|
| fk_metodo_pago | Referencia al método de pago |
| fk_compra_master | Referencia a la compra_master |
| observaciones_abono_compra | Observaciones |

## Tabla Devolucion_compra

**Descripción:** Esta tabla relaciona los productos que se devuelven al proveedor con su respectiva justificación.

| Campo | Descripción |
|-------|-------------|
| id_devolucion_compra | Identificador de la devolución |
| fk_compra_detalle | Llave foránea de la factura Compra Detalle para obtener datos no solo del producto, si no datos adicionales de la compra master
| fecha_hora_devolucion_compra | Fecha de la devolución |
| cant_devolucion_compra | Cantidad devuelta del producto |
| estado_devolucion_compra | (0) Si está pendiente (1) si ya se realizó la devolución |
| observaciones_devolucion_compra | Justificación de la devolución |

## Tabla Usuario

| Campo | Descripción |
|-------|-------------|
| id_usuario_sistema | Identificador del usuario |
| nombre_usuario_sistema | Nombre del usuario |
| nickname_usuario_sistema | Usuario de acceso |
| password_usuario_sistema | Contraseña de acceso |
| tel_usuario_sistema | Teléfono del usuario |
| dir_usuario_sistema | Dirección del usuario |
| email_usuario_sistema | Correo electrónico del usuario |
| token_usuario_sistema | Token para activar la cuenta o recuperar la contraseña |
| fk_ciudad | Ciudad del usuario |
| status_usuario_sistema | Indica si el usuario está habilitado (1) o deshabilitado (0) |

## Tabla Cliente
**Descripción:** Almacena clientes tanto corporativos como particulares

| Campo | Descripción |
|-------|-------------|
| id_cliente | Identificador del cliente |
| cedula_nit_cliente | Cédula o NIT del cliente |
| razon_social_cliente | Nombre o razón social del cliente |
| tel_cliente | Teléfono del cliente |
| direccion_cliente | Dirección del cliente |
| email_cliente | Correo electrónico del cliente |
| tipo_cliente | Es un cliente Particular(P) o Corporativo(C) |
| fk_ciudad | Referencia a la tabla Ciudad |

## Tabla Empleado_cliente

**Descripción:** Registra los nombres de los empleados que trabajan para los clientes corporativos que solicitan los productos. Pensado en la trazabilidad de las solicitudes en compras a cŕedito.

| Campo | Descripción |
|-------|-------------|
| id_empleado_cliente | Identificador del empleado del cliente |
| fk_cliente | Referencia al cliente corporativo |
| cedula_empleado_cliente | Cédula del empleado |
| nombre_empleado_cliente | Nombre del empleado |
| tel_empleado_cliente | Teléfono del empleado |

## Tabla Venta_master

**Descripción:** Al igual que la tabla *Compra_master*, almacena los datos generales de la Venta

| Campo | Descripción |
|-------|-------------|
| id_venta_master | Identificador de la venta |
| numero_venta_master | Número consecutivo de venta al expedir un recibo |
| fk_cliente | Referencia al cliente |
| fecha_hora_venta_master | Fecha de la factura |
| subtotal_venta_master | Suma total de los registros vendidos |
| total_descuento_venta_master | Suma total de descuentos |
| total_venta_venta_master | Valor total después del descuento |
| observaciones_venta_master | Observaciones de la venta |
| creado_por | Usuario que creó el registro |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro |
| fecha_actualizacion | Fecha de actualización del registro |

## Tabla Venta_detalle

**Descripción:** Se relaciona el detalle de cada producto vendido al cliente, la razón por la cual se relaciona el usuario en esta tabla se debe a las ventas a *crédito* que se pueden realizar a un cliente en diferentes fechas, y por lo tanto, pudo venderse el producto por empleados diferentes.

| Campo | Descripción |
|-------|-------------|
| id_venta_detalle | Identificador del detalle de venta |
| fk_venta_master | Referencia a venta_master |
| fk_producto | Referencia al producto vendido |
| fk_empleado_cliente | Referencia al empleado del cliente |
| creado_por | Usuario que creó el registro |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro |
| fecha_actualizacion | Fecha de actualización del registro |
| cant_venta_detalle | Cantidad vendida |
| valor_unit_venta_detalle | Valor unitario vendido |
| total_venta_detalle | Total del detalle |

## Tabla Devolucion_venta

**Descripción:** Esta tabla relaciona los productos que se devuelven por parte del cliente con su respectiva justificación.

| Campo | Descripción |
|-------|-------------|
| id_devolucion_venta | Identificador de la devolución |
| fk_devolucion_venta | Llave foránea de la factura Venta Detalle para obtener datos no solo del producto, si no datos adicionales de la venta master
| fecha_hora_devolucion_venta | Fecha de la devolución |
| cant_devolucion_venta | Cantidad devuelta del producto |
| observaciones_devolucion_venta | Justificación de la devolución |

## Tabla Abono_venta
**Descripción:** Esta tabla almacena los diferentes nombres de los archivos adjuntos que son comprobantes de abonos realizados o pago total. Según esta tabla se puede saber la cantidad de veces que ha abonado un cliente a la misma factura 

| Campo | Descripción |
|-------|-------------|
| id_abono_venta | Identificador del pago |
| movimiento_abono_venta | Estado del pago (Abonó(A) - Devolución(D) - Cancelado(C)) Se presenta cuando el cliente devuelve el producto y se devuelve el pago o abono realizado|
| valor_abono_venta | Valor abonado o pagado por el cliente |
| comprobante_abono_venta | Nombre del comprobante de pago (.jpg)|
| fecha_hora_abono_venta | Fecha y hora del pago |
| fk_metodo_pago | Referencia al método de pago |
| fk_venta_master | Referencia a la venta master |
| observaciones_abono_venta | Observaciones del pago |

## Tabla Movimiento_inventario
**Descripción:** Se realizarán los 3 inventarios LIFO FIFO Y PONDERADO

| Campo | Descripción |
|-------|-------------|
| id_inventario | Identificador del movimiento |
| fk_producto | Referencia al producto |
| tipo_inventario | Tipo de movimiento (COMPRA (C), VENTA (V), DEVOLUCION_CLIENTE (DC), DEVOLUCION_PROVEEDOR (DP), AJUSTE_POSITIVO (AP), AJUSTE_NEGATIVO (AN), INVENTARIO_INICIAL (IN)) |
| cant_inventario | Cantidad del movimiento |
| valor_unit_inventario | Valor unitario (solo para entradas) |
| fecha_hora_inventario | Fecha y hora del movimiento |
| fk_compra_detalle | Referencia al detalle de compra |
| fk_venta_detalle | Referencia al detalle de venta |
| fk_usuario | Referencia al usuario que realiza la acción|
| observaciones_inventario | Observaciones del movimiento |

## Tabla Snapshot_inventario
**Descripción:** Registra el corte mensual de cada uno de los inventarios de los productos para evitar consultas lentas en la tabla *Inventario_Movimiento*

| Campo | Descripción |
|-------|-------------|
| id_snapshot | Identificador del corte del inventario |
| fecha_corte_snapshot | Fecha del corte es la misma fecha de creación |
| fk_producto_snapshot | Referencia del producto |
| cant_final_snapshot | cantidad final del producto |
| costo_lifo_snapshot | Costo final del inventario FIFO |
| costo_fifo_snapshot | Costo final del inventario LIFO |
| costo_ponderado_snapshot | Costo final del inventario Ponderado |

## Tabla Stock_actual
**Descripción:** Se basa en la cantidad del stock actual de cada producto basado en la tabla llamada Movimiento_inventario, con el fin de obtener el saldo de cada producto en tiempo real

| Campo | Descripción |
|-------|-------------|
| id_stock | Identificador del registro |
| fk_producto | Referencia al producto |
| cant_stock | Cantidad actual del producto |

## Tabla Modulo
**Descripción:** Almacena el nombre de cada uno de los módulos visibles para el usuario
| Campo | Descripción |
|-------|-------------|
| id_modulo | Identificador del módulo |
| nombre_modulo | Nombre del módulo |

## Tabla Permisos_Usuario_Modulo
**Descripción:** Almacena la relación de los permisos de tiene cada usuario con respecto a cada uno de los módulos visible en el software web

| Campo | Descripción |
|-------|-------------|
| id_permiso | Identificador del registro |
| fk_usuario | Referencia al usuario |
| fk_modulo | Referencia a al módulo |
| lectura_registro | Permiso para leer registros |
| crear_registro | Permiso para crear registros |
| actualizar_registro | Permiso para actualizar registros |
| eliminar_registro | Permiso para eliminar registros |

## Tabla Datos_empresa
**Descripción:** Almacena los datos de la empresa que hace uso del software para mostrarlo en los reportes.

| Campo | Descripción |
|-------|-------------|
| id_de | Identificador del registro |
| nit_de | Nit de la empresa |
| razon_social_de | Razón social de la empresa |
| slogan_de | Frase utilizada por la empresa para conectar con los clientes |
| regimen_de | Tipo de régimen |
| direccion_de | Dirección |
| tel_de | Teléfono de la empresa |
| whatsapp_de | Whatsapp de la empresa |
| email_de | Email de la empresa |
| logo_de | Nombre de la imagen de la empresa que carga en los informes |
| xje_venta_de | Indica cual es el porcentaje de venta para que el sistema calcule de forma autormática al momento de realizar una compra |
| fk_usuario | Referencia al usuario |
| creado_por | Usuario que creó el registro |
| fecha_creacion | Fecha de creación del registro |
| actualizado_por | Usuario que actualizó el registro |
| fecha_actualizacion | Fecha de actualización del registro |
