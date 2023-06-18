<?php

namespace Purush\CstoreBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;

class PurushCstoreBundle extends AbstractPimcoreBundle
{
    public function getJsPaths()
    {
        return [
            '/bundles/purushcstore/js/pimcore/startup.js'
        ];
    }
}