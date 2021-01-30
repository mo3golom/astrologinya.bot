<?php

return [
    'image_text' => [
        'position_x' => env('ZODIAC_POSITION_X', 0),
        'position_y' => env('ZODIAC_POSITION_Y', 1037),
        'offset' => env('ZODIAC_TEXT_OFFSET', 10),
        'width' => env('ZODIAC_TEXT_WIDTH', 1080),
        'height' => env('ZODIAC_TEXT_HEIGHT', 640),
        'max_len' => env('ZODIAC_TEXT_MAX_LEN', 60),
        'font_size' => env('ZODIAC_TEXT_FONT_SIZE', 60),
        'font_height' => env('ZODIAC_TEXT_FONT_HEIGHT', 30),
    ],
    'default_disk' => env('ZODIAC_DEFAULT_DISK', 'public'),
];
