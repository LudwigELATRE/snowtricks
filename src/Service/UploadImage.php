<?php

namespace App\Service;

use App\Entity\Image;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadImage {

    private ParameterBagInterface $params;
    private Filesystem $filesystem;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->filesystem = new Filesystem();
    }

    /**
     * Créer un nom et un path pour l'image et l'enregistre sur le disque
     *
     * @param Image $image
     * @return Image
     * @throws FileException
     */
    public function saveImage(Image $image, string $type): Image
    {
        // Récupère le fichier de l'image uploadée
        $file = $image->getFile();

        if (!$file) {
            throw new \InvalidArgumentException('No file provided.');
        }

        // Créer un nom unique pour le fichier
        $name = md5(uniqid()) . '.' . $file->guessExtension();
        // Chemin de destination
        $path = $this->getTargetDirectory($type);

        try {
            // Vérifie si le répertoire de destination existe, sinon le crée
            if (!$this->filesystem->exists($path)) {
                $this->filesystem->mkdir($path, 0700);
            }

            // Ajoutez un débogage pour vérifier les permissions
            if (!is_writable($path)) {
                throw new \RuntimeException('The directory is not writable: ' . $path);
            }

            // Déplace le fichier
            $file->move($path, $name);
        } catch (FileException $e) {
            // Gère les erreurs lors du déplacement du fichier
            throw new FileException('Failed to move the uploaded file. ' . $e->getMessage());
        } catch (IOExceptionInterface $e) {
            // Gère les erreurs de création de répertoire
            throw new \RuntimeException('Failed to create the directory. ' . $e->getMessage());
        }

        // Donner le path et le nom au fichier dans la base de données
        // rendre le pah dinamique pour si c'est un trick ou un user
        $relativePath = '/assets/images/' . $type . '/' . $name;
        $image->setPath($relativePath);
        $image->setName($name);
        return $image;
    }

    /**
     * @return string
     */
    public function getTargetDirectory(string $type): string
    {
        if ($type === 'tricks') {
            return $this->params->get('tricks_image_directory');
        } elseif ($type === 'avatars') {
            return $this->params->get('avatars_image_directory');
        } else {
            throw new \InvalidArgumentException('Invalid image type specified.');
        }
    }

}
