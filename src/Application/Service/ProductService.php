<?php

namespace DeinBrett\Application\Service;

use DeinBrett\Domain\Entity\Board;
use DeinBrett\Infrastructure\Adapter\SqliteRepository;

class ProductService
{
    private const UPLOAD_DIR = __DIR__ . '/../../../public/uploads/products';
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB
    private const ALLOWED_EXT = ['jpg', 'jpeg', 'png', 'webp'];

    public function __construct(private SqliteRepository $repo) {}

    public function list(): array
    {
        return $this->repo->query("SELECT * FROM boards ORDER BY featured DESC, name ASC");
    }

    public function find(int $id): ?Board
    {
        return $this->repo->findById(Board::class, $id);
    }

    public function create(array $data, array $file): int
    {
        $slug = $this->uniqueSlug($this->slugify($data['name']));

        $imagePath = '';
        if (!empty($file['tmp_name'])) {
            $imagePath = $this->uploadImage($file, $slug);
        }

        $this->repo->execute(
            "INSERT INTO boards
               (name, slug, tagline, description, wood_type, construction, size, extras, price, stock, featured, image_path)
             VALUES (:name, :slug, :tagline, :description, :wood, :construction, :size, :extras, :price, :stock, :featured, :image)",
            [
                ':name'         => $data['name'],
                ':slug'         => $slug,
                ':tagline'      => $data['tagline'] ?? '',
                ':description'  => $data['description'] ?? '',
                ':wood'         => $data['wood_type'],
                ':construction' => $data['construction'],
                ':size'         => $data['size'],
                ':extras'       => json_encode(array_values($data['extras'] ?? [])),
                ':price'        => (float) $data['price'],
                ':stock'        => (int) ($data['stock'] ?? 1),
                ':featured'     => !empty($data['featured']) ? 1 : 0,
                ':image'        => $imagePath,
            ]
        );

        return $this->repo->lastInsertId();
    }

    public function update(int $id, array $data, array $file): void
    {
        $current = $this->find($id);
        if (!$current) return;

        $imagePath = $current->image_path;
        if (!empty($file['tmp_name'])) {
            $newImage = $this->uploadImage($file, $current->slug);
            if ($imagePath && $imagePath !== $newImage) {
                $this->deleteImageFile($imagePath);
            }
            $imagePath = $newImage;
        }

        $this->repo->execute(
            "UPDATE boards SET
                name         = :name,
                tagline      = :tagline,
                description  = :description,
                wood_type    = :wood,
                construction = :construction,
                size         = :size,
                extras       = :extras,
                price        = :price,
                stock        = :stock,
                featured     = :featured,
                image_path   = :image
             WHERE id = :id",
            [
                ':name'         => $data['name'],
                ':tagline'      => $data['tagline'] ?? '',
                ':description'  => $data['description'] ?? '',
                ':wood'         => $data['wood_type'],
                ':construction' => $data['construction'],
                ':size'         => $data['size'],
                ':extras'       => json_encode(array_values($data['extras'] ?? [])),
                ':price'        => (float) $data['price'],
                ':stock'        => (int) ($data['stock'] ?? 1),
                ':featured'     => !empty($data['featured']) ? 1 : 0,
                ':image'        => $imagePath,
                ':id'           => $id,
            ]
        );
    }

    public function delete(int $id): void
    {
        $product = $this->find($id);
        if (!$product) return;
        if ($product->image_path) {
            $this->deleteImageFile($product->image_path);
        }
        $this->repo->execute("DELETE FROM boards WHERE id = :id", [':id' => $id]);
    }

    private function uploadImage(array $file, string $slug): string
    {
        if (($file['error'] ?? 0) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload fehlgeschlagen (Fehler-Code ' . $file['error'] . ').');
        }
        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new \RuntimeException('Datei zu gross (max. 5 MB).');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, self::ALLOWED_EXT, true)) {
            throw new \RuntimeException('Ungültiges Dateiformat. Erlaubt: jpg, png, webp.');
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            throw new \RuntimeException('Ungültiger MIME-Typ.');
        }

        if (!is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0755, true);
        }

        $filename = $slug . '-' . substr(bin2hex(random_bytes(4)), 0, 6) . '.' . $ext;
        $target   = self::UPLOAD_DIR . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new \RuntimeException('Datei konnte nicht gespeichert werden.');
        }

        return '/uploads/products/' . $filename;
    }

    private function deleteImageFile(string $path): void
    {
        $abs = __DIR__ . '/../../../public' . $path;
        if (is_file($abs)) @unlink($abs);
    }

    public function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $map = ['ä' => 'ae', 'ö' => 'oe', 'ü' => 'ue', 'ß' => 'ss'];
        $text = strtr($text, $map);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');
        return $text ?: 'produkt';
    }

    private function uniqueSlug(string $base): string
    {
        $slug = $base;
        $i    = 1;
        while ($this->slugExists($slug)) {
            $slug = $base . '-' . (++$i);
        }
        return $slug;
    }

    private function slugExists(string $slug): bool
    {
        $rows = $this->repo->query("SELECT 1 FROM boards WHERE slug = :s LIMIT 1", [':s' => $slug]);
        return !empty($rows);
    }
}
