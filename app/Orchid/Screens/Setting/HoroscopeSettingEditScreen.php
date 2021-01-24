<?php

namespace App\Orchid\Screens\Setting;

use App\Models\HoroscopeSettingModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class HoroscopeSettingEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создание настройки гороскопа';

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
     * Query data.
     *
     * @param HoroscopeSettingModel $model
     * @return array
     */
    public function query(HoroscopeSettingModel $model): array
    {
        $this->exists = $model->exists;

        if ($this->exists) {
            $this->name = 'Редактирование настройки гороскопа';
       }

        return [
            'model' => $model,
            'send_time' => $model->send_time->format('H:i'),
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
        $zodiac = HoroscopeSettingModel::getZodiacEnum();
        $zodiacSelect = array_combine($zodiac, $zodiac);
        return [
            Layout::rows([
                Select::make('model.zodiac')
                    ->title('Знак зодиака')
                    ->options($zodiacSelect)
                    ->empty('Выбрать...')
                    ->required()
                ,
                Input::make('model.parse_url')
                    ->title('Ссылка для парсинга описания')
                    ->required()
                ,
                Input::make('model.short_description_parse_url')
                    ->title('Ссылка для парсинга короткого описания')
                ,
                SimpleMDE::make('model.template')
                    ->title('Шаблон сообщения')
                ,
                DateTimer::make('send_time')
                    ->title('Время отправки сообщения')
                    ->noCalendar()
                    ->format24hr()
                    ->allowInput()
                    ->format('H:i')
                    ->required()
                ,
            ]),
        ];
    }

    /**
     * @param HoroscopeSettingModel $model
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function createOrUpdate(HoroscopeSettingModel $model, Request $request)
    {
        $data = $request->get('model');
        $data['send_time'] = $request->get('send_time');
        $model->fill($data)->save();

        Alert::success('Запись создана успешно');

        return redirect()->route('platform.setting.horoscope.list');
    }

    /**
     * @param HoroscopeSettingModel $model
     * @return RedirectResponse
     * @throws \Exception
     */
    public function remove(HoroscopeSettingModel $model)
    {
        $model->delete();

        Alert::success('Запись удалена успешно');

        return redirect()->route('platform.setting.horoscope.list');
    }
}
