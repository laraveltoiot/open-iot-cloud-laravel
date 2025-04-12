<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $node_uuid
 * @property string $name
 * @property string|null $type
 * @property string|null $fw_version
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserNodeMapping|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Node newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Node newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Node query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Node whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Node whereFwVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Node whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Node whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Node whereNodeUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Node whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Node whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	final class IdeHelperNode {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\UserNodeMapping|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Node> $nodes
 * @property-read int|null $nodes_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	final class IdeHelperUser {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $node_id
 * @property string|null $secret_key
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserNodeMapping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserNodeMapping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserNodeMapping query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserNodeMapping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserNodeMapping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserNodeMapping whereNodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserNodeMapping whereSecretKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserNodeMapping whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserNodeMapping whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserNodeMapping whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	final class IdeHelperUserNodeMapping {}
}

