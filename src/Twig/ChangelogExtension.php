<?php

namespace Magyp\ChangelogBundle\Twig;

use Magyp\ChangelogBundle\Service\AppVersion;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class ChangelogExtension extends AbstractExtension implements GlobalsInterface {
    public function __construct(
        private AppVersion $appVersion,
        private string $globalName,
    ) {
    }

    public function getGlobals(): array {
        return [
            $this->globalName => $this->appVersion,
        ];
    }
}
