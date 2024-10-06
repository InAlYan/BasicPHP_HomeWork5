<?php

namespace Geekbrains\Application1;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use Geekbrains\Application1\Models\Time;

class Render {
    private string $viewFolder = '/src/Views/';
    private FilesystemLoader $loader;
    private Environment $environment;

    public function __construct() {
        $this->loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . $this->viewFolder);

        $this->environment = new Environment($this->loader, ['cache' => $_SERVER['DOCUMENT_ROOT'].'/cache/', ]);
    }

    public function renderPage(string $contentTemplateName = 'page-index.tpl', array $templateVariables = []) {
        $template = $this->environment->load('main.tpl');

        $templateVariables['content_template_name'] = $contentTemplateName;

        $templateVariables['content_template_header'] = 'site-header.tpl'; // Шапка

        $templateVariables['content_template_footer'] = 'site-footer.tpl'; // Подвал

        $templateVariables['content_template_sidebar'] = 'site-sidebar.tpl'; // Sidebar

        $templateVariables['content_template_cur_time'] = Time::getCurrentTime(); // Текущее время

        return $template->render($templateVariables);
    }

    public function renderError(string $contentTemplateName = 'error-rendering.tpl', array $templateVariables = []) {
        $template = $this->environment->load('error-rendering.tpl');

        return $template->render($templateVariables);
    }

}