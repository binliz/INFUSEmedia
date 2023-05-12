<?php

namespace app\Handlers;

use app\Interfaces\DBConnectionInterface;

class BannerHandler extends RequestHandler
{
    public function handle(DBConnectionInterface $connection): void
    {
        $connection->log(
            [
                'ip_address' => $this->getRequestIP(),
                'user_agent' => $this->getUserAgent(),
                'page_url' => $this->getPageUrl()
            ]
        );
        $this->sendImage();
    }

    /**
     * @return void
     */
    private function sendImage(): void
    {
        header('Content-Type: image/png');
        echo base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII='
        );
    }

}
