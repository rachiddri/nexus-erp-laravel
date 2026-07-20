<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MouvementSoldeClient;
use Illuminate\Auth\Access\HandlesAuthorization;

class MouvementSoldeClientPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MouvementSoldeClient');
    }

    public function view(AuthUser $authUser, MouvementSoldeClient $mouvementSoldeClient): bool
    {
        return $authUser->can('View:MouvementSoldeClient');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MouvementSoldeClient');
    }

    public function update(AuthUser $authUser, MouvementSoldeClient $mouvementSoldeClient): bool
    {
        return $authUser->can('Update:MouvementSoldeClient');
    }

    public function delete(AuthUser $authUser, MouvementSoldeClient $mouvementSoldeClient): bool
    {
        return $authUser->can('Delete:MouvementSoldeClient');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MouvementSoldeClient');
    }

    public function restore(AuthUser $authUser, MouvementSoldeClient $mouvementSoldeClient): bool
    {
        return $authUser->can('Restore:MouvementSoldeClient');
    }

    public function forceDelete(AuthUser $authUser, MouvementSoldeClient $mouvementSoldeClient): bool
    {
        return $authUser->can('ForceDelete:MouvementSoldeClient');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MouvementSoldeClient');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MouvementSoldeClient');
    }

    public function replicate(AuthUser $authUser, MouvementSoldeClient $mouvementSoldeClient): bool
    {
        return $authUser->can('Replicate:MouvementSoldeClient');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MouvementSoldeClient');
    }

}