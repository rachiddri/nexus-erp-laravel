<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DocumentSortieLigne;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentSortieLignePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DocumentSortieLigne');
    }

    public function view(AuthUser $authUser, DocumentSortieLigne $documentSortieLigne): bool
    {
        return $authUser->can('View:DocumentSortieLigne');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DocumentSortieLigne');
    }

    public function update(AuthUser $authUser, DocumentSortieLigne $documentSortieLigne): bool
    {
        return $authUser->can('Update:DocumentSortieLigne');
    }

    public function delete(AuthUser $authUser, DocumentSortieLigne $documentSortieLigne): bool
    {
        return $authUser->can('Delete:DocumentSortieLigne');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DocumentSortieLigne');
    }

    public function restore(AuthUser $authUser, DocumentSortieLigne $documentSortieLigne): bool
    {
        return $authUser->can('Restore:DocumentSortieLigne');
    }

    public function forceDelete(AuthUser $authUser, DocumentSortieLigne $documentSortieLigne): bool
    {
        return $authUser->can('ForceDelete:DocumentSortieLigne');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DocumentSortieLigne');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DocumentSortieLigne');
    }

    public function replicate(AuthUser $authUser, DocumentSortieLigne $documentSortieLigne): bool
    {
        return $authUser->can('Replicate:DocumentSortieLigne');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DocumentSortieLigne');
    }

}