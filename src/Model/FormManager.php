<?php

namespace App\Model;

//use App\Entity\Form;
use App\Entity\Form;
use App\Entity\User;
use App\Repository\FormRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormManager extends AbstractController{
    private $em;
    private $formRepository;

    public function __construct(
        EntityManagerInterface $em,
        FormRepository $formRepository
    ) {
        $this->em = $em;
        $this->formRepository = $formRepository;
    }

    public function getAllForm(): array
    {
        return $this->formRepository->findAll();
    }

    public function create(Form $form)
    {
        $this->em->persist($form);
        $this->em->flush();
    }
}