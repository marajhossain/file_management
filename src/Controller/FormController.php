<?php

namespace App\Controller;

use App\DependencyInjection\SystemConfigHelper;
use App\Entity\Form;
use App\Form\FormType;
use App\Model\FormManager;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

        if ($request->isMethod('POST')) {
            $user = $this->getUser();
            $formSubmitType = $request->get('form_submit_type');
            $questionFormId = $request->get('question_form_id');
            $questionAnswer = [];
            $time = time();
            $formToken = bin2hex(random_bytes(10));

            if($formSubmitType  == 'FORM'){
                $answers = $request->get('answers');
                $questionForm = $this->systemConfigHelper->getQuestion(1);

                foreach ($questionForm as $questionId => $question){
                    if($answers[0] == $questionId){
                        $questionAnswer[$questionId] = 1;
                    } else {
                        $questionAnswer[$questionId] = 0;
                    }
                }

                $paymentSlipAttachment = $form->get('payment_slip_attachment')->getData();

                if ($paymentSlipAttachment) {
                    $originalFilename = pathinfo($paymentSlipAttachment->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = 'payment-slip-'.$user->getId().'-'.strtolower(str_replace(' ', '-', $originalFilename)) . '-' . uniqid().'.'.$paymentSlipAttachment->guessExtension();

                    try {
                        $paymentSlipAttachment->move(
                            $this->uploadDir,
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    $formEntity->setPaymentSlipAttachment($newFilename);
                }

                $formEntity->setTitle($time.'-'.$questionFormId.'-'.$user->getId());
                $formEntity->setFormSubmitType($formSubmitType);
                $formEntity->setQuestionFormId($questionFormId);
                $formEntity->setQuestionAnswer(json_encode($questionAnswer));
                $formEntity->setFormToken($formToken);
                $formEntity->setStatus(1);
                $formEntity->setCreatedBy($user->getId());
                $formEntity->setCreatedTime(time());

                $this->formManager->create($formEntity);

                $url = $this->generateUrl('form_view', array('id' => $formToken),
                    UrlGeneratorInterface::ABSOLUTE_URL);
                $email = (new Email())
                    ->from('marajpersonal@gmail.com')
                    ->to($user->getEmail())
                    ->subject('New Form Create')
                    ->text('Sending emails is fun again!')
                    // ->attachFromPath('/path/to/documents/terms-of-use.pdf')
                    ->html("<p>".$user->getName().", Your EIA form submit <strong>successfully</strong>. You can see your form <a href='".$url."' target='_blank'><strong> click here ...</strong></a></p>");

                $mailer->send($email);

                $this->addFlash('success', 'Your form submitted successfully & send in your email. Please check your email.');

                return $this->redirectToRoute('form_index');

            } elseif ($formSubmitType  == 'UPLOAD'){
                $paymentSlipAttachment = $form->get('payment_slip_attachment')->getData();
                $eiaFormAttachment = $form->get('eia_form_attachment')->getData();

                if ($paymentSlipAttachment) {
                    $originalFilenameSlip = pathinfo($paymentSlipAttachment->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilenameSlip = 'payment-slip-'.$user->getId().'-'.strtolower(str_replace(' ', '-', $originalFilenameSlip)) . '-' . uniqid().'.'.$paymentSlipAttachment->guessExtension();

                    try {
                        $paymentSlipAttachment->move(
                            $this->uploadDir,
                            $newFilenameSlip
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    $formEntity->setPaymentSlipAttachment($newFilenameSlip);
                }

                if ($eiaFormAttachment) {
                    $originalFilenameForm = pathinfo($eiaFormAttachment->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilenameForm = 'eia-form-'.$user->getId().'-'.strtolower(str_replace(' ', '-', $originalFilenameForm)) . '-' . uniqid().'.'.$eiaFormAttachment->guessExtension();

                    try {
                        $eiaFormAttachment->move(
                            $this->uploadDir,
                            $newFilenameForm
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    $formEntity->setEiaFormAttachment($newFilenameForm);
                }

                $formEntity->setTitle($time.'-'.$questionFormId.'-'.$user->getId());
                $formEntity->setFormSubmitType($formSubmitType);
                $formEntity->setQuestionFormId($questionFormId);
                $formEntity->setQuestionAnswer("");
                $formEntity->setFormToken($formToken);
                $formEntity->setStatus(1);
                $formEntity->setCreatedBy($user->getId());
                $formEntity->setCreatedTime(time());

                $this->formManager->create($formEntity);

                $url = $this->generateUrl('form_view', array('id' => $formToken),
                    UrlGeneratorInterface::ABSOLUTE_URL);
                $email = (new Email())
                    ->from('marajpersonal@gmail.com')
                    ->to($user->getEmail())
                    ->subject('New Form Create')
                    ->text('Sending emails is fun again!')
                    // ->attachFromPath('/path/to/documents/terms-of-use.pdf')
                    ->html("<p>".$user->getName().", Your EIA form (attachment) submit <strong>successfully</strong>. You can see your form (attachment) <a href='".$url."' target='_blank'><strong> click here ...</strong></a></p>");

                $mailer->send($email);

                $this->addFlash('success', 'Your form submitted successfully & send in your email. Please check your email.');

                return $this->redirectToRoute('form_index');
            } else {
                $this->addFlash('error', 'Your form submitted fail.');
            }
        }

        return $this->render('form/new.html.twig', [
            'error' => "",
            'form' => $formEntity,
            'form' => $form->createView(),
            'questions' => $this->systemConfigHelper->getQuestion(1),
        ]);
    }

    /**
     * @Route("/form-download/{id}", name="form_download", methods={"GET"})
     */
    public function formDownload(): Response
    {
        $options = new Options();
        $options->set('defaultFont', 'Roboto');

        $dompdf = new Dompdf($options);

        /*$data = array(
            'headline' => 'EIA Form'
        );*/

        $html = $this->renderView('form/_form_generated.html.twig', [
            'headline' => "EIA Form",
            'questions' => $this->systemConfigHelper->getQuestion(1)
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("eid-form-".time().".pdf", [
            "Attachment" => true
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
