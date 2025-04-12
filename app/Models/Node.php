<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperNode
 */
final class Node extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_node_mappings', 'node_id', 'user_id')
            ->withTimestamps()
            ->using(UserNodeMapping::class);
    }
}
