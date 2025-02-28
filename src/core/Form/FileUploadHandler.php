<?php

namespace App\Core\Form;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * class FileUploadHandler.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class FileUploadHandler
{
    protected $filenameGenerator;

    public function setFilenameGenerator(callable $filenameGenerator): self
    {
        $this->filenameGenerator = $filenameGenerator;

        return $this;
    }

    public function handleForm(
        null|array|UploadedFile $uploadedFile,
        string $path,
        ?callable $afterUploadCallback = null,
        ?callable $afterUploadsCallback = null,
        bool $keepOriginalFilename = false
    ): null|array|string
    {
        if (null === $uploadedFile) {
            return null;
        }

        if (is_array($uploadedFile)) {
            $filenames = [];

            foreach ($uploadedFile as $file) {
                $filename = $this->handleForm($file, $path, $afterUploadCallback, null, $keepOriginalFilename);

                if ($filename !== null) {
                    $filenames[] = $filename;
                }
            }

            if (!empty($filenames) && $afterUploadsCallback) {
                $afterUploadsCallback($filenames);
            }

            return $filenames;
        } else {
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

            if ($keepOriginalFilename) {
                $filename = $originalFilename.'.'.$uploadedFile->guessExtension();
            } elseif (!is_callable($this->filenameGenerator)) {
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $filename = date('Ymd-his').$safeFilename.'.'.$uploadedFile->guessExtension();
            } else {
                $filename = call_user_func($this->filenameGenerator, $uploadedFile);
            }

            $uploadedFile->move($path, $filename);

            if ($afterUploadCallback) {
                $afterUploadCallback($filename);
            }

            return $filename;
        }
    }
}
