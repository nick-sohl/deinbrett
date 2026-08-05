<?php

return function (PDO $pdo): void {
    $count = (int) $pdo->query('SELECT COUNT(*) FROM wood_types')->fetchColumn();
    if ($count === 0) {
        $woods = [
            ['eiche',         'Europäische Eiche', 'Hell- bis Mittelbraun',       'Markant und lebendig',     'Sehr hoch (6,0 kN)', 'Hoher Gerbstoffgehalt, natürlich wasserabweisend', 'Die europäische Eiche ist ein Klassiker – robust, charaktervoll und zeitlos. Ihre ausgeprägte Maserung und der warme Braunton machen jedes Brett zum Unikat.',                             0,  10],
            ['raeuchereiche', 'Räuchereiche',      'Tiefbraun bis Espresso',      'Fein und gleichmässig',    'Sehr hoch (6,0 kN)', 'Durch Ammoniakdämpfe thermisch veredelt',           'Räuchereiche entsteht durch ein jahrhundertealtes Verfahren. Das Holz wird in Ammoniak-Atmosphäre behandelt – das Ergebnis ist eine satte Dunkelbrauntönung und eine besondere Tiefe.', 45, 20],
            ['schwarznuss',   'Schwarznuss',       'Dunkelbraun mit lila Schimmer','Geschlossen und edel',    'Hoch (5,2 kN)',      'Seltenes Edelholz, naturbelassen',                  'Schwarznuss ist das Edelholz unter den Schneidebrettern. Sein dunkles, samtiges Erscheinungsbild und der leichte Violetton machen es zum Statement-Stück in jeder Küche.',                85, 30],
            ['ahorn',         'Ahorn',             'Cremeweiß bis Hellbeige',     'Sehr fein, kaum sichtbar', 'Hoch (6,3 kN)',      'Sehr dichte Holzstruktur, messerfreundlich',        'Ahorn ist der Liebling professioneller Köche. Seine helle Farbe, extreme Dichte und glatte Oberfläche machen ihn zum optimalen Schneidholz – hygienisch, hart und elegant.',              20, 40],
            ['esche',         'Esche',             'Weißgrau mit feinen Streifen','Ausdrucksstark, flammenartig','Hoch (6,0 kN)',   'Elastisch und zäh, kaum Rissbildung',              'Esche überrascht mit ihrer unverwechselbaren Maserung: flammenartige Streifen auf hellem Grund. Gleichzeitig ist sie eine der elastischsten Holzarten – ideal für Bretter im täglichen Einsatz.', 15, 50],
            ['birke',         'Birke',             'Hellgelb bis Crème',          'Fein und gleichmässig',    'Mittel (4,3 kN)',    'Gleichmässige Textur, gut verarbeitbar',            'Birke ist ein nachhaltiges Holz aus heimischen Wäldern. Ihre gleichmässige, ruhige Maserung und das helle Crème-Weiß passen zu modernen, reduzierten Küchen.',                            0,  60],
            ['robinie',       'Robinie',           'Goldgelb bis Hellbraun',      'Ausgeprägt und dekorativ', 'Sehr hoch (7,8 kN)', 'Extrem hart, antibakteriell, langlebig',            'Robinie ist eines der härtesten europäischen Hölzer überhaupt. Goldene Töne, eine expressive Maserung und eine natürliche Antibakterienwirkung machen sie zur außergewöhnlichen Wahl.', 30, 70],
            ['birnbaum',      'Birnbaum',          'Rosa-Beige bis Hellbraun',    'Sehr fein, kaum sichtbar', 'Hoch (5,0 kN)',      'Seltenes Obstholz, besonders glatt',                'Birnbaum ist das Obstholz der Küche. Sein zartes Rosa-Beige, die samtartige Oberfläche und die Seltenheit machen es zum Highlight jedes Brett-Sortiments.',                              60, 80],
        ];
        $stmt = $pdo->prepare("INSERT INTO wood_types (key, name, color, grain, hardness, features, description, price_add, sort_order, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
        foreach ($woods as $w) $stmt->execute($w);
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM sizes')->fetchColumn();
    if ($count === 0) {
        $sizes = [
            ['S',  'S',  240, 160, 18, 'Ideal für Frühstück, Käse und schnelle Schnittarbeiten. Kompakt, leicht, pflegeleicht.',                                    226, 10],
            ['M',  'M',  280, 200, 25, 'Solide Alltagsgrösse für die meisten Schneidaufgaben. Gut in der Hand, gut auf dem Tisch.',                                346, 20],
            ['L',  'L',  440, 280, 40, 'Unser Bestseller. Grosszügige Arbeitsfläche für anspruchsvolles Kochen – das meistbestellte Brett.',                       496, 30],
            ['XL', 'XL', 520, 360, 50, 'Das Profi-Brett. Maximale Arbeitsfläche, imposante Präsenz – für die Küche als Bühne.',                                    696, 40],
        ];
        $stmt = $pdo->prepare("INSERT INTO sizes (key, label, length_mm, width_mm, height_mm, description, base_price, sort_order, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)");
        foreach ($sizes as $s) $stmt->execute($s);
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM constructions')->fetchColumn();
    if ($count === 0) {
        $constructions = [
            ['laengsholz', 'Längsholz', 'Ruhige, lineare Maserung. Leicht, formstabil, elegant.',                     0, 10],
            ['stirnholz',  'Stirnholz', 'Senkrechte Fasern. Selbstheilend, messerschonend, extrem robust.',           80, 20],
        ];
        $stmt = $pdo->prepare("INSERT INTO constructions (key, name, description, price_add, sort_order, active) VALUES (?, ?, ?, ?, ?, 1)");
        foreach ($constructions as $c) $stmt->execute($c);
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM extras')->fetchColumn();
    if ($count === 0) {
        $extras = [
            ['griffmulde',                  'Griffmulde',        'griff',  'Griff',          1, 30,  10],
            ['griff_stirnseitig',           'Griff Stirnseitig', 'griff',  'Griff',          1, 40,  20],
            ['fuesse_einfach',              'Einfach',           'fuesse', 'Füsse',          1, 45,  30],
            ['fuesse_exclusiv',             'Exclusiv',          'fuesse', 'Füsse',          1, 65,  40],
            ['schweizer_kante_stirnseitig', 'Stirnseitig',       'kante',  'Schweizer Kante',1, 35,  50],
            ['schweizer_kante_umlaufend',   'Umlaufend',         'kante',  'Schweizer Kante',1, 45,  60],
            ['saftrinne',                   'Saftrinne',         'rinnen', 'Rinnen',         1, 45,  70],
            ['saftrinne_asymmetrisch',      'Asymmetrisch',      'rinnen', 'Rinnen',         1, 55,  80],
            ['gemueserinne',                'Gemüserinne',       'rinnen', 'Rinnen',         1, 50,  90],
            ['ausschnitt_messer',           'Ausschnitt Messer', 'messer', 'Messer',         0, 60, 100],
            ['fleischbrett',                'Fleischbrett',      'typ',    'Brett-Typ',      1, 25, 110],
            ['brotbrett',                   'Brotbrett',         'typ',    'Brett-Typ',      1, 20, 120],
            ['pizzabrett',                  'Pizzabrett Ø',      'typ',    'Brett-Typ',      1, 35, 130],
        ];
        $stmt = $pdo->prepare("INSERT INTO extras (key, name, category, category_label, exclusive, price, sort_order, active) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        foreach ($extras as $e) $stmt->execute($e);
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM settings')->fetchColumn();
    if ($count === 0) {
        $settings = [
            ['shipping_cost', '0'],
            ['admin_email',   getenv('ADMIN_EMAIL') ?: 'info@deinbrett.ch'],
        ];
        $stmt = $pdo->prepare("INSERT INTO settings (key, value) VALUES (?, ?)");
        foreach ($settings as $s) $stmt->execute($s);
    }
};
