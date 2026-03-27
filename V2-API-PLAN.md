# Partymeister Competitions V2 API Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a complete V2 REST API for partymeister-competitions following the patterns proven in partymeister-accounting.

**Architecture:** Each resource gets a V2 controller extending `Motor\Core\Http\Controllers\Api\V2\ApiController`, V2 request classes (Get/Post/Patch), V2 resource + collection extending motor-core base classes, and Pest feature tests. Services are augmented with `$loadColumns` for eager loading. Non-CRUD endpoints (results, access key generation, shader showdown) get RPC-style controllers under `Api/V2/Rpc/`. All routes use `/api/v2` prefix with `V2ErrorHandler` middleware and `auth:sanctum`.

**Tech Stack:** Laravel 12, Pest 4, motor-core V2 base classes, Spatie MediaLibrary, Snowflake IDs

---

## File Inventory

### New Files (~60 files)

**Controllers** (14):
- `src/Http/Controllers/Api/V2/CompetitionTypesController.php`
- `src/Http/Controllers/Api/V2/VoteCategoriesController.php`
- `src/Http/Controllers/Api/V2/OptionGroupsController.php`
- `src/Http/Controllers/Api/V2/CompetitionsController.php`
- `src/Http/Controllers/Api/V2/EntriesController.php`
- `src/Http/Controllers/Api/V2/AccessKeysController.php`
- `src/Http/Controllers/Api/V2/CompetitionPrizesController.php`
- `src/Http/Controllers/Api/V2/VotesController.php`
- `src/Http/Controllers/Api/V2/ManualVotesController.php`
- `src/Http/Controllers/Api/V2/LiveVotesController.php`
- `src/Http/Controllers/Api/V2/Competitions/EntriesController.php` (nested)
- `src/Http/Controllers/Api/V2/Competitions/PrizesController.php` (nested)
- `src/Http/Controllers/Api/V2/Rpc/AccessKeys/GenerateController.php`
- `src/Http/Controllers/Api/V2/Rpc/Votes/ResultsController.php`

**Resources** (22):
- `src/Http/Resources/V2/{Model}Resource.php` (11: CompetitionType, VoteCategory, OptionGroup, Option, Competition, Entry, AccessKey, CompetitionPrize, Vote, ManualVote, LiveVote)
- `src/Http/Resources/V2/{Model}Collection.php` (11: matching collections)

**Requests** (30):
- `src/Http/Requests/Api/V2/{Model}GetRequest.php` (10)
- `src/Http/Requests/Api/V2/{Model}PostRequest.php` (10)
- `src/Http/Requests/Api/V2/{Model}PatchRequest.php` (10)

**Tests** (7):
- `tests/Feature/V2CompetitionTypeTest.php`
- `tests/Feature/V2VoteCategoryTest.php`
- `tests/Feature/V2OptionGroupTest.php`
- `tests/Feature/V2CompetitionTest.php`
- `tests/Feature/V2EntryTest.php`
- `tests/Feature/V2AccessKeyTest.php`
- `tests/Feature/V2VoteTest.php` (covers Vote, ManualVote, LiveVote, Results, CompetitionPrize)

### Modified Files (~12 files)

- `routes/api.php` - Add V2 route block
- `src/Services/CompetitionTypeService.php` - Add `$loadColumns`
- `src/Services/CompetitionService.php` - Add `$loadColumns`
- `src/Services/EntryService.php` - Add `$loadColumns`
- `src/Services/VoteService.php` - Add `$loadColumns`
- `src/Services/VoteCategoryService.php` - Add `$loadColumns`
- `src/Services/AccessKeyService.php` - Add `$loadColumns`
- `src/Services/OptionGroupService.php` - Add `$loadColumns`
- `src/Services/CompetitionPrizeService.php` - Add `$loadColumns`
- `database/factories/CompetitionFactory.php` - Proper field coverage
- `database/factories/CompetitionTypeFactory.php` - Proper field coverage
- `database/factories/EntryFactory.php` - Proper field coverage
- `database/factories/VoteFactory.php` - Proper field coverage
- `database/factories/VoteCategoryFactory.php` - Proper field coverage
- `database/factories/AccessKeyFactory.php` - Proper field coverage
- `database/factories/CompetitionPrizeFactory.php` - Proper field coverage
- `database/factories/OptionGroupFactory.php` - Proper field coverage

---

## Phase 1: Foundation (Factories + Services)

All factories are broken (they just set `'name' => faker->word` regardless of model). Two services are missing entirely. Fix these first so tests can work.

### Task 1.1: Fix CompetitionTypeFactory

**Files:**
- Modify: `database/factories/CompetitionTypeFactory.php`

- [ ] **Step 1: Rewrite factory definition**

```php
<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\CompetitionType;

class CompetitionTypeFactory extends Factory
{
    protected $model = CompetitionType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
        ];
    }
}
```

Note: Boolean fields (`has_platform`, etc.) default to `false` in the migration so they don't need explicit factory values. `number_of_work_stages` defaults to 0.

- [ ] **Step 2: Commit**

```bash
git add database/factories/CompetitionTypeFactory.php
git commit -m "fix: rewrite CompetitionTypeFactory with proper field coverage"
```

### Task 1.2: Fix VoteCategoryFactory

**Files:**
- Modify: `database/factories/VoteCategoryFactory.php`

- [ ] **Step 1: Rewrite factory definition**

```php
<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\VoteCategory;

class VoteCategoryFactory extends Factory
{
    protected $model = VoteCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'points' => $this->faker->numberBetween(1, 10),
            'has_negative' => false,
            'has_comment' => false,
            'has_special_vote' => false,
        ];
    }
}
```

- [ ] **Step 2: Commit**

### Task 1.3: Fix OptionGroupFactory

**Files:**
- Modify: `database/factories/OptionGroupFactory.php`

- [ ] **Step 1: Rewrite factory definition**

```php
<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\OptionGroup;

class OptionGroupFactory extends Factory
{
    protected $model = OptionGroup::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(2, true),
            'type' => $this->faker->randomElement(['single', 'multiple']),
        ];
    }
}
```

- [ ] **Step 2: Commit**

### Task 1.4: Fix CompetitionFactory

**Files:**
- Modify: `database/factories/CompetitionFactory.php`

- [ ] **Step 1: Rewrite factory definition**

```php
<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionType;

class CompetitionFactory extends Factory
{
    protected $model = Competition::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'competition_type_id' => CompetitionType::factory(),
            'sort_position' => $this->faker->numberBetween(0, 100),
            'prizegiving_sort_position' => $this->faker->numberBetween(0, 100),
            'has_prizegiving' => false,
            'upload_enabled' => false,
            'voting_enabled' => false,
        ];
    }
}
```

