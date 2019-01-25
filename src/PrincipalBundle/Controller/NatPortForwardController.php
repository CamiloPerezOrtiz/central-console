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
		if($role == 'ROLE_SUPERUSER')
		{
			$id=$_REQUEST['id'];
			$interfaces = $this->recuperarInterfaces($id);
		}
		if($role == 'ROLE_ADMINISTRATOR')
			$interfaces = $this->recuperarInterfaces($u->getGrupo());
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			if($role == 'ROLE_SUPERUSER')
			{
				$id=$_REQUEST['id'];
				$nat->setGrupo($id);
			}
			if($role == 'ROLE_ADMINISTRATOR')
				$nat->setGrupo($u->getGrupo());
			$nat->setInterface($_POST['interface']);
			$em->persist($nat);
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
		return $this->render('@Principal/natPortForward/registroNatPortForward.html.twig', array(
			'form'=>$form->createView(),
			'interfaces'=>$interfaces
		));
	}

	# Funcion para recuperar nombre por id #
	private function recuperarInterfaces($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.nombre
				FROM PrincipalBundle:Interfaces u
				WHERE u.grupo = :grupo'
		)->setParameter('grupo', $grupo);
		$datos = $query->getResult();
		return $datos;
	}

	# Funcion para mostrar los grupos #
	public function gruposNatAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$grupos = $this->recuperarTodosGrupos();
				return $this->render("@Principal/natPortForward/gruposNat.html.twig", array(
					"grupo"=>$grupos
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR" or $role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("listaNat");
	        }
	    }
	}

	# Funcion para recuperar el todos los grupos #
	private function recuperarTodosGrupos()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT DISTINCT g.nombre
				FROM PrincipalBundle:Grupos g
				ORDER BY g.nombre ASC'
		);
		$grupos = $query->getResult();
		return $grupos;
	}

	# Funcion para mostrar los target #
	public function listaNatAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$id=$_REQUEST['id'];
				$nat = $this->recuperarTodoNatPortGrupo($id);
				return $this->render('@Principal/natPortForward/listaNat.html.twig', array(
					"nat"=>$nat
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR")
	        {
	        	$nat = $this->recuperarTodoNatPortGrupo($grupo);
				return $this->render('@Principal/natPortForward/listaTNat.html.twig', array(
					"nat"=>$nat
				));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$nat = $this->recuperarTodoNatPortGrupo($grupo);
				return $this->render('@Principal/natPortForward/listaNat.html.twig', array(
					"nat"=>$nat
				));
	        }
	    }
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarTodoNatPortGrupo($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.estatus, u.interface, u.protocolo, u.sourceAdvancedType, u.sourceAdvancedAdressMask, u.sourceAdvancedFromPort,
					u.sourceAdvancedCustom, u.sourceAdvancedCustomToPort, u.destinationType, u.destinationRangeFromPort, u.destinationRangeCustom,
					u.destinationRangeCustomToPort, u.redirectTargetIp, u.redirectTargetPort, u.redirectTargetPortCustom, u.descripcion
				FROM PrincipalBundle:NatPortForward u
				WHERE  u.grupo = :grupo'
		)->setParameter('grupo', $grupo);
		$target = $query->getResult();
		return $target;
	}
}
