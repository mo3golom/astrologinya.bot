<?php

namespace App\Orchid\Screens\Service;

use App\Models\HoroscopeModel;
use App\Models\HoroscopeSettingModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class HoroscopeEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создание гороскопа';

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
     * @var string|null
     */
    private $videoUrl = null;

    /**
     * Query data.
     *
     * @param HoroscopeModel $model
     * @return array
     */
    public function query(HoroscopeModel $model): array
    {
        $this->exists = $model->exists;

        if ($this->exists) {
            $this->name = 'Редактирование гороскопа';
            $this->videoUrl = $model->video_url ?? null;
        }

        return [
            'model' => $model,
        ];
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
     * Views.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Relation::make('model.horoscope_setting_id')
                    ->fromModel(HoroscopeSettingModel::class, 'zodiac')
                    ->title('Настройка гороскопа')
                    ->required()
                ,
                TextArea::make('model.description')
                    ->title('Описание гороскопа')
                    ->required()
                ,
                TextArea::make('model.short_description')
                    ->title('Короткое описание гороскопа (используется только для canva)')
                ,
                Link::make('Скачать')
                    ->title('Видео для ТикТока (Инстаграма)')
                    ->href($this->videoUrl ?? '')
                    ->canSee(null !== $this->videoUrl && '' !== $this->videoUrl)
                    ->target('_blank')
                ,
            ]),
        ];
    }

    /**
     * @param HoroscopeModel $model
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function createOrUpdate(HoroscopeModel $model, Request $request)
    {
        $model->fill($request->get('model'))->save();

        Alert::success('Запись создана успешно');

        return redirect()->route('platform.service.horoscope.list');
    }

    /**
     * @param HoroscopeModel $model
     * @return RedirectResponse
     * @throws \Exception
     */
    public function remove(HoroscopeModel $model)
    {
        $model->delete();

        Alert::success('Запись удалена успешно');

        return redirect()->route('platform.service.horoscope.list');
    }
}