- [ ] **Step 2: Commit**

### Task 1.5: Fix EntryFactory

**Files:**
- Modify: `database/factories/EntryFactory.php`

- [ ] **Step 1: Rewrite factory definition**

Entry model has a `booted()` hook that auto-assigns `identifier`, so don't set it in factory.

```php
<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;

class EntryFactory extends Factory
{
    protected $model = Entry::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'title' => $this->faker->words(3, true),
            'author' => $this->faker->name(),
            'description' => '',
            'organizer_description' => '',
            'custom_option' => '',
            'sort_position' => $this->faker->numberBetween(0, 100),
            'status' => 0,
            'ip_address' => $this->faker->ipv4(),
        ];
    }

    public function qualified(): static
    {
        return $this->state(fn () => ['status' => 1]);
    }
}
```

- [ ] **Step 2: Commit**

### Task 1.6: Fix VoteFactory

**Files:**
- Modify: `database/factories/VoteFactory.php`

- [ ] **Step 1: Rewrite factory definition**

```php
<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Models\Vote;
use Partymeister\Competitions\Models\VoteCategory;

class VoteFactory extends Factory
{
    protected $model = Vote::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'entry_id' => Entry::factory(),
            'visitor_id' => null,
            'vote_category_id' => VoteCategory::factory(),
            'points' => $this->faker->numberBetween(1, 10),
            'special_vote' => false,
            'comment' => '',
            'ip_address' => $this->faker->ipv4(),
        ];
    }
}
```

- [ ] **Step 2: Commit**

### Task 1.7: Fix AccessKeyFactory

**Files:**
- Modify: `database/factories/AccessKeyFactory.php`

- [ ] **Step 1: Rewrite factory definition**

```php
<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\AccessKey;

class AccessKeyFactory extends Factory
{
    protected $model = AccessKey::class;

    public function definition(): array
    {
        return [
            'access_key' => strtoupper($this->faker->unique()->bothify('????-????')),
            'ip_address' => $this->faker->ipv4(),
            'is_remote' => false,
            'is_satellite' => false,
            'is_prepaid' => false,
        ];
    }
}
```

- [ ] **Step 2: Commit**

### Task 1.8: Fix CompetitionPrizeFactory

**Files:**
- Modify: `database/factories/CompetitionPrizeFactory.php`

- [ ] **Step 1: Rewrite factory definition**

```php
<?php

namespace Partymeister\Competitions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\CompetitionPrize;

class CompetitionPrizeFactory extends Factory
{
    protected $model = CompetitionPrize::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'amount' => $this->faker->randomElement(['100', '50', '25']),
            'additional' => $this->faker->optional()->sentence(),
            'rank' => $this->faker->numberBetween(1, 3),
        ];
    }
}
```

- [ ] **Step 2: Commit**

### Task 1.9: Add HasFactory traits to models

**Files:**
- Modify: All model files that use factories

- [ ] **Step 1: Check which models already have `HasFactory` and the `newFactory()` method**

Each model needs `use HasFactory;` and a `newFactory()` pointing to its factory. Check each model and add where missing. The pattern (from accounting):

```php
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompetitionType extends Model
{
    use HasFactory;
    // ... existing traits

    protected static function newFactory()
    {
        return \Partymeister\Competitions\Database\Factories\CompetitionTypeFactory::new();
    }
}
```

Apply to: `CompetitionType`, `Competition`, `Entry`, `Vote`, `VoteCategory`, `AccessKey`, `CompetitionPrize`, `OptionGroup`.

- [ ] **Step 2: Run tests to verify factories work**

```bash
cd packages/partymeister-competitions && ../../vendor/bin/pest --filter="V2" 2>/dev/null; echo "No V2 tests yet - just verifying no factory errors"
```

- [ ] **Step 3: Commit**

### Task 1.10: Add $loadColumns to services

**Files:**
- Modify: All 8 existing service files

- [ ] **Step 1: Add `$loadColumns` to each service**

```php
// CompetitionTypeService - no relations to eager load
// (no change needed, empty by default)

// VoteCategoryService - no relations to eager load
// (no change needed)

// OptionGroupService
protected array $loadColumns = ['options'];

// CompetitionService
protected array $loadColumns = ['competition_type', 'vote_categories', 'option_groups'];

// EntryService
protected array $loadColumns = ['competition'];

// AccessKeyService
protected array $loadColumns = ['visitor'];

// CompetitionPrizeService
protected array $loadColumns = ['competition'];

// VoteService
protected array $loadColumns = ['vote_category'];
```

- [ ] **Step 2: Create missing LiveVoteService**

Create: `src/Services/LiveVoteService.php`

```php
<?php

namespace Partymeister\Competitions\Services;

use Motor\Admin\Services\BaseService;
use Partymeister\Competitions\Models\LiveVote;

class LiveVoteService extends BaseService
{
    protected string $model = LiveVote::class;

    protected array $loadColumns = ['competition', 'entry'];
}
```

- [ ] **Step 3: Create missing ManualVoteService**

Create: `src/Services/ManualVoteService.php`

```php
<?php

namespace Partymeister\Competitions\Services;

use Motor\Admin\Services\BaseService;
use Partymeister\Competitions\Models\ManualVote;

class ManualVoteService extends BaseService
{
    protected string $model = ManualVote::class;
}
```

- [ ] **Step 4: Commit**

---

## Phase 2: Simple CRUD Resources (CompetitionTypes, VoteCategories, OptionGroups)

These three have minimal relationships and straightforward validation.

### Task 2.1: CompetitionTypes V2 API

**Files:**
- Create: `src/Http/Resources/V2/CompetitionTypeResource.php`
- Create: `src/Http/Resources/V2/CompetitionTypeCollection.php`
- Create: `src/Http/Requests/Api/V2/CompetitionTypeGetRequest.php`
- Create: `src/Http/Requests/Api/V2/CompetitionTypePostRequest.php`
- Create: `src/Http/Requests/Api/V2/CompetitionTypePatchRequest.php`
- Create: `src/Http/Controllers/Api/V2/CompetitionTypesController.php`
- Create: `tests/Feature/V2CompetitionTypeTest.php`

- [ ] **Step 1: Create Resource + Collection**

