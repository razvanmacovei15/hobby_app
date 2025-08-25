# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Core Development
- `composer run dev` - Start full development environment (Laravel server, queue worker, logs, and Vite)
- `composer run test` - Run the full test suite (clears config and runs PHPUnit tests)
- `php artisan serve` - Start Laravel development server only
- `npm run dev` - Start Vite development server for frontend assets
- `npm run build` - Build production assets

### Testing
- `php artisan test` - Run PHPUnit tests
- `vendor/bin/phpunit` - Direct PHPUnit execution

### Database Operations
- `php artisan migrate` - Run database migrations
- `php artisan migrate:fresh --seed` - Fresh migration with seeders
- `php artisan db:seed` - Run database seeders

### Code Quality
- `vendor/bin/pint` - Run Laravel Pint code formatter (available via composer)

## Architecture Overview

This is a Laravel 12 application with Filament 4 for admin panel functionality, built for construction project management.

### Core Domain Models
- **Contract** - Main contracts between beneficiaries and executors
- **ContractAnnex** - Contract amendments and annexes
- **ContractedService** - Services defined in contracts/annexes
- **WorkReport** - Progress reports with entries and extra services
- **Company** - Both beneficiaries and executors
- **ConstructionSite** - Project locations with hierarchical structure (Buildings → Staircases → Floors → Apartments)
- **Workspace** - Multi-tenancy system for organizing users and executors

### Service Layer Pattern
The application uses interface-based services in `app/Services/`:
- Interface definitions: `I{Service}Service.php`
- Implementations: `Implementations/{Service}Service.php`
- Services handle business logic for Contracts, WorkReports, Companies, Users, and Executors

### Filament Admin Structure
- **Resources**: CRUD interfaces in `app/Filament/Resources/`
- **Pages**: Custom pages including auth registration and company management
- **Relation Managers**: Handle model relationships within resources
- **Schemas**: Reusable form and info list definitions
- **Tables**: Custom table configurations

### Database Structure
- Uses standard Laravel migrations with foreign key relationships
- Workspaces provide multi-tenancy
- Comprehensive factory and seeder setup for development data

### Frontend
- Vite build system with Tailwind CSS 4
- Filament handles most UI components
- Custom Blade templates for specialized pages

## Key Development Notes

### Multi-tenancy
The application uses workspaces for multi-tenancy. Users can belong to multiple workspaces, and executors are workspace-specific.

### Contract Management
Contracts have a hierarchical structure:
- Contract → ContractAnnexes → ContractedServices
- Each level can have services and work reports

### Work Reporting
Work reports track progress with:
- WorkReportEntry: Progress on contracted services
- WorkReportExtraService: Additional services not in original contract

### Code Style
- Follow PSR-4 autoloading
- Use Laravel Pint for code formatting
- Interface-based service implementations
- Eloquent relationships defined in models