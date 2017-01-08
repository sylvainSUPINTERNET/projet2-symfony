<?php

namespace MatiereBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MatiereBundle\Entity\Matieres;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;


class MatiereController extends Controller
{
    /**
     * @Route("/matiere",  name="matiere")
     */
    public function matiereIndexAction(Request $request)
    {
        var_dump($request->getPathInfo());
        var_dump("ici afficher la liste de toute les matières ainsi que les actions editer / supprimmer / ajouter");
        var_dump("Ajouter indépendant, supprimer / modifier attacher le bouton a chaque apparition d'une matière");
        var_dump("SECURITER !!! (si pas super admin alors la route redirect !");

        $allMatieres = $this->getDoctrine()
            ->getRepository('MatiereBundle:Matieres')
            ->findAll();


        return $this->render('MatiereBundle:Matiere:index.html.twig', array(
            'matieres' => $allMatieres,
        ));
    }

    /**
     * @Route("/matiere/add",  name="matiereAdd")
     */
    public function addMatiereAction(Request $request)
    {
        var_dump($request->getPathInfo());
        var_dump("ici afficher la liste de toute les matières ainsi que les actions editer / supprimmer / ajouter");
        var_dump("Ajouter indépendant, supprimer / modifier attacher le bouton a chaque apparition d'une matière");
        var_dump("SECURITER !!! (si pas super admin alors la route redirect !");


        $matiere = new Matieres();
        $formAdd = $this->createFormBuilder($matiere)
            ->add('name', TextType::class, array('label' => 'Nom de la matière : '))
            ->add('save', SubmitType::class, array('label' => 'Ajouter'))
            ->getForm();

        $formAdd->handleRequest($request);

        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $matiereToAdd = $formAdd->getData();

            $repository = $this->getDoctrine()->getRepository('MatiereBundle:Matieres');
            $em = $this->getDoctrine()->getManager();
            $em->persist($matiereToAdd);
            $em->flush();

            return $this->redirectToRoute('matiereAdd');
        }

        return $this->render('MatiereBundle:Matiere:ajouter.html.twig', array(
                'formAddMatiere' => $formAdd->createView(),
            )
        );

    }

    /**
     * @Get("/matiere/matiereDelete/{id}", name="deleteMatiere")
     */
    public function deleteMatiereAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $matiereToRemove = $this->getDoctrine()
            ->getRepository('MatiereBundle:Matieres')
            ->find($id);
        $em->remove($matiereToRemove);
        $em->flush();

        return $this->redirectToRoute('matiere');
    }


    /**
     * @Route("/matiere/matiereEdit/{id}",  name="matiereEdit")
     */
    public function editMatiereAction(Request $request, $id)
    {
        $message = ""; //success

        $matiere = new Matieres();
        $formEdit = $this->createFormBuilder($matiere)
            ->add('name', TextType::class, array('label' => 'Nouveau nom de la matière : '))
            ->add('save', SubmitType::class, array('label' => 'Modifier'))
            ->getForm();

        $formEdit->handleRequest($request);

        if ($formEdit->isSubmitted() && $formEdit->isValid()) {
            $matiereToEdit = $formEdit->getData();

            $repository = $this->getDoctrine()->getRepository('MatiereBundle:Matieres');
            $em = $this->getDoctrine()->getManager();


            $matiereForEdit = $this->getDoctrine()
                ->getRepository('MatiereBundle:Matieres')
                ->find($id);
            $matiereForEdit->setName($matiereToEdit->getName());
            $em->persist($matiereForEdit);
            $em->flush();


            //return $this->redirectToRoute('/matiere/matiereEdit/'.$id);
            $message = "Modification terminé";
        }

        return $this->render('MatiereBundle:Matiere:edit.html.twig', array(
                'formEditMatiere' => $formEdit->createView(),
                'successMessage' => $message,
            )
        );
    }

}
