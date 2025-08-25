# Construction Tool - Project Plan & Documentation

## Project Overview

**Purpose**: B2B construction management platform for Romanian construction companies
**Target Market**: General contractors (primary), subcontractors (secondary)
**Goal**: MVP ready for Cluj company demos within 4 weeks

## Core Business Model

- **Primary**: General contractors (higher subscription fees, complex needs)
- **Secondary**: Smaller construction companies (lower subscription fees)
- **Focus**: Internal work progress tracking, payment calculations, contract annexes

## Current Architecture Status

### ✅ Completed
- Laravel 12 + Filament 4 foundation
- Basic models: Contract, ContractAnnex, ContractedService, WorkReport, etc.
- Multi-tenancy structure with Workspace model
- Service layer pattern implementation
- Database migrations and factories
- Basic Filament resources and forms

### 🔄 In Progress
- User registration → workspace creation workflow
- Workspace switching and default workspace logic
- Spatie roles & permissions integration

## MVP Development Plan (4 Weeks)

### Week 1: Foundation & Multi-Tenancy Core 🎯
**Priority**: Bulletproof workspace system

**Tasks:**
- [ ] Complete automatic workspace creation on user registration
  - Create Company (personal company for user)
  - Create Workspace owned by that Company
  - Assign user as "owner" role in workspace
- [ ] Implement Spatie Laravel Permission package
- [ ] Set up role system: Owner, Manager, Worker
- [ ] Add default workspace functionality:
  - Add `is_default` column to workspace_users pivot table
  - Profile settings for workspace management
- [ ] Create workspace switching UI in Filament
- [ ] Implement user invite workflow to workspaces
- [ ] Test multi-tenant data isolation

**Acceptance Criteria:**
- New user registration automatically creates workspace
- User can switch between workspaces they have access to
- Role-based permissions work correctly
- Workspace naming: "FirstName LastName's Default Workspace"

### Week 2: Contract Management MVP 💰
**Priority**: Show the money flow

**Tasks:**
- [ ] Complete Contract CRUD with workspace isolation
- [ ] Contract Annexes functionality (crucial for construction)
- [ ] Contracted Services management with pricing
- [ ] Basic payment calculation logic
- [ ] Contract status tracking (Draft, Active, Completed, Cancelled)
- [ ] PDF generation for contracts (Romanian business requirement)
- [ ] Contract search and filtering

**Acceptance Criteria:**
- Can create contracts with services and pricing
- Can add annexes to existing contracts
- Payment calculations work based on progress
- Generated PDFs look professional

### Week 3: Work Progress Reporting 📊
**Priority**: Daily operations automation

**Tasks:**
- [ ] Work Report creation with date/site selection
- [ ] Work Report Entries (progress tracking on contracted services)
- [ ] Extra Services tracking (critical for additional billing)
- [ ] Mobile-responsive forms for on-site use
- [ ] Photo upload capability for work evidence
- [ ] Progress percentage calculations
- [ ] Work report approval workflow
- [ ] Basic reporting dashboard

**Acceptance Criteria:**
- Workers can easily create work reports on mobile
- Progress updates automatically calculate payments due
- Extra services are properly tracked for billing
- Photos attach to work reports

### Week 4: Polish & Demo Preparation ✨
**Priority**: Professional presentation ready

**Tasks:**
- [ ] Dashboard with key metrics:
  - Payments due/overdue
  - Work progress by project
  - Active contracts summary
  - Recent work reports
- [ ] UI/UX improvements and consistency
- [ ] Performance optimization
- [ ] Create realistic demo data with Romanian company names
- [ ] Demo script preparation
- [ ] Bug fixes and edge cases
- [ ] Mobile optimization testing

**Demo Features That Will Sell:**
1. **Extra Work Tracking**: "Look how easy it is to document work not in original contract"
2. **Automatic Calculations**: "Progress automatically calculates payment amounts"
3. **Mobile Documentation**: "Workers document everything on-site with photos"
4. **Multi-Company**: "Invite subcontractors to collaborate in your workspace"
5. **Professional Reports**: "Generate official documents for clients"

## Technical Architecture Decisions

### Multi-Tenancy Strategy
- **Workspace Model**: Owned by Company, Users have roles within workspaces
- **Data Isolation**: Filament tenant system with workspace-based filtering
- **User Registration**: Auto-creates Company + Workspace + assigns Owner role

### Role System (Spatie Laravel Permission)
- **Owner**: Full workspace control, can invite users, manage contracts
- **Manager**: Can create/edit contracts and work reports, view all data
- **Worker**: Can create work reports, view assigned projects only

### Future Considerations
- Romanian language localization (post-MVP)
- Auto-payment integration with Romanian payment systems
- Project phases with calendar view
- Warehouse management integration
- Beneficiary-contractor communication workflow

## Database Modifications Needed

### New Tables/Columns:
- Add `is_personal` boolean to `companies` table (future use)
- Add `is_default` boolean to `workspace_users` pivot table
- Ensure proper indexes for multi-tenant queries

### Spatie Permissions Tables:
- `roles`
- `permissions` 
- `model_has_permissions`
- `model_has_roles`
- `role_has_permissions`

## Risk Mitigation

### Technical Risks:
- **Multi-tenant data leakage**: Thorough testing of workspace isolation
- **Performance with large datasets**: Proper indexing and query optimization
- **Mobile experience**: Extensive mobile testing for work reports

### Business Risks:
- **Romanian market requirements**: Research official document formats
- **Payment integration complexity**: Start with manual calculations, automate later
- **User adoption**: Focus on solving real pain points first

## Success Metrics for MVP Demo

### Technical KPIs:
- Sub-2 second page load times
- Mobile-responsive on iOS/Android
- Zero data leakage between workspaces
- 99% uptime during demo period

### Business KPIs:
- Can demo complete workflow in under 15 minutes
- At least 3 different personas can use the system effectively
- Solves at least 2 major pain points for general contractors
- Professional appearance that justifies subscription pricing

## Next Phase Planning (Post-MVP)

### Immediate Priorities:
1. Romanian language localization
2. Advanced reporting and analytics
3. Document management system
4. Mobile app development

### Future Features:
1. Auto-payment integration
2. Project timeline management
3. Warehouse management
4. Advanced workflow automation
5. Beneficiary communication portal

---

**Last Updated**: August 25, 2025
**Status**: Planning Phase → Development Starting
**Target Demo Date**: September 25, 2025