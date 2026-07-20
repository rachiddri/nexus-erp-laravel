<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DocumentsSortie;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentsSortiePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DocumentsSortie');
    }

    public function view(AuthUser $authUser, DocumentsSortie $documentsSortie): bool
    {
        return $authUser->can('View:DocumentsSortie');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DocumentsSortie');
    }

    public function update(AuthUser $authUser, DocumentsSortie $documentsSortie): bool
    {
        return $authUser->can('Update:DocumentsSortie');
    }

    public function delete(AuthUser $authUser, DocumentsSortie $documentsSortie): bool
    {
        return $authUser->can('Delete:DocumentsSortie');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DocumentsSortie');
    }

    public function restore(AuthUser $authUser, DocumentsSortie $documentsSortie): bool
    {
        return $authUser->can('Restore:DocumentsSortie');
    }

    public function forceDelete(AuthUser $authUser, DocumentsSortie $documentsSortie): bool
    {
        return $authUser->can('ForceDelete:DocumentsSortie');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DocumentsSortie');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DocumentsSortie');
    }

    public function replicate(AuthUser $authUser, DocumentsSortie $documentsSortie): bool
    {
        return $authUser->can('Replicate:DocumentsSortie');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DocumentsSortie');
    }

}