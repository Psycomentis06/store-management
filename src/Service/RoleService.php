<?php

namespace App\Service;

use App\Entity\Role;
use App\Repository\PermissionRepository;
use App\Repository\RoleRepository;

class RoleService
{

    private RoleRepository $roleRepository;
    private PermissionRepository $permissionRepository;

    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function addDefaultPermissions()
    {
        $roles = ['ROLE_USER' => 'user', 'ROLE_SUPERADMIN' => 'superadmin'];
        foreach ($roles as $roleName => $roleDefaultName) {
            $roleEntity = $this->roleRepository->findOneBy(['role' => $roleName]);
            $permissions = $this->permissionRepository->findByDefaultRole($roleDefaultName);
            if (!empty($roleEntity) && $roleEntity instanceof Role) {
                foreach ($permissions as $permission) {
                    $roleEntity->addPermission($permission);
                }
                $this->roleRepository->add($roleEntity);
            }
        }
    }

    public function cloneRole(string $from, string $newRoleName): bool
    {
        $role = $this->roleRepository->findOneBy(['role' => $from]);
        if (!empty($role)) {
            $clonedRole = clone $role;
            $clonedRole->setRole($newRoleName);
            $this->roleRepository->add($clonedRole);
            return true;
        }
        return false;
    }

}