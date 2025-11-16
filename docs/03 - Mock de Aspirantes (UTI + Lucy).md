# 03 - Mock de Aspirantes (UTI + Lucy)

## 1. Contexto funcional

Estados que manejamos en el flujo general:

- **Preinscripto**  
  - Solo necesita cuenta institucional de **Teams**.  
  - Se guarda como alumno con estado `PRE`.  
- **Aspirante**  
  - Ya tiene (o debería tener) cuenta de **Teams**.  
  - Además, se lo matricula en ciertos **cursos de Moodle** (por ejemplo, cursos de ingreso, nivelación, etc.).  
  - Se guarda como alumno con estado `ASP` (o equivalente interno).  
- **Ingresante / Alumno**  
  - Manejan la lógica de plan/materia/comisiones (no se define aquí).

Este documento define solamente el comportamiento de **ASPIRANTES** y su MOCK dentro de Lucy
(opción 3: todo el mock en Laravel).

---

## 2. Endpoints UTI relevantes para aspirantes

La UTI expone un endpoint genérico:

```text
GET https://sisinfo.unrc.edu.ar/webservice/sial/V2/04/{tipo}/listas/
```

donde `{tipo}` puede ser:

- `preinscriptos`

- `aspirantes`

- `ingresantes`

Para **aspirantes**, usamos:

`GET https://sisinfo.unrc.edu.ar/webservice/sial/V2/04/aspirantes/listas/`

### 2.1. Formato de la respuesta (lista de aspirantes)

Ejemplo simplificado de **un elemento**:

`{   "tipodoc": "DNI",   "nrodoc": 11222333,   "carreras": [     {       "id_carrera": 2,       "nombre_carrera": "NOMBRE DE LA CARRERA",       "fecha_inscri": "06/12/2024 11:42:32",       "modalidad": "2",       "comisiones": [         {           "id_comision": 542,           "nombre_comision": "Comisión 03"         }       ]     }   ] }`

Notas:

- `modalidad`:
  
  - `1` = Presencial
  
  - `2` = Distancia

- Para **aspirantes**, Lucy principalmente necesita:
  
  - `tipodoc`
  
  - `nrodoc` (DNI)
  
  - Opcional: `id_carrera`, `modalidad`, `comisiones` (si luego los usás para elegir qué cursos de Moodle asignar).

---

### 2.2. Datos personales por DNI

Se utiliza el mismo endpoint que para preinscriptos:

`GET https://sisinfo.unrc.edu.ar/webservice/sial/V2/04/alumnos/datospersonales/{dni}`

Ejemplo:

`[   {     "tipodoc": "DNI",     "nrodoc": "27896410",     "nombre": "VIRGINIA SOLEDAD",     "apellido": "BRUSASCA ",     "email": "virginiabrusasca@gmail.com",     "fecha_natal": "19/01/80",     "telefono": "54-358-486-2940",     "localidad": "General Deheza"   } ]`

Campos clave:

- `nrodoc` → DNI

- `nombre`

- `apellido`

- `email` → se usa como `email_personal` para comunicación y/o verificación

---

## 3. Comportamiento de Lucy (modo REAL) para aspirantes

Cuando `UTI_DRIVER=real` y Lucy ejecuta la ingesta de **aspirantes**:

1. **Obtener lista de aspirantes**
   
   - Lucy llama a:
     
     `GET /webservice/sial/V2/04/aspirantes/listas/`
   
   - Recorre cada elemento de la lista:
     
     - Lee `tipodoc` y `nrodoc` (DNI).
     
     - Lee opcionalmente `id_carrera`, `modalidad` y `comisiones`.

2. **Obtener datos personales por DNI**
   
   Para cada `nrodoc`:
   
   - Llama a:
     
     `GET /webservice/sial/V2/04/alumnos/datospersonales/{dni}`
   
   - Con eso obtiene `nombre`, `apellido`, `email`, etc.

3. **Crear / actualizar alumno interno**
   
   Lucy, con los datos de la UTI, crea o actualiza un **alumno** en su BD:
   
   - Identificación:
     
     - `dni = nrodoc`
     
     - `tipodoc`
   
   - Datos personales:
     
     - `nombre`
     
     - `apellido`
     
     - `email_personal = email`
   
   - Datos académicos mínimos:
     
     - `id_carrera` (si hace falta guardarlo)
     
     - `modalidad` (presencial/distancia)
   
   - Estado:
     
     - `estado = "ASP"` (aspirante)

  Si el alumno ya existía como `PRE`:

- Se cambia el **estado** de `PRE` → `ASP` (o se registra de otra forma el avance de estado).
4. **Hook de aspirante**
   
   Una vez creado/actualizado el alumno con estado `ASP`, Lucy ejecuta el hook específico:
   
   - Ver sección 4.

---

## 4. Hook de aspirante (acciones de Lucy)

El **hook de aspirante** extiende la lógica de preinscriptos.

### 4.1. Verificación / creación de cuenta institucional de Teams

