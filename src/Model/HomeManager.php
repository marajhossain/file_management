<?php
namespace App\Model;

use App\Repository\FormRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeManager extends AbstractController{
    private $em;
    private $formRepository;

    public function __construct(
        EntityManagerInterface $em,
        FormRepository $formRepository
    ) {
        $this->em = $em;
        $this->formRepository = $formRepository;
    }


}