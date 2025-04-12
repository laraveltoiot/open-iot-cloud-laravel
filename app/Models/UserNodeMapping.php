<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNodeMapping extends Model
{
    protected $table = 'user_node_mappings';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_node_mapping', 'node_id', 'user_id')
            ->withTimestamps()
            ->using(UserNodeMapping::class);
    }
}
