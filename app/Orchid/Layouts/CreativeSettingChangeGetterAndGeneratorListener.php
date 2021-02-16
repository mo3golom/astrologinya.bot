<?php

namespace App\Orchid\Layouts;

use App\Service\Creatives\CreativeFieldsContainer;
use App\Service\Creatives\Fields\CreativeFieldsInterface;
use Orchid\Screen\Layout;
use Orchid\Screen\Layouts\Listener;
use Orchid\Support\Facades\Layout as LayoutFacade;

class CreativeSettingChangeGetterAndGeneratorListener extends Listener
{
    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = ['object_getter_class', 'generator_class'];

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
            !$this->query->has('object_getter_class')
            || !$this->query->has('generator_class')
        ) {
            return [];
        }

        $classes = [
            $this->query->get('object_getter_class'),
            $this->query->get('generator_class')
        ];

        $layouts = [];

        foreach ($classes as $class) {
            if (!$this->isImplementsCreativeFieldsContainer($class)) {
                continue;
            }

            /** @var CreativeFieldsContainer $fieldsContainer */
            $fieldsContainer = app($class);
            /** @var CreativeFieldsInterface $fields */
            $fields = app($fieldsContainer->getFieldsClass());

            $layouts[] = $fields->getFields();

        }


        return $layouts;
    }

    /**
     * @param string $namespace
     * @return bool
     */
    private function isImplementsCreativeFieldsContainer(string $namespace): bool
    {
        return in_array(CreativeFieldsContainer::class, class_implements($namespace), true);
    }
}
