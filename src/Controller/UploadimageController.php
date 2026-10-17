<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use UploadImageBundle\Service\FileUploader;

final class UploadimageController extends AbstractController{
    
     #[Route('/uploadimage', name: 'app_uploadimage')] 
     public function upload(Request $request, FileUploader $fileUploader): Response
    {
        $file = $request->files->get('file');
        if (!$file) {
            return $this->json(['error' => 'Aucun fichier envoyé'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $result = $fileUploader->upload($file); // To get path and file name
            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

}
