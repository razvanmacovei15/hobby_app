# Romanian Construction Management Platform - Development Context

## Current Reality & Focus
**Status**: MVP Development Phase (NOT feature-complete platform)  
**Timeline**: 6-8 weeks to working prototype  
**Market**: Romanian B2B Construction (unvalidated)  
**Tech Stack**: Laravel 12 + Filament 4 + Multi-tenancy  

## Critical Success Criteria
1. **Complete existing incomplete features** before adding new ones
2. **Ship working MVP** to 5-10 Romanian construction companies
3. **Validate core assumptions** about daily workflows
4. **Prove willingness to pay** before expanding scope

## Development Commands (Using Laravel Sail)

### Core Development
- `./vendor/bin/sail up` - Start Docker development environment
- `./vendor/bin/sail artisan serve` - Start Laravel development server
- `./vendor/bin/sail npm run dev` - Start Vite development server for frontend assets
- `./vendor/bin/sail npm run build` - Build production assets

### Testing
- `./vendor/bin/sail artisan test` - Run PHPUnit tests
- `./vendor/bin/sail test` - Alternative test command

### Database Operations
- `./vendor/bin/sail artisan migrate` - Run database migrations
- `./vendor/bin/sail artisan migrate:fresh --seed` - Fresh migration with seeders
- `./vendor/bin/sail artisan db:seed` - Run database seeders

### Code Quality
- `./vendor/bin/sail composer pint` - Run Laravel Pint code formatter

### Debugging & Development
- `./vendor/bin/sail artisan tinker` - Laravel Tinker for debugging
- `./vendor/bin/sail logs` - View application logs

## Architecture Foundation (What Actually Works)

### Multi-Tenancy System
- **Workspace Model**: 1 workspace = 1 construction project/building permit
- **Clean Separation**: Companies → Workspaces → Users/Executors/Contracts
- **Service Layer**: Interface-based pattern (IContractService, IWorkspaceService)
- **Security**: Spatie Laravel Permission with workspace-level scoping

### Core Models & Status
```
✅ Workspace (basic, needs enhancement)
✅ Company (basic + Romanian fields)
✅ Contract → ContractAnnex → ContractedService (good structure)
✅ WorkReport → WorkReportEntry (solid polymorphic design)
✅ WorkspaceUser/WorkspaceExecutor (proper pivot models)
⚠️  BuildingPermit (too basic for Romanian law)
⚠️  Roles/Permissions (sophisticated system, basic permissions)
```

### Romanian Market Context
- **Legal Requirements**: CUI, VAT, ANAF integration, e-Factura compliance
- **Business Model**: Beneficiary (client) → General Contractor → Executors (subcontractors)
- **Construction Phases**: Planning → Permits → Construction → Completion
- **Regulatory**: Building permits mandatory, architect/engineer licenses required

## Immediate Fixes Required (Week 1-2)

### 1. Complete Basic CRUD Operations
**Problem**: Missing action icons, incomplete status workflows
```php
// ADD EVERYWHERE missing icons
EditAction::make()->icon('heroicon-o-pencil')
ViewAction::make()->icon('heroicon-o-eye')
DeleteAction::make()->icon('heroicon-o-trash')
```

**Critical Files Needing Icons**:
- `ViewExecutor.php`, `ViewContract.php`, `ViewContractAnnex.php`, `ViewWorkReport.php`

### 2. Implement Existing Status Enums
**Problem**: ContractStatus enum exists but isn't used in Contract model
```php
// Contract model - USE the existing enum
protected $casts = [
    'status' => ContractStatus::class, // THIS EXISTS but isn't implemented
];
```

### 3. Complete Work Report Status Workflow
**Problem**: Comments everywhere mention "Draft → Submitted → Approved" but it's not implemented
```php
enum WorkReportStatus: string {
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case APPROVED = 'approved';
    case LOCKED = 'locked';
}

// Add to WorkReport model
protected $fillable = [..., 'status', 'approved_at', 'approved_by'];
protected $casts = ['status' => WorkReportStatus::class];
```

## Current Implementation Status

### ✅ Working Well
- Multi-tenant workspace architecture with proper query scoping
- Service layer pattern with interface-based design
- Complex form handling with nested relationships
- Workspace-scoped permissions and role management
- Polymorphic WorkReportEntry system for flexible service tracking

### ⚠️ Partially Implemented
- **Building Permits**: Basic model exists but missing Romanian legal requirements
- **Contracts**: Good data model but missing status workflow implementation
- **User Management**: Dual system (WorkspaceUsers + WorkspaceInvitations) needs unification
- **Executors**: Basic executor types vs. comprehensive Romanian construction trades

