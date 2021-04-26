<?php

namespace Prokl\BitrixCustomPropertiesBundle;

use Prokl\BitrixCustomPropertiesBundle\DependencyInjection\BitrixCustomPropertiesExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class BitrixCustomPropertiesBundle
 * @package Prokl\BitrixCustomPropertiesBundle
 *
 * @since 10.02.2021
 */
class BitrixCustomPropertiesBundle extends Bundle
{
   /**
   * @inheritDoc
   */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            $this->extension = new BitrixCustomPropertiesExtension();
        }

        return $this->extension;
    }
}
