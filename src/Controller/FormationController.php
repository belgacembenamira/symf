<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Participant;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;



use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Form\FormationType;
use Symfony\Component\Form\Extension\Core\DataTransformer\FileToStringTransformer;

use Symfony\Component\Form\FormBuilderInterface;






class FormationController extends AbstractController
{
    /**
     * @Route("/formation", name="app_formation")
     */
    // public function index(): Response
    // {
    //     return $this->render('formation/index.html.twig', [
    //         'controller_name' => 'FormationController',
    //     ]);
    // }

    /* *
    *Route('/formation', name: 'app_formation')
    **/
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $Formation = new Formation();
        $Formation->setTitre("formation mern ");
        $Formation->setduree("30");
        $Formation->setPrice("2.00");
        $Formation->setBeginAt(new \DateTimeImmutable());
        $Formation->setImage("https://www.google.com/imgres?imgurl=https%3A%2F%2Flookaside.fbsbx.com%2Flookaside%2Fcrawler%2Fmedia%2F%3Fmedia_id%3D8965533020139012&tbnid=765I0uiCvxXx6M&vet=12ahUKEwjL3oC85vD-AhUMMuwKHa9BAGcQMygBegUIARClAQ..i&imgrefurl=https%3A%2F%2Fwww.facebook.com%2FCFP.forma.plus%2Fposts%2F8965533083472339%2F&docid=YDBFzZfZbp7duM&w=1200&h=722&itg=1&q=formation%20mern&ved=2ahUKEwjL3oC85vD-AhUMMuwKHa9BAGcQMygBegUIARClAQ");

        //Ajout de PArticipant 
        $Participant1 = new Participant();
        $Participant1->setEmail("belgacem@example.com");
        $Participant1->setFonction("Fonction1");
        $Participant1->setIsSubscribe(true);
        $Participant1->setNom("belgacem");

        $Participant2 = new Participant();
        $Participant2->setEmail("belgacem@example.com");
        $Participant2->setFonction("Fonction2");
        $Participant2->setIsSubscribe(true);
        $Participant2->setNom("belgacem");
        $entityManager->persist($Formation);
        $entityManager->persist($Participant2);
        $entityManager->persist($Participant1);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return $this->render('formation/index.html.twig', [
            'formation' => $Formation,
        ]);
    }
    /**
     * @Route("/formation/{id}", name="Formation_show")
     */
    public function show($id, Request $request)
    {
        $formation = $this->getDoctrine()
            ->getRepository(Formation::class)
            ->find($id);
        $em = $this->getDoctrine()->getManager();
        $lesformations = $em->getRepository(Formation::class)
            ->findBy(['id' => $id]);
        $publicPath = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath() . '/uploads/Formations/';
        if (!$formation) {
            throw $this->createNotFoundException(
                'No Formation found for id ' . $id
            );
        }
        return $this->render('formation/show.html.twig', [
            'lesformations' => $lesformations,
            'formation' => $formation,
            'publicPath' => $publicPath
        ]);
    }

    /**
     * @Route("/", name="home")
     */


    public function home(Request $request)
    {
        // Create the search form
        $form = $this->createFormBuilder()
            ->add('critere', TextType::class)
            ->add('valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        $repo = $entityManager->getRepository(Formation::class);

        // Fetch all formations
        $lesFormations = $repo->findAll();

        // Perform search when the form is submitted
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $critere = $data['critere']; // Retrieve the value of 'critere' field
            $lesFormations = $repo->findBy(['titre' => $critere]);
        }

        return $this->render(
            'formation/home.html.twig',
            ['lesformations' => $lesFormations, 'form' => $form->createView()]
        );
    }





    /**
     * @Route("/Ajouter", name="Ajouter")
     */
    public function ajouter(Request $request)
    {
        $formation = new Formation();

        $form = $this->createFormBuilder($formation)
            ->add('titre', TextType::class)
            ->add('begin_at', DateType::class)
            ->add('price', NumberType::class)
            ->add('duree', IntegerType::class)
            ->add('Image', FileType::class)
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();
        }

        return $this->render(
            'formation/ajouter.html.twig',
            ['form' => $form->createView()]
        );
    }

    //     /**
    //  * @Route("/Ajouter_formation", name="Ajouter_job")
    //  */
    // public function ajouter2(Request $request)
    // {
    //     $publicPath = "uploads/Formation/";
    //     $formation = new Formation();
    //     $form = $this->createForm(FormationType::class, $formation);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $image = $form->get('image')->getData();
    //         if ($image) {
    //             $imageName = $formation->getTitre() . '.' . $image->guessExtension();
    //             $image->move($publicPath, $imageName);
    //             $formation->setImage($imageName);
    //         }

    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($formation);
    //         $em->flush();

    //         return $this->redirectToRoute('home');
    //     }

    //     return $this->render(
    //         'formation/ajouter.html.twig',
    //         ['form' => $form->createView()]
    //     );
    // }


    /**
     * @Route("formation/supp/{id}", name="formation_delete")
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $formation = $entityManager->getRepository(Formation::class)->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('No formation found for this id: ' . $id);
        }

        $participants = $formation->getParticipants();

        foreach ($participants as $participant) {
            $entityManager->remove($participant);
        }

        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/editF/{id}", name="edit_formation")
     * Method({"GET","POST"})
     */
    public function edit(Request $request, $id)
    {
        $formation = $this->getDoctrine()
            ->getRepository(Formation::class)
            ->find($id);

        if (!$formation) {
            throw $this->createNotFoundException(
                'No formation found for id ' . $id
            );
        }

        $form = $this->createFormBuilder($formation)
            ->add('titre', TextType::class)
            ->add('price', MoneyType::class)
            ->add('duree', IntegerType::class)
            ->add('begin_at', DateType::class)
            ->add('Image', FileType::class, [
                'required' => false,
                'data_class' => null,
            ])
            ->add('save', SubmitType::class, ['label' => 'Update Formation'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('home', ['id' => $formation->getId()]);
        }

        return $this->render('formation/ajouter.html.twig', [
            'form' => $form->createView(),
            'formation' => $formation,
        ]);
    }




    /**
     * @Route("/listeformation", name="liste_formation")
     */
    public function afficherList(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('critere', TextType::class)
            ->add('valider', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Formation::class);
        $lesFormations = $repo->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $critere = $data['critere']; // Retrieve the value of 'critere' field
            $lesFormations = $repo->findBy(['titre' => $critere]);
        }



        return $this->render('formation/liste.html.twig', [
            'lesFormations' => $lesFormations,
            'form' => $form->createView()
        ]);
    }
}
