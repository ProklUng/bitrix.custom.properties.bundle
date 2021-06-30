<?php


namespace Prokl\BitrixCustomPropertiesBundle\Services\CustomProperties;

use Prokl\BitrixCustomPropertiesBundle\Services\IblockPropertyType\Abstraction\IblockPropertyTypeNativeInterface;

/**
 * Class TablesProperty
 * @package Prokl\BitrixCustomPropertiesBundle\Services\CustomProperties
 *
 * @since 30.06.2021
 */
class TablesProperty implements IblockPropertyTypeNativeInterface
{
    /**
     * @inheritDoc
     */
    public function init(): void
    {
        AddEventHandler(
            'iblock',
            'OnIBlockPropertyBuildList',
            [__CLASS__, 'GetUserTypeDescription']
        );
    }

    /**
     * @return array
     */
    public function GetUserTypeDescription() : array
    {
        return [
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'custom_table',
            'DESCRIPTION' => 'Таблицы',
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
            'ConvertToDB' => [__CLASS__, 'ConvertToDB'],
            'ConvertFromDB' => [__CLASS__, 'ConvertFromDB'],
            'PrepareSettings' => [__CLASS__, 'PrepareSettings'],
            'GetSettingsHTML' => [__CLASS__, 'GetSettingsHTML'],
        ];
    }

    /**
     * @param array $arFields
     *
     * @return array
     */
    public function PrepareSettings(array $arFields) : array
    {
        $col_count = (int)$arFields['USER_TYPE_SETTINGS']['COL_COUNT'];
        if ($col_count <= 1) {
            $col_count = 1;
        }

        $col_title = (string)$arFields['USER_TYPE_SETTINGS']['COL_TITLE'];

        return ['COL_COUNT' => $col_count, 'COL_TITLE' => $col_title];
    }

    /**
     * @param array $arProperty
     * @param array $strHTMLControlName
     * @param mixed $arPropertyFields
     *
     * @return string
     */
    public function GetSettingsHTML(array $arProperty, array $strHTMLControlName, &$arPropertyFields) : string
    {
        $arPropertyFields = [
            'HIDE' => ['FILTRABLE', 'COL_TITLE', 'COL_COUNT', 'DEFAULT_VALUE'],
            'SET' => ['FILTRABLE' => 'N'],
            'USER_TYPE_SETTINGS_TITLE' => 'Настройки кастомного свойства',
        ];

        return '<tr><td>Количество столбцов таблицы:</td><td><input type="text" size="50" name="'.$strHTMLControlName['NAME'].'[COL_COUNT]" value="'.$arProperty['USER_TYPE_SETTINGS']['COL_COUNT'].'"></td><td></td></tr>
<tr><td>Названия столбцов через разделитель (<b>:||:</b>):</td><td><textarea name="'.$strHTMLControlName['NAME'].'[COL_TITLE]" cols="50" rows="3">'.$arProperty['USER_TYPE_SETTINGS']['COL_TITLE'].'</textarea></td></tr>';
    }

    /**
     * @param array $arProperty
     * @param mixed $value
     * @param array $strHTMLControlName
     *
     * @return string
     */
    public function GetPropertyFieldHtml(array $arProperty, $value, array $strHTMLControlName) : string
    {
        $value = $value['VALUE'];
        $field = '';
        $col_title = explode(':||:', $arProperty['USER_TYPE_SETTINGS']['COL_TITLE']);

        $field .= '<div style="display: flex" class="custom_property_title_'.$arProperty['CODE'].'">';
        for ($i = 0; $i < $arProperty['USER_TYPE_SETTINGS']['COL_COUNT']; $i++) {
            $field .= '<span style="width: 100px;margin-right: 22px;">'.$col_title[$i].'</span>';
        }
        $field .= '</div>';

        $field .= '<div style="display: flex">';
        for ($i = 0; $i < $arProperty['USER_TYPE_SETTINGS']['COL_COUNT']; $i++) {
            $field .= '<input style="width: 100px; margin-right: 10px !important;" type="text" name="'.$strHTMLControlName["VALUE"].'['.$i.']" value="'.$value[$i].'">';
        }
        $field .= '</div>';
        $field .= '<hr>';

        $field .= '<style>.custom_property_title_'.$arProperty['CODE'].'.not_show {display: none !important;}</style>
			<script>
			Array.prototype.forEach.call(document.querySelectorAll(".custom_property_title_'.$arProperty['CODE'].'"), function(e, index){
			if(index==0){
				e.classList.remove(\'not_show\');
			} else {
				e.classList.add(\'not_show\');
			}
			});
			</script>';

        return $field;
    }

    /**
     * @param array $arProperty
     * @param mixed $value
     *
     * @return array|false
     */
    public function ConvertToDB($arProperty, $value)
    {
        $return = false;

        if (is_array($value) && array_key_exists('VALUE', $value)) {
            $arValue = [];
            foreach ($value['VALUE'] as $key => $value) {
                $arValue[$key] = htmlspecialchars($value);
            }

            $return = ['VALUE' => serialize($arValue)];
        }

        return $return;
    }

    /**
     * @param array $arProperty
     * @param mixed $value
     *
     * @return array|false
     */
    public function ConvertFromDB($arProperty, $value)
    {
        $return = false;
        if (!is_array($value['VALUE'])) {
            $return = ['VALUE' => unserialize($value['VALUE'])];
        }

        return $return;
    }
}
