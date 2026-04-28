<?php

/** @noinspection ALL */

namespace Base\Module\Src\Options\Providers;

use Base\Module\Src\Options\Interface\OptionProvider;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;

class SelectBoxProvider implements OptionProvider
{
    private array $items = [];

    public function getType(): string
    {
        return 'selectbox';
    }

    /**
     * @param array $option
     * @param string $moduleId
     * @return string
     */
    public function render(array $option, string $moduleId): string
    {
        $value = Option::get($moduleId, $option['id'], $option['params']['default'] ?? '');
        $html = '<tr>';
        $html .= '<td class="adm-detail-content-cell-l">' . $option['name'] . ':</td>';
        $html .= '<td class="adm-detail-content-cell-r">';
        $html .= '<select name="'.htmlspecialcharsbx($option['id']).'" class="typeselect">';

        foreach ($option['params']['items'] as $key => $val) {
            $selected = (string)$key === $value ? 'selected' : '';
            $html .= '<option value="'.$key.'" '.$selected.'>'.htmlspecialcharsbx($val).'</option>';
        }

        $html .= '</select>';
        $html .= '</td>';
        $html .= '</tr>';
        return $html;
    }

    /**
     * @throws ArgumentOutOfRangeException
     */
    public function save(array $option, string $moduleId, mixed $value): void
    {
        if (is_string($value)) {
            Option::set($moduleId, $option['id'], $value);
        }
    }

    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function getParamsToArray(): array
    {
        return [
            'items' => $this->items,
        ];
    }
}
