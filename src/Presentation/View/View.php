<?php

namespace DeinBrett\Presentation\View;

class View
{
    private string $page;
    private string $view;
    private array $data;

    /**
     * @param array<int,mixed> $data
     */
    public function __construct(string $page, string $view, ?array $data = [])
    {
        $this->page = $page;
        $this->view = $view;
        $this->data = $data;
    }

    public function render(): void
    {
        extract($this->data);
        include __DIR__ . "/../../../views/pages/{$this->page}/{$this->view}.php";
    }

    public function renderFull(): void
    {
        $page = $this->page;
        $view = $this->view;
        $data = $this->data;
        include __DIR__ . "/../../../views/app/layouts/base.php";
    }
}
