<?php

namespace App\Eloquent\Entries;

use Illuminate\Support\Arr;
use Statamic\Contracts\Entries\QueryBuilder;
use Statamic\Entries\EntryCollection;
use Statamic\Query\EloquentQueryBuilder;
use Statamic\Stache\Query\QueriesTaxonomizedEntries;

class EntryQueryBuilder extends EloquentQueryBuilder implements QueryBuilder
{
    use QueriesTaxonomizedEntries;

    protected $columns = [
        'id', 'site', 'origin_id', 'published', 'status', 'slug', 'uri',
        'date', 'collection', 'created_at', 'updated_at',
    ];

    protected function transform($items, $columns = [])
    {
        return EntryCollection::make($items)->map(function ($model) {
            return Entry::fromModel($model);
        });
    }

    protected function selectableColumns($columns = ['*'])
    {
        // @todo find a fix for this

        // $columns = Arr::wrap($columns);

        // if (! in_array('*', $columns)) {
        //     // Any requested columns that aren't actually columns should just be
        //     // ignored. In actual Laravel Query Builder, you'd get a database
        //     // exception. Stripping out invalid columns is fine here. They
        //     // will still be sent through and used for augmentation.
        //     $model = $this->builder->getModel();
        //     $schema = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        //     $selected = array_intersect($schema, $columns);
        // }

        // return empty($selected) ? ['*'] : $selected;

        return ['*'];
    }

    protected function column($column)
    {
        if (! in_array($column, $this->columns)) {
            $column = 'data->'.$column;
        }

        return $column;
    }

    public function get($columns = ['*'])
    {
        $this->addTaxonomyWheres();

        return parent::get($columns);
    }

    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->addTaxonomyWheres();

        return parent::paginate($perPage, $columns);
    }

    public function count()
    {
        $this->addTaxonomyWheres();

        return parent::count();
    }
}
