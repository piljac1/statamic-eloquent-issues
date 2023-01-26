<?php

namespace App\Eloquent\Entries;

use App\Models\EntryModel as Model;
use Statamic\Entries\Entry as FileEntry;
use Statamic\Facades\Entry as EntryFacade;

class Entry extends FileEntry
{
    protected $model;

    public static function fromModel(Model $model)
    {
        return (new static)
            ->locale($model->site)
            ->slug($model->slug)
            ->date($model->date)
            ->collection($model->collection)
            ->data($model->data)
            ->published($model->published)
            ->model($model);
    }

    public function model($model = null)
    {
        if (func_num_args() === 0) {
            return $this->model;
        }

        $this->model = $model;

        $this->id($model->id);

        return $this;
    }

    public function toModel()
    {
        return Model::findOrNew($this->id())->fill([
            'origin_id' => $this->originId(),
            'site' => $this->locale(),
            'slug' => $this->slug(),
            'uri' => $this->uri(),
            'date' => $this->hasDate() ? $this->date() : null,
            'collection' => $this->collectionHandle(),
            'data' => $this->data(),
            'published' => $this->published(),
            'status' => $this->status(),
        ]);
    }

    public function lastModified()
    {
        return $this->model->updated_at;
    }

    public function origin($origin = null)
    {
        if (func_num_args() > 0) {
            $this->origin = $origin;

            return $this;
        }

        if ($this->origin) {
            return $this->origin;
        }

        if (! $this->model->origin) {
            return null;
        }

        return self::fromModel($this->model->origin);
    }

    public function originId()
    {
        return $this->origin?->id() ?? $this->model?->origin_id;
    }

    public function hasOrigin()
    {
        return $this->originId() !== null;
    }

    // @todo find a fix for this (origin -> origin_id)
    public function descendants()
    {
        if (! $this->localizations) {
            $this->localizations = EntryFacade::query()
                ->where('collection', $this->collectionHandle())
                ->where('origin_id', $this->id())
                ->get()
                ->keyBy
                ->locale();
        }

        $localizations = collect($this->localizations);

        foreach ($localizations as $loc) {
            $localizations = $localizations->merge($loc->descendants());
        }

        return $localizations;
    }

    // @todo find a fix for this (origin -> origin_id)
    public function detachLocalizations()
    {
        EntryFacade::query()
            ->where('collection', $this->collectionHandle())
            ->where('origin_id', $this->id())
            ->get()
            ->each(function ($loc) {
                $loc
                    ->origin(null)
                    ->data($this->data()->merge($loc->data()))
                    ->save();
            });

        return true;
    }
}
