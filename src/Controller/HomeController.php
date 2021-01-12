<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\JpegResponse;


class HomeController extends AbstractController
{
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
        // return $this->json(['id' => bin2hex(random_bytes(5))]);
        return $this->json(['id' => $request->get('id')]);
    }

    // /**
    //  * @Route("/pdf")
    //  */
    // public function imageAction(Knp\Snappy\Pdf $knpSnappyPdf)
    // {
    //     $html = $this->renderView('MyBundle:Foo:bar.html.twig', array(
    //         'some'  => $vars
    //     ));

    //     return new JpegResponse(
    //         $knpSnappyImage->getOutputFromHtml($html),
    //         'image.jpg'
    //     );
    // }

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
