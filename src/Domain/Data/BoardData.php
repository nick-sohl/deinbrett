<?php

namespace DeinBrett\Domain\Data;

class BoardData
{
    private static array $cache = [];

    public static function woodTypes(): array
    {
        return self::$cache[__FUNCTION__] ??= [
            'eiche' => [
                'id'          => 'eiche',
                'name'        => 'Europäische Eiche',
                'color'       => 'Hell- bis Mittelbraun',
                'grain'       => 'Markant und lebendig',
                'hardness'    => 'Sehr hoch (6,0 kN)',
                'features'    => 'Hoher Gerbstoffgehalt, natürlich wasserabweisend',
                'description' => 'Die europäische Eiche ist ein Klassiker – robust, charaktervoll und zeitlos. Ihre ausgeprägte Maserung und der warme Braunton machen jedes Brett zum Unikat.',
                'price_add'   => 0,
            ],
            'raeuchereiche' => [
                'id'          => 'raeuchereiche',
                'name'        => 'Räuchereiche',
                'color'       => 'Tiefbraun bis Espresso',
                'grain'       => 'Fein und gleichmässig',
                'hardness'    => 'Sehr hoch (6,0 kN)',
                'features'    => 'Durch Ammoniakdämpfe thermisch veredelt',
                'description' => 'Räuchereiche entsteht durch ein jahrhundertealtes Verfahren. Das Holz wird in Ammoniak-Atmosphäre behandelt – das Ergebnis ist eine satte Dunkelbrauntönung und eine besondere Tiefe.',
                'price_add'   => 45,
            ],
            'schwarznuss' => [
                'id'          => 'schwarznuss',
                'name'        => 'Schwarznuss',
                'color'       => 'Dunkelbraun mit lila Schimmer',
                'grain'       => 'Geschlossen und edel',
                'hardness'    => 'Hoch (5,2 kN)',
                'features'    => 'Seltenes Edelholz, naturbelassen',
                'description' => 'Schwarznuss ist das Edelholz unter den Schneidebrettern. Sein dunkles, samtiges Erscheinungsbild und der leichte Violetton machen es zum Statement-Stück in jeder Küche.',
                'price_add'   => 85,
            ],
            'ahorn' => [
                'id'          => 'ahorn',
                'name'        => 'Ahorn',
                'color'       => 'Cremeweiß bis Hellbeige',
                'grain'       => 'Sehr fein, kaum sichtbar',
                'hardness'    => 'Hoch (6,3 kN)',
                'features'    => 'Sehr dichte Holzstruktur, messerfreundlich',
                'description' => 'Ahorn ist der Liebling professioneller Köche. Seine helle Farbe, extreme Dichte und glatte Oberfläche machen ihn zum optimalen Schneidholz – hygienisch, hart und elegant.',
                'price_add'   => 20,
            ],
            'esche' => [
                'id'          => 'esche',
                'name'        => 'Esche',
                'color'       => 'Weißgrau mit feinen Streifen',
                'grain'       => 'Ausdrucksstark, flammenartig',
                'hardness'    => 'Hoch (6,0 kN)',
                'features'    => 'Elastisch und zäh, kaum Rissbildung',
                'description' => 'Esche überrascht mit ihrer unverwechselbaren Maserung: flammenartige Streifen auf hellem Grund. Gleichzeitig ist sie eine der elastischsten Holzarten – ideal für Bretter im täglichen Einsatz.',
                'price_add'   => 15,
            ],
            'birke' => [
                'id'          => 'birke',
                'name'        => 'Birke',
                'color'       => 'Hellgelb bis Crème',
                'grain'       => 'Fein und gleichmässig',
                'hardness'    => 'Mittel (4,3 kN)',
                'features'    => 'Gleichmässige Textur, gut verarbeitbar',
                'description' => 'Birke ist ein nachhaltiges Holz aus heimischen Wäldern. Ihre gleichmässige, ruhige Maserung und das helle Crème-Weiß passen zu modernen, reduzierten Küchen.',
                'price_add'   => 0,
            ],
            'robinie' => [
                'id'          => 'robinie',
                'name'        => 'Robinie',
                'color'       => 'Goldgelb bis Hellbraun',
                'grain'       => 'Ausgeprägt und dekorativ',
                'hardness'    => 'Sehr hoch (7,8 kN)',
                'features'    => 'Extrem hart, antibakteriell, langlebig',
                'description' => 'Robinie ist eines der härtesten europäischen Hölzer überhaupt. Goldene Töne, eine expressive Maserung und eine natürliche Antibakterienwirkung machen sie zur außergewöhnlichen Wahl.',
                'price_add'   => 30,
            ],
            'birnbaum' => [
                'id'          => 'birnbaum',
                'name'        => 'Birnbaum',
                'color'       => 'Rosa-Beige bis Hellbraun',
                'grain'       => 'Sehr fein, kaum sichtbar',
                'hardness'    => 'Hoch (5,0 kN)',
                'features'    => 'Seltenes Obstholz, besonders glatt',
                'description' => 'Birnbaum ist das Obstholz der Küche. Sein zartes Rosa-Beige, die samtartige Oberfläche und die Seltenheit machen es zum Highlight jedes Brett-Sortiments.',
                'price_add'   => 60,
            ],
        ];
    }

