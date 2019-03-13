<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use PrincipalBundle\Entity\NatPortForward;
use PrincipalBundle\Entity\NatOneToOne;
use PrincipalBundle\Entity\Interfaces;
use PrincipalBundle\Form\NatPortForwardType;
use PrincipalBundle\Form\NatPortForwardEditType;
use PrincipalBundle\Form\NatOneToOneType;
use PrincipalBundle\Form\NatOneToOneEditType;
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
			$nat->setGrupo($id);
			$ipGrupos = $this->ipGrupos($id);
		}
		if($role == 'ROLE_ADMINISTRATOR')
		{
			$nat->setGrupo($u->getGrupo());
			$id=$u->getGrupo();
			$ipGrupos = $this->ipGrupos($id);
		}
		if($form->isSubmitted() && $form->isValid())
		{
			$ubicacion = $_REQUEST['ubicacion'];
			$em = $this->getDoctrine()->getEntityManager();
			//$mask = $_POST['mask'];
			/*if($mask == "32")
				$nat->setSourceAdvancedAdressMask($form->get("sourceAdvancedAdressMask")->getData());
			elseif($mask <= "31")
			{
				$address = $form->get("sourceAdvancedAdressMask")->getData();
				$adress_mask = $address . "/" . $mask; 
				$nat->setSourceAdvancedAdressMask($adress_mask);
			}*/
			/**/
			/*$maskDestination = $_POST['maskDestination'];
			if($maskDestination == "32")
				$nat->setdestinationAdressMask($form->get("destinationAdressMask")->getData());
			elseif($maskDestination <= "31")
			{
				$address = $form->get("destinationAdressMask")->getData();
				$adress_mask = $address . "/" . $maskDestination; 
				$nat->setdestinationAdressMask($adress_mask);
			}*/
			$nat->setInterface($_POST['interface']);
			$nat->setUbicacion($ubicacion);
			$em->persist($nat);
			$flush=$em->flush();
			if($flush == null)
			{
				$estatus="Successfully registration.";
				$this->session->getFlashBag()->add("estatus",$estatus);
				return $this->redirectToRoute("gruposNat");
			}
			else
				echo '<script> alert("The name of alias that you are trying to register already exists try again.");window.history.go(-1);</script>';
		}
		if(isset($_POST['solicitar']))
		{
			$ubicacion = $_POST['ubicacion'];
			return $this->render('@Principal/natPortForward/registroNatPortForward.html.twig', array(
				'form'=>$form->createView(),
				'ubicacion'=>$ubicacion
			));
		}

		return $this->render('@Principal/natPortForward/solicitudNatPort.html.twig', array(
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
		$role=$u->getRole();
		if($role == 'ROLE_SUPERUSER')
		{
			$grupo=$_REQUEST['id'];
			$ipGrupos = $this->ipGrupos($grupo);
		}
		if($role == 'ROLE_ADMINISTRATOR')
		{
			$grupo=$u->getGrupo();
			$ipGrupos = $this->ipGrupos($grupo);
		}
		if(isset($_POST['solicitar']))
		{
			#$u = $this->getUser();
			if($u != null)
			{
		        #$role=$u->getRole();
		        #$grupo=$u->getGrupo();
		        $ubicacion = $_REQUEST['ubicacion'];
		        if($role == "ROLE_SUPERUSER")
		        {
		        	#$id=$_REQUEST['id'];
					$nat = $this->recuperarTodoNatPortGrupo($grupo, $ubicacion);
					$natOne = $this->recuperarOneToOneGrupo($grupo, $ubicacion);
					return $this->render('@Principal/natPortForward/listaNat.html.twig', array(
						"nat"=>$nat,
						"natOne"=>$natOne
					));
		        }
		        if($role == "ROLE_ADMINISTRATOR")
		        {
		        	$nat = $this->recuperarTodoNatPortGrupo($grupo, $ubicacion);
		        	$natOne = $this->recuperarOneToOneGrupo($grupo, $ubicacion);
					return $this->render('@Principal/natPortForward/listaNat.html.twig', array(
						"nat"=>$nat,
						"natOne"=>$natOne
					));
		        }
		        if($role == "ROLE_USER")
		        {
		        	$nat = $this->recuperarTodoNatPortGrupo($grupo, $ubicacion);
		        	$natOne = $this->recuperarOneToOneGrupo($grupo, $ubicacion);
					return $this->render('@Principal/natPortForward/listaNat.html.twig', array(
						"nat"=>$nat,
						"natOne"=>$natOne
					));
		        }
		    }
		}

		return $this->render('@Principal/plantillas/solicitudGrupo.html.twig', array(
			'ipGrupos'=>$ipGrupos
		));
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarTodoNatPortGrupo($grupo, $ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM nat_port_forward WHERE grupo ='$grupo' AND ubicacion = '$ubicacion' ORDER BY posicion";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$nat=$stmt->fetchAll();
		return $nat;
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarOneToOneGrupo($grupo, $ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM nat_one_to_one WHERE grupo ='$grupo' AND ubicacion = '$ubicacion' ORDER BY posicion";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$nat=$stmt->fetchAll();
		return $nat;
	}

	public function editarNatPortForwardAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$nat = $em->getRepository("PrincipalBundle:NatPortForward")->find($id);
		$form = $this->createForm(NatPortForwardEditType::class,$nat);
		$u = $this->getUser();
		$role=$u->getRole();
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$nat->setInterface($_POST['interface']);
			$em->persist($nat);
			$mask = $_POST['mask'];
			if($mask == "32")
				$nat->setSourceAdvancedAdressMask($form->get("sourceAdvancedAdressMask")->getData());
			elseif($mask <= "31")
			{
				$address = $form->get("sourceAdvancedAdressMask")->getData();
				$adress_mask = $address . "/" . $mask; 
				$nat->setSourceAdvancedAdressMask($adress_mask);
			}
			$maskDestination = $_POST['maskDestination'];
			if($maskDestination == "32")
				$nat->setdestinationAdressMask($form->get("destinationAdressMask")->getData());
			elseif($maskDestination <= "31")
			{
				$address = $form->get("destinationAdressMask")->getData();
				$adress_mask = $address . "/" . $maskDestination; 
				$nat->setdestinationAdressMask($adress_mask);
			}
			$flush=$em->flush();
			return $this->redirectToRoute("gruposNat");
		}
		return $this->render('@Principal/natPortForward/editarNatPortForward.html.twig', array(
			'form'=>$form->createView()
		));
	}

	# Funcion para eliminar un target #
	public function eliminarNatPortForwardAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:NatPortForward")->find($id);
		$em->remove($formato);
		$flush=$em->flush();
		if($flush == null)
			$estatus="Successfully delete registration";
		else
			$estatus="Problems with the server try later.";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("gruposNat");
	}

	public function dragAndDropNatPortForwardAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$position = $_POST['position'];
		$i=1;
		foreach($position as $k=>$v){
		    $sql = "Update nat_port_forward SET posicion=".$i." WHERE id=".$v;
		    $stmt = $db->prepare($sql);
			$stmt->execute(array());
			$i++;
		}
		exit();
	}

	public function registroNatOneToOneAction(Request $request)
	{
		$nat = new NatOneToOne();
		$form = $this ->createForm(NatOneToOneType::class, $nat);
		$form->handleRequest($request);
		$u = $this->getUser();
		$role=$u->getRole();
		if($role == 'ROLE_SUPERUSER')
		{
			$id=$_REQUEST['id'];
			$nat->setGrupo($id);
			$ipGrupos = $this->ipGrupos($id);
		}
		if($role == 'ROLE_ADMINISTRATOR')
		{
			$nat->setGrupo($u->getGrupo());
			$id=$u->getGrupo();
			$ipGrupos = $this->ipGrupos($id);
		}
		if($form->isSubmitted() && $form->isValid())
		{
			$ubicacion = $_REQUEST['ubicacion'];
			$em = $this->getDoctrine()->getEntityManager();
			if($role == 'ROLE_SUPERUSER')
			{
				$id=$_REQUEST['id'];
				$nat->setGrupo($id);
			}
			if($role == 'ROLE_ADMINISTRATOR')
				$nat->setGrupo($u->getGrupo());
			$nat->setInterface($_POST['interface']);
			$nat->setUbicacion($ubicacion);
			$em->persist($nat);
			$flush=$em->flush();
			if($flush == null)
			{
				$estatus="Successfully registration.";
				$this->session->getFlashBag()->add("estatus",$estatus);
				return $this->redirectToRoute("gruposNat");
			}
			else
				echo '<script> alert("The name of alias that you are trying to register already exists try again.");window.history.go(-1);</script>';
		}
		if(isset($_POST['solicitar']))
		{
			$ubicacion = $_POST['ubicacion'];
			return $this->render('@Principal/natPortForward/registroNatOneToOne.html.twig', array(
				'form'=>$form->createView(),
				'ubicacion'=>$ubicacion
			));
		}
		return $this->render('@Principal/natPortForward/solicitudNatOne.html.twig', array(
			'ipGrupos'=>$ipGrupos
		));
	}

	public function editarNatOneToOneAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$nat = $em->getRepository("PrincipalBundle:NatOneToOne")->find($id);
		$form = $this->createForm(NatOneToOneEditType::class,$nat);
		$u = $this->getUser();
		$role=$u->getRole();
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$nat->setInterface($_POST['interface']);
			$em->persist($nat);
			$flush=$em->flush();
			return $this->redirectToRoute("gruposTarget");
		}
		return $this->render('@Principal/natPortForward/editarNatOneToOne.html.twig', array(
			'form'=>$form->createView()
		));
	}

	# Funcion para eliminar un target #
	public function eliminarNatOneToOneAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:NatOneToOne")->find($id);
		$em->remove($formato);
		$flush=$em->flush();
		if($flush == null)
			$estatus="Successfully delete registration";
		else
			$estatus="Problems with the server try later.";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("gruposNat");
	}

	public function dragAndDropNatOneToOneAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$position = $_POST['position'];
		$i=1;
		foreach($position as $k=>$v){
		    $sql = "Update nat_one_to_one SET posicion=".$i." WHERE id=".$v;
		    $stmt = $db->prepare($sql);
			$stmt->execute(array());
			$i++;
		}
		exit();
	}

	# Funcion para crear el XML de target #
	public function crearXMLNatAction()
	{
		if(isset($_POST['enviar']))
		{
			//foreach ($_POST['ip'] as $ips) 
			//{
				$ip = $_POST['ip'];
				$recuperarTodoDatos = $this->recuperarTodoNatPortGrupo2($ip);
				$recuperarTodoDatosOneToOne = $this->recuperarOneToOneGrupo2($ip);
				$contenido = "<?xml version='1.0'?>\n";
				$contenido .= "\t<nat>\n";
				foreach ($recuperarTodoDatos as $nat) 
				{
				    $contenido .= "\t\t<rule>\n";
				    if($nat['estatus'] === true)
						$contenido .= "\t\t\t<disabled></disabled>\n";
					$contenido .= "\t\t\t<source>\n";
						# Campo Source type #
						if($nat['source_advanced_type'] === "any")
							$contenido .= "\t\t\t\t<any></any>\n";
						if($nat['source_advanced_type'] === "single")
							$contenido .= "\t\t\t\t<address>" . $nat['source_advanced_adress_mask'] . "</address>\n";
						if($nat['source_advanced_type'] === "network")
							if ($nat['source_advanced_adress_mask1'] == 32) 
								$contenido .= "\t\t\t\t<network>" . $nat['source_advanced_adress_mask'] . "</network>\n";
							else
								$contenido .= "\t\t\t\t<network>" . $nat['source_advanced_adress_mask'] . "/" . $nat['source_advanced_adress_mask1'] . "</network>\n";
						if($nat['source_advanced_type'] === "pppoe")
							$contenido .= "\t\t\t\t<network>pppoe</network>\n";
						if($nat['source_advanced_type'] === "l2tp")
							$contenido .= "\t\t\t\t<network>pppoe</network>\n";
						if($nat['source_advanced_type'] === "wan")
							$contenido .= "\t\t\t\t<network>wan</network>\n";
						if($nat['source_advanced_type'] === "wanip")
							$contenido .= "\t\t\t\t<network>wanip</network>\n";
						if($nat['source_advanced_type'] === "lan")
							$contenido .= "\t\t\t\t<network>lan</network>\n";
						if($nat['source_advanced_type'] === "lanip")
							$contenido .= "\t\t\t\t<network>lanip</network>\n";
						# Campo Source Invert match. #
						if($nat['source_advanced_invert_match'] === true)
							$contenido .= "\t\t\t\t<not></not>\n";
						# Campo Source port range cuando los dos campos Custom son iguales. #
						if($nat['protocolo'] === "tcp" or $nat['protocolo'] === "udp" or $nat['protocolo'] === "tcp/udp" )
						{
							if($nat['source_advanced_from_port'] === "")
							{
								if($nat['source_advanced_custom'] === $nat['source_advanced_custom_to_port'])
									$contenido .= "\t\t\t\t<port>" . $nat['source_advanced_custom'] . "</port>\n";
								else
									$contenido .= "\t\t\t\t<port>" . $nat['source_advanced_custom'] . "-" . $nat['source_advanced_custom_to_port'] . "</port>\n";
							}
							if($nat['source_advanced_from_port'] === "5999")
								$contenido .= "\t\t\t\t<port>5999</port>\n";
							if($nat['source_advanced_from_port'] === "53")
								$contenido .= "\t\t\t\t<port>53</port>\n";
							if($nat['source_advanced_from_port'] === "21")
								$contenido .= "\t\t\t\t<port>21</port>\n";
							if($nat['source_advanced_from_port'] === "3000")
								$contenido .= "\t\t\t\t<port>3000</port>\n";
							if($nat['source_advanced_from_port'] === "80")
								$contenido .= "\t\t\t\t<port>80</port>\n";
							if($nat['source_advanced_from_port'] === "443")
								$contenido .= "\t\t\t\t<port>443</port>\n";
							if($nat['source_advanced_from_port'] === "5190")
								$contenido .= "\t\t\t\t<port>5190</port>\n";
							if($nat['source_advanced_from_port'] === "113")
								$contenido .= "\t\t\t\t<port>113</port>\n";
							if($nat['source_advanced_from_port'] === "993")
								$contenido .= "\t\t\t\t<port>993</port>\n";
							if($nat['source_advanced_from_port'] === "4500")
								$contenido .= "\t\t\t\t<port>4500</port>\n";
							if($nat['source_advanced_from_port'] === "500")
								$contenido .= "\t\t\t\t<port>500</port>\n";
							if($nat['source_advanced_from_port'] === "1701")
								$contenido .= "\t\t\t\t<port>1701</port>\n";
							if($nat['source_advanced_from_port'] === "389")
								$contenido .= "\t\t\t\t<port>389</port>\n";
							if($nat['source_advanced_from_port'] === "1755")
								$contenido .= "\t\t\t\t<port>1755</port>\n";
							if($nat['source_advanced_from_port'] === "7000")
								$contenido .= "\t\t\t\t<port>7000</port>\n";
							if($nat['source_advanced_from_port'] === "445")
								$contenido .= "\t\t\t\t<port>445</port>\n";
							if($nat['source_advanced_from_port'] === "3389")
								$contenido .= "\t\t\t\t<port>3389</port>\n";
							if($nat['source_advanced_from_port'] === "1512")
								$contenido .= "\t\t\t\t<port>1512</port>\n";
							if($nat['source_advanced_from_port'] === "1863")
								$contenido .= "\t\t\t\t<port>1863</port>\n";
							if($nat['source_advanced_from_port'] === "119")
								$contenido .= "\t\t\t\t<port>119</port>\n";
							if($nat['source_advanced_from_port'] === "123")
								$contenido .= "\t\t\t\t<port>123</port>\n";
							if($nat['source_advanced_from_port'] === "138")
								$contenido .= "\t\t\t\t<port>138</port>\n";
							if($nat['source_advanced_from_port'] === "137")
								$contenido .= "\t\t\t\t<port>137</port>\n";
							if($nat['source_advanced_from_port'] === "139")
								$contenido .= "\t\t\t\t<port>139</port>\n";
							if($nat['source_advanced_from_port'] === "1194")
								$contenido .= "\t\t\t\t<port>1194</port>\n";
							if($nat['source_advanced_from_port'] === "110")
								$contenido .= "\t\t\t\t<port>110</port>\n";
							if($nat['source_advanced_from_port'] === "995")
								$contenido .= "\t\t\t\t<port>995</port>\n";
							if($nat['source_advanced_from_port'] === "1723")
								$contenido .= "\t\t\t\t<port>1723</port>\n";
							if($nat['source_advanced_from_port'] === "1812")
								$contenido .= "\t\t\t\t<port>1812</port>\n";
							if($nat['source_advanced_from_port'] === "1813")
								$contenido .= "\t\t\t\t<port>1813</port>\n";
							if($nat['source_advanced_from_port'] === "5004")
								$contenido .= "\t\t\t\t<port>5004</port>\n";
							if($nat['source_advanced_from_port'] === "5060")
								$contenido .= "\t\t\t\t<port>5060</port>\n";
							if($nat['source_advanced_from_port'] === "25")
								$contenido .= "\t\t\t\t<port>25</port>\n";
							if($nat['source_advanced_from_port'] === "465")
								$contenido .= "\t\t\t\t<port>465</port>\n";
							if($nat['source_advanced_from_port'] === "161")
								$contenido .= "\t\t\t\t<port>161</port>\n";
							if($nat['source_advanced_from_port'] === "162")
								$contenido .= "\t\t\t\t<port>162</port>\n";
							if($nat['source_advanced_from_port'] === "22")
								$contenido .= "\t\t\t\t<port>22</port>\n";
							if($nat['source_advanced_from_port'] === "3478")
								$contenido .= "\t\t\t\t<port>3278</port>\n";
							if($nat['source_advanced_from_port'] === "587")
								$contenido .= "\t\t\t\t<port>587</port>\n";
							if($nat['source_advanced_from_port'] === "3544")
								$contenido .= "\t\t\t\t<port>3544</port>\n";
							if($nat['source_advanced_from_port'] === "23")
								$contenido .= "\t\t\t\t<port>23</port>\n";
							if($nat['source_advanced_from_port'] === "69")
								$contenido .= "\t\t\t\t<port>69</port>\n";
							if($nat['source_advanced_from_port'] === "5900")
								$contenido .= "\t\t\t\t<port>5900</port>\n";				
						}
					$contenido .= "\t\t\t</source>\n";
					$contenido .= "\t\t\t<destination>\n";
						# Campo destination type #
						if($nat['destination_type'] === "any")
							$contenido .= "\t\t\t\t<any></any>\n";
						if($nat['destination_type'] === "single")
							$contenido .= "\t\t\t\t<address>" . $nat['destination_adress_mask'] . "</address>\n";
						if($nat['destination_type'] === "network")
							if ($nat['destination_adress_mask2'] == 32) 
								$contenido .= "\t\t\t\t<network>" . $nat['destination_adress_mask'] . "</network>\n";
							else
								$contenido .= "\t\t\t\t<network>" . $nat['destination_adress_mask'] . "/" . $nat['destination_adress_mask2'] . "</network>\n";
						if($nat['destination_type'] === "pppoe")
							$contenido .= "\t\t\t\t<network>pppoe</network>\n";
						if($nat['destination_type'] === "l2tp")
							$contenido .= "\t\t\t\t<network>pppoe</network>\n";
						if($nat['destination_type'] === "wan")
							$contenido .= "\t\t\t\t<network>wan</network>\n";
						if($nat['destination_type'] === "wanip")
							$contenido .= "\t\t\t\t<network>wanip</network>\n";
						if($nat['destination_type'] === "lan")
							$contenido .= "\t\t\t\t<network>lan</network>\n";
						if($nat['destination_type'] === "lanip")
							$contenido .= "\t\t\t\t<network>lanip</network>\n";
						# Campo destination Invert match. #
						if($nat['destination_invert_match'] === true)
						{
							$contenido .= "\t\t\t\t<not></not>\n";
						}
						# Campo destination port range cuando los dos campos Custom son iguales. #
						if($nat['protocolo'] === "tcp" or $nat['protocolo'] === "udp" or $nat['protocolo'] === "tcp/udp" )
						{
							if($nat['destination_range_from_port'] === "")
							{
								if($nat['destination_range_custom'] === $nat['destination_range_custom_to_port'])
									$contenido .= "\t\t\t\t<port>" . $nat['destination_range_custom'] . "</port>\n";
								else
									$contenido .= "\t\t\t\t<port>" . $nat['destination_range_custom'] . "-" . $nat['destination_range_custom_to_port'] . "</port>\n";
							}

							if($nat['destination_range_from_port'] === "5999")
								$contenido .= "\t\t\t\t<port>5999</port>\n";
							if($nat['destination_range_from_port'] === "53")
								$contenido .= "\t\t\t\t<port>53</port>\n";
							if($nat['destination_range_from_port'] === "21")
								$contenido .= "\t\t\t\t<port>21</port>\n";
							if($nat['destination_range_from_port'] === "3000")
								$contenido .= "\t\t\t\t<port>3000</port>\n";
							if($nat['destination_range_from_port'] === "80")
								$contenido .= "\t\t\t\t<port>80</port>\n";
							if($nat['destination_range_from_port'] === "443")
								$contenido .= "\t\t\t\t<port>443</port>\n";
							if($nat['destination_range_from_port'] === "5190")
								$contenido .= "\t\t\t\t<port>5190</port>\n";
							if($nat['destination_range_from_port'] === "113")
								$contenido .= "\t\t\t\t<port>113</port>\n";
							if($nat['destination_range_from_port'] === "993")
								$contenido .= "\t\t\t\t<port>993</port>\n";
							if($nat['destination_range_from_port'] === "4500")
								$contenido .= "\t\t\t\t<port>4500</port>\n";
							if($nat['destination_range_from_port'] === "500")
								$contenido .= "\t\t\t\t<port>500</port>\n";
							if($nat['destination_range_from_port'] === "1701")
								$contenido .= "\t\t\t\t<port>1701</port>\n";
							if($nat['destination_range_from_port'] === "389")
								$contenido .= "\t\t\t\t<port>389</port>\n";
							if($nat['destination_range_from_port'] === "1755")
								$contenido .= "\t\t\t\t<port>1755</port>\n";
							if($nat['destination_range_from_port'] === "7000")
								$contenido .= "\t\t\t\t<port>7000</port>\n";
							if($nat['destination_range_from_port'] === "445")
								$contenido .= "\t\t\t\t<port>445</port>\n";
							if($nat['destination_range_from_port'] === "3389")
								$contenido .= "\t\t\t\t<port>3389</port>\n";
							if($nat['destination_range_from_port'] === "1512")
								$contenido .= "\t\t\t\t<port>1512</port>\n";
							if($nat['destination_range_from_port'] === "1863")
								$contenido .= "\t\t\t\t<port>1863</port>\n";
							if($nat['destination_range_from_port'] === "119")
								$contenido .= "\t\t\t\t<port>119</port>\n";
							if($nat['destination_range_from_port'] === "123")
								$contenido .= "\t\t\t\t<port>123</port>\n";
							if($nat['destination_range_from_port'] === "138")
								$contenido .= "\t\t\t\t<port>138</port>\n";
							if($nat['destination_range_from_port'] === "137")
								$contenido .= "\t\t\t\t<port>137</port>\n";
							if($nat['destination_range_from_port'] === "139")
								$contenido .= "\t\t\t\t<port>139</port>\n";
							if($nat['destination_range_from_port'] === "1194")
								$contenido .= "\t\t\t\t<port>1194</port>\n";
							if($nat['destination_range_from_port'] === "110")
								$contenido .= "\t\t\t\t<port>110</port>\n";
							if($nat['destination_range_from_port'] === "995")
								$contenido .= "\t\t\t\t<port>995</port>\n";
							if($nat['destination_range_from_port'] === "1723")
								$contenido .= "\t\t\t\t<port>1723</port>\n";
							if($nat['destination_range_from_port'] === "1812")
								$contenido .= "\t\t\t\t<port>1812</port>\n";
							if($nat['destination_range_from_port'] === "1813")
								$contenido .= "\t\t\t\t<port>1813</port>\n";
							if($nat['destination_range_from_port'] === "5004")
								$contenido .= "\t\t\t\t<port>5004</port>\n";
							if($nat['destination_range_from_port'] === "5060")
								$contenido .= "\t\t\t\t<port>5060</port>\n";
							if($nat['destination_range_from_port'] === "25")
								$contenido .= "\t\t\t\t<port>25</port>\n";
							if($nat['destination_range_from_port'] === "465")
								$contenido .= "\t\t\t\t<port>465</port>\n";
							if($nat['destination_range_from_port'] === "161")
								$contenido .= "\t\t\t\t<port>161</port>\n";
							if($nat['destination_range_from_port'] === "162")
								$contenido .= "\t\t\t\t<port>162</port>\n";
							if($nat['destination_range_from_port'] === "22")
								$contenido .= "\t\t\t\t<port>22</port>\n";
							if($nat['destination_range_from_port'] === "3478")
								$contenido .= "\t\t\t\t<port>3278</port>\n";
							if($nat['destination_range_from_port'] === "587")
								$contenido .= "\t\t\t\t<port>587</port>\n";
							if($nat['destination_range_from_port'] === "3544")
								$contenido .= "\t\t\t\t<port>3544</port>\n";
							if($nat['destination_range_from_port'] === "23")
								$contenido .= "\t\t\t\t<port>23</port>\n";
							if($nat['destination_range_from_port'] === "69")
								$contenido .= "\t\t\t\t<port>69</port>\n";
							if($nat['destination_range_from_port'] === "5900")
								$contenido .= "\t\t\t\t<port>5900</port>\n";
						}
					$contenido .= "\t\t\t</destination>\n";
					$contenido .= "\t\t\t<protocol>" . $nat['protocolo'] . "</protocol>\n";
					# Interface #
				    $contenido .= "\t\t\t<interface>" . $nat['interface'] . "</interface>\n";
				    # Description #
				    $contenido .= "\t\t\t<descr>" . $nat['descripcion'] . "</descr>\n";
				    # Filter rule association #
				    if($nat['filter_rule_association'] === "")
				    	$contenido .= "\t\t\t<associated-rule-id></associated-rule-id>\n";
					# NAT reflection #
					if($nat['nat_reflection'] === "enable")
						$contenido .= "\t\t\t<natreflection>enable</natreflection>\n";
					if($nat['nat_reflection'] === "purenat")
						$contenido .= "\t\t\t<natreflection>purenat</natreflection>\n";
					if($nat['nat_reflection'] === "disable")
						$contenido .= "\t\t\t<natreflection>disable</natreflection>\n";
				    $contenido .= "\t\t</rule>\n";
				}
				# Nat one to one #
				foreach ($recuperarTodoDatosOneToOne as $natone) 
				{
					$contenido .= "\t\t<onetoone>\n";
					# Disabled #
					if($natone['estatus'] === true)
						$contenido .= "\t\t\t<disabled></disabled>\n";
					# External subnet IP #
					$contenido .= "\t\t\t<external>" . $natone['external_subnet_ip'] . "</external>\n";
					# Description #
					$contenido .= "\t\t\t<descr>" . $natone['descripcion'] . "</descr>\n";
					# Interface #
					$contenido .= "\t\t\t<interface>" . $natone['interface'] . "</interface>\n";
					# Internal IP #
					$contenido .= "\t\t\t<source>\n";
						# Type Address/mask #
						if($natone['internal_ip_type'] === "any")
							$contenido .= "\t\t\t\t<any></any>\n";
						if($natone['internal_ip_type'] === "single")
							$contenido .= "\t\t\t\t<address>" . $natone['internal_adress_mask'] . "</address>\n";
						if($natone['internal_ip_type'] === "network")
							$contenido .= "\t\t\t\t<address>" . $natone['internal_adress_mask'] . "</address>\n";
						if($natone['internal_ip_type'] === "pppoe")
							$contenido .= "\t\t\t\t<network>pppoe</network>\n";
						if($natone['internal_ip_type'] === "l2tp")
							$contenido .= "\t\t\t\t<network>l2tp</network>\n";
						if($natone['internal_ip_type'] === "wan")
							$contenido .= "\t\t\t\t<network>wan</network>\n";
						if($natone['internal_ip_type'] === "wanip")
							$contenido .= "\t\t\t\t<network>wanip</network>\n";
						if($natone['internal_ip_type'] === "lan")
							$contenido .= "\t\t\t\t<network>lan</network>\n";
						if($natone['internal_ip_type'] === "lanip")
							$contenido .= "\t\t\t\t<network>lanip</network>\n";
						# Not Invert the sense of the match #
						if($natone['internal_ip'] === true)
							$contenido .= "\t\t\t\t<not></not>\n";
					$contenido .= "\t\t\t</source>\n";
					# Destination #
					$contenido .= "\t\t\t<destination>\n";
						# Type Address/mask #
						if($natone['destination_type'] === "any")
							$contenido .= "\t\t\t\t<any></any>\n";
						if($natone['destination_type'] === "single")
							$contenido .= "\t\t\t\t<address>" . $natone['destination_adress_mask'] . "</address>\n";
						if($natone['destination_type'] === "network")
							$contenido .= "\t\t\t\t<address>" . $natone['destination_adress_mask'] . "</address>\n";
						if($natone['destination_type'] === "pppoe")
							$contenido .= "\t\t\t\t<network>pppoe</network>\n";
						if($natone['destination_type'] === "l2tp")
							$contenido .= "\t\t\t\t<network>l2tp</network>\n";
						if($natone['destination_type'] === "wan")
							$contenido .= "\t\t\t\t<network>wan</network>\n";
						if($natone['destination_type'] === "wanip")
							$contenido .= "\t\t\t\t<network>wanip</network>\n";
						if($natone['destination_type'] === "lan")
							$contenido .= "\t\t\t\t<network>lan</network>\n";
						if($natone['destination_type'] === "lanip")
							$contenido .= "\t\t\t\t<network>lanip</network>\n";
						# Not Invert the sense of the match #
						if($natone['destination'] === true)
							$contenido .= "\t\t\t\t<not></not>\n";
					$contenido .= "\t\t\t</destination>\n";
					$contenido .= "\t\t</onetoone>\n";
				}
				$contenido .= "\t</nat>";
				$archivo = fopen("conf.xml", 'w');
				fwrite($archivo, $contenido);
				fclose($archivo);
				#ips_to_change#
				$ips_to_change = fopen("ips_to_change.txt", 'w');
				$em = $this->getDoctrine()->getEntityManager();
			    $db = $em->getConnection();
				$query = "SELECT primer_octeto, segundo_octeto, tercer_octeto, cuarto_octeto FROM grupos WHERE descripcion = '$ip'";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$grupos=$stmt->fetchAll();
				fwrite($ips_to_change,$grupos[0]['primer_octeto'].".".$grupos[0]['segundo_octeto'].".".$grupos[0]['tercer_octeto'].".".$grupos[0]['cuarto_octeto']."\n");
				fclose($ips_to_change);
				$ipstochange = "ips_to_change.txt";
				#change_to_do#
				$change_to_do = fopen("change_to_do.txt", 'w');
				fwrite($change_to_do,'nat.py'."\n");
				fclose($change_to_do);
				$changetodo = "change_to_do.txt";
				# Mover el archivo a la carpeta #
				$archivoConfig = "conf.xml";
				$serv = '/var/www/html/central-console/web/Groups/';
				//$destinoConfig = $serv . "/" . $id . "/" . $conf . "/conf.xml";
				$destinoConfig = '/var/www/html/central-console/web/clients/UI/conf.xml';
			   	if (!copy($archivoConfig, $destinoConfig)) 
				    echo "Error al copiar $archivoConfig...\n";
				$destinoConfigips_to_change = '/var/www/html/central-console/web/clients/UI/ips_to_change.txt';
			   	copy($ipstochange, $destinoConfigips_to_change);
			   	$destinoConfigchange_to_do = '/var/www/html/central-console/web/clients/UI/change_to_do.txt';
			   	copy($changetodo, $destinoConfigchange_to_do);
				unlink("conf.xml");
				unlink("ips_to_change.txt");
				unlink("change_to_do.txt");
			//}
			echo '<script> alert("The configuration has been saved.");</script>';
		}
		$id=$_REQUEST['id'];
		$grupos = $this->ipGruposSolo($id);
		return $this->render("@Principal/natPortForward/aplicarCambios.html.twig", array(
			"grupos"=>$grupos
		));
	}

	private function ipGruposSolo($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM grupos WHERE descripcion = '$grupo'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$grupos=$stmt->fetchAll();
		return $grupos;
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarTodoNatPortGrupo2($ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM nat_port_forward WHERE ubicacion = '$ubicacion' ORDER BY posicion";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$nat=$stmt->fetchAll();
		return $nat;
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarOneToOneGrupo2($ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM nat_one_to_one WHERE ubicacion = '$ubicacion' ORDER BY posicion";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$nat=$stmt->fetchAll();
		return $nat;
	}
}
