# 02 - Mock de Preinscriptos (UTI + Lucy)

## 1. Contexto funcional

- **Preinscripto**: persona que completó la preinscripción a una carrera.
- En Lucy, cuando se detecta un preinscripto:
  - Se crea un **alumno** con estado `PRE`.
  - Se usa su **dni**, **nombre** y **email_personal** para enviarle las credenciales de su cuenta institucional.
- En **noviembre** de cada año, todos los alumnos con estado `PRE` se eliminan de la base de datos de Lucy.

El flujo de negocio para preinscriptos es **simple y acotado**:

1. Obtener la lista de preinscriptos desde UTI (o desde el mock).
2. Obtener los datos personales de cada DNI.
3. Crear/actualizar alumno en Lucy con estado `PRE`.
4. Crear cuenta institucional en Teams y enviar contraseña por correo.
5. En noviembre, borrar alumnos `PRE`.

---

## 2. Endpoints UTI relevantes

### 2.1. Listado por estado (preinscriptos)

**Endpoint real UTI**

GET https://sisinfo.unrc.edu.ar/webservice/sial/V2/04/preinscriptos/listas/

`**Respuesta (ejemplo simplificado)** ```json {   "tipodoc": "DNI",   "nrodoc": 11222333,   "carreras": [     {       "id_carrera": 2,       "nombre_carrera": "NOMBRE DE LA CARRERA",       "fecha_inscri": "06/12/2024 11:42:32",       "modalidad": "2",       "comisiones": [         {           "id_comision": 542,           "nombre_comision": "Comisión 03"         }       ]     }   ] }`

Notas:

- `{tipo}` puede ser: `preinscriptos`, `aspirantes`, `ingresantes`.

- `modalidad`:
  
  - `1` = Presencial
  
  - `2` = Distancia

- Para Lucy, en el caso de **preinscriptos**, lo **único indispensable** de esta respuesta es el **DNI** (`nrodoc`).  
  El resto (carrera, comisiones, modalidad) puede ignorarse para esta etapa.

---

### 2.2. Datos personales por DNI

**Endpoint real UTI**

`GET https://sisinfo.unrc.edu.ar/webservice/sial/V2/04/alumnos/datospersonales/{dni}`

**Respuesta (ejemplo)**

`[   {     "tipodoc": "DNI",     "nrodoc": "27896410",     "nombre": "VIRGINIA SOLEDAD",     "apellido": "BRUSASCA ",     "email": "virginiabrusasca@gmail.com",     "fecha_natal": "19/01/80",     "telefono": "54-358-486-2940",     "localidad": "General Deheza"   } ]`

Para Lucy, los campos clave para preinscriptos son:

- `nrodoc` → DNI

- `nombre`

- `apellido`

- `email` → se usará como `email_personal` para el envío de credenciales

El resto de los datos pueden guardarse como información adicional, pero **no son críticos** para el flujo de preinscriptos.

---

## 3. Comportamiento de Lucy (modo REAL)

Cuando `UTI_DRIVER=real`:

1. Lucy llama a `/preinscriptos/listas/` y obtiene una lista de personas con `tipodoc` + `nrodoc`.

2. Para cada `nrodoc` de la lista:
   
   - Llama a `/alumnos/datospersonales/{dni}`.
   
   - Obtiene: `nombre`, `apellido`, `email`, etc.

3. Con esa información, Lucy:
   
   - Crea o actualiza un registro de **alumno** interno, por ejemplo:
     
     - `dni = nrodoc`
     
     - `nombre`
     
     - `apellido`
     
     - `email_personal = email`
     
     - `estado = "PRE"`

4. Se dispara el **hook de preinscripto** (ver sección 4).

En noviembre, un proceso programado (job/command) elimina de la BD todos los alumnos con `estado = PRE`.

---

## 4. Hook de preinscripto (acciones de Lucy)

Cuando Lucy procesa un preinscripto (tanto en modo real como mock):

1. **Crear cuenta institucional de Teams**
   
   - Usando la API pública/conocida de Microsoft (Graph API u otra interfaz definida).
   
   - La cuenta se asocia al alumno utilizando su DNI y/o email institucional generado.

2. **Enviar contraseña por correo**
   
   - Se genera (o se recibe) una contraseña inicial.
   
   - Se envía un correo al `email_personal` del alumno con:
     
     - usuario/correo institucional,
     
     - contraseña inicial,
     
     - instrucciones básicas de acceso.

3. **Guardar credenciales para reenvío**
   
   - Lucy almacena la información necesaria para reenvíos:
     
     - identificador de cuenta institucional,
     
     - contraseña inicial o mecanismo para resetearla,
     
     - estado de notificación (ej. “credenciales enviadas”).

> Para el caso de preinscriptos, **no** se realizan todavía matriculaciones en Moodle ni asignaciones complejas de materias.  
> Eso se maneja en etapas posteriores (aspirantes / ingresantes / alumnos).

---

## 5. Comportamiento de Lucy (modo MOCK – opción 3)

Cuando `UTI_DRIVER=mock`:

- Lucy **NO llama a la UTI real**.

- En su lugar, usa un **cliente mock** (`UtiClientMock`) que simula los dos endpoints:
  
  1. `/preinscriptos/listas/`
  
  2. `/alumnos/datospersonales/{dni}`

### 5.1. Fuente de datos del mock

El mock puede obtener los datos desde:

- **CSVs** de prueba (ejemplo):
  
  - `preinscriptos_listas.csv` → lista de DNIs + carrera/modalidad/fecha.
  
  - `alumnos_datospersonales.csv` → DNI + nombre + apellido + email.

- o **tablas de BD** dedicadas a mock (ej.: `mock_preinscriptos`, `mock_alumnos_datospersonales`).

Lucy **no sabe** si los datos vienen de CSV, BD o UTI real:  
siempre ve el mismo formato JSON.

### 5.2. Flujo en modo mock

1. `UtiClientMock::getLista("preinscriptos")` devuelve JSON igual al endpoint real.

2. `UtiClientMock::getDatosPersonales(dni)` devuelve JSON igual a `datospersonales/{dni}`.

3. Lucy procesa estos datos exactamente igual que en modo real:
   
   - crea/actualiza alumno `estado = PRE`,
   
   - dispara el hook de Teams + correo,
   
   - registra logs/batchs.

4. Los tests pueden repetirse limpiando la BD (borrando alumnos `PRE`) y volviendo a correr la ingesta con el mismo set de datos mock.

---

## 6. Limpieza anual de preinscriptos

- En **noviembre** se ejecuta un proceso de limpieza que:
  
  - elimina todos los alumnos con `estado = PRE`,
  
  - y sus datos asociados que sean exclusivamente de preinscripción.

- Esta limpieza se aplica tanto si los preinscriptos llegaron desde la UTI real como desde el mock.

---

## 7. Resumen

- **Preinscripto** = alumno interno en Lucy con `estado = PRE`, creado a partir de la lista UTI + datos personales por DNI.

- Solo importa realmente:
  
  - `dni`,
  
  - `nombre`,
  
  - `apellido`,
  
  - `email_personal` (desde `datospersonales`).

- Hook de preinscripto:
  
  1. Crear cuenta institucional de Teams.
  
  2. Enviar contraseña a `email_personal`.
  
  3. Guardar datos para reenvío.

- El **mock** en Laravel replica los endpoints UTI necesarios usando CSV/BD, de manera transparente para Lucy.

- En noviembre se borran todos los alumnos con estado `PRE`.
