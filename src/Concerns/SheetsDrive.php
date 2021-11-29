<?php

namespace Revolution\Google\Sheets\Concerns;

use Google\Service;
use Google\Service\Drive;
use Illuminate\Container\Container;
use PulkitJalan\Google\Client;

trait SheetsDrive
{
    /**
     * @var Drive
     */
    protected $drive;

    /**
     * @return array
     */
    public function spreadsheetList(): array
    {
        $list = [];

        $files = $this->getDriveService()
            ->files
            ->listFiles(
                [
                    'q' => "mimeType = 'application/vnd.google-apps.spreadsheet'",
                ]
            )
            ->getFiles();

        foreach ($files as $file) {
            $list[$file->id] = $file->name;
        }

        return $list;
    }

    /**
     * @param  Drive|Service  $drive
     * @return $this
     */
    public function setDriveService($drive)
    {
        $this->drive = $drive;

        return $this;
    }

    /**
     * @return Drive|Service
     */
    public function getDriveService()
    {
        if (is_null($this->drive)) {
            $this->drive = Container::getInstance()->make(Client::class)->make('drive');
        }

        return $this->drive;
    }
}
