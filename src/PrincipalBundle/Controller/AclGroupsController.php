<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\AclGroups;
use PrincipalBundle\Form\AclGroupsType;

class AclGroupsController extends Controller
{
	public function registroAclGroupsAction(Request $request)
	{
		$acl = new AclGroups();
		$form = $this ->createForm(AclGroupsType::class, $acl);
		$form->handleRequest($request);
		if($form->isSubmitted())
		{
			dump($acl);
		}
		return $this->render('@Principal/acl/registroAclGroups.html.twig', array(
			'form'=>$form->createView()
		));
	}
}
