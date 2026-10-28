<?php

namespace Magyp\ChangelogBundle\Service;

use Magyp\ChangelogBundle\Model\Cambio;
use Magyp\ChangelogBundle\Model\ChangelogRelease;

/**
 * Lee y expone el CHANGELOG.md del proyecto.
 */
class AppVersion {
    /**
     * @var list<ChangelogRelease>|null
     */
    private ?array $changelog = null;

    public function __construct(
        private string $changelogPath,
    ) {
    }

    public function actual(): ?ChangelogRelease {
        foreach ($this->getChangelog() as $release) {
            if ('Unreleased' !== $release->getVersion()) {
                return $release;
            }
        }

        return null;
    }

    /**
     * @return list<ChangelogRelease>
     */
    public function getChangelog(): array {
        if (null === $this->changelog) {
            $this->changelog = $this->parseChangelog();
        }

        return $this->changelog;
    }

    /**
     * @return list<ChangelogRelease>
     */
    private function parseChangelog(): array {
        if (!is_readable($this->changelogPath)) {
            return [];
        }

        $markdown = file_get_contents($this->changelogPath);
        if (false === $markdown) {
            return [];
        }

        $urls = [];
        $markdown = preg_replace_callback(
            '/^\[([^\]]+)\]:\s+(\S+)\s*$/m',
            function (array $m) use (&$urls): string {
                $urls[$m[1]] = $m[2];

                return '';
            },
            $markdown
        );

        $releases = [];
        $current = null;
        $seccion = null;

        foreach (preg_split('/\R/', $markdown) as $line) {
            if (preg_match('/^## \[([^\]]+)\](?:\s+-\s+(\d{4}-\d{2}-\d{2}))?(?:\s+\[YANKED\])?/i', $line, $m)) {
                if (null !== $current) {
                    $releases[] = $this->buildRelease($current, $urls);
                }
                $current = [
                    'version' => $m[1],
                    'fecha' => $m[2] ?? null,
                    'descripcion' => null,
                    'secciones' => [],
                    'yanked' => str_contains(strtoupper($line), '[YANKED]'),
                ];
                $seccion = null;
                continue;
            }

            if (null === $current) {
                continue;
            }

            if (preg_match('/^###\s+(.+)/', $line, $m)) {
                $seccion = trim($m[1]);
                $current['secciones'][$seccion] ??= [];
                continue;
            }

            if (null !== $seccion && preg_match('/^(\s*)-\s+(.+)/', $line, $m)) {
                $this->agregarCambio(
                    $current['secciones'][$seccion],
                    $this->parseCambio($m[2]),
                    intdiv(strlen(str_replace("\t", '  ', $m[1])), 2),
                );
                continue;
            }

            $texto = trim($line);
            if ('' === $texto || str_starts_with($texto, '#')) {
                continue;
            }
            if (null === $seccion && null === $current['descripcion']) {
                $current['descripcion'] = $texto;
            }
        }

        if (null !== $current) {
            $releases[] = $this->buildRelease($current, $urls);
        }

        return array_values(array_filter(
            $releases,
            fn (ChangelogRelease $r) => 'Unreleased' !== $r->getVersion() || !$r->isVacia()
        ));
    }

    /**
     * @param array{version: string, fecha: ?string, descripcion: ?string, secciones: array<string, list<Cambio>>, yanked: bool} $datos
     * @param array<string, string>                                                                                              $urls
     */
    private function buildRelease(array $datos, array $urls): ChangelogRelease {
        return new ChangelogRelease(
            $datos['version'],
            $datos['fecha'],
            $datos['descripcion'],
            $datos['secciones'],
            $urls[$datos['version']] ?? null,
            $datos['yanked'],
        );
    }

    /**
     * @param list<Cambio> $items
     */
    private function agregarCambio(array &$items, Cambio $cambio, int $nivel): void {
        if (0 === $nivel || [] === $items) {
            $items[] = $cambio;

            return;
        }

        $items[array_key_last($items)]->ultimoEnNivel($nivel - 1)->addSubitem($cambio);
    }

    private function parseCambio(string $item): Cambio {
        $tarea = null;
        if (preg_match('/\(#(\d+)\)/', $item, $m)) {
            $tarea = (int) $m[1];
            $item = trim(preg_replace('/\s*\(#\d+\)/', '', $item));
        }

        $interno = 1 === preg_match('/^\[Internal\]\s*/', $item);
        if ($interno) {
            $item = trim(preg_replace('/^\[Internal\]\s*/', '', $item) ?? $item);
        }

        return new Cambio($tarea, null, $item, $interno);
    }
}
