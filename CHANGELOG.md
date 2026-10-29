# Changelog

Todos los cambios notables de este bundle se documentan en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/),
y este proyecto adhiere a [Semantic Versioning](https://semver.org/lang/es/).

Un cambio puede detallarse con subítems anidados. Cada nivel se sangra con dos espacios.
Un ítem que empieza con `[Internal]` queda en este archivo y no se muestra en el historial del sitio:

    - Cambio principal. (#12345)
      - Detalle del mismo cambio.
        - Detalle de ese detalle.
    - [Internal] Refactor que no cambia el comportamiento visible.

## [Unreleased]

## [1.0.2] - 2026-10-29

## Changed 

- Changelog corregido

## [1.0.0] - 2026-10-28

### Added

- Página de Symfony que muestra el `CHANGELOG.md` del proyecto.
- Lectura de versiones, fechas, secciones, ítems anidados, número de tarea `(#123)` e ítems `[Internal]`.
- Etiquetas de sección traducidas al español.
- Configuración de la ruta, el layout, la URL de la tarea, la visibilidad de los cambios internos y el global de Twig.

### Changed

- La versión del paquete sale del tag de git. `composer.json` ya no declara `version`.
- `vendor/` y `composer.lock` quedan fuera del repositorio.

[Unreleased]: https://github.com/GarbiSebastian/ChangelogBundle/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/GarbiSebastian/ChangelogBundle/releases/tag/v1.0.0