- Si el alumno ya tenía cuenta de Teams creada en la etapa de preinscripto:
  
  - Lucy puede verificar que la cuenta siga activa.

- Si por algún motivo **no existe** la cuenta institucional:
  
  - Lucy crea la cuenta en Teams usando la API correspondiente (Graph API u otra).
  
  - Asocia la cuenta al DNI y a los datos internos del alumno.

> En la práctica, se espera que la mayoría de aspirantes ya tengan su cuenta de Teams, creada en la etapa `PRE`.

### 4.2. Matriculación en cursos de Moodle para aspirantes

Este es el punto donde **diverge la lógica** respecto a preinscriptos:

- Para cada aspirante, Lucy:
  
  - Determina a qué **cursos de Moodle** debe matricularlo:
    
    - Por ejemplo:
      
      - Cursos de ingreso,
      
      - Cursos de nivelación,
      
      - Cursos informativos para aspirantes.
    
    - Esta lógica puede depender de:
      
      - `id_carrera`,
      
      - `modalidad`,
      
      - `comisiones`,
      
      - reglas internas de configuración (tablas de mapeo en Lucy).
  
  - Llama a la API de Moodle o usa el conector que tenga para:
    
    - **Matricular al alumno** (según su cuenta institucional) en esos cursos.

Lucy puede registrar:

- Qué cursos de aspirantes se asignaron.

- Fecha de matriculación.

- Si hubo errores (por ejemplo, usuario inexistente en Moodle).

### 4.3. Notificación (opcional)

Opcionalmente, Lucy puede:

- Enviar un correo al `email_personal` (y/o institucional) indicando:
  
  - Que ya es **aspirante activo**.
  
  - A qué cursos de Moodle fue matriculado.
  
  - Enlaces de acceso.

---

## 5. Comportamiento de Lucy (modo MOCK – opción 3)

Cuando `UTI_DRIVER=mock`:

- Lucy **NO llama a la UTI real**.

- Usa un `UtiClientMock` que simula los dos endpoints:
  
  1. `/aspirantes/listas/`
  
  2. `/alumnos/datospersonales/{dni}`

### 5.1. Fuente de datos del mock

El mock puede usar:

- **CSVs** de prueba, por ejemplo:
  
  - `aspirantes_listas.csv`:
    
    `tipodoc,nrodoc,id_carrera,nombre_carrera,fecha_inscri,modalidad,id_comision,nombre_comision DNI,40123456,2,Lic. en X,06/12/2024 11:42:32,2,542,"Comisión 03" DNI,39200500,2,Lic. en X,06/12/2024 11:50:10,2,541,"Comisión 02"`
  
  - `alumnos_datospersonales.csv` (compartido con preinscriptos):
    
    `tipodoc,nrodoc,nombre,apellido,email,fecha_natal,telefono,localidad DNI,40123456,LAURA,PEREZ,laura.perez@gmail.com,01/01/2006,54-...,RIO CUARTO DNI,39200500,MARTIN,GOMEZ,martin.gomez@yahoo.com,15/03/2005,54-...,RIO CUARTO`

- o **tablas de BD** mock si preferís importar esos CSVs dentro de Laravel y trabajar con Eloquent.

El `UtiClientMock`:

- Lee los datos desde CSV/BD.

- Arma el JSON **con el mismo formato** que la UTI real.

- Lucy procesa todo de la misma forma que en modo `real`.

### 5.2. Flujo en modo mock

1. `UtiClientMock::getLista("aspirantes")` devuelve una lista JSON con `tipodoc`, `nrodoc`, `carreras[...]`.

2. `UtiClientMock::getDatosPersonales(dni)` devuelve el JSON con `nombre`, `apellido`, `email`, etc.

3. Lucy:
   
   - crea/actualiza alumno con `estado = "ASP"`,
   
   - verifica/crea cuenta de Teams,
   
   - matricula en cursos de Moodle de **aspirantes**,
   
   - registra logs/batchs.

> La diferencia con preinscriptos es que en el hook para aspirantes se incluyen las **matriculaciones en Moodle**, además de la cuenta de Teams (si hace falta).

---

## 6. Resumen

- **Aspirante** = alumno interno en Lucy con `estado = "ASP"` (o similar), que:
  
  - proviene de la lista `/aspirantes/listas/`,
  
  - se complementa con datos de `/alumnos/datospersonales/{dni}`.

- El flujo para aspirantes incluye:
  
  1. Crear/actualizar alumno en Lucy (dni, nombre, apellido, email_personal, etc.).
  
  2. Verificar/crear cuenta institucional de Teams (si aún no existe).
  
  3. Matricular al alumno en los **cursos de Moodle de aspirantes** (según reglas de carrera/modalidad/comisiones).

- El **mock** en Laravel (opción 3) simula estos endpoints usando CSVs o BD interna, pero Lucy siempre ve el mismo formato JSON que la UTI real.

- A partir de aspirantes, la lógica diverge de preinscriptos especialmente por la parte de **Moodle** y por el cambio de **estado** (`PRE` → `ASP`).
