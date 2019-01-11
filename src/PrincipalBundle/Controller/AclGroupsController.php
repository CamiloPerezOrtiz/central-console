<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\AclGroups;
use PrincipalBundle\Form\AclGroupsType;
use Symfony\Component\HttpFoundation\Session\Session;

class AclGroupsController extends Controller
{
	# Variables #
	private $session; 

	# Constructor # 
	public function __construct()
	{
		$this->session = new Session();
	}

	public function registroAclGroupsAction(Request $request)
	{
		$acl = new AclGroups();
		$form = $this ->createForm(AclGroupsType::class, $acl);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($acl);
			$flush=$em->flush();
			if($flush == null)
			{
				$estatus="Successfully registration.";
				$this->session->getFlashBag()->add("estatus",$estatus);
				return $this->redirectToRoute("listaUsuarios");
			}
			else
			{
				$estatus="Problems with the server try later.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		return $this->render('@Principal/acl/registroAclGroups.html.twig', array(
			'form'=>$form->createView()
		));
	}
}