```php
// src/Http/Resources/V2/CompetitionTypeResource.php
<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\CompetitionType;

/**
 * @mixin CompetitionType
 */
class CompetitionTypeResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'has_platform' => (bool) $this->has_platform,
            'has_filesize' => (bool) $this->has_filesize,
            'has_screenshot' => (bool) $this->has_screenshot,
            'has_video' => (bool) $this->has_video,
            'has_audio' => (bool) $this->has_audio,
            'has_recordings' => (bool) $this->has_recordings,
            'has_composer' => (bool) $this->has_composer,
            'has_running_time' => (bool) $this->has_running_time,
            'is_anonymous' => (bool) $this->is_anonymous,
            'number_of_work_stages' => (int) $this->number_of_work_stages,
            'has_remote_entries' => (bool) $this->has_remote_entries,
            'file_is_optional' => (bool) $this->file_is_optional,
            'has_config_file' => (bool) $this->has_config_file,
            'has_ai_options' => (bool) $this->has_ai_options,
            'has_engine_options' => (bool) $this->has_engine_options,
            'has_out_of_competition_voting' => (bool) $this->has_out_of_competition_voting,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
```

```php
// src/Http/Resources/V2/CompetitionTypeCollection.php
<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class CompetitionTypeCollection extends BaseCollection
{
    public $collects = CompetitionTypeResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
```

- [ ] **Step 2: Create Request classes**

```php
// src/Http/Requests/Api/V2/CompetitionTypeGetRequest.php
<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionTypeGetRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}
```

```php
// src/Http/Requests/Api/V2/CompetitionTypePostRequest.php
<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionTypePostRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'has_platform' => 'nullable|boolean',
            'has_filesize' => 'nullable|boolean',
            'has_screenshot' => 'nullable|boolean',
            'has_video' => 'nullable|boolean',
            'has_audio' => 'nullable|boolean',
            'has_recordings' => 'nullable|boolean',
            'has_composer' => 'nullable|boolean',
            'has_running_time' => 'nullable|boolean',
            'is_anonymous' => 'nullable|boolean',
            'number_of_work_stages' => 'nullable|integer|min:0',
            'has_remote_entries' => 'nullable|boolean',
            'file_is_optional' => 'nullable|boolean',
            'has_config_file' => 'nullable|boolean',
            'has_ai_options' => 'nullable|boolean',
            'has_engine_options' => 'nullable|boolean',
            'has_out_of_competition_voting' => 'nullable|boolean',
        ];
    }
}
```

```php
// src/Http/Requests/Api/V2/CompetitionTypePatchRequest.php
// Same as Post but all fields use 'sometimes|' prefix
<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionTypePatchRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'has_platform' => 'sometimes|nullable|boolean',
            'has_filesize' => 'sometimes|nullable|boolean',
            'has_screenshot' => 'sometimes|nullable|boolean',
            'has_video' => 'sometimes|nullable|boolean',
            'has_audio' => 'sometimes|nullable|boolean',
            'has_recordings' => 'sometimes|nullable|boolean',
            'has_composer' => 'sometimes|nullable|boolean',
            'has_running_time' => 'sometimes|nullable|boolean',
            'is_anonymous' => 'sometimes|nullable|boolean',
            'number_of_work_stages' => 'sometimes|nullable|integer|min:0',
            'has_remote_entries' => 'sometimes|nullable|boolean',
            'file_is_optional' => 'sometimes|nullable|boolean',
            'has_config_file' => 'sometimes|nullable|boolean',
            'has_ai_options' => 'sometimes|nullable|boolean',
            'has_engine_options' => 'sometimes|nullable|boolean',
            'has_out_of_competition_voting' => 'sometimes|nullable|boolean',
        ];
    }
}
```

- [ ] **Step 3: Create Controller**

```php
// src/Http/Controllers/Api/V2/CompetitionTypesController.php
<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionTypeGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionTypePatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionTypePostRequest;
use Partymeister\Competitions\Http\Resources\V2\CompetitionTypeCollection;
use Partymeister\Competitions\Http\Resources\V2\CompetitionTypeResource;
use Partymeister\Competitions\Models\CompetitionType;
use Partymeister\Competitions\Services\CompetitionTypeService;

class CompetitionTypesController extends ApiController
{
    protected string $model = CompetitionType::class;
    protected string $modelResource = 'competition_type';

    public function index(CompetitionTypeGetRequest $request): CompetitionTypeCollection
    {
        $paginator = CompetitionTypeService::collection()->getPaginator();
        return (new CompetitionTypeCollection($paginator))
            ->additional(['meta' => ['message' => 'Competition types retrieved']]);
    }

    public function store(CompetitionTypePostRequest $request): JsonResponse
    {
        $result = CompetitionTypeService::create($request)->getResult();
        return (new CompetitionTypeResource($result))
            ->additional(['meta' => ['message' => 'Competition type created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(CompetitionType $competition_type): CompetitionTypeResource
    {
        $result = CompetitionTypeService::show($competition_type)->getResult();
        return (new CompetitionTypeResource($result))
            ->additional(['meta' => ['message' => 'Competition type retrieved']]);
    }

    public function update(CompetitionTypePatchRequest $request, CompetitionType $competition_type): CompetitionTypeResource
    {
        $result = CompetitionTypeService::update($competition_type, $request)->getResult();
        return (new CompetitionTypeResource($result))
            ->additional(['meta' => ['message' => 'Competition type updated']]);
    }

    public function destroy(CompetitionType $competition_type): Response
    {
        $result = CompetitionTypeService::delete($competition_type)->getResult();
        if ($result) {
            return $this->noContentResponse();
        }
        abort(404, 'Problem deleting competition type');
    }
}
```

- [ ] **Step 4: Write tests**

```php
// tests/Feature/V2CompetitionTypeTest.php
<?php

use Motor\Admin\Models\User;
use Partymeister\Competitions\Models\CompetitionType;
use Spatie\Permission\Models\Role;

pest()->group('V2', 'CompetitionType');

beforeEach(function () {
    $role = Role::create(['name' => 'SuperAdmin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole($role);

    CompetitionType::create(['name' => 'Demo']);
    CompetitionType::create(['name' => 'Music']);
});

describe('V2 CompetitionTypes API', function () {

    it('includes api_version v2 in response meta', function () {
        $this->asAdmin()->getJson('/api/v2/competition-types')
            ->assertOk()
            ->assertJsonPath('meta.api_version', 'v2');
    });

    it('can list all competition types', function () {
        assertV2CrudIndex('/api/v2/competition-types', 2, ['id', 'name', 'has_platform']);
    });

    it('can show a competition type', function () {
        assertV2CrudShow(
            '/api/v2/competition-types/'.CompetitionType::first()->id,
            ['id', 'name', 'has_platform', 'has_filesize', 'has_ai_options']
        );
    });

    it('can create a competition type', function () {
        assertV2CrudCreate('/api/v2/competition-types', [
            'name' => 'Graphics',
            'has_screenshot' => true,
        ], CompetitionType::class);
    });

    it('validates required name on create', function () {
        $this->asAdmin()->postJson('/api/v2/competition-types', [])
            ->assertStatus(422);
    });

    it('can update a competition type', function () {
        assertV2CrudUpdate(
            '/api/v2/competition-types/'.CompetitionType::first()->id,
            ['name' => 'Updated'],
            'name',
            'Updated'
        );
    });

    it('can update boolean flags', function () {
        $ct = CompetitionType::first();
        $this->asAdmin()->patchJson('/api/v2/competition-types/'.$ct->id, [
            'has_ai_options' => true,
            'has_engine_options' => true,
        ])->assertOk()
            ->assertJsonPath('data.has_ai_options', true)
            ->assertJsonPath('data.has_engine_options', true);
    });

    it('can delete a competition type', function () {
        assertV2CrudDelete(
            '/api/v2/competition-types/'.CompetitionType::latest('id')->first()->id,
            CompetitionType::class
        );
    });
});
```

