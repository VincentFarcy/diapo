<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class FileUploader
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function uploadFile($file)
    {
        if ($file) {

            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $directory = $this->params->get('image_directory');
                $file->move($directory . "/", $newFilename);

            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                return false;
            }

            return $newFilename;

        } else {

            return false;
        }
    }

    public function deleteUploadedFile(String $UploadedfileName) {

        $directory = $this->params->get('image_directory');
        $filesystem = new Filesystem();
        $filesystem->remove($directory . "/" . $UploadedfileName);
    }
}