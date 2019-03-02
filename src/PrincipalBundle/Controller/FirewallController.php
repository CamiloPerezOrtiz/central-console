<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use PrincipalBundle\Entity\FirewallLan;
use PrincipalBundle\Form\FirewallLanType;

class FirewallController extends Controller
{
	# Variables #
	private $session; 

	# Constructor # 
	public function __construct()
	{
		$this->session = new Session();
	}

	# Funcion para mostrar los grupos #
	public function gruposFirewallAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$grupos = $this->recuperarTodosGrupos();
				return $this->render("@Principal/firewall/gruposFirewall.html.twig", array(
					"grupo"=>$grupos
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR" or $role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("listaFirewall");
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

	# Funcion para registrar un nuevo aliases #
	public function registroFirewallWanAction(Request $request)
	{
		$firewall = new FirewallLan();
		$form = $this ->createForm(FirewallLanType::class, $firewall);
		$form->handleRequest($request);
		$u = $this->getUser();
		$role=$u->getRole();
		if($role == 'ROLE_SUPERUSER')
		{
			$id=$_REQUEST['id'];
			$firewall->setGrupo($id);
			$ipGrupos = $this->ipGrupos($id);
		}
		if($role == 'ROLE_ADMINISTRATOR')
		{
			$firewall->setGrupo($u->getGrupo());
			$id=$u->getGrupo();
			$ipGrupos = $this->ipGrupos($id);
		}
		if($form->isSubmitted() && $form->isValid())
		{
			$ubicacion = $_REQUEST['ubicacion'];
			$em = $this->getDoctrine()->getEntityManager();
			$mask = $_POST['mask'];
			if($mask == "32")
				$firewall->setSourceAddresMask($form->get("sourceAddresMask")->getData());
			elseif($mask <= "31")
			{
				$address = $form->get("sourceAddresMask")->getData();
				$adress_mask = $address . "/" . $mask; 
				$firewall->setSourceAddresMask($adress_mask);
			}
			$maskDestifirewallion = $_POST['maskDestifirewall'];
			if($maskDestifirewallion == "32")
				$firewall->setDestinationAddresMask($form->get("destinationAddresMask")->getData());
			elseif($maskDestifirewallion <= "31")
			{
				$address = $form->get("destinationAddresMask")->getData();
				$adress_mask = $address . "/" . $maskDestifirewallion; 
				$firewall->setDestinationAddresMask($adress_mask);
			}
			$firewall->setInterface($_POST['interface']);
			$firewall->setUbicacion($ubicacion);
			$em->persist($firewall);
			$flush=$em->flush();
			if($flush == null)
			{
				$estatus="Successfully registration.";
				$this->session->getFlashBag()->add("estatus",$estatus);
				return $this->redirectToRoute("gruposfirewall");
			}
			else
				echo '<script> alert("The name of alias that you are trying to register already exists try again.");window.history.go(-1);</script>';
		}
		if(isset($_POST['solicitar']))
		{
			$ubicacion = $_POST['ubicacion'];
			return $this->render('@Principal/firewall/registroFirewallWan.html.twig', array(
				'form'=>$form->createView(),
				'ubicacion'=>$ubicacion
			));
		}

		return $this->render('@Principal/firewall/solicitudFirewallWan.html.twig', array(
			'ipGrupos'=>$ipGrupos
		));
		
	}

	private function ipGrupos($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM grupos WHERE nombre = '$grupo'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$grupos=$stmt->fetchAll();
		return $grupos;
	}
}
