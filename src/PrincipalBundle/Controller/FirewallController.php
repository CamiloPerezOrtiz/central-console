<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class FirewallController extends Controller
{
	// Se declara la variable session 
	private $session;

	// Se realiza el constructor 
	public function __construct()
	{
		$this->session = new Session();
	}

	public function listGroupFirewallAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
			$em = $this->getDoctrine()->getEntityManager();
	        $db = $em->getConnection();
	        $role=$u->getRole();
	        if($role == "ROLE_SUPERUSER")
	        {
				$querySelect = "SELECT DISTINCT nombre FROM grupos ORDER BY nombre ASC";
				$stmtSelect = $db->prepare($querySelect);
				$paramsSelect =array();
				$stmtSelect->execute($paramsSelect);
				$listaGrupo=$stmtSelect->fetchAll();
				return $this->render("@Principal/firewall/listGroup.html.twig", array("grupo"=>$listaGrupo));
	        }
	        if($role == "ROLE_ADMIN")
	        	return $this->redirectToRoute("listFirewall");
	        if($role == "ROLE_USER")
	        	return $this->redirectToRoute("listFirewall");
	    }
		return $this->redirectToRoute("dashboard");
	}

	// Funcion para listar las acl groups del sistema 
    public function listFirewallSuperUserAction($id)
    {
		$u = $this->getUser();
		if($u != null)
		{
			//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
			$em = $this->getDoctrine()->getEntityManager();
	        $db = $em->getConnection();

	        $role=$u->getRole();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$query = "SELECT * FROM firewallwan WHERE namegroup = '$id' ORDER BY position_order";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$acl=$stmt->fetchAll();
				//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$queryOne = "SELECT * FROM firewalllan WHERE namegroup = '$id' ORDER BY position_order";
				$stmtOne = $db->prepare($queryOne);
				$paramsOne =array();
				$stmtOne->execute($paramsOne);
				$aclOne=$stmtOne->fetchAll();
				return $this->render("@Principal/firewall/listFirewall.html.twig", array("acls"=>$acl,"aclsOne"=>$aclOne));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }

    // Funcion para listar las acl groups del sistema 
    public function listFirewallAction()
    {
    	$authenticationUtils = $this->get("security.authentication_utils");
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		$u = $this->getUser();
		if($u != null)
		{
			//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
			$em = $this->getDoctrine()->getEntityManager();
	        $db = $em->getConnection();

	        $role=$u->getRole();
	        if($role == "ROLE_ADMIN")
	        {
	        	$grupo=$u->getNameGroup();
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$query = "SELECT * FROM firewallwan where namegroup ='$grupo' ORDER BY position_order";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$acl=$stmt->fetchAll();
				//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$queryOne = "SELECT * FROM firewalllan where namegroup ='$grupo' ORDER BY position_order";
				$stmtOne = $db->prepare($queryOne);
				$paramsOne =array();
				$stmtOne->execute($paramsOne);
				$aclOne=$stmtOne->fetchAll();
				return $this->render("@Principal/firewall/listFirewall.html.twig", array("acls"=>$acl,"aclsOne"=>$aclOne));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$grupo=$u->getNameGroup();
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$query = "SELECT * FROM firewallwan where namegroup ='$grupo' ORDER BY position_order";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$acl=$stmt->fetchAll();
				//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$queryOne = "SELECT * FROM firewalllan where namegroup ='$grupo' ORDER BY position_order";
				$stmtOne = $db->prepare($queryOne);
				$paramsOne =array();
				$stmtOne->execute($paramsOne);
				$aclOne=$stmtOne->fetchAll();
				return $this->render("@Principal/firewall/listFirewall.html.twig", array("acls"=>$acl,"aclsOne"=>$aclOne));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }

    public function registerFirewallSuperUserAction(Request $request,$id)
	{
        if(isset($_POST['enviar']))
		{
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$type = $_POST['type'];
			$disabled = $_POST['disabled'];
			$interface = $_POST['interface'];
			$ipprotocol = $_POST['ipprotocol'];
			$proto = $_POST['proto'];
			$res = $_POST['icmptype'];
			$icmptype = implode(",",$res);
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$srcbeginport = $_POST['srcbeginport'];
			$srcbeginport_cust = $_POST['srcbeginport_cust'];
			$srcendport = $_POST['srcendport'];
			$srcendport_cust = $_POST['srcendport_cust'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$dstbeginport = $_POST['dstbeginport'];
			$dstbeginport_cust = $_POST['dstbeginport_cust'];
			$dstendport = $_POST['dstendport'];
			$dstendport_cust = $_POST['dstendport_cust'];
			$log = $_POST['log'];
			$descr = $_POST['descr'];
			$gateway = $_POST['gateway'];
			//$position_order = $_POST['position_order'];
			$query = "INSERT INTO firewallwan(type, disabled, interface, ipprotocol, proto, icmptype, srcnot, srctype, src, srcmask, srcbeginport, srcbeginport_cust, srcendport, srcendport_cust, dstnot, dsttype, dst, dstmask, dstbeginport, dstbeginport_cust, dstendport, dstendport_cust, log, descr, gateway, namegroup) VALUES ('$type','$disabled','$interface', '$ipprotocol', '$proto', '$icmptype', '$srcnot', '$srctype', '$src', '$srcmask', '$srcbeginport', '$srcbeginport_cust','$srcendport','$srcendport_cust', '$dstnot', '$dsttype', '$dst', '$dstmask', '$dstbeginport', '$dstbeginport_cust', '$dstendport', '$dstendport_cust', '$log', '$descr', '$gateway','$id')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupFirewall");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/firewall/registerFirewall.html.twig");
	}

	public function registerFirewallWanAction()
	{
        if(isset($_POST['enviar']))
		{
			$u = $this->getUser();
			$grupo=$u->getNameGroup();
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$type = $_POST['type'];
			$disabled = $_POST['disabled'];
			$interface = $_POST['interface'];
			$ipprotocol = $_POST['ipprotocol'];
			$proto = $_POST['proto'];
			$res = $_POST['icmptype'];
			$icmptype = implode(",",$res);
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$srcbeginport = $_POST['srcbeginport'];
			$srcbeginport_cust = $_POST['srcbeginport_cust'];
			$srcendport = $_POST['srcendport'];
			$srcendport_cust = $_POST['srcendport_cust'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$dstbeginport = $_POST['dstbeginport'];
			$dstbeginport_cust = $_POST['dstbeginport_cust'];
			$dstendport = $_POST['dstendport'];
			$dstendport_cust = $_POST['dstendport_cust'];
			$log = $_POST['log'];
			$descr = $_POST['descr'];
			$gateway = $_POST['gateway'];
			//$position_order = $_POST['position_order'];
			$query = "INSERT INTO firewallwan(type, disabled, interface, ipprotocol, proto, icmptype, srcnot, srctype, src, srcmask, srcbeginport, srcbeginport_cust, srcendport, srcendport_cust, dstnot, dsttype, dst, dstmask, dstbeginport, dstbeginport_cust, dstendport, dstendport_cust, log, descr, gateway, namegroup) VALUES ('$type','$disabled','$interface', '$ipprotocol', '$proto', '$icmptype', '$srcnot', '$srctype', '$src', '$srcmask', '$srcbeginport', '$srcbeginport_cust','$srcendport','$srcendport_cust', '$dstnot', '$dsttype', '$dst', '$dstmask', '$dstbeginport', '$dstbeginport_cust', '$dstendport', '$dstendport_cust', '$log', '$descr', '$gateway','$grupo')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupFirewall");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/firewall/registerFirewall.html.twig");
	}

	public function refreshFirewallWanAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$position = $_POST['position'];
		$i=1;
		foreach($position as $k=>$v){
		    $sql = "Update firewallwan SET position_order=".$i." WHERE id=".$v;
		    $stmt = $db->prepare($sql);
			$stmt->execute(array());
			$i++;
		}
		exit();
	}

	public function deleteFirewallWanAction($id)
	{
		// Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$query = "DELETE FROM firewallwan WHERE id = '$id'";
		$stmt = $db->prepare($query);
		$stmt->execute(array());
		// Se validad si la accion de borrar se cumplio
		if($stmt == null)
		{
			// Se notifica al actor que la eliminacion fue correcta 
			$estatus="Problems with the server try later.";
			$this->session->getFlashBag()->add("estatus",$estatus);
		}
		// De lo contrario se notifica al actor
		else
		{
			$estatus="Registry successfully deleted";
			$this->session->getFlashBag()->add("estatus",$estatus);
		}
		// Se redirecciona al listado
		return $this->redirectToRoute("listGroupFirewall");
	}

	public function editFirewallWanAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$querySelect = "SELECT * FROM firewallwan WHERE id = '$id'";
		$stmtSelect = $db->prepare($querySelect);
		$paramsSelect =array();
		$stmtSelect->execute($paramsSelect);
		$listaGrupo=$stmtSelect->fetchAll();
		foreach ($listaGrupo as $value) 
		{
			$array= explode(',',$value['icmptype']);
		}
		if(isset($_POST['enviar']))
		{
			$type = $_POST['type'];
			$disabled = $_POST['disabled'];
			$interface = $_POST['interface'];
			$ipprotocol = $_POST['ipprotocol'];
			$proto = $_POST['proto'];
			$res = $_POST['icmptype'];
			$icmptype = implode(",",$res);
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$srcbeginport = $_POST['srcbeginport'];
			$srcbeginport_cust = $_POST['srcbeginport_cust'];
			$srcendport = $_POST['srcendport'];
			$srcendport_cust = $_POST['srcendport_cust'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$dstbeginport = $_POST['dstbeginport'];
			$dstbeginport_cust = $_POST['dstbeginport_cust'];
			$dstendport = $_POST['dstendport'];
			$dstendport_cust = $_POST['dstendport_cust'];
			$log = $_POST['log'];
			$descr = $_POST['descr'];
			$gateway = $_POST['gateway'];
			$query = "UPDATE firewallwan SET type= '$type', disabled = '$disabled', interface = '$interface', ipprotocol = '$ipprotocol', proto = '$proto', icmptype = '$icmptype', srcnot = '$srcnot', srctype = '$srctype', src = '$src', srcmask = '$srcmask', srcbeginport = '$srcbeginport', srcbeginport_cust = '$srcbeginport_cust', srcendport = '$srcendport', srcendport_cust = '$srcendport_cust', dstnot = '$dstnot', dsttype = '$dsttype', dst = '$dst', dstmask = '$dstmask', dstbeginport = '$dstbeginport', dstbeginport_cust = '$dstbeginport_cust', dstendport = '$dstendport', dstendport_cust = '$dstendport_cust', log = '$log', descr = '$descr', gateway = '$gateway' WHERE id = '$id'";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupFirewall");
		}
		return $this->render("@Principal/firewall/editFirewall.html.twig", array("value"=>$listaGrupo,"value2"=>$array));
	}

	public function registerFirewallLanSuperUserAction(Request $request,$id)
	{
        if(isset($_POST['enviar']))
		{
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$type = $_POST['type'];
			$disabled = $_POST['disabled'];
			$interface = $_POST['interface'];
			$ipprotocol = $_POST['ipprotocol'];
			$proto = $_POST['proto'];
			$res = $_POST['icmptype'];
			$icmptype = implode(",",$res);
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$srcbeginport = $_POST['srcbeginport'];
			$srcbeginport_cust = $_POST['srcbeginport_cust'];
			$srcendport = $_POST['srcendport'];
			$srcendport_cust = $_POST['srcendport_cust'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$dstbeginport = $_POST['dstbeginport'];
			$dstbeginport_cust = $_POST['dstbeginport_cust'];
			$dstendport = $_POST['dstendport'];
			$dstendport_cust = $_POST['dstendport_cust'];
			$log = $_POST['log'];
			$descr = $_POST['descr'];
			$gateway = $_POST['gateway'];
			//$position_order = $_POST['position_order'];
			$query = "INSERT INTO firewalllan(type, disabled, interface, ipprotocol, proto, icmptype, srcnot, srctype, src, srcmask, srcbeginport, srcbeginport_cust, srcendport, srcendport_cust, dstnot, dsttype, dst, dstmask, dstbeginport, dstbeginport_cust, dstendport, dstendport_cust, log, descr, gateway, namegroup) VALUES ('$type','$disabled','$interface', '$ipprotocol', '$proto', '$icmptype', '$srcnot', '$srctype', '$src', '$srcmask', '$srcbeginport', '$srcbeginport_cust','$srcendport','$srcendport_cust', '$dstnot', '$dsttype', '$dst', '$dstmask', '$dstbeginport', '$dstbeginport_cust', '$dstendport', '$dstendport_cust', '$log', '$descr', '$gateway','$id')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupFirewall");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/firewall/registerFirewallLan.html.twig");
	}

	public function registerFirewallLanAction()
	{
        if(isset($_POST['enviar']))
		{
			$u = $this->getUser();
			$grupo=$u->getNameGroup();
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$type = $_POST['type'];
			$disabled = $_POST['disabled'];
			$interface = $_POST['interface'];
			$ipprotocol = $_POST['ipprotocol'];
			$proto = $_POST['proto'];
			$res = $_POST['icmptype'];
			$icmptype = implode(",",$res);
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$srcbeginport = $_POST['srcbeginport'];
			$srcbeginport_cust = $_POST['srcbeginport_cust'];
			$srcendport = $_POST['srcendport'];
			$srcendport_cust = $_POST['srcendport_cust'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$dstbeginport = $_POST['dstbeginport'];
			$dstbeginport_cust = $_POST['dstbeginport_cust'];
			$dstendport = $_POST['dstendport'];
			$dstendport_cust = $_POST['dstendport_cust'];
			$log = $_POST['log'];
			$descr = $_POST['descr'];
			$gateway = $_POST['gateway'];
			//$position_order = $_POST['position_order'];
			$query = "INSERT INTO firewalllan(type, disabled, interface, ipprotocol, proto, icmptype, srcnot, srctype, src, srcmask, srcbeginport, srcbeginport_cust, srcendport, srcendport_cust, dstnot, dsttype, dst, dstmask, dstbeginport, dstbeginport_cust, dstendport, dstendport_cust, log, descr, gateway, namegroup) VALUES ('$type','$disabled','$interface', '$ipprotocol', '$proto', '$icmptype', '$srcnot', '$srctype', '$src', '$srcmask', '$srcbeginport', '$srcbeginport_cust','$srcendport','$srcendport_cust', '$dstnot', '$dsttype', '$dst', '$dstmask', '$dstbeginport', '$dstbeginport_cust', '$dstendport', '$dstendport_cust', '$log', '$descr', '$gateway','$grupo')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupFirewall");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/firewall/registerFirewall.html.twig");
	}

	public function refreshFirewallLanAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$position = $_POST['position'];
		$i=1;
		foreach($position as $k=>$v){
		    $sql = "Update firewalllan SET position_order=".$i." WHERE id=".$v;
		    $stmt = $db->prepare($sql);
			$stmt->execute(array());
			$i++;
		}
		exit();
	}

	public function deleteFirewallLanAction($id)
	{
		// Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$query = "DELETE FROM firewalllan WHERE id = '$id'";
		$stmt = $db->prepare($query);
		$stmt->execute(array());
		// Se validad si la accion de borrar se cumplio
		if($stmt == null)
		{
			// Se notifica al actor que la eliminacion fue correcta 
			$estatus="Problems with the server try later.";
			$this->session->getFlashBag()->add("estatus",$estatus);
		}
		// De lo contrario se notifica al actor
		else
		{
			$estatus="Registry successfully deleted";
			$this->session->getFlashBag()->add("estatus",$estatus);
		}
		// Se redirecciona al listado
		return $this->redirectToRoute("listGroupFirewall");
	}

	public function editFirewallLanAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$querySelect = "SELECT * FROM firewalllan WHERE id = '$id'";
		$stmtSelect = $db->prepare($querySelect);
		$paramsSelect =array();
		$stmtSelect->execute($paramsSelect);
		$listaGrupo=$stmtSelect->fetchAll();
		foreach ($listaGrupo as $value) 
		{
			$array= explode(',',$value['icmptype']);
		}
		if(isset($_POST['enviar']))
		{
			$type = $_POST['type'];
			$disabled = $_POST['disabled'];
			$interface = $_POST['interface'];
			$ipprotocol = $_POST['ipprotocol'];
			$proto = $_POST['proto'];
			$res = $_POST['icmptype'];
			$icmptype = implode(",",$res);
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$srcbeginport = $_POST['srcbeginport'];
			$srcbeginport_cust = $_POST['srcbeginport_cust'];
			$srcendport = $_POST['srcendport'];
			$srcendport_cust = $_POST['srcendport_cust'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$dstbeginport = $_POST['dstbeginport'];
			$dstbeginport_cust = $_POST['dstbeginport_cust'];
			$dstendport = $_POST['dstendport'];
			$dstendport_cust = $_POST['dstendport_cust'];
			$log = $_POST['log'];
			$descr = $_POST['descr'];
			$gateway = $_POST['gateway'];
			$query = "UPDATE firewalllan SET type= '$type', disabled = '$disabled', interface = '$interface', ipprotocol = '$ipprotocol', proto = '$proto', icmptype = '$icmptype', srcnot = '$srcnot', srctype = '$srctype', src = '$src', srcmask = '$srcmask', srcbeginport = '$srcbeginport', srcbeginport_cust = '$srcbeginport_cust', srcendport = '$srcendport', srcendport_cust = '$srcendport_cust', dstnot = '$dstnot', dsttype = '$dsttype', dst = '$dst', dstmask = '$dstmask', dstbeginport = '$dstbeginport', dstbeginport_cust = '$dstbeginport_cust', dstendport = '$dstendport', dstendport_cust = '$dstendport_cust', log = '$log', descr = '$descr', gateway = '$gateway' WHERE id = '$id'";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupFirewall");
		}
		return $this->render("@Principal/firewall/editFirewall.html.twig", array("value"=>$listaGrupo,"value2"=>$array));
	}

	public function createXMLFirewallAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();
    	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
		$query = "SELECT * FROM firewallwan WHERE namegroup = '$id' ORDER BY position_order";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		// Se alamacena la consulta en una variable 
		$formato=$stmt->fetchAll();
		// Se crea un nuevo documento XML con la version 
	    $contenido = "<?xml version='1.0'?>\n";
	    // Se crear el nombre de la etiqueta
		$contenido .= "\t<filter>\n";
		// Se realiza un ciclo para llenar las demas etiquetas del archivo xml 
		foreach ($formato as $formatos) 
		{
			$contenido .= "\t\t<rule>\n";
				$contenido .= "\t\t\t<id></id>\n";
				# Action #
				if($formatos['type'] === "pass")
				{
					$contenido .= "\t\t\t<type>pass</type>\n";
				}
				if($formatos['type'] === "block")
				{
					$contenido .= "\t\t\t<type>block</type>\n";
				}
				if($formatos['type'] === "reject")
				{
					$contenido .= "\t\t\t<type>reject</type>\n";
				}
				# Interface #
				if($formatos['interface'] === "wan")
				{
					$contenido .= "\t\t\t<interface>wan</interface>\n";
				}
				if($formatos['interface'] === "lan")
				{
					$contenido .= "\t\t\t<interface>lan</interface>\n";
				}
				# Address Family #
				if($formatos['ipprotocol'] === "inet")
				{
					$contenido .= "\t\t\t<ipprotocol>inet</ipprotocol>\n";
				}
				if($formatos['ipprotocol'] === "inet6")
				{
					$contenido .= "\t\t\t<ipprotocol>inet6</ipprotocol>\n";
				}
				if($formatos['ipprotocol'] === "Reject")
				{
					$contenido .= "\t\t\t<ipprotocol>inet46</ipprotocol>\n";
				}
				$contenido .= "\t\t\t<tag></tag>\n";
				$contenido .= "\t\t\t<tagged></tagged>\n";
				$contenido .= "\t\t\t<max></max>\n";
				$contenido .= "\t\t\t<max-src-nodes></max-src-nodes>\n";
				$contenido .= "\t\t\t<max-src-conn></max-src-conn>\n";
				$contenido .= "\t\t\t<max-src-states></max-src-states>\n";
				$contenido .= "\t\t\t<statetimeout></statetimeout>\n";
				$contenido .= "\t\t\t<statetype>keep state</statetype>\n";
				$contenido .= "\t\t\t<os></os>\n";
				if($formatos['proto'] === "tcp")
				{
					$contenido .= "\t\t\t<protocol>tcp</protocol>\n";
				}
				if($formatos['proto'] === "udp")
				{
					$contenido .= "\t\t\t<protocol>udp</protocol>\n";
				}
				if($formatos['proto'] === "tcp/udp")
				{
					$contenido .= "\t\t\t<protocol>tcp/udp</protocol>\n";
				}
				if($formatos['proto'] === "icmp")
				{
					$contenido .= "\t\t\t<protocol>icmp</protocol>\n";
				}
				$contenido .= "\t\t\t<source>\n";
					if($formatos['srctype'] === "any")
					{
						$contenido .= "\t\t\t\t<any></any>\n";
					}
					if($formatos['srctype'] === "single")
					{
						$contenido .= "\t\t\t\t<address>" . $formatos['src'] . "</address>\n";
					}
					if($formatos['srctype'] === "network")
					{
						$contenido .= "\t\t\t\t<address>" . $formatos['src'] . "/" . $formatos['srcmask'] . "</address>\n";
					}
					if($formatos['srctype'] === "pppoe")
					{
						$contenido .= "\t\t\t\t<network>pppoe</network>\n";
					}
					if($formatos['srctype'] === "l2tp")
					{
						$contenido .= "\t\t\t\t<network>l2tp</network>\n";
					}
					if($formatos['srctype'] === "wan")
					{
						$contenido .= "\t\t\t\t<network>wan</network>\n";
					}
					if($formatos['srctype'] === "wanip")
					{
						$contenido .= "\t\t\t\t<network>wanip</network>\n";
					}
					if($formatos['srctype'] === "lan")
					{
						$contenido .= "\t\t\t\t<network>lan</network>\n";
					}
					if($formatos['srctype'] === "lanip")
					{
						$contenido .= "\t\t\t\t<network>lanip</network>\n";
					}
					# Invert match. #
					if($formatos['srcnot'] === "yes")
					{
						$contenido .= "\t\t\t\t<not></not>\n";
					}
					# Source Port Range #
					if($formatos['srcbeginport'] === "")
					{
						if($formatos['srcbeginport_cust'] === $formatos['srcendport_cust']) 
						{
							$contenido .= "\t\t\t\t<port>" . $formatos['srcbeginport_cust'] . "</port>\n";
						}
						else
						{
							$contenido .= "\t\t\t\t<port>" . $formatos['srcbeginport_cust'] . $formatos['srcendport_cust'] . "</port>\n";
						}
						$contenido .= "\t\t\t\t<not></not>\n";
					}
					if($formatos['srcbeginport'] === "5999")
					{
						$contenido .= "\t\t\t\t<port>5999</port>\n";
					}

					if($formatos['srcbeginport'] === "53")
					{
						$contenido .= "\t\t\t\t<port>53</port>\n";
					}

					if($formatos['srcbeginport'] === "21")
					{
						$contenido .= "\t\t\t\t<port>21</port>\n";
					}

					if($formatos['srcbeginport'] === "3000")
					{
						$contenido .= "\t\t\t\t<port>3000</port>\n";
					}

					if($formatos['srcbeginport'] === "80")
					{
						$contenido .= "\t\t\t\t<port>80</port>\n";
					}

					if($formatos['srcbeginport'] === "443")
					{
						$contenido .= "\t\t\t\t<port>443</port>\n";
					}

					if($formatos['srcbeginport'] === "5190")
					{
						$contenido .= "\t\t\t\t<port>5190</port>\n";
					}

					if($formatos['srcbeginport'] === "113")
					{
						$contenido .= "\t\t\t\t<port>113</port>\n";
					}

					if($formatos['srcbeginport'] === "993")
					{
						$contenido .= "\t\t\t\t<port>993</port>\n";
					}

					if($formatos['srcbeginport'] === "4500")
					{
						$contenido .= "\t\t\t\t<port>4500</port>\n";
					}

					if($formatos['srcbeginport'] === "500")
					{
						$contenido .= "\t\t\t\t<port>500</port>\n";
					}

					if($formatos['srcbeginport'] === "1701")
					{
						$contenido .= "\t\t\t\t<port>1701</port>\n";
					}

					if($formatos['srcbeginport'] === "389")
					{
						$contenido .= "\t\t\t\t<port>389</port>\n";
					}

					if($formatos['srcbeginport'] === "1755")
					{
						$contenido .= "\t\t\t\t<port>1755</port>\n";
					}

					if($formatos['srcbeginport'] === "7000")
					{
						$contenido .= "\t\t\t\t<port>7000</port>\n";
					}

					if($formatos['srcbeginport'] === "445")
					{
						$contenido .= "\t\t\t\t<port>445</port>\n";
					}

					if($formatos['srcbeginport'] === "3389")
					{
						$contenido .= "\t\t\t\t<port>3389</port>\n";
					}

					if($formatos['srcbeginport'] === "1512")
					{
						$contenido .= "\t\t\t\t<port>1512</port>\n";
					}

					if($formatos['srcbeginport'] === "1863")
					{
						$contenido .= "\t\t\t\t<port>1863</port>\n";
					}

					if($formatos['srcbeginport'] === "119")
					{
						$contenido .= "\t\t\t\t<port>119</port>\n";
					}

					if($formatos['srcbeginport'] === "123")
					{
						$contenido .= "\t\t\t\t<port>123</port>\n";
					}

					if($formatos['srcbeginport'] === "138")
					{
						$contenido .= "\t\t\t\t<port>138</port>\n";
					}

					if($formatos['srcbeginport'] === "137")
					{
						$contenido .= "\t\t\t\t<port>137</port>\n";
					}

					if($formatos['srcbeginport'] === "139")
					{
						$contenido .= "\t\t\t\t<port>139</port>\n";
					}

					if($formatos['srcbeginport'] === "1194")
					{
						$contenido .= "\t\t\t\t<port>1194</port>\n";
					}

					if($formatos['srcbeginport'] === "110")
					{
						$contenido .= "\t\t\t\t<port>110</port>\n";
					}

					if($formatos['srcbeginport'] === "995")
					{
						$contenido .= "\t\t\t\t<port>995</port>\n";
					}

					if($formatos['srcbeginport'] === "1723")
					{
						$contenido .= "\t\t\t\t<port>1723</port>\n";
					}

					if($formatos['srcbeginport'] === "1812")
					{
						$contenido .= "\t\t\t\t<port>1812</port>\n";
					}

					if($formatos['srcbeginport'] === "1813")
					{
						$contenido .= "\t\t\t\t<port>1813</port>\n";
					}

					if($formatos['srcbeginport'] === "5004")
					{
						$contenido .= "\t\t\t\t<port>5004</port>\n";
					}

					if($formatos['srcbeginport'] === "5060")
					{
						$contenido .= "\t\t\t\t<port>5060</port>\n";
					}

					if($formatos['srcbeginport'] === "25")
					{
						$contenido .= "\t\t\t\t<port>25</port>\n";
					}

					if($formatos['srcbeginport'] === "465")
					{
						$contenido .= "\t\t\t\t<port>465</port>\n";
					}

					if($formatos['srcbeginport'] === "161")
					{
						$contenido .= "\t\t\t\t<port>161</port>\n";
					}

					if($formatos['srcbeginport'] === "162")
					{
						$contenido .= "\t\t\t\t<port>162</port>\n";
					}

					if($formatos['srcbeginport'] === "22")
					{
						$contenido .= "\t\t\t\t<port>22</port>\n";
					}

					if($formatos['srcbeginport'] === "3478")
					{
						$contenido .= "\t\t\t\t<port>3278</port>\n";
					}

					if($formatos['srcbeginport'] === "587")
					{
						$contenido .= "\t\t\t\t<port>587</port>\n";
					}

					if($formatos['srcbeginport'] === "3544")
					{
						$contenido .= "\t\t\t\t<port>3544</port>\n";
					}

					if($formatos['srcbeginport'] === "23")
					{
						$contenido .= "\t\t\t\t<port>23</port>\n";
					}

					if($formatos['srcbeginport'] === "69")
					{
						$contenido .= "\t\t\t\t<port>69</port>\n";
					}

					if($formatos['srcbeginport'] === "5900")
					{
						$contenido .= "\t\t\t\t<port>5900</port>\n";
					}
				$contenido .= "\t\t\t</source>\n";
				# destination #
				$contenido .= "\t\t\t<destination>\n";
					if($formatos['dsttype'] === "any")
					{
						$contenido .= "\t\t\t\t<any></any>\n";
					}
					if($formatos['dsttype'] === "single")
					{
						$contenido .= "\t\t\t\t<address>" . $formatos['dst'] . "</address>\n";
					}
					if($formatos['dsttype'] === "network")
					{
						$contenido .= "\t\t\t\t<address>" . $formatos['dst'] . "/" . $formatos['dstmask'] . "</address>\n";
					}
					if($formatos['dsttype'] === "pppoe")
					{
						$contenido .= "\t\t\t\t<network>pppoe</network>\n";
					}
					if($formatos['dsttype'] === "l2tp")
					{
						$contenido .= "\t\t\t\t<network>l2tp</network>\n";
					}
					if($formatos['dsttype'] === "wan")
					{
						$contenido .= "\t\t\t\t<network>wan</network>\n";
					}
					if($formatos['dsttype'] === "wanip")
					{
						$contenido .= "\t\t\t\t<network>wanip</network>\n";
					}
					if($formatos['dsttype'] === "lan")
					{
						$contenido .= "\t\t\t\t<network>lan</network>\n";
					}
					if($formatos['dsttype'] === "lanip")
					{
						$contenido .= "\t\t\t\t<network>lanip</network>\n";
					}
					# Invert match. #
					if($formatos['dstnot'] === "yes")
					{
						$contenido .= "\t\t\t\t<not></not>\n";
					}
					# Source Port Range #
					if($formatos['dstbeginport'] === "")
					{
						if($formatos['dstbeginport_cust'] === $formatos['dstendport_cust']) 
						{
							$contenido .= "\t\t\t\t<port>" . $formatos['dstbeginport_cust'] . "</port>\n";
						}
						else
						{
							$contenido .= "\t\t\t\t<port>" . $formatos['dstbeginport_cust'] . $formatos['dstendport_cust'] . "</port>\n";
						}
						$contenido .= "\t\t\t\t<not></not>\n";
					}
					if($formatos['dstbeginport'] === "5999")
					{
						$contenido .= "\t\t\t\t<port>5999</port>\n";
					}

					if($formatos['dstbeginport'] === "53")
					{
						$contenido .= "\t\t\t\t<port>53</port>\n";
					}

					if($formatos['dstbeginport'] === "21")
					{
						$contenido .= "\t\t\t\t<port>21</port>\n";
					}

					if($formatos['dstbeginport'] === "3000")
					{
						$contenido .= "\t\t\t\t<port>3000</port>\n";
					}

					if($formatos['dstbeginport'] === "80")
					{
						$contenido .= "\t\t\t\t<port>80</port>\n";
					}

					if($formatos['dstbeginport'] === "443")
					{
						$contenido .= "\t\t\t\t<port>443</port>\n";
					}

					if($formatos['dstbeginport'] === "5190")
					{
						$contenido .= "\t\t\t\t<port>5190</port>\n";
					}

					if($formatos['dstbeginport'] === "113")
					{
						$contenido .= "\t\t\t\t<port>113</port>\n";
					}

					if($formatos['dstbeginport'] === "993")
					{
						$contenido .= "\t\t\t\t<port>993</port>\n";
					}

					if($formatos['dstbeginport'] === "4500")
					{
						$contenido .= "\t\t\t\t<port>4500</port>\n";
					}

					if($formatos['dstbeginport'] === "500")
					{
						$contenido .= "\t\t\t\t<port>500</port>\n";
					}

					if($formatos['dstbeginport'] === "1701")
					{
						$contenido .= "\t\t\t\t<port>1701</port>\n";
					}

					if($formatos['dstbeginport'] === "389")
					{
						$contenido .= "\t\t\t\t<port>389</port>\n";
					}

					if($formatos['dstbeginport'] === "1755")
					{
						$contenido .= "\t\t\t\t<port>1755</port>\n";
					}

					if($formatos['dstbeginport'] === "7000")
					{
						$contenido .= "\t\t\t\t<port>7000</port>\n";
					}

					if($formatos['dstbeginport'] === "445")
					{
						$contenido .= "\t\t\t\t<port>445</port>\n";
					}

					if($formatos['dstbeginport'] === "3389")
					{
						$contenido .= "\t\t\t\t<port>3389</port>\n";
					}

					if($formatos['dstbeginport'] === "1512")
					{
						$contenido .= "\t\t\t\t<port>1512</port>\n";
					}

					if($formatos['dstbeginport'] === "1863")
					{
						$contenido .= "\t\t\t\t<port>1863</port>\n";
					}

					if($formatos['dstbeginport'] === "119")
					{
						$contenido .= "\t\t\t\t<port>119</port>\n";
					}

					if($formatos['dstbeginport'] === "123")
					{
						$contenido .= "\t\t\t\t<port>123</port>\n";
					}

					if($formatos['dstbeginport'] === "138")
					{
						$contenido .= "\t\t\t\t<port>138</port>\n";
					}

					if($formatos['dstbeginport'] === "137")
					{
						$contenido .= "\t\t\t\t<port>137</port>\n";
					}

					if($formatos['dstbeginport'] === "139")
					{
						$contenido .= "\t\t\t\t<port>139</port>\n";
					}

					if($formatos['dstbeginport'] === "1194")
					{
						$contenido .= "\t\t\t\t<port>1194</port>\n";
					}

					if($formatos['dstbeginport'] === "110")
					{
						$contenido .= "\t\t\t\t<port>110</port>\n";
					}

					if($formatos['dstbeginport'] === "995")
					{
						$contenido .= "\t\t\t\t<port>995</port>\n";
					}

					if($formatos['dstbeginport'] === "1723")
					{
						$contenido .= "\t\t\t\t<port>1723</port>\n";
					}

					if($formatos['dstbeginport'] === "1812")
					{
						$contenido .= "\t\t\t\t<port>1812</port>\n";
					}

					if($formatos['dstbeginport'] === "1813")
					{
						$contenido .= "\t\t\t\t<port>1813</port>\n";
					}

					if($formatos['dstbeginport'] === "5004")
					{
						$contenido .= "\t\t\t\t<port>5004</port>\n";
					}

					if($formatos['dstbeginport'] === "5060")
					{
						$contenido .= "\t\t\t\t<port>5060</port>\n";
					}

					if($formatos['dstbeginport'] === "25")
					{
						$contenido .= "\t\t\t\t<port>25</port>\n";
					}

					if($formatos['dstbeginport'] === "465")
					{
						$contenido .= "\t\t\t\t<port>465</port>\n";
					}

					if($formatos['dstbeginport'] === "161")
					{
						$contenido .= "\t\t\t\t<port>161</port>\n";
					}

					if($formatos['dstbeginport'] === "162")
					{
						$contenido .= "\t\t\t\t<port>162</port>\n";
					}

					if($formatos['dstbeginport'] === "22")
					{
						$contenido .= "\t\t\t\t<port>22</port>\n";
					}

					if($formatos['dstbeginport'] === "3478")
					{
						$contenido .= "\t\t\t\t<port>3278</port>\n";
					}

					if($formatos['dstbeginport'] === "587")
					{
						$contenido .= "\t\t\t\t<port>587</port>\n";
					}

					if($formatos['dstbeginport'] === "3544")
					{
						$contenido .= "\t\t\t\t<port>3544</port>\n";
					}

					if($formatos['dstbeginport'] === "23")
					{
						$contenido .= "\t\t\t\t<port>23</port>\n";
					}

					if($formatos['dstbeginport'] === "69")
					{
						$contenido .= "\t\t\t\t<port>69</port>\n";
					}

					if($formatos['dstbeginport'] === "5900")
					{
						$contenido .= "\t\t\t\t<port>5900</port>\n";
					}
				$contenido .= "\t\t\t</destination>\n";
			if($formatos['disabled'] === "yes")
			{
				$contenido .= "\t\t\t<disabled></disabled>\n";
			}	
			if($formatos['log'] === "yes")
			{
				$contenido .= "\t\t\t<log></log>\n";
			}	
			$contenido .= "\t\t\t<descr>" . $formatos['descr'] . "</descr>\n";
			$contenido .= "\t\t</rule>\n";
		}

		$queryOne = "SELECT * FROM firewalllan WHERE namegroup = '$id' ORDER BY position_order";
		$stmtOne = $db->prepare($queryOne);
		$paramsOne =array();
		$stmtOne->execute($paramsOne);
		// Se alamacena la consulta en una variable 
		$formatoOne=$stmtOne->fetchAll();
		// Se realiza un ciclo para llenar las demas etiquetas del archivo xml 
		foreach ($formatoOne as $formatos) 
		{
			$contenido .= "\t\t<rule>\n";
				$contenido .= "\t\t\t<id></id>\n";
				# Action #
				if($formatos['type'] === "pass")
				{
					$contenido .= "\t\t\t<type>pass</type>\n";
				}
				if($formatos['type'] === "block")
				{
					$contenido .= "\t\t\t<type>block</type>\n";
				}
				if($formatos['type'] === "reject")
				{
					$contenido .= "\t\t\t<type>reject</type>\n";
				}
				# Interface #
				if($formatos['interface'] === "wan")
				{
					$contenido .= "\t\t\t<interface>wan</interface>\n";
				}
				if($formatos['interface'] === "lan")
				{
					$contenido .= "\t\t\t<interface>lan</interface>\n";
				}
				# Address Family #
				if($formatos['ipprotocol'] === "inet")
				{
					$contenido .= "\t\t\t<ipprotocol>inet</ipprotocol>\n";
				}
				if($formatos['ipprotocol'] === "inet6")
				{
					$contenido .= "\t\t\t<ipprotocol>inet6</ipprotocol>\n";
				}
				if($formatos['ipprotocol'] === "Reject")
				{
					$contenido .= "\t\t\t<ipprotocol>inet46</ipprotocol>\n";
				}
				$contenido .= "\t\t\t<tag></tag>\n";
				$contenido .= "\t\t\t<tagged></tagged>\n";
				$contenido .= "\t\t\t<max></max>\n";
				$contenido .= "\t\t\t<max-src-nodes></max-src-nodes>\n";
				$contenido .= "\t\t\t<max-src-conn></max-src-conn>\n";
				$contenido .= "\t\t\t<max-src-states></max-src-states>\n";
				$contenido .= "\t\t\t<statetimeout></statetimeout>\n";
				$contenido .= "\t\t\t<statetype>keep state</statetype>\n";
				$contenido .= "\t\t\t<os></os>\n";
				if($formatos['proto'] === "tcp")
				{
					$contenido .= "\t\t\t<protocol>tcp</protocol>\n";
				}
				if($formatos['proto'] === "udp")
				{
					$contenido .= "\t\t\t<protocol>udp</protocol>\n";
				}
				if($formatos['proto'] === "tcp/udp")
				{
					$contenido .= "\t\t\t<protocol>tcp/udp</protocol>\n";
				}
				if($formatos['proto'] === "icmp")
				{
					$contenido .= "\t\t\t<protocol>icmp</protocol>\n";
				}
				$contenido .= "\t\t\t<source>\n";
					if($formatos['srctype'] === "any")
					{
						$contenido .= "\t\t\t\t<any></any>\n";
					}
					if($formatos['srctype'] === "single")
					{
						$contenido .= "\t\t\t\t<address>" . $formatos['src'] . "</address>\n";
					}
					if($formatos['srctype'] === "network")
					{
						$contenido .= "\t\t\t\t<address>" . $formatos['src'] . "/" . $formatos['srcmask'] . "</address>\n";
					}
					if($formatos['srctype'] === "pppoe")
					{
						$contenido .= "\t\t\t\t<network>pppoe</network>\n";
					}
					if($formatos['srctype'] === "l2tp")
					{
						$contenido .= "\t\t\t\t<network>l2tp</network>\n";
					}
					if($formatos['srctype'] === "wan")
					{
						$contenido .= "\t\t\t\t<network>wan</network>\n";
					}
					if($formatos['srctype'] === "wanip")
					{
						$contenido .= "\t\t\t\t<network>wanip</network>\n";
					}
					if($formatos['srctype'] === "lan")
					{
						$contenido .= "\t\t\t\t<network>lan</network>\n";
					}
					if($formatos['srctype'] === "lanip")
					{
						$contenido .= "\t\t\t\t<network>lanip</network>\n";
					}
					# Invert match. #
					if($formatos['srcnot'] === "yes")
					{
						$contenido .= "\t\t\t\t<not></not>\n";
					}
					# Source Port Range #
					if($formatos['srcbeginport'] === "")
					{
						if($formatos['srcbeginport_cust'] === $formatos['srcendport_cust']) 
						{
							$contenido .= "\t\t\t\t<port>" . $formatos['srcbeginport_cust'] . "</port>\n";
						}
						else
						{
							$contenido .= "\t\t\t\t<port>" . $formatos['srcbeginport_cust'] . $formatos['srcendport_cust'] . "</port>\n";
						}
						$contenido .= "\t\t\t\t<not></not>\n";
					}
					if($formatos['srcbeginport'] === "5999")
					{
						$contenido .= "\t\t\t\t<port>5999</port>\n";
					}

					if($formatos['srcbeginport'] === "53")
					{
						$contenido .= "\t\t\t\t<port>53</port>\n";
					}

					if($formatos['srcbeginport'] === "21")
					{
						$contenido .= "\t\t\t\t<port>21</port>\n";
					}

					if($formatos['srcbeginport'] === "3000")
					{
						$contenido .= "\t\t\t\t<port>3000</port>\n";
					}

					if($formatos['srcbeginport'] === "80")
					{
						$contenido .= "\t\t\t\t<port>80</port>\n";
					}

					if($formatos['srcbeginport'] === "443")
					{
						$contenido .= "\t\t\t\t<port>443</port>\n";
					}

					if($formatos['srcbeginport'] === "5190")
					{
						$contenido .= "\t\t\t\t<port>5190</port>\n";
					}

					if($formatos['srcbeginport'] === "113")
					{
						$contenido .= "\t\t\t\t<port>113</port>\n";
					}

					if($formatos['srcbeginport'] === "993")
					{
						$contenido .= "\t\t\t\t<port>993</port>\n";
					}

					if($formatos['srcbeginport'] === "4500")
					{
						$contenido .= "\t\t\t\t<port>4500</port>\n";
					}

					if($formatos['srcbeginport'] === "500")
					{
						$contenido .= "\t\t\t\t<port>500</port>\n";
					}

					if($formatos['srcbeginport'] === "1701")
					{
						$contenido .= "\t\t\t\t<port>1701</port>\n";
					}

					if($formatos['srcbeginport'] === "389")
					{
						$contenido .= "\t\t\t\t<port>389</port>\n";
					}

					if($formatos['srcbeginport'] === "1755")
					{
						$contenido .= "\t\t\t\t<port>1755</port>\n";
					}

					if($formatos['srcbeginport'] === "7000")
					{
						$contenido .= "\t\t\t\t<port>7000</port>\n";
					}

					if($formatos['srcbeginport'] === "445")
					{
						$contenido .= "\t\t\t\t<port>445</port>\n";
					}

					if($formatos['srcbeginport'] === "3389")
					{
						$contenido .= "\t\t\t\t<port>3389</port>\n";
					}

					if($formatos['srcbeginport'] === "1512")
					{
						$contenido .= "\t\t\t\t<port>1512</port>\n";
					}

					if($formatos['srcbeginport'] === "1863")
					{
						$contenido .= "\t\t\t\t<port>1863</port>\n";
					}

					if($formatos['srcbeginport'] === "119")
					{
						$contenido .= "\t\t\t\t<port>119</port>\n";
					}

					if($formatos['srcbeginport'] === "123")
					{
						$contenido .= "\t\t\t\t<port>123</port>\n";
					}

					if($formatos['srcbeginport'] === "138")
					{
						$contenido .= "\t\t\t\t<port>138</port>\n";
					}

					if($formatos['srcbeginport'] === "137")
					{
						$contenido .= "\t\t\t\t<port>137</port>\n";
					}

					if($formatos['srcbeginport'] === "139")
					{
						$contenido .= "\t\t\t\t<port>139</port>\n";
					}

					if($formatos['srcbeginport'] === "1194")
					{
						$contenido .= "\t\t\t\t<port>1194</port>\n";
					}

					if($formatos['srcbeginport'] === "110")
					{
						$contenido .= "\t\t\t\t<port>110</port>\n";
					}

					if($formatos['srcbeginport'] === "995")
					{
						$contenido .= "\t\t\t\t<port>995</port>\n";
					}

					if($formatos['srcbeginport'] === "1723")
					{
						$contenido .= "\t\t\t\t<port>1723</port>\n";
					}

					if($formatos['srcbeginport'] === "1812")
					{
						$contenido .= "\t\t\t\t<port>1812</port>\n";
					}

					if($formatos['srcbeginport'] === "1813")
					{
						$contenido .= "\t\t\t\t<port>1813</port>\n";
					}

					if($formatos['srcbeginport'] === "5004")
					{
						$contenido .= "\t\t\t\t<port>5004</port>\n";
					}

					if($formatos['srcbeginport'] === "5060")
					{
						$contenido .= "\t\t\t\t<port>5060</port>\n";
					}

					if($formatos['srcbeginport'] === "25")
					{
						$contenido .= "\t\t\t\t<port>25</port>\n";
					}

					if($formatos['srcbeginport'] === "465")
					{
						$contenido .= "\t\t\t\t<port>465</port>\n";
					}

					if($formatos['srcbeginport'] === "161")
					{
						$contenido .= "\t\t\t\t<port>161</port>\n";
					}

					if($formatos['srcbeginport'] === "162")
					{
						$contenido .= "\t\t\t\t<port>162</port>\n";
					}

					if($formatos['srcbeginport'] === "22")
					{
						$contenido .= "\t\t\t\t<port>22</port>\n";
					}

					if($formatos['srcbeginport'] === "3478")
					{
						$contenido .= "\t\t\t\t<port>3278</port>\n";
					}

					if($formatos['srcbeginport'] === "587")
					{
						$contenido .= "\t\t\t\t<port>587</port>\n";
					}

					if($formatos['srcbeginport'] === "3544")
					{
						$contenido .= "\t\t\t\t<port>3544</port>\n";
					}

					if($formatos['srcbeginport'] === "23")
					{
						$contenido .= "\t\t\t\t<port>23</port>\n";
					}

					if($formatos['srcbeginport'] === "69")
					{
						$contenido .= "\t\t\t\t<port>69</port>\n";
					}

					if($formatos['srcbeginport'] === "5900")
					{
						$contenido .= "\t\t\t\t<port>5900</port>\n";
					}
				$contenido .= "\t\t\t</source>\n";
				# destination #
				$contenido .= "\t\t\t<destination>\n";
					if($formatos['dsttype'] === "any")
					{
						$contenido .= "\t\t\t\t<any></any>\n";
					}
					if($formatos['dsttype'] === "single")
					{
						$contenido .= "\t\t\t\t<address>" . $formatos['src'] . "</address>\n";
					}
					if($formatos['dsttype'] === "network")
					{
						$contenido .= "\t\t\t\t<address>" . $formatos['src'] . "/" . $formatos['srcmask'] . "</address>\n";
					}
					if($formatos['dsttype'] === "pppoe")
					{
						$contenido .= "\t\t\t\t<network>pppoe</network>\n";
					}
					if($formatos['dsttype'] === "l2tp")
					{
						$contenido .= "\t\t\t\t<network>l2tp</network>\n";
					}
					if($formatos['dsttype'] === "wan")
					{
						$contenido .= "\t\t\t\t<network>wan</network>\n";
					}
					if($formatos['dsttype'] === "wanip")
					{
						$contenido .= "\t\t\t\t<network>wanip</network>\n";
					}
					if($formatos['dsttype'] === "lan")
					{
						$contenido .= "\t\t\t\t<network>lan</network>\n";
					}
					if($formatos['dsttype'] === "lanip")
					{
						$contenido .= "\t\t\t\t<network>lanip</network>\n";
					}
					# Invert match. #
					if($formatos['dstnot'] === "yes")
					{
						$contenido .= "\t\t\t\t<not></not>\n";
					}
					# Source Port Range #
					if($formatos['dstbeginport'] === "")
					{
						if($formatos['dstbeginport_cust'] === $formatos['dstendport_cust']) 
						{
							$contenido .= "\t\t\t\t<port>" . $formatos['dstbeginport_cust'] . "</port>\n";
						}
						else
						{
							$contenido .= "\t\t\t\t<port>" . $formatos['dstbeginport_cust'] . $formatos['dstendport_cust'] . "</port>\n";
						}
						$contenido .= "\t\t\t\t<not></not>\n";
					}
					if($formatos['dstbeginport'] === "5999")
					{
						$contenido .= "\t\t\t\t<port>5999</port>\n";
					}

					if($formatos['dstbeginport'] === "53")
					{
						$contenido .= "\t\t\t\t<port>53</port>\n";
					}

					if($formatos['dstbeginport'] === "21")
					{
						$contenido .= "\t\t\t\t<port>21</port>\n";
					}

					if($formatos['dstbeginport'] === "3000")
					{
						$contenido .= "\t\t\t\t<port>3000</port>\n";
					}

					if($formatos['dstbeginport'] === "80")
					{
						$contenido .= "\t\t\t\t<port>80</port>\n";
					}

					if($formatos['dstbeginport'] === "443")
					{
						$contenido .= "\t\t\t\t<port>443</port>\n";
					}

					if($formatos['dstbeginport'] === "5190")
					{
						$contenido .= "\t\t\t\t<port>5190</port>\n";
					}

					if($formatos['dstbeginport'] === "113")
					{
						$contenido .= "\t\t\t\t<port>113</port>\n";
					}

					if($formatos['dstbeginport'] === "993")
					{
						$contenido .= "\t\t\t\t<port>993</port>\n";
					}

					if($formatos['dstbeginport'] === "4500")
					{
						$contenido .= "\t\t\t\t<port>4500</port>\n";
					}

					if($formatos['dstbeginport'] === "500")
					{
						$contenido .= "\t\t\t\t<port>500</port>\n";
					}

					if($formatos['dstbeginport'] === "1701")
					{
						$contenido .= "\t\t\t\t<port>1701</port>\n";
					}

					if($formatos['dstbeginport'] === "389")
					{
						$contenido .= "\t\t\t\t<port>389</port>\n";
					}

					if($formatos['dstbeginport'] === "1755")
					{
						$contenido .= "\t\t\t\t<port>1755</port>\n";
					}

					if($formatos['dstbeginport'] === "7000")
					{
						$contenido .= "\t\t\t\t<port>7000</port>\n";
					}

					if($formatos['dstbeginport'] === "445")
					{
						$contenido .= "\t\t\t\t<port>445</port>\n";
					}

					if($formatos['dstbeginport'] === "3389")
					{
						$contenido .= "\t\t\t\t<port>3389</port>\n";
					}

					if($formatos['dstbeginport'] === "1512")
					{
						$contenido .= "\t\t\t\t<port>1512</port>\n";
					}

					if($formatos['dstbeginport'] === "1863")
					{
						$contenido .= "\t\t\t\t<port>1863</port>\n";
					}

					if($formatos['dstbeginport'] === "119")
					{
						$contenido .= "\t\t\t\t<port>119</port>\n";
					}

					if($formatos['dstbeginport'] === "123")
					{
						$contenido .= "\t\t\t\t<port>123</port>\n";
					}

					if($formatos['dstbeginport'] === "138")
					{
						$contenido .= "\t\t\t\t<port>138</port>\n";
					}

					if($formatos['dstbeginport'] === "137")
					{
						$contenido .= "\t\t\t\t<port>137</port>\n";
					}

					if($formatos['dstbeginport'] === "139")
					{
						$contenido .= "\t\t\t\t<port>139</port>\n";
					}

					if($formatos['dstbeginport'] === "1194")
					{
						$contenido .= "\t\t\t\t<port>1194</port>\n";
					}

					if($formatos['dstbeginport'] === "110")
					{
						$contenido .= "\t\t\t\t<port>110</port>\n";
					}

					if($formatos['dstbeginport'] === "995")
					{
						$contenido .= "\t\t\t\t<port>995</port>\n";
					}

					if($formatos['dstbeginport'] === "1723")
					{
						$contenido .= "\t\t\t\t<port>1723</port>\n";
					}

					if($formatos['dstbeginport'] === "1812")
					{
						$contenido .= "\t\t\t\t<port>1812</port>\n";
					}

					if($formatos['dstbeginport'] === "1813")
					{
						$contenido .= "\t\t\t\t<port>1813</port>\n";
					}

					if($formatos['dstbeginport'] === "5004")
					{
						$contenido .= "\t\t\t\t<port>5004</port>\n";
					}

					if($formatos['dstbeginport'] === "5060")
					{
						$contenido .= "\t\t\t\t<port>5060</port>\n";
					}

					if($formatos['dstbeginport'] === "25")
					{
						$contenido .= "\t\t\t\t<port>25</port>\n";
					}

					if($formatos['dstbeginport'] === "465")
					{
						$contenido .= "\t\t\t\t<port>465</port>\n";
					}

					if($formatos['dstbeginport'] === "161")
					{
						$contenido .= "\t\t\t\t<port>161</port>\n";
					}

					if($formatos['dstbeginport'] === "162")
					{
						$contenido .= "\t\t\t\t<port>162</port>\n";
					}

					if($formatos['dstbeginport'] === "22")
					{
						$contenido .= "\t\t\t\t<port>22</port>\n";
					}

					if($formatos['dstbeginport'] === "3478")
					{
						$contenido .= "\t\t\t\t<port>3278</port>\n";
					}

					if($formatos['dstbeginport'] === "587")
					{
						$contenido .= "\t\t\t\t<port>587</port>\n";
					}

					if($formatos['dstbeginport'] === "3544")
					{
						$contenido .= "\t\t\t\t<port>3544</port>\n";
					}

					if($formatos['dstbeginport'] === "23")
					{
						$contenido .= "\t\t\t\t<port>23</port>\n";
					}

					if($formatos['dstbeginport'] === "69")
					{
						$contenido .= "\t\t\t\t<port>69</port>\n";
					}

					if($formatos['dstbeginport'] === "5900")
					{
						$contenido .= "\t\t\t\t<port>5900</port>\n";
					}
				$contenido .= "\t\t\t</destination>\n";
			if($formatos['disabled'] === "yes")
			{
				$contenido .= "\t\t\t<disabled></disabled>\n";
			}	
			if($formatos['log'] === "yes")
			{
				$contenido .= "\t\t\t<log></log>\n";
			}	
			$contenido .= "\t\t\t<descr>" . $formatos['descr'] . "</descr>\n";
			$contenido .= "\t\t</rule>\n";
		}

		$contenido .= "\t\t<separator>\n";
			$contenido .= "\t\t\t<wan></wan>\n";
			$contenido .= "\t\t\t<lan></lan>\n";
		$contenido .= "\t\t</separator>\n";
		$contenido .= "\t</filter>";
		// Se crea o actualiza el archivo 
		$archivo = fopen('conf.xml', 'w');
		// Se abre el archivo y se ingresa la informacion almacenada en la variable 
		fwrite($archivo, $contenido);
		// Se cierra el archivo 
		fclose($archivo); 
		# Mover el archivo a la carpeta #
		$archivoConfig = 'conf.xml';
		$destinoConfig = "Groups/$id/conf.xml";
	   	if (!copy($archivoConfig, $destinoConfig)) 
	   	{
		    echo "Error al copiar $archivoConfig...\n";
		}
		unlink("conf.xml");
		$estatus="The configuration has been saved";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("listGroupFirewall");
	}

	// funcion para correr el script aplicar cambios en target categories
	public function aplicateXMLFirewallAction($id)
	{
	    $authenticationUtils = $this->get("security.authentication_utils");
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		$u = $this->getUser();
		if($u != null)
		{
			//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
			$em = $this->getDoctrine()->getEntityManager();
	        $db = $em->getConnection();

	        $role=$u->getRole();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$archivo = fopen("change_to_do.txt", 'w');
				// Se abre el archivo y se ingresa la informacion almacenada en la variable 
				fwrite($archivo, "firewallrules.py");
				fwrite ($archivo, "\n");
				// Se cierra el archivo 
				fclose($archivo); 
				# Mover el archivo a la carpeta #
				$archivoConfig = 'change_to_do.txt';
				$destinoConfig = "centralizedConsole/change_to_do.txt";
			   	if (!copy($archivoConfig, $destinoConfig)) 
			   	{
				   echo "Error al copiar $archivoConfig...\n";
				}

				$archivoConfig = "Groups/$id/conf.xml";
				$destinoConfig = "centralizedConsole/conf.xml";
			   	if (!copy($archivoConfig, $destinoConfig)) 
			   	{
			   		echo "Error al copiar $archivoConfig...\n";
				}
				  
	        	return $this->redirectToRoute('listGroup');
	        }
	        if($role == "ROLE_ADMIN")
	        {	   
	        	$archivo = fopen("change_to_do.txt", 'w');
				// Se abre el archivo y se ingresa la informacion almacenada en la variable 
				fwrite($archivo, "firewallrules.py");
				fwrite ($archivo, "\n");
				// Se cierra el archivo 
				fclose($archivo); 
				# Mover el archivo a la carpeta #
				$archivoConfig = 'change_to_do.txt';
				$destinoConfig = "centralizedConsole/change_to_do.txt";
			   	if (!copy($archivoConfig, $destinoConfig)) 
			   	{
				   echo "Error al copiar $archivoConfig...\n";
				}

				$archivoConfig = "Groups/$id/conf.xml";
				$destinoConfig = "centralizedConsole/conf.xml";
			   	if (!copy($archivoConfig, $destinoConfig)) 
			   	{
			   		echo "Error al copiar $archivoConfig...\n";
				}     	
				
				return $this->redirectToRoute('listGroupIp');
			}
		}
		return $this->redirectToRoute('dashboard');
	}
}
