<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PaiementImputation;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaiementImputationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PaiementImputation');
    }

    public function view(AuthUser $authUser, PaiementImputation $paiementImputation): bool
    {
        return $authUser->can('View:PaiementImputation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PaiementImputation');
    }

    public function update(AuthUser $authUser, PaiementImputation $paiementImputation): bool
    {
        return $authUser->can('Update:PaiementImputation');
    }

    public function delete(AuthUser $authUser, PaiementImputation $paiementImputation): bool
    {
        return $authUser->can('Delete:PaiementImputation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PaiementImputation');
    }

    public function restore(AuthUser $authUser, PaiementImputation $paiementImputation): bool
    {
        return $authUser->can('Restore:PaiementImputation');
    }

    public function forceDelete(AuthUser $authUser, PaiementImputation $paiementImputation): bool
    {
        return $authUser->can('ForceDelete:PaiementImputation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PaiementImputation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PaiementImputation');
    }

    public function replicate(AuthUser $authUser, PaiementImputation $paiementImputation): bool
    {
        return $authUser->can('Replicate:PaiementImputation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PaiementImputation');
    }

}