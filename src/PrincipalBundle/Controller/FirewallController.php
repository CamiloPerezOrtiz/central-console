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
			$firewall->setSourceType($_POST['sourceAdvancedType']);
			$firewall->setDestinationType($_POST['destinationType']);
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
		$query = "SELECT interfaz, nombre, tipo FROM interfaces WHERE descripcion = '$ubicacion'";
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

	# Funcion para eliminar un target #
	public function eliminarFirewallWanAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:FirewallLan")->find($id);
		$em->remove($formato);
		$flush=$em->flush();
		if($flush == null)
			$estatus="Successfully delete registration";
		else
			$estatus="Problems with the server try later.";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("gruposFirewall");
	}

	public function crearXMLFirewallAction()
	{
		if(isset($_POST['enviar']))
		{
			foreach ($_POST['ip'] as $ips) 
			{
				$recuperarTodoDatos = $this->datos_firewall($ips);
				$contenido = "<?xml version='1.0'?>\n";
				$contenido .= "\t<filter>\n";
				foreach ($recuperarTodoDatos as $firewall) 
				{
				    $contenido .= "\t\t<rule>\n";
				    	$contenido .= "\t\t\t<id></id>\n";
				    	# Action #
				    	if($firewall['action'] === "pass")
							$contenido .= "\t\t\t<type>pass</type>\n";
						if($firewall['action'] === "block")
							$contenido .= "\t\t\t<type>block</type>\n";
						if($firewall['action'] === "reject")
							$contenido .= "\t\t\t<type>reject</type>\n";
						# Interface #
						if($firewall['interface'] === "wan")
							$contenido .= "\t\t\t<interface>wan</interface>\n";
						if($firewall['interface'] === "lan")
							$contenido .= "\t\t\t<interface>lan</interface>\n";
						if($firewall['interface'] === "opt1")
							$contenido .= "\t\t\t<interface>opt1</interface>\n";
						if($firewall['interface'] === "opt2")
							$contenido .= "\t\t\t<interface>opt2</interface>\n";
						if($firewall['interface'] === "opt3")
							$contenido .= "\t\t\t<interface>opt3</interface>\n";
						# Address Family #
						if($firewall['adress_family'] === "inet")
							$contenido .= "\t\t\t<ipprotocol>inet</ipprotocol>\n";
						if($firewall['adress_family'] === "inet6")
							$contenido .= "\t\t\t<ipprotocol>inet6</ipprotocol>\n";
						if($firewall['adress_family'] === "inet46")
							$contenido .= "\t\t\t<ipprotocol>inet46</ipprotocol>\n";
						$contenido .= "\t\t\t<tag></tag>\n";
						$contenido .= "\t\t\t<tagged></tagged>\n";
						$contenido .= "\t\t\t<max></max>\n";
						$contenido .= "\t\t\t<max-src-nodes></max-src-nodes>\n";
						$contenido .= "\t\t\t<max-src-conn></max-src-conn>\n";
						$contenido .= "\t\t\t<max-src-states></max-src-states>\n";
						$contenido .= "\t\t\t<statetimeout></statetimeout>\n";
						$contenido .= "\t\t\t<statetype>keep state</statetype>\n";
						$contenido .= "\t\t\t<os></os>\n";
						# Protocolos #
						if($firewall['protocolo'] === "tcp")
							$contenido .= "\t\t\t<protocol>tcp</protocol>\n";
						if($firewall['protocolo'] === "udp")
							$contenido .= "\t\t\t<protocol>udp</protocol>\n";
						if($firewall['protocolo'] === "tcp/udp")
							$contenido .= "\t\t\t<protocol>tcp/udp</protocol>\n";
						if($firewall['protocolo'] === "icmp")
							$contenido .= "\t\t\t<protocol>icmp</protocol>\n";
						# Source #
						$contenido .= "\t\t\t<source>\n";
							if($firewall['source_type'] === "any")
								$contenido .= "\t\t\t\t<any></any>\n";
							if($firewall['source_type'] === "single")
								$contenido .= "\t\t\t\t<address>" . $formatos['source_addres_mask'] . "</address>\n";
							if($firewall['source_type'] === "single")
							{
								if ($firewall['source_advanced_adress_mask1'] == 32) 
									$contenido .= "\t\t\t\t<address>" . $firewall['source_addres_mask'] . "</address>\n";
								else
									$contenido .= "\t\t\t\t<address>" . $firewall['source_addres_mask'] . "/" . $firewall['source_advanced_adress_mask1'] . "</address>\n";
							}
							if($firewall['source_type'] === "pppoe")
								$contenido .= "\t\t\t\t<address>pppoe</address>\n";
							if($firewall['source_type'] === "l2tp")
								$contenido .= "\t\t\t\t<address>l2tp</address>\n";
							if($firewall['source_type'] === "wan")
								$contenido .= "\t\t\t\t<address>wan</address>\n";
							if($firewall['source_type'] === "wanip")
								$contenido .= "\t\t\t\t<address>wanip</address>\n";
							if($firewall['source_type'] === "lan")
								$contenido .= "\t\t\t\t<address>lan</address>\n";
							if($firewall['source_type'] === "lanip")
								$contenido .= "\t\t\t\t<address>lanip</address>\n";
							if($firewall['source_type'] === "opt1")
								$contenido .= "\t\t\t\t<address>opt1</address>\n";
							if($firewall['source_type'] === "opt1ip")
								$contenido .= "\t\t\t\t<address>opt1ip</address>\n";
							if($firewall['source_type'] === "opt2")
								$contenido .= "\t\t\t\t<address>opt2</address>\n";
							if($firewall['source_type'] === "opt2ip")
								$contenido .= "\t\t\t\t<address>opt2ip</address>\n";
							if($firewall['source_type'] === "opt3")
								$contenido .= "\t\t\t\t<address>opt3</address>\n";
							if($firewall['source_type'] === "opt3ip")
								$contenido .= "\t\t\t\t<address>opt3ip</address>\n";
							# Invert match. #
							if($firewall['source_invert_match'] === true)
								$contenido .= "\t\t\t\t<not></not>\n";
							# Source Port Range #
							if($firewall['source_port_range_from'] === "")
							{
								if($firewall['source_port_range_custom'] === $firewall['source_port_range_custom_to'])
									$contenido .= "\t\t\t\t<port>" . $firewall['source_port_range_custom'] . "</port>\n";
								else
									$contenido .= "\t\t\t\t<port>" . $firewall['source_port_range_custom'] . "-" . $firewall['source_port_range_custom_to'] . "</port>\n";
								$contenido .= "\t\t\t\t<not></not>\n";
							}
							if($firewall['source_port_range_from'] === "5999")
								$contenido .= "\t\t\t\t<port>5999</port>\n";
							if($firewall['source_port_range_from'] === "53")
								$contenido .= "\t\t\t\t<port>53</port>\n";
							if($firewall['source_port_range_from'] === "21")
								$contenido .= "\t\t\t\t<port>21</port>\n";
							if($firewall['source_port_range_from'] === "3000")
								$contenido .= "\t\t\t\t<port>3000</port>\n";
							if($firewall['source_port_range_from'] === "80")
								$contenido .= "\t\t\t\t<port>80</port>\n";
							if($firewall['source_port_range_from'] === "443")
								$contenido .= "\t\t\t\t<port>443</port>\n";
							if($firewall['source_port_range_from'] === "5190")
								$contenido .= "\t\t\t\t<port>5190</port>\n";
							if($firewall['source_port_range_from'] === "113")
								$contenido .= "\t\t\t\t<port>113</port>\n";
							if($firewall['source_port_range_from'] === "993")
								$contenido .= "\t\t\t\t<port>993</port>\n";
							if($firewall['source_port_range_from'] === "4500")
								$contenido .= "\t\t\t\t<port>4500</port>\n";
							if($firewall['source_port_range_from'] === "500")
								$contenido .= "\t\t\t\t<port>500</port>\n";
							if($firewall['source_port_range_from'] === "1701")
								$contenido .= "\t\t\t\t<port>1701</port>\n";
							if($firewall['source_port_range_from'] === "389")
								$contenido .= "\t\t\t\t<port>389</port>\n";
							if($firewall['source_port_range_from'] === "1755")
								$contenido .= "\t\t\t\t<port>1755</port>\n";
							if($firewall['source_port_range_from'] === "7000")
								$contenido .= "\t\t\t\t<port>7000</port>\n";
							if($firewall['source_port_range_from'] === "445")
								$contenido .= "\t\t\t\t<port>445</port>\n";
							if($firewall['source_port_range_from'] === "3389")
								$contenido .= "\t\t\t\t<port>3389</port>\n";
							if($firewall['source_port_range_from'] === "1512")
								$contenido .= "\t\t\t\t<port>1512</port>\n";
							if($firewall['source_port_range_from'] === "1863")
								$contenido .= "\t\t\t\t<port>1863</port>\n";
							if($firewall['source_port_range_from'] === "119")
								$contenido .= "\t\t\t\t<port>119</port>\n";
							if($firewall['source_port_range_from'] === "123")
								$contenido .= "\t\t\t\t<port>123</port>\n";
							if($firewall['source_port_range_from'] === "138")
								$contenido .= "\t\t\t\t<port>138</port>\n";
							if($firewall['source_port_range_from'] === "137")
								$contenido .= "\t\t\t\t<port>137</port>\n";
							if($firewall['source_port_range_from'] === "139")
								$contenido .= "\t\t\t\t<port>139</port>\n";
							if($firewall['source_port_range_from'] === "1194")
								$contenido .= "\t\t\t\t<port>1194</port>\n";
							if($firewall['source_port_range_from'] === "110")
								$contenido .= "\t\t\t\t<port>110</port>\n";
							if($firewall['source_port_range_from'] === "995")
								$contenido .= "\t\t\t\t<port>995</port>\n";
							if($firewall['source_port_range_from'] === "1723")
								$contenido .= "\t\t\t\t<port>1723</port>\n";
							if($firewall['source_port_range_from'] === "1812")
								$contenido .= "\t\t\t\t<port>1812</port>\n";
							if($firewall['source_port_range_from'] === "1813")
								$contenido .= "\t\t\t\t<port>1813</port>\n";
							if($firewall['source_port_range_from'] === "5004")
								$contenido .= "\t\t\t\t<port>5004</port>\n";
							if($firewall['source_port_range_from'] === "5060")
								$contenido .= "\t\t\t\t<port>5060</port>\n";
							if($firewall['source_port_range_from'] === "25")
								$contenido .= "\t\t\t\t<port>25</port>\n";
							if($firewall['source_port_range_from'] === "465")
								$contenido .= "\t\t\t\t<port>465</port>\n";
							if($firewall['source_port_range_from'] === "161")
								$contenido .= "\t\t\t\t<port>161</port>\n";
							if($firewall['source_port_range_from'] === "162")
								$contenido .= "\t\t\t\t<port>162</port>\n";
							if($firewall['source_port_range_from'] === "22")
								$contenido .= "\t\t\t\t<port>22</port>\n";
							if($firewall['source_port_range_from'] === "3478")
								$contenido .= "\t\t\t\t<port>3278</port>\n";
							if($firewall['source_port_range_from'] === "587")
								$contenido .= "\t\t\t\t<port>587</port>\n";
							if($firewall['source_port_range_from'] === "3544")
								$contenido .= "\t\t\t\t<port>3544</port>\n";
							if($firewall['source_port_range_from'] === "23")
								$contenido .= "\t\t\t\t<port>23</port>\n";
							if($firewall['source_port_range_from'] === "69")
								$contenido .= "\t\t\t\t<port>69</port>\n";
							if($firewall['source_port_range_from'] === "5900")
								$contenido .= "\t\t\t\t<port>5900</port>\n";
						$contenido .= "\t\t\t</source>\n";
						$contenido .= "\t\t\t<destination>\n";
							if($firewall['destination_type'] === "any")
								$contenido .= "\t\t\t\t<any></any>\n";
							if($firewall['destination_type'] === "single")
								$contenido .= "\t\t\t\t<address>" . $firewall['destination_addres_mask'] . "</address>\n";
							if($firewall['destination_type'] === "single")
							{
								if ($firewall['destination_adress_mask2'] == 32) 
									$contenido .= "\t\t\t\t<address>" . $firewall['destination_addres_mask'] . "</address>\n";
								else
									$contenido .= "\t\t\t\t<address>" . $firewall['destination_addres_mask'] . "/" . $firewall['destination_adress_mask2'] . "</address>\n";
							}
							if($firewall['destination_type'] === "pppoe")
								$contenido .= "\t\t\t\t<address>pppoe</address>\n";
							if($firewall['destination_type'] === "l2tp")
								$contenido .= "\t\t\t\t<address>l2tp</address>\n";
							if($firewall['destination_type'] === "wan")
								$contenido .= "\t\t\t\t<address>wan</address>\n";
							if($firewall['destination_type'] === "wanip")
								$contenido .= "\t\t\t\t<address>wanip</address>\n";
							if($firewall['destination_type'] === "lan")
								$contenido .= "\t\t\t\t<address>lan</address>\n";
							if($firewall['destination_type'] === "lanip")
								$contenido .= "\t\t\t\t<address>lanip</address>\n";
							if($firewall['destination_type'] === "opt1")
								$contenido .= "\t\t\t\t<address>opt1</address>\n";
							if($firewall['destination_type'] === "opt1ip")
								$contenido .= "\t\t\t\t<address>opt1ip</address>\n";
							if($firewall['destination_type'] === "opt2")
								$contenido .= "\t\t\t\t<address>opt2</address>\n";
							if($firewall['destination_type'] === "opt2ip")
								$contenido .= "\t\t\t\t<address>opt2ip</address>\n";
							if($firewall['destination_type'] === "opt3")
								$contenido .= "\t\t\t\t<address>opt3</address>\n";
							if($firewall['destination_type'] === "opt3ip")
								$contenido .= "\t\t\t\t<address>opt3ip</address>\n";
							# Invert match. #
							if($firewall['destination_invert_match'] === true)
								$contenido .= "\t\t\t\t<not></not>\n";
							# Source Port Range #
							if($firewall['destination_port_range_from'] === "")
							{
								if($firewall['destination_port_range_custom'] === $firewall['destination_port_range_custom_to'])
									$contenido .= "\t\t\t\t<port>" . $firewall['destination_port_range_custom'] . "</port>\n";
								else
									$contenido .= "\t\t\t\t<port>" . $firewall['destination_port_range_custom'] . "-" . $firewall['destination_port_range_custom_to'] . "</port>\n";
								$contenido .= "\t\t\t\t<not></not>\n";
							}
							if($firewall['destination_port_range_from'] === "5999")
								$contenido .= "\t\t\t\t<port>5999</port>\n";
							if($firewall['destination_port_range_from'] === "53")
								$contenido .= "\t\t\t\t<port>53</port>\n";
							if($firewall['destination_port_range_from'] === "21")
								$contenido .= "\t\t\t\t<port>21</port>\n";
							if($firewall['destination_port_range_from'] === "3000")
								$contenido .= "\t\t\t\t<port>3000</port>\n";
							if($firewall['destination_port_range_from'] === "80")
								$contenido .= "\t\t\t\t<port>80</port>\n";
							if($firewall['destination_port_range_from'] === "443")
								$contenido .= "\t\t\t\t<port>443</port>\n";
							if($firewall['destination_port_range_from'] === "5190")
								$contenido .= "\t\t\t\t<port>5190</port>\n";
							if($firewall['destination_port_range_from'] === "113")
								$contenido .= "\t\t\t\t<port>113</port>\n";
							if($firewall['destination_port_range_from'] === "993")
								$contenido .= "\t\t\t\t<port>993</port>\n";
							if($firewall['destination_port_range_from'] === "4500")
								$contenido .= "\t\t\t\t<port>4500</port>\n";
							if($firewall['destination_port_range_from'] === "500")
								$contenido .= "\t\t\t\t<port>500</port>\n";
							if($firewall['destination_port_range_from'] === "1701")
								$contenido .= "\t\t\t\t<port>1701</port>\n";
							if($firewall['destination_port_range_from'] === "389")
								$contenido .= "\t\t\t\t<port>389</port>\n";
							if($firewall['destination_port_range_from'] === "1755")
								$contenido .= "\t\t\t\t<port>1755</port>\n";
							if($firewall['destination_port_range_from'] === "7000")
								$contenido .= "\t\t\t\t<port>7000</port>\n";
							if($firewall['destination_port_range_from'] === "445")
								$contenido .= "\t\t\t\t<port>445</port>\n";
							if($firewall['destination_port_range_from'] === "3389")
								$contenido .= "\t\t\t\t<port>3389</port>\n";
							if($firewall['destination_port_range_from'] === "1512")
								$contenido .= "\t\t\t\t<port>1512</port>\n";
							if($firewall['destination_port_range_from'] === "1863")
								$contenido .= "\t\t\t\t<port>1863</port>\n";
							if($firewall['destination_port_range_from'] === "119")
								$contenido .= "\t\t\t\t<port>119</port>\n";
							if($firewall['destination_port_range_from'] === "123")
								$contenido .= "\t\t\t\t<port>123</port>\n";
							if($firewall['destination_port_range_from'] === "138")
								$contenido .= "\t\t\t\t<port>138</port>\n";
							if($firewall['destination_port_range_from'] === "137")
								$contenido .= "\t\t\t\t<port>137</port>\n";
							if($firewall['destination_port_range_from'] === "139")
								$contenido .= "\t\t\t\t<port>139</port>\n";
							if($firewall['destination_port_range_from'] === "1194")
								$contenido .= "\t\t\t\t<port>1194</port>\n";
							if($firewall['destination_port_range_from'] === "110")
								$contenido .= "\t\t\t\t<port>110</port>\n";
							if($firewall['destination_port_range_from'] === "995")
								$contenido .= "\t\t\t\t<port>995</port>\n";
							if($firewall['destination_port_range_from'] === "1723")
								$contenido .= "\t\t\t\t<port>1723</port>\n";
							if($firewall['destination_port_range_from'] === "1812")
								$contenido .= "\t\t\t\t<port>1812</port>\n";
							if($firewall['destination_port_range_from'] === "1813")
								$contenido .= "\t\t\t\t<port>1813</port>\n";
							if($firewall['destination_port_range_from'] === "5004")
								$contenido .= "\t\t\t\t<port>5004</port>\n";
							if($firewall['destination_port_range_from'] === "5060")
								$contenido .= "\t\t\t\t<port>5060</port>\n";
							if($firewall['destination_port_range_from'] === "25")
								$contenido .= "\t\t\t\t<port>25</port>\n";
							if($firewall['destination_port_range_from'] === "465")
								$contenido .= "\t\t\t\t<port>465</port>\n";
							if($firewall['destination_port_range_from'] === "161")
								$contenido .= "\t\t\t\t<port>161</port>\n";
							if($firewall['destination_port_range_from'] === "162")
								$contenido .= "\t\t\t\t<port>162</port>\n";
							if($firewall['destination_port_range_from'] === "22")
								$contenido .= "\t\t\t\t<port>22</port>\n";
							if($firewall['destination_port_range_from'] === "3478")
								$contenido .= "\t\t\t\t<port>3278</port>\n";
							if($firewall['destination_port_range_from'] === "587")
								$contenido .= "\t\t\t\t<port>587</port>\n";
							if($firewall['destination_port_range_from'] === "3544")
								$contenido .= "\t\t\t\t<port>3544</port>\n";
							if($firewall['destination_port_range_from'] === "23")
								$contenido .= "\t\t\t\t<port>23</port>\n";
							if($firewall['destination_port_range_from'] === "69")
								$contenido .= "\t\t\t\t<port>69</port>\n";
							if($firewall['destination_port_range_from'] === "5900")
								$contenido .= "\t\t\t\t<port>5900</port>\n";
						$contenido .= "\t\t\t</destination>\n";
						if($firewall['estatus'] === true)
							$contenido .= "\t\t\t<disabled></disabled>\n";
						if($firewall['log'] === true)
							$contenido .= "\t\t\t<log></log>\n";
						$contenido .= "\t\t\t<descr>" . $firewall['descripcion'] . "</descr>\n";
				    $contenido .= "\t\t</rule>\n";
				}
				$contenido .= "\t</filter>";
				$archivo = fopen("$ips.xml", 'w');
				fwrite($archivo, $contenido);
				fclose($archivo);
				#change_to_do#
				$change_to_do = fopen("change_to_do.txt", 'w');
				fwrite($change_to_do,'firewallrules.py'."\n");
				fclose($change_to_do);
				$changetodo = "change_to_do.txt";
				# Mover el archivo a la carpeta #
				$archivoConfig = "$ips.xml";
				$serv = '/var/www/html/central-console/web/clients/Ejemplo_2/';
				$destinoConfig = $serv . $ips;
				$res = $destinoConfig . "/conf.xml";
			   	if (!copy($archivoConfig, $res)) 
				    echo "Error al copiar $res...\n";
			   	$destinoConfigchange_to_do = $destinoConfig . '/change_to_do.txt';
			   	copy($changetodo, $destinoConfigchange_to_do);
				unlink("$ips.xml");
				unlink("change_to_do.txt");
			}
			echo '<script> alert("The configuration has been saved.");</script>';
		}
		$id=$_REQUEST['id'];
		$grupos = $this->datos_grupos($id);
		return $this->render("@Principal/grupos/aplicarCambios.html.twig", array(
			"grupos"=>$grupos
		));
	}

	private function datos_grupos($grupo)
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

	# Funcion para recuperar los todos los aliases #
	private function datos_firewall($ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM firewall_lan WHERE ubicacion = '$ubicacion' ORDER BY posicion";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$nat=$stmt->fetchAll();
		return $nat;
	}
}
