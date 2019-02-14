<?php

namespace VCR\VCRBundle;

use allejo\VCR\VCRCleaner;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class VCRVCRBundle extends Bundle
{
    public function boot()
    {
        $cassettePath = $this->container->getParameter('vcr.cassette.path');

        if (!is_dir($cassettePath)) {
            $fs = new Filesystem();
            $fs->mkdir($cassettePath);
        }

        if ($this->container->getParameter('vcr.enabled')) {
            $recorder     = $this->container->get('vcr.recorder');
            $cassetteName = $this->container->getParameter('vcr.cassette.name');

            $recorder->turnOn();
            $recorder->insertCassette($cassetteName);
        }

        if ($this->container->getParameter('vcr.sanitizer.enabled')) {
            VCRCleaner::enable([
                'request' => $this->container->getParameter('vcr.sanitizer.request'),
                'response' => $this->container->getParameter('vcr.sanitizer.response'),
            ]);
        }
    }
}
