<?php

namespace DeinBrett\Presentation\Controller;

use DeinBrett\Application\Service\CartService;
use DeinBrett\Domain\Entity\Board;
use DeinBrett\Domain\Data\BoardData;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;
use DeinBrett\Presentation\View\View;

class ShopController
{
    public function __construct(
        private SqliteRepository $repo,
        private CartService      $cart,
    ) {}

    public function index(): void
    {
        $boards    = $this->repo->findAll(Board::class);
        $woodTypes = BoardData::woodTypes();
        $sizes     = BoardData::sizes();
        $cartIds   = array_column($this->cart->items(), 'board_id');

        $view = new View('shop', 'index', [
            'boards'    => $boards,
            'woodTypes' => $woodTypes,
            'sizes'     => $sizes,
            'cartIds'   => $cartIds,
            'cartCount' => $this->cart->count(),
            'showHero'  => false,
        ]);
        $view->renderFull();
    }
}
