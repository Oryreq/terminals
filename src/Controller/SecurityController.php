<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Service\Attribute\Required;


class SecurityController extends AbstractController
{
    #[Required]
    public EntityManagerInterface $entityManager;

    #[Required]
    public UserRepository $userRepository;


    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils    $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin');
        }

        $userAdmin = $this->userRepository->findOneBy(['username' => 'admin']);
        if (!$userAdmin) {
            $userAdmin = (new User())
                ->setUsername('admin')
                ->setRoles(array_values(User::ROLES))
                ->setPassword('$2y$13$dKHroammGwy5m..V51QWzeoMwdltwX.sn2kU.xwa1Z52wrZ4qAqya');
            $this->entityManager->persist($userAdmin);
            $this->entityManager->flush();
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,

            'translation_domain' => 'admin',

            'csrf_token_intention' => 'authenticate',

            // the URL users are redirected to after the login (default: '/admin')
            'target_path' => $this->generateUrl('admin'),

            // the label displayed for the username form field (the |trans filter is applied to it)
            'username_label' => 'Логин',

            // the label displayed for the password form field (the |trans filter is applied to it)
            'password_label' => 'Пароль',

            // the label displayed for the Sign In form button (the |trans filter is applied to it)
            'sign_in_label' => 'Вход',
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}