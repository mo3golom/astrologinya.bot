<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Creatives;

use App\Models\CreativeSettingModel;
use App\Orchid\Layouts\CreativeSettingChangeGetterAndGeneratorListener;
use App\Service\Creatives\Generators\OnePageTextVideoGenerator;
use App\Service\Creatives\ObjectGetters\FromModelObjectGetter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class CreativeSettingEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Настройки креативов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

    /**
     * @var bool
     */
    private $exists = false;

    /**
     * @var array
     */
    private $objectGetters;

    /**
     * @var array
     */
    private $generators;

    public function __construct()
    {
        $this->objectGetters = config('creatives.object_getters');
        $this->generators = config('creatives.generators');
    }

    /**
     * Query data.
     *
     * @param CreativeSettingModel $model
     * @return array
     */
    public function query(CreativeSettingModel $model): array
    {
        $this->exists = $model->exists;

        if ($this->exists) {
            $query = [
                'is_active' => $model->is_active,
                'name' => $model->name,
                'object_getter_class' => $model->object_getter_class,
                'generator_class' => $model->generator_class,
                'is_complete' => $model->is_complete,
            ];

            foreach ($model->settings as $key => $value) {
                $query[$key] = $value;
            }

            return $query;
        }

        return [];
    }

    /**
     * Button commands.
     *
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Button::make('Создать')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Обновить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists),
        ];
    }

    /**
     * @param string|null $objectGetterClass
     * @param string|null $generatorClass
     * @return array
     */
    public function asyncSeeCreativeSettingsFieldsFromType(string $objectGetterClass = null, string $generatorClass = null): array
    {
        if (
            null === $objectGetterClass
            || null === $generatorClass
        ) {
            return [];
        }

        return [
            'object_getter_class' => $objectGetterClass,
            'generator_class' => $generatorClass,
        ];
    }

    /**
     * Views.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('name')
                    ->title('Название')
                    ->autocomplete(false)
                    ->required()
                ,
                CheckBox::make('is_active')
                    ->value(true)
                    ->placeholder('Вкл.')
                    ->title('Активность')
                ,
                Select::make('object_getter_class')
                    ->options($this->objectGetters)
                    ->title('Геттер данных')
                    ->empty('...')
                    ->required()
                ,
                Select::make('generator_class')
                    ->options($this->generators)
                    ->title('Генератор')
                    ->empty('...')
                    ->required()
                ,
            ])->title('Основные настройки'),
            CreativeSettingChangeGetterAndGeneratorListener::class,
        ];
    }

    /**
     * @param CreativeSettingModel $model
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function createOrUpdate(CreativeSettingModel $model, Request $request): RedirectResponse
    {
        $exists = $model->exists;
        $data = $request->all();
        $is_active = isset($data['is_active']);
        $name = $data['name'];
        $objectGetterClass = $data['object_getter_class'];
        $generatorClass = $data['generator_class'];

        unset($data['is_active'], $data['name'], $data['object_getter_class'], $data['generator_class'], $data['_token']);

        $model
            ->fill([
                'is_active' => $is_active,
                'name' => $name,
                'object_getter_class' => $objectGetterClass,
                'generator_class' => $generatorClass,
                'settings' => $data,
                'is_complete' => false,
            ])
            ->save()
        ;

        Alert::success(sprintf('Запись %s успешно', ($exists ? 'обновлена' : 'создана')));

        return redirect()->route('platform.service.creative.settings.edit', ['creativeSetting' => $model->creative_setting_id]);
    }

    /**
     * @param CreativeSettingModel $model
     * @return RedirectResponse
     * @throws \Exception
     */
    public function remove(CreativeSettingModel $model): RedirectResponse
    {
        $model->delete();

        Alert::success('Запись удалена успешно');

        return redirect()->route('platform.service.horoscope.list');
    }
}