- [ ] **Step 5: Register route in `routes/api.php`** (add V2 block at bottom of file)

```php
// V2 API routes
Route::prefix('api/v2')
    ->name('v2.')
    ->middleware([\Motor\Core\Http\Middleware\V2\V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::apiResource('competition-types', \Partymeister\Competitions\Http\Controllers\Api\V2\CompetitionTypesController::class);
    });
```

- [ ] **Step 6: Run tests**

```bash
cd /Users/dfox/Development/partymeister-template-headless && vendor/bin/pest packages/partymeister-competitions/tests/Feature/V2CompetitionTypeTest.php
```

Expected: All tests pass.

- [ ] **Step 7: Commit**

### Task 2.2: VoteCategories V2 API

**Files:**
- Create: `src/Http/Resources/V2/VoteCategoryResource.php`
- Create: `src/Http/Resources/V2/VoteCategoryCollection.php`
- Create: `src/Http/Requests/Api/V2/VoteCategoryGetRequest.php`
- Create: `src/Http/Requests/Api/V2/VoteCategoryPostRequest.php`
- Create: `src/Http/Requests/Api/V2/VoteCategoryPatchRequest.php`
- Create: `src/Http/Controllers/Api/V2/VoteCategoriesController.php`
- Create: `tests/Feature/V2VoteCategoryTest.php`

- [ ] **Step 1: Create Resource**

```php
// VoteCategoryResource.php
public function toArray($request): array
{
    return [
        'id' => (int) $this->id,
        'name' => $this->name,
        'points' => (int) $this->points,
        'has_negative' => (bool) $this->has_negative,
        'has_comment' => (bool) $this->has_comment,
        'has_special_vote' => (bool) $this->has_special_vote,
        'created_at' => $this->created_at?->toIso8601String(),
        'updated_at' => $this->updated_at?->toIso8601String(),
    ];
}
```

- [ ] **Step 2: Create Collection** (same pattern as CompetitionTypeCollection)

- [ ] **Step 3: Create Request classes**

Post rules:
```php
'name' => 'required|string|max:255',
'points' => 'required|integer|min:1',
'has_negative' => 'nullable|boolean',
'has_comment' => 'nullable|boolean',
'has_special_vote' => 'nullable|boolean',
```

Patch: same with `sometimes|` prefix on all.

- [ ] **Step 4: Create Controller** (follow CompetitionTypesController pattern exactly)

- [ ] **Step 5: Write tests** (follow CompetitionTypeTest pattern)

- [ ] **Step 6: Add route**

```php
Route::apiResource('vote-categories', V2\VoteCategoriesController::class);
```

- [ ] **Step 7: Run tests, commit**

### Task 2.3: OptionGroups V2 API (with nested Options)

**Files:**
- Create: `src/Http/Resources/V2/OptionGroupResource.php`
- Create: `src/Http/Resources/V2/OptionGroupCollection.php`
- Create: `src/Http/Resources/V2/OptionResource.php`
- Create: `src/Http/Resources/V2/OptionCollection.php`
- Create: `src/Http/Requests/Api/V2/OptionGroupGetRequest.php`
- Create: `src/Http/Requests/Api/V2/OptionGroupPostRequest.php`
- Create: `src/Http/Requests/Api/V2/OptionGroupPatchRequest.php`
- Create: `src/Http/Controllers/Api/V2/OptionGroupsController.php`
- Create: `tests/Feature/V2OptionGroupTest.php`

- [ ] **Step 1: Create Option + OptionGroup resources**

```php
// OptionResource.php
public function toArray($request): array
{
    return [
        'id' => (int) $this->id,
        'name' => $this->name,
        'sort_position' => (int) $this->sort_position,
    ];
}

// OptionGroupResource.php
public function toArray($request): array
{
    return [
        'id' => (int) $this->id,
        'name' => $this->name,
        'type' => $this->type,
        'options' => OptionResource::collection($this->whenLoaded('options')),
        'created_at' => $this->created_at?->toIso8601String(),
        'updated_at' => $this->updated_at?->toIso8601String(),
    ];
}
```

- [ ] **Step 2: Create Collections**

- [ ] **Step 3: Create Request classes**

Post rules:
```php
'name' => 'required|string|max:255',
'type' => 'required|in:single,multiple',
'options' => 'nullable|array',
'options.*.name' => 'required_with:options|string|max:255',
```

- [ ] **Step 4: Create Controller**

Note: The existing `OptionGroupService` already handles creating/updating nested options via `afterCreate()` and `afterUpdate()`. The V2 controller just needs standard CRUD; the service layer handles the options cascade.

- [ ] **Step 5: Write tests**

Include test that creates an option group with nested options and verifies they appear in the response.

- [ ] **Step 6: Add route, run tests, commit**

---

## Phase 3: Core Resources (Competitions, Entries, AccessKeys, CompetitionPrizes)

These have relationships, more complex validation, and nested sub-resources.

### Task 3.1: Competitions V2 API

**Files:**
- Create: `src/Http/Resources/V2/CompetitionResource.php`
- Create: `src/Http/Resources/V2/CompetitionCollection.php`
- Create: `src/Http/Requests/Api/V2/CompetitionGetRequest.php`
- Create: `src/Http/Requests/Api/V2/CompetitionPostRequest.php`
- Create: `src/Http/Requests/Api/V2/CompetitionPatchRequest.php`
- Create: `src/Http/Controllers/Api/V2/CompetitionsController.php`
- Create: `tests/Feature/V2CompetitionTest.php`

- [ ] **Step 1: Create Resource**

