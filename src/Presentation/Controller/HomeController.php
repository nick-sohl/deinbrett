<?php

namespace DeinBrett\Presentation\Controller;

use DeinBrett\Presentation\Helper\Htmx;
use DeinBrett\Presentation\View\View;

class HomeController
{
    public function index(): void
    {
        $view = new View("home", "index");

        if (Htmx::isHtmx()) {
            $view->render();
        } else {
            $view->renderFull();
        }
    }
}
