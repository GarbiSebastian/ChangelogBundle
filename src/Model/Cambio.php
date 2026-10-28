<?php

namespace Magyp\ChangelogBundle\Model;

/**
 * Un ítem de cambio del CHANGELOG.md.
 */
class Cambio {
    /**
     * @var list<Cambio>
     */
    private array $subitems = [];

    public function __construct(
        private ?int $tarea,
        private ?string $titulo,
        private ?string $descripcion,
        private bool $interno = false,
    ) {
    }

    public function addSubitem(self $subitem): void {
        $this->subitems[] = $subitem;
    }

    /**
     * Último ítem a la profundidad dada. Este cambio es el nivel 0.
     */
    public function ultimoEnNivel(int $nivel): self {
        $actual = $this;
        for ($i = 0; $i < $nivel; ++$i) {
            if ([] === $actual->subitems) {
                return $actual;
            }
            $actual = $actual->subitems[array_key_last($actual->subitems)];
        }

        return $actual;
    }

    /**
     * @return list<Cambio>
     */
    public function getSubitems(): array {
        return $this->subitems;
    }

    public function getTarea(): ?int {
        return $this->tarea;
    }

    public function getTitulo(): ?string {
        return $this->titulo;
    }

    public function getDescripcion(): ?string {
        return $this->descripcion;
    }

    public function isInterno(): bool {
        return $this->interno;
    }
}
