<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\DepositFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
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

            if ($user->getBankAccount()->balanceValid($form->getData()['amount'])) {
                $user->getBankAccount()->deposit($form->getData()['amount']);

                $em->persist($user);
                $em->flush();
            };
        }

        return $this->render('operation/deposit.html.twig', [
            'controller_name' => 'OperationController',
            'form' => $form->createView(),

        ]);
    }

    #[Route('/withdraw', name: 'account_withdraw')]
    public function withdraw(): Response
    {
        return $this->render('operation/withdraw.html.twig', [
            'controller_name' => 'OperationController',
        ]);
    }
}
