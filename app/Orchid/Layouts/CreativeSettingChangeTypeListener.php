<?php

namespace App\Orchid\Layouts;

use App\Service\Creatives\CreativeFieldsContainer;
use App\Service\Creatives\Fields\CreativeFieldsInterface;
use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout as LayoutFacade;

class CreativeSettingChangeTypeListener extends Listener
{
    private const FIELDS_CONTAINERS_KEYS = [
        'object_getter',
        'generator',
    ];

    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = ['creative_setting_type_select'];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * The name of the method must
     * begin with the prefix "async"
     *
     * @var string
     */
    protected $asyncMethod = 'asyncSeeCreativeSettingsFieldsFromType';

    /**
     * @return Layout[]
     */
    protected function layouts(): array
    {
        // Если нет выбранного типа креатива или где-то потерали список типов
        if (
            !$this->query->has('creative_setting_type_select')
            || !$this->query->has('creative_types')
        ) {
            return [];
        }

        $creativeType = $this->query->get('creative_setting_type_select');
        $creativeTypes = $this->query->get('creative_types');

        // Если нет такого типа креатива в списке
        if (!isset($creativeTypes[$creativeType])) {
            return [];
        }

        $layouts = [];

        foreach (self::FIELDS_CONTAINERS_KEYS as $key) {
            if (
                !isset($creativeTypes[$creativeType][$key])
                || !$this->isImplementsCreativeFieldsContainer($creativeTypes[$creativeType][$key])
            ) {
                continue;
            }

            /** @var CreativeFieldsContainer $fieldsContainer */
            $fieldsContainer = app($creativeTypes[$creativeType][$key]);
            /** @var CreativeFieldsInterface $fields */
            $fields = app($fieldsContainer->getFieldsClass());

            $layouts[] = $fields->getFields();

        }


        return $layouts;
    }

    /**
     * @param string $namesapce
     * @return bool
     */
    private function isImplementsCreativeFieldsContainer(string $namesapce): bool
    {
        return in_array(CreativeFieldsContainer::class, class_implements($namesapce), true);
    }
}
