<?php

use App\Models\User;
use Illuminate\Support\Facades\File;

beforeEach(function () {
    File::delete(storage_path('app/maintenance.json'));
});

test('admin can save maintenance target for pasien only or pengurus and pasien', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($admin);

    $response = $this->post(route('admin.maintenance.enable'), [
        'message' => 'Maintenance test',
        'target' => 'pasien',
    ]);

    $response->assertRedirect(route('admin.maintenance.index'));

    $payload = json_decode(File::get(storage_path('app/maintenance.json')), true);

    expect($payload['target'])->toBe('pasien');

    $response = $this->post(route('admin.maintenance.enable'), [
        'message' => 'Maintenance test',
        'target' => 'pengurus_pasien',
    ]);

    $response->assertRedirect(route('admin.maintenance.index'));

    $payload = json_decode(File::get(storage_path('app/maintenance.json')), true);

    expect($payload['target'])->toBe('pengurus_pasien');
});
