<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperUserNodeMapping
 */
final class UserNodeMapping extends Pivot
{
    protected $table = 'user_node_mappings';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
