<?php

declare(strict_types=1);

namespace App\Service\Creatives\Fields;

use Carbon\Carbon;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Facades\Layout;

class FromCreativeSettingFields implements CreativeFieldsInterface
{
    /**
     * @var string
     */
    private $disk;

    public function __construct()
    {
        $this->disk = config('creatives.disk');
    }

    public function getFields(): Rows
    {
        return
            Layout::rows([
                Input::make('object_name')
                    ->title('Slug для данных')
                    ->value(Carbon::now()->format('dmYHis'))
                    ->help('Необходимо для проверки для какой записи еще нет креатива')
                ,
                Matrix::make('creative_key_value_table')
                    ->columns([
                        'Ключ' => 'key',
                        'Значение' => 'value',
                    ])
                    ->title('Значения для креатива')
                ,
                Upload::make('creative_attachments')
                    ->storage($this->disk)
                    ->parallelUploads(1)
                    ->title('Вложения для креатива')
                ,

            ])
                ->title('Настройки для генератора')
            ;
    }
}