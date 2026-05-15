<?php

namespace DeinBrett\Presentation\Controller;

use DeinBrett\Domain\Data\BoardData;
use DeinBrett\Presentation\Helper\Csrf;

class KalkulatorController
{
    public function calculate(): void
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

        $result = BoardData::calculatePrice($woodId, $sizeId, $constructionId, (array) $extraIds);

        include __DIR__ . '/../../../views/partials/kalkulator-summary.php';
    }

    public function woodInfo(): void
    {
        $woodId    = $_GET['wood'] ?? 'eiche';
        $woodTypes = BoardData::woodTypes();
        $wood      = $woodTypes[$woodId] ?? $woodTypes['eiche'];

        // Return updated selector + properties so active state stays in sync
        $allWoods  = $woodTypes;

        include __DIR__ . '/../../../views/partials/wood-properties.php';
    }

    public function sizeInfo(): void
    {
        $sizeId  = $_GET['size'] ?? 'L';
        $sizes   = BoardData::sizes();
        $size    = $sizes[$sizeId] ?? $sizes['L'];
        $allSizes = $sizes;

        include __DIR__ . '/../../../views/partials/size-properties.php';
    }
}
