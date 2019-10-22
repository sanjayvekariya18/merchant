<?php
namespace App\Helpers;

class DomScrapeStatus
{
    public $domStatus = "scraped";

    public $manualStatus = "manual";

    public $updatedStatus = "Updated";

    public function getScrapedStatus()
    {
        return $this->domStatus;
    }

    public function getManualStatus()
    {
        return $this->manualStatus;
    }

    public function getUpdatedStatus()
    {
        return $this->updatedStatus;
    }
}
