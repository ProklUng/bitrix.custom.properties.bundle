<?php

namespace Prokl\BitrixCustomPropertiesBundle\Services\CustomProperties;

use CDBResult;
use CGroup;
use CUserTypeManager;
use Prokl\BitrixCustomPropertiesBundle\Services\CustomProperties\Abstracts\AbstractUserTypeProperty;

/**
 * Class CUserTypeUserGroup
 * Кастомное UF свойство - привязка к группе пользователей.
 * @package Prokl\BitrixCustomPropertiesBundle\Services\CustomProperties
 *
 * @since 09.02.2021
 */
class CUserTypeUserGroup extends AbstractUserTypeProperty
{
    /**
     * Массив описания собственного типа свойств.
     *
     * @return array
     */
    public function GetUserTypeDescription() : array
    {
        return [
            'USER_TYPE_ID' => 'custom_usergroup', //Уникальный идентификатор типа свойств
            'CLASS_NAME' => __CLASS__,
            'DESCRIPTION' => 'Привязка к группе пользователей',
            'BASE_TYPE' => CUserTypeManager::BASE_TYPE_INT,
        ];
    }

    /**
     * Получаем список значений.
     *
     * @param mixed $arUserField
     *
     * @return array|bool|CDBResult
     */
    public function GetList($arUserField)
    {
        $by = 'c_sort';
        $order = 'asc';
        $groups = CGroup::GetList($by, $order, ['ACTIVE' => 'Y']);
        $rsEnum = [];

        while ($arGroup = $groups->Fetch()) {
            $rsEnum[] = [
                'ID' => $arGroup['ID'],
                'VALUE' => $arGroup['NAME']
            ];
        }

        return $rsEnum;
    }

    /**
     * Получаем текст для пустого значения свойства.
     *
     * @param array $arUserField
     *
     * @return mixed|string|string[]
     */
    protected static function getEmptyCaption(array $arUserField)
    {
        return $arUserField['SETTINGS']['CAPTION_NO_VALUE'] !== ''
            ? $arUserField['SETTINGS']['CAPTION_NO_VALUE']
            : 'Группа пользователей не выбрана';
    }
}
