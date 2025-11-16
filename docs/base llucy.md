# Base de datos de llucy

## Plan de Estudios

### Plan
1. id
2. Nombre(char de 4)
3. Fecha Desde
4. Nombre

### Modalidad
1. id
2. Nombre
3. Coidgo (char de 1. P: PResencial D: Distancia T: Todas. ALgo distinyo en el futuro)

### Carrera
1. id
2. id_plan
3. Nombre
4. Código

### Materia
1. id
2. id_carrera
3. Nombre
4. Codigo
5. Codigo UTI
6. Cuatrimestre (1 o  2 ... O 3 en el futuro)

Nota: Hay tres carrerts. Contador. Administrador. Econoomía. Hay 18 materias que perteneces a las 3 materias en común.  
Por razones de operatividad, hay otra carrera que llamaremos Ingreso

### Comisiones
1. id
2. id_materia
3. Nombre
4. Codigo
5. Uti
6. id_modaliad

Nota: El servicio es por materia, pero la materia puede dividirse en comisiones o no (podemos forzar la comisión 0). Aca esa comisión que tiene una sola, es A


## Alumnos

### Alumnos
1. id
2. tipo doc
3. nrodoc
4. Nombre
5. Apellido
6. email
7. localidad
8. telefono
9. email_institucional
10. cohorte
11. fecha_incorpocion
12. Estado (Preinscripto, Aspirnte, Ingresante, Alumno)
13. id_modalidad


### Alumnos_Carreras
0. id
1. id_alumno

### Alumnos_Materias
1. id  
2. id_alumno_carrera
5. Cuatrimestre
6. id_modalidad
6. Año
7. Cuatrimestre

### Alumno_Comisiones
1.id
2. id_alumno_materia
3. Año
4. Cuatrimestre
5. Fecha

NOTA: Ver si si puede simplificar
Un alumno se inscribe en una materia, que tiene un año y una comision.   
Desde allí deduzco todo. Fuerzo que la comision 0 (tenga todas las modalidades). O puede tener 2 (P y D). o 7 distribuidas arbitrariamente.



Nota.
Hay 4 API de la UTI

/prescriptos
/aspirantes
/ingresantes
/alumnos

Las 3 primeras son la evolucion de un ingresante  a la facultad. Consultado un listado, desde hasta como parametro, nos devuelve en que estado esta a la fecha actual.
PEro sigue apareciendo en los anteriores estados cuando la fecha es menor

Alumnos, es el estado final.
Allí hay que hacer um pedido de modificación a la UTI

1) Que agregue el campo cohorte. Que es el año que ingreso
2) Que dado una fecha, qu iuu
3. Cuatrimestre
4. Año
e significa inicio de clases se deduce el cuatrimestre. Con ese cuatrimestre se que materias tengo que abrir el cursado. Pido a la uti el listado de materias. Me devuelve la inscripción.  Puede suceder que el alumno exista, el listado viene por DNI. Actualizo sus datos y lo inscribo en la comision y materias. Sino existe tengo que pedir a la uti por su DNI en busqueda de sus datos y agregarlo

3) Arbitrariamente, en una fecha. El alumno alumno con fecha de incorporacion, mayor que x dias. Se da de baja



## Fin del sistema

La Facultad tiene MS Teams y un Moodle Propio

1. Cuando viene un presincripto. Se crea el usuario de Teams.  
2. Cuando es Aspirante, se lo ingresa compulsivamente a las materias ya definidas por un burocrata (3 o 7 materias). Tambien viene el dato a que comisión
3. Cuando es ingresante o alumno, ya el datos de inscripcion a una materia y comisión viene dado  

El Pressincripto sino se convierte a Ingresante (pasa por ASPIRANTE, o es ASPIRANTE directo) se da de baja en una fecha dada  
  
Si una personas es preinscripta nuevamente, es como si nunca hubiera estados

Si es ingresante, a una fecha que es la inscripción a una materia de segundo año ya es alumno  
  
 
 Ingresante y Alumnos se borran de la base por algun parametro de fecha  
   
     
 Cuando es presincripto se crea una cuenta institucional en TEAMS 
 Cuando es ASPIRANTE se registra en cursos determinados en Moodle, es como crear una cuenta en Moodle, con su email institucional
 Cuando es Ingresante o Alumno, se verifica que tenga cuenta TEAMS y se le asigna a las materias dadas . Sino se crea la cuenta institucional y se lo habilita en Moodle  
   
   Moodle permite el ingreso via OPENID con Microsoft  
     
 
 En un tiempo dado si no tiene actividad un alumno, que se registra en otro sistema oficial. Se da de baja en ambas plataformas
 
