<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\DepositFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{

    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'Home',
        ]);
    }

    #[Route('/deposit', name: 'account_deposit')]
    public function deposit(Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(DepositFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $em->getRepository(User::class)->find($request->get('user'));

            $user->getBankAccount()->deposit($form->getData()['amount']);

            if ($user->getBankAccount()->sendEmail(new \DateTime())) {
                $this->sendEmail($user, 'account_deposit');
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('operation/deposit.html.twig', [
            'controller_name' => 'OperationController',
            'form' => $form->createView(),

        ]);
    }

    #[Route('/withdraw', name: 'account_withdraw')]
    public function withdraw(Request $request, EntityManagerInterface $em): Response
    {


        $form = $this->createForm(DepositFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $em->getRepository(User::class)->find($request->get('user'));

            $user->getBankAccount()->withdraw($form->getData()['amount']);

            if ($user->getBankAccount()->sendEmail(new \DateTime())) {
                $this->sendEmail($user, 'account_withdraw');
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }


        return $this->render('operation/withdraw.html.twig', [
            'controller_name' => 'OperationController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/email', name: 'app_email')]
    
    private function sendEmail(User $user, string $route)
    {
        $this->emailVerifier->sendEmailConfirmation(
            $route,
            $user,
            (new TemplatedEmail())
                ->from(new Address('noreply@bankaccount.com', 'Bank Acccount'))
                ->to($user->getEmail())
                ->subject('Operation confirmer')
                ->htmlTemplate('mailer/contentEmail.html.twig')
        );
    }
}
