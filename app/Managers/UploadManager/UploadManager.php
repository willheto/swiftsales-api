<?php

namespace App\Managers\UploadManager;

use App\Models\File;

class UploadManager
{

    public static function uploadFile($base64File, $fileName)
    {
        $allowedMimeTypes = [
            'image/jpeg',
            'image/gif',
            'image/png',
            'image/bmp',
            'image/svg+xml',
            'image/webp',
            'image/tiff',
            'application/pdf',
            'application/msword', // DOC
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
            'application/vnd.ms-excel', // XLS
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // XLSX
            'application/vnd.ms-powerpoint', // PPT
            'application/vnd.openxmlformats-officedocument.presentationml.presentation', // PPTX
            'text/plain', // TXT
            'text/csv', // CSV
            'application/rtf', // RTF
        ];

        // only allow image file types
        if (!in_array(mime_content_type($base64File), $allowedMimeTypes)) {
            throw new \Exception('Invalid file type: ' . mime_content_type($base64File));
        }

        $base64data = explode(',', $base64File, 2)[1];
        // Decode base64 to binary data
        $fileData = base64_decode($base64data);

        // save file to public/uploads
        $filePath = app()->basePath('public') . '/uploads/' . $fileName;

        file_put_contents($filePath, $fileData);

        $fileUrl = url('uploads/' . $fileName);

        // Create a new File record
        $file = File::create([
            'fileName' => $fileName,
            'filePath' => $fileUrl,
        ]);

        return $file;
    }
}
