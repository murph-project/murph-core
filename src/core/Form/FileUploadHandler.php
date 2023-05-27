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

    public function handleForm(?UploadedFile $uploadedFile, string $path, ?callable $afterUploadCallback = null, bool $keepOriginalFilename = false): void
    {
        if (null === $uploadedFile) {
            return;
        }

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
    }
}
