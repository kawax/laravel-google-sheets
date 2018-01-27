<?php

namespace Revolution\Google\Sheets\Traits;

use Google_Service_Drive;

trait SheetsDrive
{
    /**
     * @var Google_Service_Drive
     */
    protected $drive;

    /**
     * @return array
     */
    public function spreadsheetList(): array
    {
        if (is_null($this->drive)) {
            return [];
        }

        $list = [];

        $files = $this->drive
            ->files
            ->listFiles([
                'q' => "mimeType = 'application/vnd.google-apps.spreadsheet'",
            ])
            ->getFiles();

        foreach ($files as $file) {
            $list[$file->id] = $file->name;
        }

        return $list;
    }

    /**
     * @param Google_Service_Drive|\Google_Service $drive
     *
     * @return $this
     */
    public function setDriveService($drive)
    {
        $this->drive = $drive;

        return $this;
    }

    /**
     * @return Google_Service_Drive|\Google_Service
     */
    public function getDriveService()
    {
        return $this->drive;
    }
}
