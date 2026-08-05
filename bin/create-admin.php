<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$pdo = DeinBrett\Infrastructure\Database\Database::getInstance()->getPdo();

function prompt(string $label, bool $hidden = false): string
{
    echo $label;
    if ($hidden && PHP_OS_FAMILY !== 'Windows') {
        system('stty -echo');
        $line = trim(fgets(STDIN));
        system('stty echo');
        echo "\n";
        return $line;
    }
    return trim(fgets(STDIN));
}

$email = prompt('Email: ');
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "Invalid email.\n");
    exit(1);
}

$firstName = prompt('Vorname: ');
$lastName  = prompt('Nachname: ');

$password  = prompt('Passwort: ', true);
$password2 = prompt('Passwort (Wiederholung): ', true);
if ($password === '' || $password !== $password2) {
    fwrite(STDERR, "Passwörter stimmen nicht überein.\n");
    exit(1);
}
if (strlen($password) < 8) {
    fwrite(STDERR, "Passwort muss mindestens 8 Zeichen haben.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$existing = $pdo->prepare("SELECT id FROM users WHERE lower(email) = ?");
$existing->execute([strtolower($email)]);
$row = $existing->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, password = ? WHERE id = ?");
    $stmt->execute([$firstName, $lastName, $hash, $row['id']]);
    echo "Admin-User aktualisiert (id={$row['id']}).\n";
} else {
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $email, $hash]);
    echo "Admin-User angelegt (id={$pdo->lastInsertId()}).\n";
}
