<?php

namespace App\Controller;

use App\DependencyInjection\SystemConfigHelper;
use App\Model\FormManager;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\JpegResponse;


class HomeController extends AbstractController
{
    private $systemConfigHelper;
    private $formManager;
    private $uploadDir;

    /**
     * @param FormManager $formManager
     * @param $uploadDir
     */
    public function __construct(
        FormManager $formManager,
        $uploadDir
    ) {
        $this->systemConfigHelper = new SystemConfigHelper();
        $this->formManager = $formManager;
        $this->uploadDir = $uploadDir;
    }
    /**
     * @Route("/home", name="home_index")
     */
    public function index(): Response
    {
        /*return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);*/

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/form-view/{id}", name="form_view")
     * @param Request $request
     * @return Response
     */
    public function formView(Request $request): Response
    {
        $form = $this->formManager->getFormByToken($request->get("id"));
        return $this->render('home/_form_generated.html.twig', [
            'questions' => $this->systemConfigHelper->getQuestion(1),
            'form' => $form,
            'answer' => json_decode($form->getQuestionAnswer(), true),
            'formSubmitType' => $form->getFormSubmitType(),
            'eiaFromAttachment' => $form->getEiaFormAttachment(),
            'paymentSlipAttachment' => $form->getPaymentSlipAttachment()
        ]);
    }

     /**
      * @Route("/pdf")
      */
    public function generate_pdf(){

        $options = new Options();
        $options->set('defaultFont', 'Roboto');


        $dompdf = new Dompdf($options);

        $data = array(
            'headline' => 'my headline'
        );
        $html = $this->renderView('form/_form_generated.html.twig', [
            'headline' => "Test pdf generator",
            'questions' => $this->systemConfigHelper->getQuestion(1)
        ]);


        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("testpdf.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('marajpersonal@gmail.com')
            ->to('maraj.hossain@devnetlimited.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return $this->json(['username' => 'jane.doe']);
    }
}
