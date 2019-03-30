<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\Acl;
use PrincipalBundle\Form\AclType;
use PrincipalBundle\Form\AclEditType;
use Symfony\Component\HttpFoundation\Session\Session;

class AclController extends Controller
{
	# Variables #
	private $session; 

	# Constructor # 
	public function __construct()
	{
		$this->session = new Session();
	}

	# Funcion para mostrar los grupos #
	public function grupos_aclAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$grupos = $this->obtener_grupo_nombre();
				return $this->render("@Principal/acl/grupos_acl.html.twig", array(
					"grupo"=>$grupos
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR" or $role == "ROLE_USER")
	        	return $this->redirectToRoute("lista_acl");
	    }
	}

	# Funcion para registrar un nuevo aliases #
	public function registro_aclAction(Request $request)
	{
		$acl = new Acl();
		$form = $this ->createForm(AclType::class, $acl);
		$form->handleRequest($request);
		$u = $this->getUser();
		$plantel=$_REQUEST['id'];
		$grupo=$u->getGrupo();
		$recuperar_nombre_target = $this->recuperar_nombre_target($plantel, $grupo);
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$recuperar_nombre = $this->recuperar_nombre($form->get("nombre")->getData(), $plantel);
			if(count($recuperar_nombre)==0)
			{
				$target_rule = implode(" ",$_POST['target_rule']);
				$acl->setTargetRule($target_rule." all [ all]");
				$acl->setTargetRulesList($target_rule);
				$acl->setGrupo($grupo);
				$acl->setUbicacion($plantel);
				$em->persist($acl);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="Successfully registration.";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("grupos_acl");
				}
				else
				{
					$estatus="Problems with the server try later.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
			}
			else
				echo '<script> alert("The name of acl that you are trying to register already exists try again.");window.history.go(-1);</script>';
		}
		return $this->render('@Principal/acl/registro_acl.html.twig', array(
			'form'=>$form->createView(),
			'plantel'=>$plantel,
			'recuperar_nombre_target'=>$recuperar_nombre_target,
		));
	}

	public function lista_aclAction()
	{
		$u = $this->getUser();
		$role=$u->getRole();
		if($role == 'ROLE_SUPERUSER')
			$grupo=$_REQUEST['id'];
		else
			$grupo=$u->getGrupo();
		$informacion_grupos_descripcion = $this->informacion_grupos_descripcion($grupo);
		if(isset($_POST['solicitar']))
		{
			$ubicacion = $_POST['ubicacion'];
			$obtener_datos_acl = $this->obtener_datos_acl($grupo, $ubicacion);
			return $this->render('@Principal/acl/lista_acl.html.twig', array(
				"obtener_datos_acl"=>$obtener_datos_acl,
				'ubicacion'=>$ubicacion
			));
		}
		return $this->render('@Principal/plantillas/solicitud_grupo.html.twig', array(
			'informacion_grupos_descripcion'=>$informacion_grupos_descripcion
		));
	}

	public function editar_aclAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$acl = $em->getRepository("PrincipalBundle:Acl")->find($id);
		$form = $this->createForm(AclEditType::class,$acl);
		$obtener_grupo_ubicacion_acl = $this->obtener_grupo_ubicacion_acl($id);
		$target = $this->recuperar_nombre_target($obtener_grupo_ubicacion_acl[0]['ubicacion'],$obtener_grupo_ubicacion_acl[0]['grupo']);
		$obtener_target_acl = $this->obtener_target_acl($id);
		$target_rule= explode(' ',$obtener_target_acl[0]['targetRulesList']);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$array_target_rule = $_POST['target_rule'];
			$target_rule = implode(" ",$_POST['target_rule']);
			$acl->setTargetRule($target_rule." all [ all]");
			$acl->setTargetRulesList($target_rule);
			$em->persist($acl);
			$flush=$em->flush();
			return $this->redirectToRoute("grupos_acl");
		}
		return $this->render('@Principal/acl/editar_acl.html.twig', array(
			'form'=>$form->createView(),
			"target"=>$target,
			"target_rule"=>$target_rule
		));
	}

	public function eliminar_aclAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:Acl")->find($id);
		$em->remove($formato);
		$flush=$em->flush();
		if($flush == null)
			$estatus="Successfully delete registration";
		else
			$estatus="Problems with the server try later.";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("grupos_acl");
	}

	public function crear_xml_aclAction()
	{
		if(isset($_POST['enviar']))
		{
			foreach ($_POST['ip'] as $ips) 
			{
				$obtener_datos_grupos_xml = $this->obtener_datos_grupos_xml($ips);
				$contenido = "<?xml version='1.0'?>\n";
				$contenido .= "\t<squidguardacl>\n";
				foreach ($obtener_datos_grupos_xml as $acl) 
				{
				    $contenido .= "\t\t\t<config>\n";
				    $contenido .= "\t\t\t\t<disabled>" . $acl['estatus'] . "</disabled>\n";
				    $contenido .= "\t\t\t\t<name>" . $acl['nombre'] . "</name>\n";
				    $contenido .= "\t\t\t\t<source>" . $acl['cliente'] . "</source>\n";
				    $contenido .= "\t\t\t\t<time></time>\n";
				    $contenido .= "\t\t\t\t<dest>" . $acl['targetRule'] . "</dest>\n";
				    $contenido .= "\t\t\t\t<notallowingip>" . $acl['notAllowIp'] . "</notallowingip>\n";
				    $contenido .= "\t\t\t\t<redirect_mode>" . $acl['redirectMode'] . "</redirect_mode>\n";
				    $contenido .= "\t\t\t\t<redirect>" . $acl['redirect'] . "</redirect>\n";
				    $contenido .= "\t\t\t\t<safesearch></safesearch>\n";
				    $contenido .= "\t\t\t\t<rewrite></rewrite>\n";
				    $contenido .= "\t\t\t\t<overrewrite></overrewrite>\n";
				    $contenido .= "\t\t\t\t<description>" . $acl['descripcion'] . "</description>\n";
				    $contenido .= "\t\t\t\t<enablelog>" . $acl['log'] . "</enablelog>\n";
				    $contenido .= "\t\t\t</config>\n";
				}
			    $contenido .= "\t</squidguardacl>";
				$archivo = fopen("$ips.xml", 'w');
				fwrite($archivo, $contenido);
				fclose($archivo);
				$change_to_do = fopen("change_to_do.txt", 'w');
				fwrite($change_to_do,'aclgroups.py'."\n");
				fclose($change_to_do);
				$tipo_python = "change_to_do.txt";
				# Mover el archivo a la carpeta #
				$archivo_configuracion = "$ips.xml";
				$direccion_servidor = '/var/www/html/central-console/web/clients/Ejemplo_2/';
				$destino_configuracion = $direccion_servidor . $ips;
				$archivo_xml = $destino_configuracion . "/conf.xml";
			   	copy($archivo_configuracion, $archivo_xml);
			   	$archivo_tipo_python = $destino_configuracion . '/change_to_do.txt';
			   	copy($tipo_python, $archivo_tipo_python);
				unlink("$ips.xml");
				unlink("change_to_do.txt");
			}
			echo '<script> alert("The configuration has been saved.");</script>';
		}
		$id=$_REQUEST['id'];
		$grupos = $this->informacion_grupos_descripcion($id);
		return $this->render("@Principal/grupos/aplicar_cambios.html.twig", array(
			"grupos"=>$grupos
		));
	}

	# Area de consultas #
	# Funcion utilizada en grupos_acl #
	private function obtener_grupo_nombre()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT DISTINCT g.nombre FROM PrincipalBundle:Grupos g ORDER BY g.nombre ASC');
		$grupos = $query->getResult();
		return $grupos;
	}
	# Funcion utilizada en registro_acl #
	private function informacion_interfaces_plantel($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT DISTINCT descripcion FROM interfaces WHERE grupo = '$grupo'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$grupos=$stmt->fetchAll();
		return $grupos;
	}
	# Funcion utilizada en registro_acl #
	private function recuperar_nombre_target($ubicacion, $grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre, u.descripcion, u.ubicacion FROM PrincipalBundle:Target u WHERE  u.ubicacion = :ubicacion
				AND u.grupo =:grupo')->setParameter('ubicacion', $ubicacion)->setParameter('grupo', $grupo);
		$target = $query->getResult();
		return $target;
	}
	# Funcion utilizada en registro_acl #
	private function recuperar_nombre($nombre, $grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT u.nombre FROM PrincipalBundle:Acl u WHERE  u.nombre = :nombre
				AND u.ubicacion = :grupo')->setParameter('nombre', $nombre)->setParameter('grupo', $grupo);
		$datos = $query->getResult();
		return $datos;
	}
	# Funcion utilizada en lista_acl #
	private function informacion_grupos_descripcion($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT descripcion FROM grupos WHERE nombre = '$grupo'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$grupos=$stmt->fetchAll();
		return $grupos;
	}
	# Funcion para recuperar los todos los aliases #
	private function obtener_datos_acl($grupo, $ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.estatus, u.nombre, u.descripcion, u.grupo, u.ubicacion FROM PrincipalBundle:Acl u 
				WHERE  u.grupo = :grupo AND u.ubicacion = :ubicacion')->setParameter('grupo', $grupo)->setParameter('ubicacion', $ubicacion);
		$acl = $query->getResult();
		return $acl;
	}
	# Funcion utilizada en editar_acl #
	private function obtener_grupo_ubicacion_acl($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT u.grupo, u.ubicacion FROM PrincipalBundle:Acl u WHERE  u.id = :id')->setParameter('id', $id);
		$target = $query->getResult();
		return $target;
	}
	# Funcion utilizada en editar_acl #
	private function obtener_target_acl($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT u.targetRulesList FROM PrincipalBundle:Acl u WHERE  u.id = :id')->setParameter('id', $id);
		$datos = $query->getResult();
		return $datos;
	}
	# Funcion utilizada en crear_xml_acl #
	private function obtener_datos_grupos_xml($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT u.estatus, u.nombre, u.cliente, u.targetRule, u.notAllowIp, u.redirectMode, u.redirect, u.descripcion, u.log
			FROM PrincipalBundle:Acl u WHERE  u.ubicacion = :ubicacion')->setParameter('ubicacion', $grupo);
		$datos = $query->getResult();
		return $datos;
	}
}