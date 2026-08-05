<?php

namespace DeinBrett\Application\Service;

use DeinBrett\Infrastructure\Adapter\SqliteRepository;

class ConfiguratorService
{
    private array $cache = [];

    public function __construct(private SqliteRepository $repo) {}

    public function woodTypes(): array
    {
        if (isset($this->cache['woods'])) return $this->cache['woods'];
        $rows = $this->repo->query(
            "SELECT * FROM wood_types WHERE active = 1 ORDER BY sort_order, name"
        );
        $out = [];
        foreach ($rows as $r) {
            $out[$r['key']] = [
                'id'          => $r['key'],
                'name'        => $r['name'],
                'color'       => $r['color'],
                'grain'       => $r['grain'],
                'hardness'    => $r['hardness'],
                'features'    => $r['features'],
                'description' => $r['description'],
                'price_add'   => (float) $r['price_add'],
                'image_path'  => $r['image_path'],
            ];
        }
        return $this->cache['woods'] = $out;
    }

    public function sizes(): array
    {
        if (isset($this->cache['sizes'])) return $this->cache['sizes'];
        $rows = $this->repo->query(
            "SELECT * FROM sizes WHERE active = 1 ORDER BY sort_order, base_price"
        );
        $out = [];
        foreach ($rows as $r) {
            $out[$r['key']] = [
                'label'       => $r['label'],
                'length'      => (int) $r['length_mm'],
                'width'       => (int) $r['width_mm'],
                'height'      => (int) $r['height_mm'],
                'description' => $r['description'],
                'base_price'  => (float) $r['base_price'],
            ];
        }
        return $this->cache['sizes'] = $out;
    }

    public function constructions(): array
    {
        if (isset($this->cache['constructions'])) return $this->cache['constructions'];
        $rows = $this->repo->query(
            "SELECT * FROM constructions WHERE active = 1 ORDER BY sort_order, name"
        );
        $out = [];
        foreach ($rows as $r) {
            $out[$r['key']] = [
                'id'          => $r['key'],
                'name'        => $r['name'],
                'description' => $r['description'],
                'price_add'   => (float) $r['price_add'],
            ];
        }
        return $this->cache['constructions'] = $out;
    }

    public function extras(): array
    {
        if (isset($this->cache['extras'])) return $this->cache['extras'];
        $rows = $this->repo->query(
            "SELECT * FROM extras WHERE active = 1 ORDER BY sort_order, name"
        );
        $out = [];
        foreach ($rows as $r) {
            $out[$r['key']] = [
                'id'    => $r['key'],
                'name'  => $r['name'],
                'group' => $r['category'],
                'price' => (float) $r['price'],
            ];
        }
        return $this->cache['extras'] = $out;
    }

    public function extrasGrouped(): array
    {
        if (isset($this->cache['extrasGrouped'])) return $this->cache['extrasGrouped'];
        $rows = $this->repo->query(
            "SELECT * FROM extras WHERE active = 1 ORDER BY sort_order, name"
        );
        $groups = [];
        foreach ($rows as $r) {
            $cat = $r['category'];
            if (!isset($groups[$cat])) {
                $groups[$cat] = [
                    'label'     => $r['category_label'] ?: $cat,
                    'exclusive' => (int) $r['exclusive'] === 1,
                    'items'     => [],
                ];
            }
            $groups[$cat]['items'][$r['key']] = [
                'id'    => $r['key'],
                'name'  => $r['name'],
                'group' => $cat,
                'price' => (float) $r['price'],
            ];
        }
        return $this->cache['extrasGrouped'] = $groups;
    }

    public function calculatePrice(string $woodId, string $sizeId, string $constructionId, array $extraIds): array
    {
        $woods         = $this->woodTypes();
        $sizes         = $this->sizes();
        $constructions = $this->constructions();
        $extras        = $this->extras();

        $wood         = $woods[$woodId]                 ?? reset($woods)         ?: ['name' => $woodId, 'price_add' => 0];
        $size         = $sizes[$sizeId]                 ?? reset($sizes)         ?: ['label' => $sizeId, 'base_price' => 0];
        $construction = $constructions[$constructionId] ?? reset($constructions) ?: ['name' => $constructionId, 'price_add' => 0];

        $basePrice = (float) $size['base_price'] + (float) $wood['price_add'] + (float) $construction['price_add'];

        $extrasTotal    = 0.0;
        $selectedExtras = [];
        foreach ($extraIds as $eid) {
            if (isset($extras[$eid])) {
                $extrasTotal += $extras[$eid]['price'];
                $selectedExtras[] = $extras[$eid];
            }
        }

        return [
            'wood'         => $wood,
            'size'         => $size,
            'construction' => $construction,
            'base_price'   => $basePrice,
            'extras'       => $selectedExtras,
            'extras_total' => $extrasTotal,
            'total'        => $basePrice + $extrasTotal,
        ];
    }
}