```php
// CompetitionResource.php
public function toArray($request): array
{
    return [
        'id' => (int) $this->id,
        'name' => $this->name,
        'competition_type' => new CompetitionTypeResource($this->whenLoaded('competition_type')),
        'has_prizegiving' => (bool) $this->has_prizegiving,
        'upload_enabled' => (bool) $this->upload_enabled,
        'voting_enabled' => (bool) $this->voting_enabled,
        'sort_position' => (int) $this->sort_position,
        'prizegiving_sort_position' => (int) $this->prizegiving_sort_position,
        'vote_categories' => VoteCategoryResource::collection($this->whenLoaded('vote_categories')),
        'option_groups' => OptionGroupResource::collection($this->whenLoaded('option_groups')),
        'entry_count' => $this->whenCounted('entries', $this->entries_count),
        'created_at' => $this->created_at?->toIso8601String(),
        'updated_at' => $this->updated_at?->toIso8601String(),
    ];
}
```

Note: Don't include `entries` or `qualified_entries` in the default resource to avoid N+1. Those are available via the nested `/competitions/{id}/entries` endpoint.

- [ ] **Step 2: Create Collection**

- [ ] **Step 3: Create Request classes**

Post rules:
```php
'name' => 'required|string|max:255',
'competition_type_id' => 'required|exists:competition_types,id',
'has_prizegiving' => 'nullable|boolean',
'upload_enabled' => 'nullable|boolean',
'voting_enabled' => 'nullable|boolean',
'sort_position' => 'nullable|integer',
'prizegiving_sort_position' => 'nullable|integer',
'vote_categories' => 'nullable|array',
'vote_categories.*' => 'exists:vote_categories,id',
'option_groups' => 'nullable|array',
'option_groups.*' => 'exists:option_groups,id',
```

- [ ] **Step 4: Create Controller**

The controller follows standard CRUD. Note that `CompetitionService::afterCreate()` already handles attaching `option_groups` and `vote_categories` from the request, so no extra logic is needed.

- [ ] **Step 5: Write tests**

Test CRUD plus relationship attachment:
- Create competition with vote_categories array
- Verify competition_type is included in response
- Verify option_groups are attached

- [ ] **Step 6: Add route, run tests, commit**

### Task 3.2: Nested Competition Entries endpoint

**Files:**
- Create: `src/Http/Controllers/Api/V2/Competitions/EntriesController.php`

- [ ] **Step 1: Create nested controller**

```php
// src/Http/Controllers/Api/V2/Competitions/EntriesController.php
<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Competitions;

use Illuminate\Routing\Controller;
use Partymeister\Competitions\Http\Resources\V2\EntryCollection;
use Partymeister\Competitions\Models\Competition;

class EntriesController extends Controller
{
    public function index(Competition $competition): EntryCollection
    {
        $entries = $competition->entries()
            ->with('competition')
            ->orderBy('sort_position')
            ->paginate();

        return (new EntryCollection($entries))
            ->additional(['meta' => ['message' => 'Competition entries retrieved']]);
    }
}
```

- [ ] **Step 2: Add route**

```php
Route::get('competitions/{competition}/entries', [V2\Competitions\EntriesController::class, 'index'])
    ->name('competitions.entries.index');
```

- [ ] **Step 3: Add test case to V2CompetitionTest**

- [ ] **Step 4: Commit**

### Task 3.3: Nested Competition Prizes endpoint

**Files:**
- Create: `src/Http/Controllers/Api/V2/Competitions/PrizesController.php`

- [ ] **Step 1: Create nested controller**

```php
<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Competitions;

use Illuminate\Routing\Controller;
use Partymeister\Competitions\Http\Resources\V2\CompetitionPrizeCollection;
use Partymeister\Competitions\Models\Competition;

class PrizesController extends Controller
{
    public function index(Competition $competition): CompetitionPrizeCollection
    {
        $prizes = $competition->prizes()
            ->orderBy('rank')
            ->paginate();

        return (new CompetitionPrizeCollection($prizes))
            ->additional(['meta' => ['message' => 'Competition prizes retrieved']]);
    }
}
```

- [ ] **Step 2: Add route**

```php
Route::get('competitions/{competition}/prizes', [V2\Competitions\PrizesController::class, 'index'])
    ->name('competitions.prizes.index');
```

- [ ] **Step 3: Commit**

### Task 3.4: Entries V2 API

**Files:**
- Create: `src/Http/Resources/V2/EntryResource.php`
- Create: `src/Http/Resources/V2/EntryCollection.php`
- Create: `src/Http/Requests/Api/V2/EntryGetRequest.php`
- Create: `src/Http/Requests/Api/V2/EntryPostRequest.php`
- Create: `src/Http/Requests/Api/V2/EntryPatchRequest.php`
- Create: `src/Http/Controllers/Api/V2/EntriesController.php`
- Create: `tests/Feature/V2EntryTest.php`

- [ ] **Step 1: Create Resource**

```php
// EntryResource.php
public function toArray($request): array
{
    return [
        'id' => (int) $this->id,
        'identifier' => (int) $this->identifier,
        'title' => $this->title,
        'author' => $this->author,
        'filesize' => $this->filesize,
        'platform' => $this->platform,
        'description' => $this->description,
        'organizer_description' => $this->organizer_description,
        'running_time' => $this->running_time,
        'custom_option' => $this->custom_option,
        'sort_position' => (int) $this->sort_position,
        'status' => (int) $this->status,
        'ip_address' => $this->ip_address,
        'allow_release' => (bool) $this->allow_release,
        'is_remote' => (bool) $this->is_remote,
        'is_recorded' => (bool) $this->is_recorded,
        'is_prepared' => (bool) $this->is_prepared,
        'upload_enabled' => (bool) $this->upload_enabled,
        'discord_name' => $this->discord_name,
        'has_explicit_content' => (bool) $this->has_explicit_content,
        'needs_content_check' => (bool) $this->needs_content_check,
        'notify_about_status' => (bool) $this->notify_about_status,
        'representative' => $this->representative,
        'ai_usage' => $this->ai_usage,
        'ai_usage_description' => $this->ai_usage_description,
        'engine_option' => $this->engine_option,
        'engine_option_description' => $this->engine_option_description,
        'author_name' => $this->author_name,
        'author_email' => $this->author_email,
        'author_phone' => $this->author_phone,
        'author_address' => $this->author_address,
        'author_zip' => $this->author_zip,
        'author_city' => $this->author_city,
        'author_country_iso_3166_1' => $this->author_country_iso_3166_1,
        'composer_name' => $this->composer_name,
        'composer_email' => $this->composer_email,
        'composer_phone' => $this->composer_phone,
        'composer_address' => $this->composer_address,
        'composer_zip' => $this->composer_zip,
        'composer_city' => $this->composer_city,
        'composer_country_iso_3166_1' => $this->composer_country_iso_3166_1,
        'composer_not_member_of_copyright_collective' => (bool) $this->composer_not_member_of_copyright_collective,
        'final_file_media_id' => $this->final_file_media_id,
        'competition' => new CompetitionResource($this->whenLoaded('competition')),
        'created_at' => $this->created_at?->toIso8601String(),
        'updated_at' => $this->updated_at?->toIso8601String(),
    ];
}
```

