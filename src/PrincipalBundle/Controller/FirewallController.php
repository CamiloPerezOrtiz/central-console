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
	        	return $this->redirectToRoute("listaFirewallWan");
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
			$icm = $_POST['icmp_subtypes'];
			$firewall->setInterface($_POST['interface']);
			$firewall->setIcmSubtypes(implode(",",$icm));
			$firewall->setUbicacion($ubicacion);
			$em->persist($firewall);
			$flush=$em->flush();
			if($flush == null)
			{
				$estatus="Successfully registration.";
				$this->session->getFlashBag()->add("estatus",$estatus);
				return $this->redirectToRoute("gruposFirewall");
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

	# Funcion para mostrar los target #
	public function listaFirewallWanAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$id=$_REQUEST['id'];
				$firewallWan = $this->recuperarTodoFirewallWanPortGrupo($id);
				#$natOne = $this->recuperarOneToOneGrupo($id);
				return $this->render('@Principal/firewall/listaFirewall.html.twig', array(
					"firewallWan"=>$firewallWan,
					#"natOne"=>$natOne
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR")
	        {
	        	$firewallWan = $this->recuperarTodoFirewallWanPortGrupo($grupo);
	        	#$natOne = $this->recuperarOneToOneGrupo($grupo);
				return $this->render('@Principal/firewall/listaFirewall.html.twig', array(
					"firewallWan"=>$firewallWan,
					#"natOne"=>$natOne
				));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$firewallWan = $this->recuperarTodoFirewallWanPortGrupo($grupo);
	        	#$natOne = $this->recuperarOneToOneGrupo($grupo);
				return $this->render('@Principal/firewall/listaFirewall.html.twig', array(
					"firewallWan"=>$firewallWan,
					#"natOne"=>$natOne
				));
	        }
	    }
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarTodoFirewallWanPortGrupo($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM firewall_lan WHERE grupo ='$grupo' ORDER BY posicion";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$nat=$stmt->fetchAll();
		return $nat;
	}
}