### ❌ Missing Critical Features
- **Work Report Approval Workflow**: Commented everywhere but not implemented
- **Mobile Optimization**: Forms too complex for field workers
- **Document Management**: No file attachments for contracts/permits
- **Basic Reporting**: No PDF generation for Romanian legal compliance
- **Activity Tracking**: No audit trail for legal compliance

## Development Guidelines

### When Adding New Features
1. **Complete existing feature first** - Don't start new resources with incomplete ones
2. **Validate with users** - Every new feature needs user validation
3. **Mobile-first forms** - Construction workers use phones, not desktops
4. **Romanian compliance** - Every business feature must meet Romanian legal standards

### Code Quality Standards
- Use existing service layer patterns
- Maintain workspace query scoping
- Implement proper status workflows
- Add comprehensive validation
- Write tests for critical Romanian compliance features

### Service Layer Architecture (CRITICAL)
**MANDATORY for all business logic implementation:**

1. **Check for Service Interface**: Before building any business logic for a model, check if a service interface exists (e.g., `IUserService`, `IContractService`)
2. **Create Missing Services**: If no service interface exists, create both:
   - Interface: `app/Services/I[Model]Service.php`
   - Implementation: `app/Services/Implementations/[Model]Service.php`
3. **Register in AppServiceProvider**: Always bind interfaces to implementations in `app/Providers/AppServiceProvider.php`:
   ```php
   $this->app->bind(IContractService::class, ContractService::class);
   ```
4. **Use Services Everywhere**: NEVER write business logic directly in:
   - Filament Pages
   - Filament Resources  
   - Filament Actions
   - Controllers
   
   **Always inject and use services for testability**
5. **Dependency Injection**: Use constructor injection in Filament components or method injection in actions:
   ```php
   // Constructor injection
   public function __construct(private IContractService $contractService) {}
   
   // Method injection (preferred for Filament actions)
   ->action(function (IContractService $contractService) {
       $contractService->someMethod($this->record);
   })
   ```

### Complete Feature Implementation Checklist
When implementing any new model feature, ALWAYS create/update ALL of these:

**Database Layer:**
- [ ] Migration with proper foreign keys and indexes
- [ ] Model with relationships, casts, and fillable fields
- [ ] Factory with realistic fake data + factory states for different statuses
- [ ] Seeder with representative sample data
- [ ] **RUN MIGRATION**: `./vendor/bin/sail artisan migrate`

**Business Logic:**
- [ ] Interface defining all business methods
- [ ] Implementation in `app/Services/Implementations/`
- [ ] Service registration in AppServiceProvider
- [ ] Proper error handling and validation

**UI Layer (Filament):**
- [ ] Resource with proper icons and navigation
- [ ] Form schema with proper defaults and validation
- [ ] Table schema with filters and actions
- [ ] Infolist schema for view pages
- [ ] All action buttons with appropriate icons

**Testing:**
- [ ] Feature tests for critical business logic
- [ ] Factory usage in tests for data setup

**Authorization & Permissions:**
- [ ] Add permission category to `PermissionCategory` enum (case, label, description)
- [ ] Add permissions to `PermissionSeeder` (view, create, edit, delete)
- [ ] Run seeder: `./vendor/bin/sail artisan db:seed --class=PermissionSeeder`
- [ ] Create policy: `./vendor/bin/sail artisan make:policy [Model]Policy --model=[Model]`
- [ ] Implement policy methods with permission checks (`$user->can('resource.action')`)
- [ ] Register policy in `AppServiceProvider::boot()` using `Gate::policy()`

### UI/UX Principles
- **Construction Industry Focus**: Use industry terminology and workflows
- **Romanian Language**: Critical for user adoption
- **Simple Before Complex**: Basic working features before advanced ones
- **Mobile Responsive**: Field workers are primary daily users

## Next Actions for Claude

When helping with this project:

1. **Focus on completing existing incomplete features** before adding new ones
2. **Prioritize Romanian legal compliance** over advanced features
3. **Keep mobile field workers in mind** for all form design
4. **Validate business logic** against Romanian construction practices
5. **Push back on feature creep** - encourage shipping working MVP first

Remember: This is an MVP to validate market fit, not a feature-complete platform. Success is measured by Romanian construction companies using it daily, not by feature count.

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.10
- filament/filament (FILAMENT) - v4
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- livewire/livewire (LIVEWIRE) - v3
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v11
- rector/rector (RECTOR) - v2
- tailwindcss (TAILWINDCSS) - v4


## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.


=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs
- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms


=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Comments
- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.


=== filament/core rules ===

