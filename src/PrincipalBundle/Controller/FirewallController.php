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
	        	return $this->redirectToRoute("listaFirewallWan");
	    }
	}

	# Funcion para recuperar el todos los grupos #
	private function recuperarTodosGrupos()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT DISTINCT g.nombre FROM PrincipalBundle:Grupos g ORDER BY g.nombre ASC');
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
		$ubicacion=$_REQUEST['id'];
		$interfaces_equipo = $this->informacion_interfaces($ubicacion);
		$grupo=$u->getGrupo();
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$ubicacion = $_REQUEST['ubicacion'];
			$firewall->setGrupo($grupo);
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
		return $this->render('@Principal/firewall/registroFirewallWan.html.twig', array(
			'form'=>$form->createView(),
			'ubicacion'=>$ubicacion,
			'interfaces_equipo'=>$interfaces_equipo
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

	private function informacion_interfaces($ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM interfaces WHERE descripcion = '$ubicacion'";
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
		$role=$u->getRole();
		if($role == 'ROLE_SUPERUSER')
		{
			$grupo=$_REQUEST['id'];
			$ipGrupos = $this->ipGrupos($grupo);
		}
		else
		{
			$grupo=$u->getGrupo();
			$ipGrupos = $this->ipGrupos($grupo);
		}
		if(isset($_POST['solicitar']))
		{
			$ubicacion = $_POST['ubicacion'];
			$recuperar_interfaces = $this->recuperar_interfaces($ubicacion);
			return $this->render('@Principal/firewall/solicitudFirewallWan.html.twig', array(
				'recuperar_interfaces'=>$recuperar_interfaces,
				'ubicacion'=>$ubicacion,
			));
		}
		if(isset($_POST['solicitar_interfaz']))
		{
			$interfaz = $_POST['interfaz'];
			$ubicacion = $_POST['ubicacion'];
			$interfaces_equipo = $this->informacion_interfaces($ubicacion);
			if($u != null)
			{
		        $ubicacion = $_REQUEST['ubicacion'];
		        if($role == "ROLE_SUPERUSER")
		        {
					$firewallWan = $this->recuperarTodoFirewallWanPortGrupo($grupo, $ubicacion, $interfaz);
					#$natOne = $this->recuperarOneToOneGrupo($id);
					return $this->render('@Principal/firewall/listaFirewall.html.twig', array(
						"firewallWan"=>$firewallWan,
						'ubicacion'=>$ubicacion,
						'interfaces_equipo'=>$interfaces_equipo,
						'interfaz'=>$interfaz,
						#"natOne"=>$natOne
					));
		        }
		        if($role == "ROLE_ADMINISTRATOR")
		        {
		        	$firewallWan = $this->recuperarTodoFirewallWanPortGrupo($grupo, $ubicacion, $interfaz);
		        	#$natOne = $this->recuperarOneToOneGrupo($grupo);
					return $this->render('@Principal/firewall/listaFirewall.html.twig', array(
						"firewallWan"=>$firewallWan,
						'ubicacion'=>$ubicacion,
						'interfaces_equipo'=>$interfaces_equipo,
						'interfaz'=>$interfaz,
						#"natOne"=>$natOne
					));
		        }
		        if($role == "ROLE_USER")
		        {
		        	$firewallWan = $this->recuperarTodoFirewallWanPortGrupo($grupo, $ubicacion, $interfaz);
		        	#$natOne = $this->recuperarOneToOneGrupo($grupo);
					return $this->render('@Principal/firewall/listaFirewall.html.twig', array(
						"firewallWan"=>$firewallWan,
						#"natOne"=>$natOn,
						'ubicacion'=>$ubicacion,
						'interfaces_equipo'=>$interfaces_equipo,
						'interfaz'=>$interfaz,
					));
		        }
		    }
		}
		return $this->render('@Principal/plantillas/solicitudGrupo.html.twig', array(
			'ipGrupos'=>$ipGrupos
		));
	}

	private function recuperar_interfaces($ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT interfaz, nombre FROM interfaces WHERE descripcion = '$ubicacion'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$nat=$stmt->fetchAll();
		return $nat;
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarTodoFirewallWanPortGrupo($grupo, $ubicacion, $interfaz)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM firewall_lan WHERE grupo ='$grupo' AND ubicacion = '$ubicacion' AND interface = '$interfaz'  ORDER BY posicion";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$nat=$stmt->fetchAll();
		return $nat;
	}

	public function dragAndDropFirewallWanAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$position = $_POST['position'];
		$i=1;
		foreach($position as $k=>$v){
		    $sql = "Update firewall_lan SET posicion=".$i." WHERE id=".$v;
		    $stmt = $db->prepare($sql);
			$stmt->execute(array());
			$i++;
		}
		exit();
	}
}
