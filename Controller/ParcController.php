<?php

namespace ParcsBundle\Controller;

use AppBundle\Entity\Parc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Parc controller.
 *
 * @Route("parc")
 */
class ParcController extends Controller
{
    /**
     * Lists all parc entities.
     *
     * @Route("/all", name="parc_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $parcs = $em->getRepository('AppBundle:Parc')->findBy(['etat'=>true]);

        return $this->render('parc/index.html.twig', array(
            'parcs' => $parcs,
        ));
    }
    /**
     * Lists all parc entities.
     *
     * @Route("/{adresse}", name="parc_By_Adresse")
     * @Method("GET")
     */
    public function filterByAddresseAction($adresse)
    {
        $em = $this->getDoctrine()->getManager();

        $parcs = $em->getRepository('AppBundle:Parc')->findBy(['adresse'=>$adresse,'etat'=>true]);

        return $this->render('parc/index.html.twig', array(
            'parcs' => $parcs,
        ));
    }

    /**
     * Creates a new parc entity.
     *
     * @Route("/addParc/new", name="parc_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $parc = new Parc();
        $form = $this->createForm('ParcsBundle\Form\ParcType', $parc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $parc->getImage();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $parc->setImage($fileName);
            $parc->setEtat(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($parc);
            $em->flush();
            return $this->redirectToRoute('parc_show', array('id' => $parc->getId()));
        }

        return $this->render('parc/new.html.twig', array(
            'parc' => $parc,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a parc entity.
     *
     * @Route("/{id}", name="parc_show")
     * @Method("GET")
     */
    public function showAction(Parc $parc)
    {
        $deleteForm = $this->createDeleteForm($parc);

        return $this->render('parc/show.html.twig', array(
            'parc' => $parc,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing parc entity.
     *
     * @Route("/{id}/edit", name="parc_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Parc $parc)
    {
        $deleteForm = $this->createDeleteForm($parc);
        $editForm = $this->createForm('ParcsBundle\Form\ParcType', $parc);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $file = $parc->getImage();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $parc->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parc_edit', array('id' => $parc->getId()));
        }

        return $this->render('parc/edit.html.twig', array(
            'parc' => $parc,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a parc entity.
     *
     * @Route("/{id}", name="parc_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Parc $parc)
    {
        $form = $this->createDeleteForm($parc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($parc);
            $em->flush();
        }

        return $this->redirectToRoute('parc_index');
    }

    /**
     * Creates a form to delete a parc entity.
     *
     * @param Parc $parc The parc entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Parc $parc)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parc_delete', array('id' => $parc->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
