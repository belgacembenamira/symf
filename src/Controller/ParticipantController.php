<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant", name="app_participnt")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }
    /**
     * @Route("/listeparticipant", name="liste_participant")
     */
    public function afficherList(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('critere', TextType::class)
            ->add('valider', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Participant::class); // Utiliser la classe Participant au lieu de Formation
        $lesParticipants = $repo->findAll(); // Utiliser findAll pour récupérer tous les participants

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $lesParticipants = $repo->recherche($data['critere']); // Utiliser la méthode "recherche" appropriée pour rechercher les participants
        }

        return $this->render('participant/liste.html.twig', [ // Utiliser le template "liste.html.twig" approprié pour les participants
            'lesParticipants' => $lesParticipants, // Utiliser le nom de variable approprié pour les participants
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/AjouterParticipant", name="AjouterParticipant")
     */
    public function ajouterParticipant(Request $request)
    {
        $participant = new Participant();

        $form = $this->createFormBuilder($participant)
            ->add('nom', TextType::class)
            ->add('email', EmailType::class)
            ->add('is_subscribe', CheckboxType::class, [
                'label' => 'Abonné',
                'required' => false,
            ])
            ->add('fonction', TextType::class)
            ->add('Formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'titre',
                'placeholder' => 'Sélectionner une formation',
                'required' => false,
            ])
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participant);
            $em->flush();

            $this->addFlash('notice', 'Participant ajouté avec succès.');

            return $this->redirectToRoute('liste_participant');
        }

        return $this->render('participant/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
 * @Route("/participant/delete/{id}", name="participant_delete")
 */
public function delete($id): Response
{
    $participant = $this->getDoctrine()->getRepository(Participant::class)->find($id);

    if (!$participant) {
        throw $this->createNotFoundException('No participant found for this id: ' . $id);
    }

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($participant);
    $entityManager->flush();

    $this->addFlash('notice', 'Participant supprimé avec succès.');

    return $this->redirectToRoute('liste_participant');
}


/**
 * @Route("/editParticipant/{id}", name="edit_participant")
 * Method({"GET","POST"})
 */
public function editParticipant(Request $request, $id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $participant = $entityManager->getRepository(Participant::class)->find($id);

    if (!$participant) {
        throw $this->createNotFoundException('No Participant found for id ' . $id);
    }

    $form = $this->createFormBuilder($participant)
        ->add('nom', TextType::class)
        ->add('email', EmailType::class)
        ->add('is_subscribe', CheckboxType::class, [
            'label' => 'Abonné',
            'required' => false,
        ])
        ->add('fonction', TextType::class)
        ->add('Formation', EntityType::class, [
            'class' => Formation::class,
            'choice_label' => 'titre',
            'placeholder' => 'Sélectionner une formation',
            'required' => false,
        ])
        ->add('Valider', SubmitType::class)
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('liste_participant');
    }

    return $this->render('participant/ajouter.html.twig', [
        'form' => $form->createView()
    ]);
}



/**
 * @Route("/participant/{id}", name="participant_show")
 */
public function show($id, Request $request)
{
    $participant = $this->getDoctrine()
        ->getRepository(Participant::class)
        ->find($id);

    if (!$participant) {
        throw $this->createNotFoundException(
            'No Participant found for id ' . $id
        );
    }

    return $this->render('participant/show.html.twig', [
        'participant' => $participant,
    ]);
}

    
    
}
