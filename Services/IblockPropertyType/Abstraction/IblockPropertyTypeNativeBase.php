<?php

namespace Prokl\BitrixCustomPropertiesBundle\Services\IblockPropertyType\Abstraction;

/**
 * Class IblockPropertyTypeNativeBase
 * @package Prokl\BitrixCustomPropertiesBundle\Services\IblockPropertyType\Abstraction
 *
 * @since 10.02.2021
 */
class IblockPropertyTypeNativeBase implements IblockPropertyTypeNativeInterface
{
    /**
     * @inheritdoc
     */
    public function init() : void
    {
        /** @psalm-suppress UndefinedFunction */
        AddEventHandler(
            'iblock',
            'OnIBlockPropertyBuildList',
            [$this, 'getUserTypeDescription']
        );
    }
}
