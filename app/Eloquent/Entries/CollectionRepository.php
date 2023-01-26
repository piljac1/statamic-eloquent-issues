<?php

namespace App\Eloquent\Entries;

use App\Models\EntryModel;
use Statamic\Contracts\Entries\Collection;
use Statamic\Stache\Repositories\CollectionRepository as StacheRepository;

class CollectionRepository extends StacheRepository
{
    public function updateEntryUris(Collection $collection, $ids = null)
    {
        $collection
            ->queryEntries()
            ->get()
            ->each(function ($entry) {
                EntryModel::where('id', $entry->id())->update(['uri' => $entry->uri()]);
            });
    }
}
