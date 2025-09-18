<?php

namespace App\Services;

use App\Enums\RoleTemplate;
use App\Models\Permission\Role;

interface IRoleTemplateService
{
    /**
     * Get all available role templates
     *
     * @return array<string, string>
     */
    public function getAvailableTemplates(): array;

    /**
     * Create a role from a template in the current workspace
     *
     * @param RoleTemplate $template
     * @param string|null $customName Optional custom name for the role
     * @return Role
     */
    public function createRoleFromTemplate(RoleTemplate $template, ?string $customName = null): Role;

    /**
     * Get permissions for a specific template
     *
     * @param RoleTemplate $template
     * @return array
     */
    public function getTemplatePermissions(RoleTemplate $template): array;

    /**
     * Preview template permissions with descriptions
     *
     * @param RoleTemplate $template
     * @return array
     */
    public function previewTemplate(RoleTemplate $template): array;

    /**
     * Check if template can be applied to current workspace
     *
     * @param RoleTemplate $template
     * @return bool
     */
    public function canApplyTemplate(RoleTemplate $template): bool;
}