<?php

namespace App\Service\Creatives\Fields;

use Orchid\Screen\Fields\Group;
use Orchid\Screen\Layouts\Rows;

interface CreativeFieldsInterface
{
    public function getFields(): Rows;
}