- [ ] **Step 2: Create Collection**

- [ ] **Step 3: Create Request classes**

Post rules (V2 API requires the essential fields; address fields optional in V2):
```php
'competition_id' => 'required|exists:competitions,id',
'title' => 'required|string|max:255',
'author' => 'required|string|max:255',
'sort_position' => 'nullable|integer',
'status' => 'nullable|integer',
'description' => 'nullable|string',
'organizer_description' => 'nullable|string',
'running_time' => 'nullable|string|max:255',
'custom_option' => 'nullable|string|max:255',
'filesize' => 'nullable|string|max:255',
'platform' => 'nullable|string|max:255',
'ip_address' => 'nullable|string|max:255',
'allow_release' => 'nullable|boolean',
'is_remote' => 'nullable|boolean',
'is_recorded' => 'nullable|boolean',
'is_prepared' => 'nullable|boolean',
'upload_enabled' => 'nullable|boolean',
'discord_name' => 'nullable|string|max:255',
'has_explicit_content' => 'nullable|boolean',
'needs_content_check' => 'nullable|boolean',
'notify_about_status' => 'nullable|boolean',
'representative' => 'nullable|string|max:255',
'ai_usage' => 'nullable|string|max:255',
'ai_usage_description' => 'nullable|string',
'engine_option' => 'nullable|string|max:255',
'engine_option_description' => 'nullable|string',
'author_name' => 'nullable|string|max:255',
'author_email' => 'nullable|email|max:255',
'author_phone' => 'nullable|string|max:255',
'author_address' => 'nullable|string|max:255',
'author_zip' => 'nullable|string|max:255',
'author_city' => 'nullable|string|max:255',
'author_country_iso_3166_1' => 'nullable|string|max:2',
'composer_name' => 'nullable|string|max:255',
'composer_email' => 'nullable|email|max:255',
'composer_phone' => 'nullable|string|max:255',
'composer_address' => 'nullable|string|max:255',
'composer_zip' => 'nullable|string|max:255',
'composer_city' => 'nullable|string|max:255',
'composer_country_iso_3166_1' => 'nullable|string|max:2',
'composer_not_member_of_copyright_collective' => 'nullable|boolean',
'final_file_media_id' => 'nullable|integer',
```

- [ ] **Step 4: Create Controller**

Standard CRUD. Note: File uploads are NOT handled by V2 API CRUD - they remain backend-only. The V2 API manages entry metadata only.

- [ ] **Step 5: Write tests**

- [ ] **Step 6: Add route, run tests, commit**

### Task 3.5: AccessKeys V2 API

**Files:**
- Create: `src/Http/Resources/V2/AccessKeyResource.php`
- Create: `src/Http/Resources/V2/AccessKeyCollection.php`
- Create: `src/Http/Requests/Api/V2/AccessKeyGetRequest.php`
- Create: `src/Http/Requests/Api/V2/AccessKeyPostRequest.php`
- Create: `src/Http/Requests/Api/V2/AccessKeyPatchRequest.php`
- Create: `src/Http/Controllers/Api/V2/AccessKeysController.php`
- Create: `tests/Feature/V2AccessKeyTest.php`

- [ ] **Step 1: Create Resource**

```php
public function toArray($request): array
{
    return [
        'id' => (int) $this->id,
        'access_key' => $this->access_key,
        'ip_address' => $this->ip_address,
        'registered_at' => $this->registered_at,
        'is_remote' => (bool) $this->is_remote,
        'is_satellite' => (bool) $this->is_satellite,
        'is_prepaid' => (bool) $this->is_prepaid,
        'visitor_id' => $this->visitor_id,
        'created_at' => $this->created_at?->toIso8601String(),
        'updated_at' => $this->updated_at?->toIso8601String(),
    ];
}
```

- [ ] **Step 2: Create Collection, Request classes, Controller**

Post rules:
```php
'access_key' => 'required|string|max:255',
'ip_address' => 'nullable|string|max:255',
'registered_at' => 'nullable|date',
'is_remote' => 'nullable|boolean',
'is_satellite' => 'nullable|boolean',
'is_prepaid' => 'nullable|boolean',
'visitor_id' => 'nullable|exists:visitors,id',
```

- [ ] **Step 3: Write tests, add route, run tests, commit**

### Task 3.6: CompetitionPrizes V2 API

**Files:**
- Create: `src/Http/Resources/V2/CompetitionPrizeResource.php`
- Create: `src/Http/Resources/V2/CompetitionPrizeCollection.php`
- Create: `src/Http/Requests/Api/V2/CompetitionPrizeGetRequest.php`
- Create: `src/Http/Requests/Api/V2/CompetitionPrizePostRequest.php`
- Create: `src/Http/Requests/Api/V2/CompetitionPrizePatchRequest.php`
- Create: `src/Http/Controllers/Api/V2/CompetitionPrizesController.php`

- [ ] **Step 1: Create Resource**

```php
public function toArray($request): array
{
    return [
        'id' => (int) $this->id,
        'competition' => new CompetitionResource($this->whenLoaded('competition')),
        'amount' => $this->amount,
        'additional' => $this->additional,
        'rank' => (int) $this->rank,
        'created_at' => $this->created_at?->toIso8601String(),
        'updated_at' => $this->updated_at?->toIso8601String(),
    ];
}
```

- [ ] **Step 2: Create Collection, Request classes, Controller**

Post rules:
```php
'competition_id' => 'required|exists:competitions,id',
'amount' => 'nullable|string|max:255',
'additional' => 'nullable|string|max:255',
'rank' => 'required|integer|min:1',
```

- [ ] **Step 3: Add route, commit**

---

## Phase 4: Votes (Votes, ManualVotes, LiveVotes)

### Task 4.1: Votes V2 API

**Files:**
- Create: `src/Http/Resources/V2/VoteResource.php`
- Create: `src/Http/Resources/V2/VoteCollection.php`
- Create: `src/Http/Requests/Api/V2/VoteGetRequest.php`
- Create: `src/Http/Requests/Api/V2/VotePostRequest.php`
- Create: `src/Http/Requests/Api/V2/VotePatchRequest.php`
- Create: `src/Http/Controllers/Api/V2/VotesController.php`
- Create: `tests/Feature/V2VoteTest.php`

- [ ] **Step 1: Create Resource**

