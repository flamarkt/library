<?php

namespace Flamarkt\Library;

use Carbon\Carbon;
use Flamarkt\Core\Product\Product;
use Flarum\Database\AbstractModel;
use Flarum\Database\ScopeVisibilityTrait;
use Flarum\Foundation\EventGeneratorTrait;
use Flarum\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations;

/**
 * @property int $id
 * @property int $user_id
 * @property string $uid
 * @property array $conversions
 * @property int $width
 * @property int $height
 * @property int $size
 * @property string $filename
 * @property string $title
 * @property string $description
 * @property int $product_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $hidden_at
 *
 * @property User|null $user
 * @property Product[]|Collection $products
 */
class File extends AbstractModel
{
    use EventGeneratorTrait, ScopeVisibilityTrait;

    protected $table = 'flamarkt_files';

    public $timestamps = true;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'hidden_at' => 'datetime',
    ];

    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function getConversionsAttribute($value)
    {
        return explode(',', $value);
    }

    public function setConversionsAttribute($conversions)
    {
        //TODO: sort?
        $this->attributes['conversions'] = implode(',', $conversions);
    }
}
