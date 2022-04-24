<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\ResetPasswordException;
use Doctrine\Persistence\ManagerRegistry;
use Redis;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserService
{
    private UserPasswordHasherInterface $userPasswordHasher;
    private ManagerRegistry $managerRegistry;
    private Redis $redis;
    private MailerInterface $mailer;
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel, MailerInterface $mailer, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry, Redis $redis)
    {
        $this->kernel = $kernel;
        $this->mailer = $mailer;
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
        //$dbUser = $this->getUserByUsername($user->getUsername());
        //if (!empty($dbUser)) throw new UserAlreadyExistsException('There is a user in database with given username \' ' . $user->getUsername() . '\'');
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    public function getUserByUsername(string $username): User
    {
        $user = $this->managerRegistry->getRepository(User::class)->findOneBy(["username" => $username]);
        if (empty($user)) throw new UserNotFoundException('There is no user with given username \'' . $username . '\'');
        return $user;
    }

    public function getUserById(int $id): User
    {
        $user = $this->managerRegistry->getRepository(User::class)->find($id);
        if (empty($user)) throw new UserNotFoundException('There is no user with given ID \'' . $id . '\'');
        return $user;
    }

    /**
     * Send a temp verification key to the user's email
     * @return void
     */
    public function requestResetPassword(User $user)
    {
        $vKey = mt_rand(100000, 999999);
        $timeout = $this->kernel->getContainer()->getParameter('app.v_key_timeout');
        $timeout = empty($timeout) ? 900 : $timeout; # 15min default
        $this->redis->set(\App\Utils\RedisKeys::getResetPasswordVKey($user->getUserIdentifier()), $vKey, $timeout);
        $container = $this->kernel->getContainer();
        $email = (new TemplatedEmail())
            ->from(new Address($container->getParameter('app.email_address'), $container->getParameter('app.email_sender')))
            ->to(new Address($user->getEmail(), $user->getUsername()))
            ->subject('Password Reset')
            ->htmlTemplate('email/rest_password.html.twig');
    }

    /**
     * Check user's verification key
     * @param User $user
     * @param int $vKey
     * @return ?ResetPasswordException
     */
    public function verifyResetPassword(User $user, int $vKey): ?ResetPasswordException
    {
        $redisVKey = $this->redis->get(\App\Utils\RedisKeys::getResetPasswordVKey($user->getUserIdentifier()));

        $return = new ResetPasswordException("Invalid Verification Key");

        if (empty($redisVKey)) {
            return $return;
        } else if ($vKey == $redisVKey) {
            return null;
        }
        return $return;
    }

    /**
     * Reset user's password
     * @return void
     */
    public function resetPassword()
    {

    }
}