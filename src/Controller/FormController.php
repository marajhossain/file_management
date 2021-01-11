<?php

namespace App\Controller;

use App\DependencyInjection\SystemConfigHelper;
use App\Entity\Form;
use App\Form\FormType;
use App\Model\FormManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form")
 */
class FormController extends AbstractController
{
    private $systemConfigHelper;
    private $formManager;
    private $uploadDir;

    /**
     * @param FormManager $formManager
     * @param $uploadDir
     */
    public function __construct(FormManager $formManager, $uploadDir) {
        $this->systemConfigHelper = new SystemConfigHelper();
        $this->formManager = $formManager;
        $this->uploadDir = $uploadDir;
    }

    /**
     * @Route("/", name="form_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('form/index.html.twig', [
            'forms' => $this->formManager->getAllForm(),
            'getStatus' => $this->systemConfigHelper->getStatus()
        ]);
    }

    /**
     * @Route("/new", name="form_new", methods={"GET","POST"})
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function new(Request $request, MailerInterface $mailer): Response
    {
        $formEntity = new Form();
        $form = $this->createForm(FormType::class, $formEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $thumbnail_image = $form->get('thumbnail_image')->getData();

            if ($thumbnail_image) {
                $originalFilename = pathinfo($thumbnail_image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = strtolower(str_replace(' ', '-', $originalFilename)) . '-' . uniqid().'.'.$thumbnail_image->guessExtension();

                try {
                    $thumbnail_image->move(
                        $this->uploadDir,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $formEntity->setThumbnailImage($newFilename);
            }

            $formEntity->setFormToken(bin2hex(random_bytes(60)));
            $formEntity->setCreatedBy($user->getId());
            $formEntity->setCreatedTime(time());

            $this->formManager->create($formEntity);

            $email = (new Email())
                ->from('marajpersonal@gmail.com')
                ->to($user->getEmail())
                ->subject('New Form Create')
                ->text('Sending emails is fun again!')
                ->html('<p><a href="http://www.devnetlimited.com/" target="_blank">click here</a></p>');

            $mailer->send($email);

            return $this->redirectToRoute('form_index');
        }

        return $this->render('form/new.html.twig', [
            'form' => $formEntity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="form_show", methods={"GET"})
     */
    public function show(Form $form): Response
    {
        return $this->render('form/show.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="form_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Form $form): Response
    {
        $form = $this->createForm(FormType::class, $form);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('form_index');
        }

        return $this->render('form/edit.html.twig', [
            'form' => $form,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="form_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Form $form): Response
    {
        if ($this->isCsrfTokenValid('delete'.$form->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($form);
            $entityManager->flush();
        }

        return $this->redirectToRoute('form_index');
    }
}
