<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Flate;
use App\Models\Building;
use App\Models\Resident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlatsResidentsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $building;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('email', 'admin@example.com')->first();
        $this->building = Building::first();
    }

    /**
     * Test flats index page renders successfully and references flat_number attribute.
     */
    public function test_flats_index_renders_successfully(): void
    {
        $response = $this->actingAs($this->admin)->get(route('flats.index'));

        $response->assertStatus(200);
        $response->assertSee('A-101');
    }

    /**
     * Test flat creation validation and DB persistence.
     */
    public function test_flat_can_be_created(): void
    {
        $response = $this->actingAs($this->admin)->post(route('flats.store'), [
            'building_id' => $this->building->id,
            'flate_number' => 'B-202',
            'floor' => 2,
            'status' => 'vacant',
            'owner_name' => 'Jane Smith',
            'owner_phone' => '1234567890',
            'owner_email' => 'jane@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('flats.index'));

        $this->assertDatabaseHas('flates', [
            'flate_number' => 'B-202',
            'floor' => '2',
            'status' => 'vacant',
            'owner_name' => 'Jane Smith',
        ]);
    }

    /**
     * Test flat update validation and DB persistence.
     */
    public function test_flat_can_be_updated(): void
    {
        $flat = Flate::first();

        $response = $this->actingAs($this->admin)->put(route('flats.update', $flat->id), [
            'building_id' => $this->building->id,
            'flate_number' => 'A-101-Modified',
            'floor' => 1,
            'status' => 'self-occupied',
            'owner_name' => 'John Doe Sr.',
            'owner_phone' => '+1 (555) 333-3333',
            'owner_email' => 'resident@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('flats.index'));

        $this->assertDatabaseHas('flates', [
            'id' => $flat->id,
            'flate_number' => 'A-101-Modified',
            'status' => 'self-occupied',
            'owner_name' => 'John Doe Sr.',
        ]);
    }

    /**
     * Test resident index page renders and is robust against null flats.
     */
    public function test_resident_index_renders_with_and_without_assigned_flat(): void
    {
        // 1. Check index with flat assigned
        $response = $this->actingAs($this->admin)->get(route('residents.index'));
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('A-101');

        // 2. Get the residents and force their flat relationship to null
        $residents = Resident::paginate(10);
        foreach ($residents as $r) {
            $r->setRelation('flat', null);
        }

        // 3. Render the view directly passing the residents with null flats, ensuring it prints "N/A" and does not crash
        $view = $this->view('admin.residents.index', [
            'residents' => $residents,
            'search' => '',
        ]);

        $view->assertSee('John Doe');
        $view->assertSee('N/A');
    }

    /**
     * Test resident creation validation and DB persistence.
     */
    public function test_resident_can_be_created(): void
    {
        // Create an empty flat first
        $flat = Flate::create([
            'society_id' => $this->admin->society_id,
            'building_id' => $this->building->id,
            'flate_number' => 'C-303',
            'floor' => 3,
            'status' => 'vacant',
        ]);

        $response = $this->actingAs($this->admin)->post(route('residents.store'), [
            'flate_id' => $flat->id,
            'name' => 'Alice Margatroid',
            'email' => 'alice@example.com',
            'phone' => '9876543210',
            'family_members' => 2,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('residents.index'));

        $this->assertDatabaseHas('residents', [
            'name' => 'Alice Margatroid',
            'email' => 'alice@example.com',
            'flate_id' => $flat->id,
        ]);

        // The flat status should be updated to occupied automatically
        $this->assertDatabaseHas('flates', [
            'id' => $flat->id,
            'status' => 'occupied',
        ]);
    }

    /**
     * Test resident update validation and DB persistence.
     */
    public function test_resident_can_be_updated(): void
    {
        $resident = Resident::first();
        $flat = Flate::first();

        $response = $this->actingAs($this->admin)->put(route('residents.update', $resident->id), [
            'flate_id' => $flat->id,
            'name' => 'John Doe Update',
            'email' => 'resident@example.com',
            'phone' => '+1 (555) 333-3333',
            'family_members' => 4,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('residents.index'));

        $this->assertDatabaseHas('residents', [
            'id' => $resident->id,
            'name' => 'John Doe Update',
            'family_members' => 4,
        ]);
    }
}