```php
public function toArray($request): array
{
    return [
        'id' => (int) $this->id,
        'competition_id' => (int) $this->competition_id,
        'entry_id' => (int) $this->entry_id,
        'visitor_id' => $this->visitor_id ? (int) $this->visitor_id : null,
        'vote_category' => new VoteCategoryResource($this->whenLoaded('vote_category')),
        'points' => (int) $this->points,
        'special_vote' => (bool) $this->special_vote,
        'comment' => $this->comment,
        'ip_address' => $this->ip_address,
        'created_at' => $this->created_at?->toIso8601String(),
        'updated_at' => $this->updated_at?->toIso8601String(),
    ];
}
```

- [ ] **Step 2: Create Collection, Request classes**

Post rules:
```php
'competition_id' => 'required|exists:competitions,id',
'entry_id' => 'required|exists:entries,id',
'visitor_id' => 'nullable|exists:visitors,id',
'vote_category_id' => 'required|exists:vote_categories,id',
'points' => 'required|integer',
'special_vote' => 'nullable|boolean',
'comment' => 'nullable|string',
'ip_address' => 'nullable|string|max:255',
```

- [ ] **Step 3: Create Controller, add route**

- [ ] **Step 4: Write tests, run tests, commit**

### Task 4.2: ManualVotes V2 API

**Files:**
- Create: `src/Http/Resources/V2/ManualVoteResource.php`
- Create: `src/Http/Resources/V2/ManualVoteCollection.php`
- Create: `src/Http/Requests/Api/V2/ManualVoteGetRequest.php`
- Create: `src/Http/Requests/Api/V2/ManualVotePostRequest.php`
- Create: `src/Http/Requests/Api/V2/ManualVotePatchRequest.php`
- Create: `src/Http/Controllers/Api/V2/ManualVotesController.php`

- [ ] **Step 1: Create Resource**

```php
public function toArray($request): array
{
    return [
        'id' => (int) $this->id,
        'competition_id' => (int) $this->competition_id,
        'entry_id' => (int) $this->entry_id,
        'points' => (int) $this->points,
        'ip_address' => $this->ip_address,
        'created_at' => $this->created_at?->toIso8601String(),
        'updated_at' => $this->updated_at?->toIso8601String(),
    ];
}
```

- [ ] **Step 2: Create Collection, Request classes, Controller**

Post rules:
```php
'competition_id' => 'required|exists:competitions,id',
'entry_id' => 'required|exists:entries,id',
'points' => 'required|integer',
'ip_address' => 'nullable|string|max:255',
```

- [ ] **Step 3: Add route, commit**

### Task 4.3: LiveVotes V2 API

**Files:**
- Create: `src/Http/Resources/V2/LiveVoteResource.php`
- Create: `src/Http/Resources/V2/LiveVoteCollection.php`
- Create: `src/Http/Requests/Api/V2/LiveVoteGetRequest.php`
- Create: `src/Http/Requests/Api/V2/LiveVotePostRequest.php`
- Create: `src/Http/Requests/Api/V2/LiveVotePatchRequest.php`
- Create: `src/Http/Controllers/Api/V2/LiveVotesController.php`

- [ ] **Step 1: Create Resource**

```php
public function toArray($request): array
{
    return [
        'id' => (int) $this->id,
        'competition' => new CompetitionResource($this->whenLoaded('competition')),
        'entry' => new EntryResource($this->whenLoaded('entry')),
        'sort_position' => (int) $this->sort_position,
        'title' => $this->title,
        'author' => $this->author,
        'created_at' => $this->created_at?->toIso8601String(),
        'updated_at' => $this->updated_at?->toIso8601String(),
    ];
}
```

- [ ] **Step 2: Create Collection, Request classes, Controller**

Post rules:
```php
'competition_id' => 'required|exists:competitions,id',
'entry_id' => 'required|exists:entries,id',
'sort_position' => 'required|integer',
'title' => 'required|string|max:255',
'author' => 'required|string|max:255',
```

- [ ] **Step 3: Add route, commit**

---

## Phase 5: RPC/Special Endpoints

### Task 5.1: Vote Results RPC endpoint

**Files:**
- Create: `src/Http/Controllers/Api/V2/Rpc/Votes/ResultsController.php`

- [ ] **Step 1: Create controller**

This wraps `VoteService::getAllVotesByRank()` and `getAllSpecialVotesByRank()` in V2 envelope format.

```php
<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Rpc\Votes;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Partymeister\Competitions\Services\VoteService;

class ResultsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $results = VoteService::getAllVotesByRank();
        $special = VoteService::getAllSpecialVotesByRank();

        return response()->json([
            'data' => [
                'results' => array_values($results),
                'special' => $special,
            ],
            'meta' => [
                'api_version' => 'v2',
                'message' => 'Vote results retrieved',
            ],
        ]);
    }
}
```

- [ ] **Step 2: Add route**

```php
// In the V2 RPC group
Route::prefix('api/v2/rpc')
    ->name('v2.rpc.')
    ->middleware([\Motor\Core\Http\Middleware\V2\V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::get('votes/results', \Partymeister\Competitions\Http\Controllers\Api\V2\Rpc\Votes\ResultsController::class)
            ->name('votes.results');
    });
```

- [ ] **Step 3: Add test to V2VoteTest**

- [ ] **Step 4: Commit**

### Task 5.2: Access Key Generation RPC endpoint

**Files:**
- Create: `src/Http/Controllers/Api/V2/Rpc/AccessKeys/GenerateController.php`

- [ ] **Step 1: Create controller**

```php
<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Rpc\AccessKeys;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Partymeister\Competitions\Http\Requests\Api\V2\AccessKeyGeneratePostRequest;
use Partymeister\Competitions\Models\AccessKey;
use Partymeister\Competitions\Services\AccessKeyService;

class GenerateController extends Controller
{
    public function __invoke(AccessKeyGeneratePostRequest $request): JsonResponse
    {
        $countBefore = AccessKey::count();

        AccessKeyService::generate($request);

        $countAfter = AccessKey::count();
        $generated = $countAfter - $countBefore;

        return response()->json([
            'data' => [
                'generated' => $generated,
            ],
            'meta' => [
                'api_version' => 'v2',
                'message' => "{$generated} access keys generated",
            ],
        ], 201);
    }
}
```

- [ ] **Step 2: Create request class**

```php
// src/Http/Requests/Api/V2/AccessKeyGeneratePostRequest.php
<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class AccessKeyGeneratePostRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1|max:2000',
        ];
    }
}
```

- [ ] **Step 3: Add RPC route**

```php
Route::post('access-keys/generate', \Partymeister\Competitions\Http\Controllers\Api\V2\Rpc\AccessKeys\GenerateController::class)
    ->name('access-keys.generate');
```

- [ ] **Step 4: Add test to V2AccessKeyTest**

