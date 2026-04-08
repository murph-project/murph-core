<?php

namespace App\Core\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FilesizeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('readable_filesize', [$this, 'readableFilesize']),
        ];
    }

    public function readableFilesize(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; ++$i) {
            $bytes /= 1024;
        }

        return round($bytes, $precision).' '.$units[$i];
    }
}
