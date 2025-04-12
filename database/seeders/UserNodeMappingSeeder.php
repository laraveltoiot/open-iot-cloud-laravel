<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Node;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Str;

final class UserNodeMappingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $nodes = Node::take(3)->get();
        foreach ($nodes as $node) {
            DB::table('user_node_mappings')->insert([
                'user_id' => $user->id,
                'node_id' => $node->id,
                'secret_key' => Str::uuid(),
                'status' => 'confirmed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
