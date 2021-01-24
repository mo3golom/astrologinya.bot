<?php

declare(strict_types=1);

namespace App\Service;

use Illuminate\Support\Facades\Log;
use PHPHtmlParser\Dom;

/**
 * Class OrakulParserService
 */
class OrakulParserService implements ParserServiceInterface
{
    /**
     * @var Dom
     */
    protected $dom;

    public function __construct(Dom $dom)
    {
        $this->dom = $dom;
    }

    public function parse(string $url): string
    {
        try {
            $this->dom->loadFromUrl($url);
            $horoBlock = $this->dom->find('.horoBlock')[0];

            return trim(strip_tags($horoBlock->find('p')[0]->text));
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . $th->getTraceAsString());
        }

        return '';
    }
}