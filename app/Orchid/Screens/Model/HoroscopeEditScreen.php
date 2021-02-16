<?php

namespace App\Orchid\Screens\Model;

use App\Models\HoroscopeModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
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
     * @var string
     */
    private $disk;

    /**
     * HoroscopeSettingEditScreen constructor.
     */
    public function __construct()
    {
        $this->disk = config('creatives.disk');
    }

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
        $zodiac = HoroscopeModel::getZodiacEnum();
        $zodiacSelect = array_combine($zodiac, $zodiac);

        return [
            Layout::rows([
                Select::make('model.zodiac_name')
                    ->title('Знак зодиака')
                    ->options($zodiacSelect)
                    ->empty('...')
                    ->required()
                ,
                Input::make('model.description_parse_url')
                    ->title('Ссылка для парсинга короткого описания')
                    ->required()
                    ->autocomplete(false)
                ,
                TextArea::make('model.description')
                    ->title('Описание гороскопа')
                    ->rows(4)
                    ->readonly()
                ,
                Upload::make('model.video_id')
                    ->title('Шаблон видео для ТикТока (Инстаграма)')
                    ->acceptedFiles('video/mp4,video/x-m4v,video/*')
                    ->maxFiles(1)
                    ->storage($this->disk)
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
        $exists = $model->exists;
        $data = $request->get('model');

        if (
            isset($data['video_id'])
            && is_array($data['video_id'])
        ) {
            $data['video_id'] = (int) $data['video_id'][0];
        }

        $model->fill($data)->save();

        Alert::success(sprintf('Запись %s успешно', $exists ? 'обновлена' : 'создана'));

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
