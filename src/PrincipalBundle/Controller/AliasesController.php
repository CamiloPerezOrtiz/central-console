<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\Aliases;
use PrincipalBundle\Entity\AliasesDescripcion;
use PrincipalBundle\Form\AliasesType;

class AliasesController extends Controller
{
	public function registroAliasesAction(Request $request)
	{
		$alias = new Aliases();
		$form = $this ->createForm(AliasesType::class, $alias);
		$form->handleRequest($request);
		if($form->isSubmitted())
		{
			dump($alias);
		}
		return $this->render('@Principal/aliases/registroAliases.html.twig', array(
			'form'=>$form->createView()
		));
	}
}
