<?php

namespace App\Managers\UploadManager;

use App\Models\File;

class UploadManager
{

    public function handleUploadFile(string $base64File, string $fileName): File
    {
        $fileType = mime_content_type($base64File);
        if (!$fileType) {
            throw new \Exception('Invalid base64 file');
        }

        $this->checkIfFileTypeIsValid($fileType);

        $fileData = $this->decodeBase64File($base64File);
        $this->saveFile($fileData, $fileName);

        $fileUrl = url('uploads/' . $fileName);
        // Create a new File record
        $file = File::create([
            'fileName' => $fileName,
            'filePath' => $fileUrl,
        ]);

        return $file;
    }

    protected function saveFile(string $fileData, string $fileName): void
    {
        # @phpstan-ignore-next-line
        $filePath = app()->basePath('public') . '/uploads/' . $fileName;
        file_put_contents($filePath, $fileData);
    }

    protected function decodeBase64File(string $base64File): string
    {
        $base64data = explode(',', $base64File, 2)[1];
        $fileData = base64_decode($base64data);

        return $fileData;
    }

    protected function checkIfFileTypeIsValid(string $fileType): void
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

        if (!in_array($fileType, $allowedMimeTypes)) {
            throw new \Exception('Invalid file type: ' . $fileType);
        }
    }
}
