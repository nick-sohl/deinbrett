<?php

namespace DeinBrett\Presentation\Controller;

use DeinBrett\Application\Service\CartService;
use DeinBrett\Domain\Data\BoardData;
use DeinBrett\Domain\Entity\Board;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;
use DeinBrett\Presentation\Helper\Csrf;
use DeinBrett\Presentation\Helper\Htmx;

class CartController
{
    public function __construct(
        private SqliteRepository $repo,
        private CartService      $cart,
    ) {}

    public function add(): void
    {
        Csrf::verify();
        $boardId = (int) ($_POST['board_id'] ?? 0);

        if ($boardId > 0) {
            /** @var Board|null $board */
            $board = $this->repo->findById(Board::class, $boardId);
            if ($board && $board->isAvailable()) {
                $this->cart->add($board);
            }
        }

        if (Htmx::isHtmx()) {
            $cart = $this->cart;
            include __DIR__ . '/../../../views/partials/cart-add-response.php';
            return;
        }

        header('Location: /shop');
        exit;
    }

    public function addCustom(): void
    {
        Csrf::verify();

        $validWoods         = array_keys(BoardData::woodTypes());
        $validSizes         = array_keys(BoardData::sizes());
        $validConstructions = array_keys(BoardData::constructions());
        $validExtras        = array_keys(BoardData::extras());

        $woodId         = in_array($_POST['wood']         ?? '', $validWoods,         true) ? $_POST['wood']         : 'eiche';
        $sizeId         = in_array($_POST['size']         ?? '', $validSizes,         true) ? $_POST['size']         : 'L';
        $constructionId = in_array($_POST['construction'] ?? '', $validConstructions, true) ? $_POST['construction'] : 'stirnholz';
        $extraIds       = array_values(array_intersect((array) ($_POST['extras'] ?? []), $validExtras));

        $result = BoardData::calculatePrice($woodId, $sizeId, $constructionId, $extraIds);

        $woodName  = $result['wood']['name']         ?? $woodId;
        $sizeName  = $result['size']['label']        ?? $sizeId;
        $constName = $result['construction']['name'] ?? $constructionId;
        $label     = "{$woodName} {$constName} {$sizeName}";
        if (!empty($result['extras'])) {
            $label .= ' + ' . implode(', ', array_column($result['extras'], 'name'));
        }

        $cartKey = $this->cart->addCustom($label, (float) $result['total'], [
            'wood'         => $woodId,
            'size'         => $sizeId,
            'construction' => $constructionId,
            'extras'       => $extraIds,
            'breakdown'    => $result,
        ]);

        $cart    = $this->cart;
        $boardId = $cartKey;
        include __DIR__ . '/../../../views/partials/cart-add-response.php';
    }

    public function remove(): void
    {
        Csrf::verify();
        $boardId = $_POST['board_id'] ?? '';

        if (str_starts_with($boardId, 'custom_')) {
            $this->cart->remove($boardId);
        } else {
            $this->cart->remove((int) $boardId);
        }

        header('Location: /checkout');
        exit;
    }
}
