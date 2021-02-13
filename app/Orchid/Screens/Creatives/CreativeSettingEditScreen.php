<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Creatives;

use App\Models\CreativeSettingModel;
use App\Orchid\Layouts\CreativeSettingChangeTypeListener;
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
    private $creativeTypes;

    /**
     * CreativeSettingScreen constructor.
     */
    public function __construct()
    {
        $this->creativeTypes = config('creatives.creatives');
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
                'creative_setting_type_select' => $model->type,
                'is_complete' => $model->is_complete,
                'creative_types' => $this->creativeTypes,
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
     * Метод для слушателя
     *
     * @param string|null $type
     * @return array
     */
    public function asyncSeeCreativeSettingsFieldsFromType(string $type = null): array
    {
        if (null === $type) {
            return [];
        }

        return [
            'creative_setting_type_select' => $type,
            'creative_types' => $this->creativeTypes,
        ];
    }

    /**
     * Views.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        $creativeTypes = [];
        foreach ($this->creativeTypes as $key => $creativeType) {
            $creativeTypes[$key] = $creativeType['name'] ?? $key;
        }

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
                Select::make('creative_setting_type_select')
                    ->options($creativeTypes)
                    ->title('Тип креатива')
                    ->empty('...')
                    ->required()
                ,
            ])->title('Основные настройки'),
            CreativeSettingChangeTypeListener::class,
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
        $type = $data['creative_setting_type_select'];

        unset($data['is_active'], $data['name'], $data['creative_setting_type_select'], $data['_token']);

        $model
            ->fill([
                'is_active' => $is_active,
                'name' => $name,
                'type' => $type,
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
