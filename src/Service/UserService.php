<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use Doctrine\Persistence\ManagerRegistry;
use Redis;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserService
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private ManagerRegistry $managerRegistry;
    private Redis $redis;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry, Redis $redis)
    {
        $this->redis = $redis;
        $this->managerRegistry = $managerRegistry;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function login(string $username, string $password)
    {

    }

    public function logout()
    {

    }

    public function create(User $user): User
    {
        $dbUser = $this->getUserByUsername($user->getUsername());
        if (!empty($dbUser)) throw new UserAlreadyExistsException('There is a user in database with given username \' ' . $user->getUsername() . '\'');
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    public function getUserById(int $id): User
    {
        $user = $this->managerRegistry->getRepository(User::class)->find($id);
        if (empty($user)) throw new UserNotFoundException('There is no user with given ID \'' . $id . '\'' );
        return $user;
    }

    public function getUserByUsername(string $username): User
    {
        $user = $this->managerRegistry->getRepository(User::class)->findOneBy(["username" => $username]);
        if (empty($user)) throw new UserNotFoundException('There is no user with given username \'' . $username . '\'' );
        return $user;
    }

    /**
     * Send a temp verification key to the user's email
     * @return void
     */
    public function requestResetPassword(User $user)
    {

    }

    /**
     * Check user's verification key
     * @return void
     */
    public function verifyResetPassword()
    {

    }

    /**
     * Reset user's password
     * @return void
     */
    public function resetPassword()
    {

    }
}