    public static function sizes(): array
    {
        return self::$cache[__FUNCTION__] ??= [
            'S' => [
                'label'       => 'S',
                'length'      => 240,
                'width'       => 160,
                'height'      => 18,
                'description' => 'Ideal für Frühstück, Käse und schnelle Schnittarbeiten. Kompakt, leicht, pflegeleicht.',
                'base_price'  => 226,
            ],
            'M' => [
                'label'       => 'M',
                'length'      => 280,
                'width'       => 200,
                'height'      => 25,
                'description' => 'Solide Alltagsgrösse für die meisten Schneidaufgaben. Gut in der Hand, gut auf dem Tisch.',
                'base_price'  => 346,
            ],
            'L' => [
                'label'       => 'L',
                'length'      => 440,
                'width'       => 280,
                'height'      => 40,
                'description' => 'Unser Bestseller. Grosszügige Arbeitsfläche für anspruchsvolles Kochen – das meistbestellte Brett.',
                'base_price'  => 496,
            ],
            'XL' => [
                'label'       => 'XL',
                'length'      => 520,
                'width'       => 360,
                'height'      => 50,
                'description' => 'Das Profi-Brett. Maximale Arbeitsfläche, imposante Präsenz – für die Küche als Bühne.',
                'base_price'  => 696,
            ],
        ];
    }

    public static function constructions(): array
    {
        return self::$cache[__FUNCTION__] ??= [
            'laengsholz' => [
                'id'          => 'laengsholz',
                'name'        => 'Längsholz',
                'price_add'   => 0,
                'description' => 'Ruhige, lineare Maserung. Leicht, formstabil, elegant.',
            ],
            'stirnholz' => [
                'id'          => 'stirnholz',
                'name'        => 'Stirnholz',
                'price_add'   => 80,
                'description' => 'Senkrechte Fasern. Selbstheilend, messerschonend, extrem robust.',
            ],
        ];
    }

    public static function extras(): array
    {
        return self::$cache[__FUNCTION__] ??= [
            'griffmulde'                  => ['id' => 'griffmulde',                  'name' => 'Griffmulde',        'group' => 'griff',  'price' => 30],
            'griff_stirnseitig'           => ['id' => 'griff_stirnseitig',           'name' => 'Griff Stirnseitig', 'group' => 'griff',  'price' => 40],
            'fuesse_einfach'              => ['id' => 'fuesse_einfach',              'name' => 'Einfach',           'group' => 'fuesse', 'price' => 45],
            'fuesse_exclusiv'             => ['id' => 'fuesse_exclusiv',             'name' => 'Exclusiv',          'group' => 'fuesse', 'price' => 65],
            'schweizer_kante_stirnseitig' => ['id' => 'schweizer_kante_stirnseitig', 'name' => 'Stirnseitig',       'group' => 'kante',  'price' => 35],
            'schweizer_kante_umlaufend'   => ['id' => 'schweizer_kante_umlaufend',   'name' => 'Umlaufend',         'group' => 'kante',  'price' => 45],
            'saftrinne'                   => ['id' => 'saftrinne',                   'name' => 'Saftrinne',         'group' => 'rinnen', 'price' => 45],
            'saftrinne_asymmetrisch'      => ['id' => 'saftrinne_asymmetrisch',      'name' => 'Asymmetrisch',      'group' => 'rinnen', 'price' => 55],
            'gemueserinne'                => ['id' => 'gemueserinne',                'name' => 'Gemüserinne',       'group' => 'rinnen', 'price' => 50],
            'ausschnitt_messer'           => ['id' => 'ausschnitt_messer',           'name' => 'Ausschnitt Messer', 'group' => 'messer', 'price' => 60],
            'fleischbrett'                => ['id' => 'fleischbrett',                'name' => 'Fleischbrett',      'group' => 'typ',    'price' => 25],
            'brotbrett'                   => ['id' => 'brotbrett',                   'name' => 'Brotbrett',         'group' => 'typ',    'price' => 20],
            'pizzabrett'                  => ['id' => 'pizzabrett',                  'name' => 'Pizzabrett Ø',      'group' => 'typ',    'price' => 35],
        ];
    }

    public static function extrasGrouped(): array
    {
        $groups = [
            'griff'  => ['label' => 'Griff',          'exclusive' => true,  'items' => []],
            'fuesse' => ['label' => 'Füsse',           'exclusive' => true,  'items' => []],
            'kante'  => ['label' => 'Schweizer Kante', 'exclusive' => true,  'items' => []],
            'rinnen' => ['label' => 'Rinnen',          'exclusive' => true,  'items' => []],
            'messer' => ['label' => 'Messer',          'exclusive' => false, 'items' => []],
            'typ'    => ['label' => 'Brett-Typ',       'exclusive' => true,  'items' => []],
        ];
        foreach (self::extras() as $extra) {
            $groups[$extra['group']]['items'][$extra['id']] = $extra;
        }
        return $groups;
    }

    public static function calculatePrice(
        string $woodId,
        string $sizeId,
        string $constructionId,
        array  $extraIds
    ): array {
        $woods        = self::woodTypes();
        $sizes        = self::sizes();
        $constructions = self::constructions();
        $extras       = self::extras();

        $wood         = $woods[$woodId]           ?? $woods['eiche'];
        $size         = $sizes[$sizeId]           ?? $sizes['M'];
        $construction = $constructions[$constructionId] ?? $constructions['laengsholz'];

        $basePrice    = $size['base_price'] + $wood['price_add'] + $construction['price_add'];
        $extrasTotal  = 0;
        $selectedExtras = [];

        foreach ($extraIds as $eid) {
            if (isset($extras[$eid])) {
                $extrasTotal += $extras[$eid]['price'];
                $selectedExtras[] = $extras[$eid];
            }
        }

        return [
            'wood'           => $wood,
            'size'           => $size,
            'construction'   => $construction,
            'base_price'     => $basePrice,
            'extras'         => $selectedExtras,
            'extras_total'   => $extrasTotal,
            'total'          => $basePrice + $extrasTotal,
        ];
    }
}
