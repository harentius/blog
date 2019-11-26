<?php

namespace Harentius\FolkprogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HarentiusFolkprogBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'HarentiusBlogBundle';
    }
}
