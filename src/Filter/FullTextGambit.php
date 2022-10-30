<?php

namespace Flamarkt\Library\Filter;

use ClarkWinkelmann\Scout\ScoutStatic;
use Flamarkt\Library\File;
use Flarum\Extension\ExtensionManager;
use Flarum\Search\GambitInterface;
use Flarum\Search\SearchState;

class FullTextGambit implements GambitInterface
{
    public function apply(SearchState $search, $bit)
    {
        if (!resolve(ExtensionManager::class)->isEnabled('clarkwinkelmann-scout')) {
            $search->getQuery()->where('title', 'like', '%' . $bit . '%');

            return;
        }

        $builder = ScoutStatic::makeBuilder(File::class, $bit);

        $ids = $builder->keys();

        $search->getQuery()->whereIn('id', $ids);

        $search->setDefaultSort(function ($query) use ($ids) {
            if (!count($ids)) {
                return;
            }

            $query->orderByRaw('FIELD(id' . str_repeat(', ?', count($ids)) . ')', $ids);
        });
    }
}
