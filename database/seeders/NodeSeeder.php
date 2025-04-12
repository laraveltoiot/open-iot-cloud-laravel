<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Node;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

final class NodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'esp32',
            'pico1',
            'pico2',
            'arduino',
            'rp2354b',
        ];
        foreach ($types as $i => $type) {
            Node::create([
                'node_uuid' => Str::uuid(),
                'name' => 'Test Node '.($i + 1),
                'type' => $type,
                'fw_version' => '1.0.0',
            ]);
        }
    }
}
