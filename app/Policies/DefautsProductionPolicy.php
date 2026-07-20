<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DefautsProduction;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefautsProductionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DefautsProduction');
    }

    public function view(AuthUser $authUser, DefautsProduction $defautsProduction): bool
    {
        return $authUser->can('View:DefautsProduction');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DefautsProduction');
    }

    public function update(AuthUser $authUser, DefautsProduction $defautsProduction): bool
    {
        return $authUser->can('Update:DefautsProduction');
    }

    public function delete(AuthUser $authUser, DefautsProduction $defautsProduction): bool
    {
        return $authUser->can('Delete:DefautsProduction');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DefautsProduction');
    }

    public function restore(AuthUser $authUser, DefautsProduction $defautsProduction): bool
    {
        return $authUser->can('Restore:DefautsProduction');
    }

    public function forceDelete(AuthUser $authUser, DefautsProduction $defautsProduction): bool
    {
        return $authUser->can('ForceDelete:DefautsProduction');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DefautsProduction');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DefautsProduction');
    }

    public function replicate(AuthUser $authUser, DefautsProduction $defautsProduction): bool
    {
        return $authUser->can('Replicate:DefautsProduction');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DefautsProduction');
    }

}