- [ ] **Step 5: Commit**

---

## Phase 6: Quality Verification

### Task 6.1: Register competition test path in Pest

**Files:**
- Modify: `tests/Pest.php` (in project root)

- [ ] **Step 1: Add competitions test path**

Add `'../packages/partymeister-competitions/tests/Feature'` to the `uses()` call's `->in()` array, following the accounting pattern.

- [ ] **Step 2: Commit**

### Task 6.2: Run full test suite

- [ ] **Step 1: Run all V2 competition tests**

```bash
cd /Users/dfox/Development/partymeister-template-headless && vendor/bin/pest packages/partymeister-competitions/tests/Feature/ --group=V2
```

Expected: All tests pass.

- [ ] **Step 2: Run accounting tests to verify no regressions**

```bash
vendor/bin/pest packages/partymeister-accounting/tests/Feature/ --group=V2
```

- [ ] **Step 3: Fix any failures**

### Task 6.3: Run Pint code formatting

- [ ] **Step 1: Run Pint on the competitions package**

```bash
vendor/bin/pint packages/partymeister-competitions/
```

- [ ] **Step 2: Run tests again after formatting**

- [ ] **Step 3: Commit formatting changes separately**

```bash
cd packages/partymeister-competitions && git add -A && git commit -m "style: apply Pint formatting to competitions package"
```

### Task 6.4: Final verification and push

- [ ] **Step 1: Run full test suite one more time**

```bash
cd /Users/dfox/Development/partymeister-template-headless && vendor/bin/pest --group=V2
```

- [ ] **Step 2: Push submodule**

```bash
cd packages/partymeister-competitions && git push origin 2026
```

- [ ] **Step 3: Update parent repo submodule reference and push**

```bash
cd /Users/dfox/Development/partymeister-template-headless && git add packages/partymeister-competitions && git commit -m "chore: update partymeister-competitions submodule to V2 API" && git push
```

---

## V2 Route Summary

When complete, the V2 API will expose:

```
GET    /api/v2/competition-types          CompetitionTypesController@index
POST   /api/v2/competition-types          CompetitionTypesController@store
GET    /api/v2/competition-types/{id}     CompetitionTypesController@show
PATCH  /api/v2/competition-types/{id}     CompetitionTypesController@update
DELETE /api/v2/competition-types/{id}     CompetitionTypesController@destroy

GET    /api/v2/vote-categories            VoteCategoriesController@index
POST   /api/v2/vote-categories            VoteCategoriesController@store
GET    /api/v2/vote-categories/{id}       VoteCategoriesController@show
PATCH  /api/v2/vote-categories/{id}       VoteCategoriesController@update
DELETE /api/v2/vote-categories/{id}       VoteCategoriesController@destroy

GET    /api/v2/option-groups              OptionGroupsController@index
POST   /api/v2/option-groups              OptionGroupsController@store
GET    /api/v2/option-groups/{id}         OptionGroupsController@show
PATCH  /api/v2/option-groups/{id}         OptionGroupsController@update
DELETE /api/v2/option-groups/{id}         OptionGroupsController@destroy

GET    /api/v2/competitions               CompetitionsController@index
POST   /api/v2/competitions               CompetitionsController@store
GET    /api/v2/competitions/{id}          CompetitionsController@show
PATCH  /api/v2/competitions/{id}          CompetitionsController@update
DELETE /api/v2/competitions/{id}          CompetitionsController@destroy
GET    /api/v2/competitions/{id}/entries  Competitions\EntriesController@index
GET    /api/v2/competitions/{id}/prizes   Competitions\PrizesController@index

GET    /api/v2/entries                    EntriesController@index
POST   /api/v2/entries                    EntriesController@store
GET    /api/v2/entries/{id}               EntriesController@show
PATCH  /api/v2/entries/{id}               EntriesController@update
DELETE /api/v2/entries/{id}               EntriesController@destroy

GET    /api/v2/access-keys                AccessKeysController@index
POST   /api/v2/access-keys                AccessKeysController@store
GET    /api/v2/access-keys/{id}           AccessKeysController@show
PATCH  /api/v2/access-keys/{id}           AccessKeysController@update
DELETE /api/v2/access-keys/{id}           AccessKeysController@destroy

GET    /api/v2/competition-prizes         CompetitionPrizesController@index
POST   /api/v2/competition-prizes         CompetitionPrizesController@store
GET    /api/v2/competition-prizes/{id}    CompetitionPrizesController@show
PATCH  /api/v2/competition-prizes/{id}    CompetitionPrizesController@update
DELETE /api/v2/competition-prizes/{id}    CompetitionPrizesController@destroy

GET    /api/v2/votes                      VotesController@index
POST   /api/v2/votes                      VotesController@store
GET    /api/v2/votes/{id}                 VotesController@show
PATCH  /api/v2/votes/{id}                 VotesController@update
DELETE /api/v2/votes/{id}                 VotesController@destroy

GET    /api/v2/manual-votes               ManualVotesController@index
POST   /api/v2/manual-votes               ManualVotesController@store
GET    /api/v2/manual-votes/{id}          ManualVotesController@show
PATCH  /api/v2/manual-votes/{id}          ManualVotesController@update
DELETE /api/v2/manual-votes/{id}          ManualVotesController@destroy

GET    /api/v2/live-votes                 LiveVotesController@index
POST   /api/v2/live-votes                 LiveVotesController@store
GET    /api/v2/live-votes/{id}            LiveVotesController@show
PATCH  /api/v2/live-votes/{id}            LiveVotesController@update
DELETE /api/v2/live-votes/{id}            LiveVotesController@destroy

GET    /api/v2/rpc/votes/results          Rpc\Votes\ResultsController
POST   /api/v2/rpc/access-keys/generate   Rpc\AccessKeys\GenerateController
```

## Out of Scope (Intentionally Excluded)

These V1 endpoints are **NOT** ported to V2 in this plan:

1. **Shader Showdown** (`/api/shader-showdown/*`) - Uses custom token auth, serves a standalone Vue app. Will get its own dedicated V2 plan if needed.
2. **Competition Playlist** (`/api/competitions/{id}/playlist-data`) - Complex slide template integration with partymeister-slides. Requires cross-package coordination.
3. **Prizegiving Playlist** (`/api/prizegiving/playlist-data`) - Same cross-package dependency as competition playlist.
4. **Frontend Voting** (`/api/frontend/votes`) - Visitor-auth endpoint, separate auth flow. Will be addressed in the visitor/frontend API plan.
5. **SyncController** - Disabled, security vulnerability. Delete entirely, do not port.
6. **File uploads** - Entry file uploads remain backend-only; V2 API manages metadata.
