<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\NatPortForward;
use PrincipalBundle\Entity\Interfaces;
use PrincipalBundle\Form\NatPortForwardType;
use Symfony\Component\HttpFoundation\Session\Session;

class NatPortForwardController extends Controller
{
	# Variables #
	private $session; 

	# Constructor # 
	public function __construct()
	{
		$this->session = new Session();
	}

	# Funcion para registrar un nuevo aliases #
	public function registroNatPortForwardAction(Request $request)
	{
		$nat = new NatPortForward();
		$form = $this ->createForm(NatPortForwardType::class, $nat);
		$form->handleRequest($request);
		$u = $this->getUser();
		$role=$u->getRole();
		$interfaces = $this->recuperarInterfaces();
		$protocolo = $this->recuperarProtocolo();
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$verificarNombre = $this->recuperarNombreId($form->get("nombre")->getData());
			if(count($verificarNombre)==0)
			{
				if($role == 'ROLE_SUPERUSER')
					$acl->setGrupo($id);
				if($role == 'ROLE_ADMINISTRATOR')
					$acl->setGrupo($u->getGrupo());
				$array_target_rule = $_POST['target_rule'];
				if(count(array_unique($array_target_rule)) === 1)
				{
					$acl->setTargetRule("all [ all]");
				 	$acl->setTargetRulesList("all [ all]");
				}
				else
				{
					$target_rule = implode(" ",$_POST['target_rule']);
					$acl->setTargetRule($target_rule." all [ all]");
					$acl->setTargetRulesList($target_rule);
				}
				$em->persist($acl);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="Successfully registration.";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("gruposAcl");
				}
				else
				{
					$estatus="Problems with the server try later.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
			}
			else
			{
				$estatus="The name of alias that you are trying to register already exists try again.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		return $this->render('@Principal/natPortForward/registroNatPortForward.html.twig', array(
			'form'=>$form->createView(),
			'interfaces'=>$interfaces,
			'protocolo'=>$protocolo
		));
	}

	# Funcion para recuperar nombre por id #
	private function recuperarInterfaces()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre
				FROM PrincipalBundle:Interfaces u'
		);
		$datos = $query->getResult();
		return $datos;
	}

	# Funcion para recuperar nombre por id #
	private function recuperarProtocolo()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre, u.valor
				FROM PrincipalBundle:Protocolo u'
		);
		$datos = $query->getResult();
		return $datos;
	}
}