## Filament
- Filament is used by this application, check how and where to follow existing application conventions.
- Filament is a Server-Driven UI (SDUI) framework for Laravel. It allows developers to define user interfaces in PHP using structured configuration objects. It is built on top of Livewire, Alpine.js, and Tailwind CSS.
- You can use the `search-docs` tool to get information from the official Filament documentation when needed. This is very useful for Artisan command arguments, specific code examples, testing functionality, relationship management, and ensuring you're following idiomatic practices.
- Utilize static `make()` methods for consistent component initialization.

### Artisan
- You must use the Filament specific Artisan commands to create new files or components for Filament. You can find these with the `list-artisan-commands` tool, or with `php artisan` and the `--help` option.
- Inspect the required options, always pass `--no-interaction`, and valid arguments for other options when applicable.

### Filament's Core Features
- Actions: Handle doing something within the application, often with a button or link. Actions encapsulate the UI, the interactive modal window, and the logic that should be executed when the modal window is submitted. They can be used anywhere in the UI and are commonly used to perform one-time actions like deleting a record, sending an email, or updating data in the database based on modal form input.
- Forms: Dynamic forms rendered within other features, such as resources, action modals, table filters, and more.
- Infolists: Read-only lists of data.
- Notifications: Flash notifications displayed to users within the application.
- Panels: The top-level container in Filament that can include all other features like pages, resources, forms, tables, notifications, actions, infolists, and widgets.
- Resources: Static classes that are used to build CRUD interfaces for Eloquent models. Typically live in `app/Filament/Resources`.
- Schemas: Represent components that define the structure and behavior of the UI, such as forms, tables, or lists.
- Tables: Interactive tables with filtering, sorting, pagination, and more.
- Widgets: Small component included within dashboards, often used for displaying data in charts, tables, or as a stat.

### Relationships
- Determine if you can use the `relationship()` method on form components when you need `options` for a select, checkbox, repeater, or when building a `Fieldset`:

```php
Forms\Components\Select::make('user_id')
    ->label('Author')
    ->relationship('author')
    ->required(),
```


## Testing
- It's important to test Filament functionality for user satisfaction.
- Ensure that you are authenticated to access the application within the test.
- Filament uses Livewire, so start assertions with `livewire()` or `Livewire::test()`.

### Example Tests

```php
livewire(ListUsers::class)
    ->assertCanSeeTableRecords($users)
    ->searchTable($users->first()->name)
    ->assertCanSeeTableRecords($users->take(1))
    ->assertCanNotSeeTableRecords($users->skip(1))
    ->searchTable($users->last()->email)
    ->assertCanSeeTableRecords($users->take(-1))
    ->assertCanNotSeeTableRecords($users->take($users->count() - 1));
```

```php
livewire(CreateUser::class)
    ->fillForm([
        'name' => 'Howdy',
        'email' => 'howdy@example.com',
    ])
    ->call('create')
    ->assertNotified()
    ->assertRedirect();

assertDatabaseHas(User::class, [
    'name' => 'Howdy',
    'email' => 'howdy@example.com',
]);
```

```php
use Filament\Facades\Filament;

Filament::setCurrentPanel('app');
```

```php
livewire(EditInvoice::class, [
    'invoice' => $invoice,
])->callAction('send');

expect($invoice->refresh())->isSent()->toBeTrue();
```


=== filament/v4 rules ===

## Filament 4

### Important Version 4 Changes
- File visibility is now `private` by default.
- The `deferFilters` method from Filament v3 is now the default behavior in Filament v4, so users must click a button before the filters are applied to the table. To disable this behavior, you can use the `deferFilters(false)` method.
- The `Grid`, `Section`, and `Fieldset` layout components no longer span all columns by default.
- The `all` pagination page method is not available for tables by default.
- All action classes extend `Filament\Actions\Action`. No action classes exist in `Filament\Tables\Actions`.
- The `Form` & `Infolist` layout components have been moved to `Filament\Schemas\Components`, for example `Grid`, `Section`, `Fieldset`, `Tabs`, `Wizard`, etc.
- A new `Repeater` component for Forms has been added.
- Icons now use the `Filament\Support\Icons\Heroicon` Enum by default. Other options are available and documented.

### Organize Component Classes Structure
- Schema components: `Schemas/Components/`
- Table columns: `Tables/Columns/`
- Table filters: `Tables/Filters/`
- Actions: `Actions/`


