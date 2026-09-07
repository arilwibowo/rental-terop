<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublicImageStorage
{
    public static function store(UploadedFile $image, string $folder): string
    {
        $folder = trim($folder, '/');
        $directory = self::directory($folder);

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
        $image->move($directory, $filename);

        return 'images/' . $folder . '/' . $filename;
    }

    public static function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        $candidatePaths = [
            base_path('../public_html/' . $path),
            public_path($path),
            storage_path('app/public/' . $path),
        ];

        foreach ($candidatePaths as $candidatePath) {
            if (File::exists($candidatePath)) {
                File::delete($candidatePath);
            }
        }
    }

    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return asset('storage/' . $path);
    }

    private static function directory(string $folder): string
    {
        $publicHtmlPath = base_path('../public_html/images/' . $folder);

        if (File::isDirectory(base_path('../public_html'))) {
            return $publicHtmlPath;
        }

        return public_path('images/' . $folder);
    }
}
