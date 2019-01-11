<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\Aliases;
use PrincipalBundle\Entity\AliasesTipo;
use PrincipalBundle\Form\AliasesType;
use Symfony\Component\HttpFoundation\Session\Session;

class AliasesController extends Controller
{
	# Variables #
	private $session; 

	# Constructor # 
	public function __construct()
	{
		$this->session = new Session();
	}

	# Funcion para registrar un nuevo aliases #
	public function registroAliasesAction(Request $request)
	{
		$alias = new Aliases();
		$form = $this ->createForm(AliasesType::class, $alias);
		$form->handleRequest($request);
		if($form->isSubmitted())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$verificarNombre = $this->recuperarNombreId($form->get("nombre")->getData());
			if(count($verificarNombre)==0)
			{
				$u = $this->getUser();
				$grupo=$u->getGrupo();
				$ip_port = implode(" ",$_POST['ip_port']);
				$descripcion_ip_port = implode(" ||",$_POST['descripcion_ip_port']);
				$alias->setId_aliases_tipo($_POST['id_aliases_tipo']);
				$alias->setIp_port($ip_port);
				$alias->setDescripcion_ip_port($descripcion_ip_port);
				$alias->setGrupo($grupo);
				$em->persist($alias);
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
			else
			{
				$estatus="The name of alias that you are trying to register already exists try again.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		return $this->render('@Principal/aliases/registroAliases.html.twig', array(
			'form'=>$form->createView(),
			"tipo"=>$tipo = $this->recuperarTipo()
		));
	}

	private function recuperarNombreId($nombre)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.nombre
				FROM PrincipalBundle:Aliases u
				WHERE  u.nombre = :nombre'
		)->setParameter('nombre', $nombre);
		$datos = $query->getResult();
		return $datos;
	}

	private function recuperarTipo()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre, u.valor
				FROM PrincipalBundle:AliasesTipo u'
		);
		$datos = $query->getResult();
		return $datos;
	}

	public function editarAliasesAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$alias = $em->getRepository("PrincipalBundle:Aliases")->find($id);
		$form = $this->createForm(AliasesType::class,$alias);
		$form->handleRequest($request);
		$recuperarDatosId = $this->recuperarDatosId($id);
		$ip_port= explode(' ',$recuperarDatosId[0]['ip_port']);
		$descripcion_ip_port = explode(" ||",$recuperarDatosId[0]['descripcion_ip_port']);
		if($form->isSubmitted() && $form->isValid())
		{
			$ip_port = implode(" ",$_POST['ip_port']);
			$descripcion_ip_port = implode(" ",$_POST['descripcion_ip_port']);
			$descripcion_ip_port = implode(" ||",$_POST['descripcion_ip_port']);
			$alias->setIp_port($ip_port);
			$alias->setDescripcion_ip_port($descripcion_ip_port);
			$em->persist($alias);
			$flush=$em->flush();
		}
		return $this->render("@Principal/aliases/editarAliases.html.twig",array(
			"form"=>$form->createView(),
			"ip_port"=>$ip_port,
			"descripcion_ip_port"=>$descripcion_ip_port,
			"tipo"=>$tipo = $this->recuperarTipo()
		));
	}

	private function recuperarDatosId($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.ip_port, u.descripcion_ip_port
				FROM PrincipalBundle:Aliases u
				WHERE  u.id = :id'
		)->setParameter('id', $id);
		$datos = $query->getResult();
		return $datos;
	}
}