=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation
- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues
- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization
- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] <name>` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error
- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.


=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure
- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.


=== livewire/core rules ===

## Livewire Core
- Use the `search-docs` tool to find exact version specific documentation for how to write Livewire & Livewire tests.
- Use the `php artisan make:livewire [Posts\\CreatePost]` artisan command to create new components
- State should live on the server, with the UI reflecting it.
- All Livewire requests hit the Laravel backend, they're like regular HTTP requests. Always validate form data, and run authorization checks in Livewire actions.

## Livewire Best Practices
- Livewire components require a single root element.
- Use `wire:loading` and `wire:dirty` for delightful loading states.
- Add `wire:key` in loops:

    ```blade
    @foreach ($items as $item)
        <div wire:key="item-{{ $item->id }}">
            {{ $item->name }}
        </div>
    @endforeach
    ```

- Prefer lifecycle hooks like `mount()`, `updatedFoo()`) for initialization and reactive side effects:

```php
public function mount(User $user) { $this->user = $user; }
public function updatedSearch() { $this->resetPage(); }
```


## Testing Livewire

```php
Livewire::test(Counter::class)
    ->assertSet('count', 0)
    ->call('increment')
    ->assertSet('count', 1)
    ->assertSee(1)
    ->assertStatus(200);
```


```php
$this->get('/posts/create')
    ->assertSeeLivewire(CreatePost::class);
```


=== livewire/v3 rules ===

## Livewire 3

### Key Changes From Livewire 2
- These things changed in Livewire 2, but may not have been updated in this application. Verify this application's setup to ensure you conform with application conventions.
    - Use `wire:model.live` for real-time updates, `wire:model` is now deferred by default.
    - Components now use the `App\Livewire` namespace (not `App\Http\Livewire`).
    - Use `$this->dispatch()` to dispatch events (not `emit` or `dispatchBrowserEvent`).
    - Use the `components.layouts.app` view as the typical layout path (not `layouts.app`).

### New Directives
- `wire:show`, `wire:transition`, `wire:cloak`, `wire:offline`, `wire:target` are available for use. Use the documentation to find usage examples.

### Alpine
- Alpine is now included with Livewire, don't manually include Alpine.js.
- Plugins included with Alpine: persist, intersect, collapse, and focus.

### Lifecycle Hooks
- You can listen for `livewire:init` to hook into Livewire initialization, and `fail.status === 419` for the page expiring:

```js
document.addEventListener('livewire:init', function () {
    Livewire.hook('request', ({ fail }) => {
        if (fail && fail.status === 419) {
            alert('Your session expired');
        }
    });

    Livewire.hook('message.failed', (message, component) => {
        console.error(message);
    });
});
```


=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.


=== phpunit/core rules ===

## PHPUnit Core

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit <name>` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should test all of the happy paths, failure paths, and weird paths.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files, these are core to the application.

### Running Tests
- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test`.
- To run all tests in a file: `php artisan test tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --filter=testName` (recommended after making a change to a related file).


=== tailwindcss/core rules ===

## Tailwind Core

- Use Tailwind CSS classes to style HTML, check and use existing tailwind conventions within the project before writing your own.
- Offer to extract repeated patterns into components that match the project's conventions (i.e. Blade, JSX, Vue, etc..)
- Think through class placement, order, priority, and defaults - remove redundant classes, add classes to parent or child carefully to limit repetition, group elements logically
- You can use the `search-docs` tool to get exact examples from the official documentation when needed.

### Spacing
- When listing items, use gap utilities for spacing, don't use margins.

```html
<div class="flex gap-8">
    <div>Superior</div>
    <div>Michigan</div>
    <div>Erie</div>
</div>
```


### Dark Mode
- If existing pages and components support dark mode, new pages and components must support dark mode in a similar way, typically using `dark:`.


=== tailwindcss/v4 rules ===

## Tailwind 4

- Always use Tailwind CSS v4 - do not use the deprecated utilities.
- `corePlugins` is not supported in Tailwind v4.
- In Tailwind v4, you import Tailwind using a regular CSS `@import` statement, not using the `@tailwind` directives used in v3:

```diff
- @tailwind base;
- @tailwind components;
- @tailwind utilities;
+ @import "tailwindcss";
```


### Replaced Utilities
- Tailwind v4 removed deprecated utilities. Do not use the deprecated option - use the replacement.
- Opacity values are still numeric.

| Deprecated |	Replacement |
|------------+--------------|
| bg-opacity-* | bg-black/* |
| text-opacity-* | text-black/* |
| border-opacity-* | border-black/* |
| divide-opacity-* | divide-black/* |
| ring-opacity-* | ring-black/* |
| placeholder-opacity-* | placeholder-black/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |
</laravel-boost-guidelines>

# important-instruction-reminders
Do what has been asked; nothing more, nothing less.
NEVER create files unless they're absolutely necessary for achieving your goal.
ALWAYS prefer editing an existing file to creating a new one.
NEVER proactively create documentation files (*.md) or README files. Only create documentation files if explicitly requested by the User.
