<?php

namespace Magyp\ChangelogBundle\Controller;

use Magyp\ChangelogBundle\Service\AppVersion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ChangelogController extends AbstractController {
    public function __construct(
        private AppVersion $appVersion,
        private string $layout,
        private ?string $issueUrlPattern,
        private bool $showInternal,
    ) {
    }

    public function index(): Response {
        return $this->render('@MagypChangelog/changelog/index.html.twig', [
            'app_version' => $this->appVersion,
            'layout' => $this->layout,
            'issue_url_pattern' => $this->issueUrlPattern,
            'show_internal' => $this->showInternal,
        ]);
    }
}
