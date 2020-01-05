<?php

namespace Revolution\Google\Sheets\Concerns;

use Google_Service_Drive;
use Illuminate\Container\Container;
use PulkitJalan\Google\Client;

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
        $list = [];

        $files = $this->getDriveService()
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
     * @param  Google_Service_Drive|\Google_Service  $drive
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
        if (is_null($this->drive)) {
            $this->drive = Container::getInstance()->make(Client::class)->make('drive');
        }

        return $this->drive;
    }
}
