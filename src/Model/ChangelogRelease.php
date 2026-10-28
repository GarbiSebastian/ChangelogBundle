<?php

namespace Magyp\ChangelogBundle\Model;

/**
 * Una versión del CHANGELOG.md (Keep a Changelog).
 */
class ChangelogRelease {
    /**
     * @param array<string, list<Cambio>> $secciones
     */
    public function __construct(
        private string $version,
        private ?string $fecha,
        private ?string $descripcion,
        private array $secciones = [],
        private ?string $url = null,
        private bool $yanked = false,
    ) {
    }

    public function getVersion(): string {
        return $this->version;
    }

    public function getFecha(): ?string {
        return $this->fecha;
    }

    public function getDescripcion(): ?string {
        return $this->descripcion;
    }

    /**
     * @return array<string, list<Cambio>>
     */
    public function getSecciones(): array {
        return $this->secciones;
    }

    public function getUrl(): ?string {
        return $this->url;
    }

    public function isYanked(): bool {
        return $this->yanked;
    }

    public function isVacia(): bool {
        if (null !== $this->descripcion && '' !== $this->descripcion) {
            return false;
        }
        foreach ($this->secciones as $cambios) {
            if ([] !== $cambios) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string {
        return $this->version;
    }
}
