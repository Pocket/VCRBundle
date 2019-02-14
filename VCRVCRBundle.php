<?php

namespace VCR\VCRBundle;

use allejo\VCR\VCRCleaner;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use VCR\Configuration;

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
            /** @var Configuration $config */
            $config = $this->container->get('vcr.config');

            $config->setMode($this->container->getParameter('vcr.mode'));

            if ($this->container->hasParameter('vcr.whitelist')) {
                $config->setWhiteList($this->container->getParameter('vcr.whitelist'));
            }

            $recorder->turnOn();
            $recorder->insertCassette($cassetteName);
        }

        if ($this->container->getParameter('vcr.sanitizer.enabled')) {
            VCRCleaner::enable([
                'request' => $this->container->getParameter('vcr.sanitizer.request'),
            ]);
        }
    }
}
