<?php

namespace App\Form\transformers;

use App\Repository\RoleRepository;
use Symfony\Component\Form\DataTransformerInterface;

class RolesStringArrayToObjectTransformer implements DataTransformerInterface
{
    private RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function transform(mixed $value): array
    {
        if (empty($value))
            return array();
        $res = [];
        foreach ($value as $roleName) {
            $role = $this->roleRepository->findOneBy(['role' => $roleName]);
            if (!empty($role))
                $res[] = $role;
        }
        return $res;
    }

    public function reverseTransform(mixed $roles)
    {
        return $roles;
    }
}