# Sistema Integral de Gestión Académica e Institucional

## Documento de Análisis, Diseño Funcional y Arquitectura

### Versión 1.0

---

# 1. Introducción

Este documento define la arquitectura funcional, técnica y operativa de un Sistema Integral de Gestión Académica e Institucional para establecimientos educativos en Colombia.

El objetivo es centralizar en una única plataforma los procesos institucionales, académicos, administrativos y de comunicación pública.

La solución estará compuesta por:

- Sitio Web Institucional (SPA Pública).
- Panel Administrativo.
- Gestión Académica.
- Gestión de Matrículas.
- Gestión de Estudiantes.
- Gestión Docente.
- Calificaciones.
- Boletines.
- Convivencia Escolar.
- Seguridad y Auditoría.

---

# 2. Objetivos

## Objetivo General

Diseñar e implementar una plataforma integral que permita administrar los procesos académicos e institucionales de manera centralizada, segura y escalable.

## Objetivos Específicos

- Digitalizar procesos académicos.
- Automatizar matrículas.
- Facilitar la gestión docente.
- Generar boletines automáticamente.
- Mantener historial académico.
- Mejorar la comunicación institucional.
- Disponer de una página web institucional administrable.

---

# 3. Arquitectura General

## SPA Pública

Tecnologías previstas:

- React.js
- Bootstrap
- API REST Laravel

### Funciones

- Mostrar información institucional.
- Mostrar noticias.
- Mostrar galería.
- Mostrar docentes.
- Canal de contacto.
- Difusión institucional.

---

## Backend Administrativo

Tecnologías previstas:

- Laravel
- MySQL
- AdminLTE

### Funciones

- Administración general.
- Gestión académica.
- Matrículas.
- Usuarios y permisos.
- Reportes.

---

# 4. Módulo Página Web

## Configuración Institucional

Administra:

- Nombre de la institución.
- Logo.
- Descripción.
- Dirección.
- Teléfonos.
- Correo electrónico.
- Página web.
- Misión.
- Visión.
- Historia.

## Banners

Permite administrar:

- Imagen.
- Título.
- Descripción.
- Orden.
- Estado.

## Noticias

Permite:

- Crear noticias.
- Publicar noticias.
- Programar publicaciones.
- Mostrar noticias destacadas.

## Galería

Permite:

- Crear álbumes.
- Publicar fotografías.
- Organizar contenido visual.

## Docentes

Permite publicar:

- Fotografía.
- Cargo.
- Perfil.
- Formación académica.

## Contacto

Información institucional visible para visitantes.

---

# 5. SPA Pública

## Inicio

Componentes:

- Slider principal.
- Presentación institucional.
- Noticias destacadas.
- Galería destacada.
- Acceso al sistema.

## Nosotros

- Historia.
- Misión.
- Visión.
- Filosofía institucional.

## Noticias

- Listado.
- Detalle.

## Galería

- Fotografías.
- Eventos.

## Docentes

- Directorio institucional.

## Contacto

- Datos de ubicación.
- Medios de contacto.

---

# 6. Configuración Académica

## Años Lectivos

Ejemplos:

- 2026
- 2027
- 2028

Funciones:

- Crear.
- Activar.
- Cerrar.

## Períodos Académicos

Ejemplo:

- Primer período.
- Segundo período.
- Tercer período.
- Cuarto período.

## Grados

Ejemplo:

- Preescolar.
- Primero.
- Segundo.
- ...
- Once.

## Cursos

Ejemplo:

- A
- B
- C

Resultado:

- Primero A
- Primero B
- Segundo A

## Asignaturas

- Matemáticas.
- Español.
- Ciencias.
- Inglés.
- Tecnología.

## Escala de Calificaciones

- Bajo.
- Básico.
- Alto.
- Superior.

---

# 7. Gestión de Estudiantes

## Registro de Estudiantes

Información:

- Datos personales.
- Fotografía.
- Información médica.
- Información familiar.

## Acudientes

Relación:

- Padre.
- Madre.
- Tutor.

## Historial Académico

Permite mantener la trazabilidad de toda la vida escolar.

---

# 8. Matrículas

## Matrícula Nueva

Ingreso de estudiantes nuevos.

## Renovación

Renovación automática para estudiantes activos.

## Promoción de Curso

Ejemplo:

Primero A → Segundo A

Segundo A → Tercero A

Funciones:

- Validación académica.
- Creación automática de matrícula.
- Conservación del historial.

## Traslados

- Cambio de curso.
- Cambio de jornada.

## Retiros

- Registro.
- Motivo.
- Seguimiento.

---

# 9. Gestión Académica

## Asignación Académica

Relación:

Docente → Asignatura → Curso

## Planeaciones

- Por período.
- Por asignatura.

## Actividades

- Talleres.
- Tareas.
- Proyectos.
- Evaluaciones.

---

# 10. Calificaciones

## Registro de Notas

Tipos:

- Exámenes.
- Talleres.
- Tareas.
- Proyectos.

## Recuperaciones

- Académicas.
- Refuerzo.

## Consolidado

- Promedios.
- Desempeños.
- Estadísticas.

---

# 11. Boletines e Informes

## Boletines

- Por período.
- Finales.

## Certificados

- Estudio.
- Matrícula.
- Notas.

## Constancias

- Conducta.
- Permanencia.

---

# 12. Convivencia Escolar

## Observador

Registro cronológico de observaciones.

## Llamados de Atención

- Leves.
- Graves.

## Citaciones

- Padres.
- Acudientes.

## Compromisos

- Académicos.
- Disciplinarios.

---

# 13. Talento Humano

## Docentes

- Hoja de vida.
- Formación.
- Experiencia.

## Administrativos

- Personal institucional.
- Cargos.

---

# 14. Seguridad

## Usuarios

- Administrador.
- Rector.
- Coordinador.
- Docente.
- Secretaría.
- Estudiante.
- Acudiente.

## Roles

Control granular de acceso.

## Auditoría

Registro de:

- Creaciones.
- Modificaciones.
- Eliminaciones.
- Accesos.

---

# 15. Estructura General del Menú AdminLTE

## Página Web

- Configuración Institucional
- Banners
- Noticias
- Galería
- Docentes
- Contacto

## Configuración Académica

- Años Lectivos
- Períodos
- Grados
- Cursos
- Asignaturas
- Escala de Calificaciones

## Estudiantes

- Estudiantes
- Acudientes
- Historial
- Documentos

## Matrículas

- Matrícula Nueva
- Renovaciones
- Promoción de Curso
- Traslados
- Retiros

## Gestión Académica

- Asignación Académica
- Planeaciones
- Actividades

## Calificaciones

- Registro de Notas
- Recuperaciones
- Consolidados

## Boletines e Informes

- Boletines
- Certificados
- Reportes

## Convivencia

- Observador
- Citaciones
- Compromisos

## Talento Humano

- Docentes
- Administrativos

## Seguridad

- Usuarios
- Roles
- Auditoría

---

# 16. Roadmap de Desarrollo

Fase 1
- Página Web.

Fase 2
- Configuración Académica.

Fase 3
- Estudiantes.

Fase 4
- Matrículas.

Fase 5
- Gestión Académica.

Fase 6
- Calificaciones.

Fase 7
- Boletines.

Fase 8
- Convivencia.

Fase 9
- Reportes y estadísticas.

---

# 17. Conclusión

Este diseño proporciona una base sólida, escalable y profesional para un sistema de gestión académica alineado con las necesidades de las instituciones educativas colombianas.
