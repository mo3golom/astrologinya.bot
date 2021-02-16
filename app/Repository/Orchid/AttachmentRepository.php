<?php

declare(strict_types=1);

namespace App\Repository\Orchid;

use App\Repository\ModelRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Orchid\Attachment\Models\Attachment;

class AttachmentRepository extends ModelRepository
{
    /**
     * @var Attachment
     */
    protected $model;

    public function __construct(Attachment $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $disk
     * @param string $path
     * @return Model
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function createFromDiskAndPath(string $disk, string $path): Model
    {
        $storage = Storage::disk($disk);
        $fullPath = 'yandexcloud' === $disk ? $storage->url($path) : $storage->path($path);
        $pathinfo = pathinfo($fullPath);

        return $this->create([
            'name' => $pathinfo['filename'],
            'mime' => $storage->getMimetype($path),
            'hash' => sha1_file($fullPath),
            'extension' => $pathinfo['extension'],
            'original_name' => $pathinfo['filename'],
            'size' => $storage->getSize($path),
            'path' => str_replace($pathinfo['basename'], '', $path),
            'disk' => $disk,
            'group' => null,
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * @param array $ids
     * @return Collection
     */
    public function getByIds(array $ids): Collection
    {
        return
            $this->model
                ->newQuery()
                ->whereIn('id', $ids)
                ->get()
            ;
    }
}