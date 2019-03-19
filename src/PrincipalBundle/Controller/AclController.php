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

	# Funcion para registrar un nuevo aliases #
	public function registroAclAction(Request $request)
	{
		$acl = new Acl();
		$form = $this ->createForm(AclType::class, $acl);
		$form->handleRequest($request);
		$u = $this->getUser();
		$grupo=$_REQUEST['id'];
		$ipGrupos = $this->ipInterfaces($grupo);
		$grupo_plantel=$u->getGrupo();
		if($form->isSubmitted() && $form->isValid())
		{
			#$ubicacion = $_REQUEST['ubicacion'];
			$em = $this->getDoctrine()->getEntityManager();
			$verificarNombre = $this->recuperarNombreId($form->get("nombre")->getData(), $grupo_plantel);
			if(count($verificarNombre)==0)
			{
				$acl->setGrupo($grupo_plantel);
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
				$acl->setUbicacion($grupo);
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
				echo '<script> alert("The name of alias that you are trying to register already exists try again.");window.history.go(-1);</script>';
		}
		/*if(isset($_POST['solicitar']))
		{
			$ubicacion = $_POST['ubicacion'];
			$target = $this->recuperarNombreTarget($ubicacion, $id);
			return $this->render('@Principal/acl/registroAcl.html.twig', array(
				'form'=>$form->createView(),
				"target"=>$target,
				'ipGrupos'=>$ipGrupos,
				'ubicacion'=>$ubicacion
			));
		}*/
		//$ubicacion = $_POST['ubicacion'];
		$target = $this->recuperarNombreTarget($grupo, $grupo_plantel);
		return $this->render('@Principal/acl/registroAcl.html.twig', array(
			'form'=>$form->createView(),
			'ipGrupos'=>$ipGrupos,
			'grupo'=>$grupo,
			'target'=>$target,
		));
	}

	# Funcion para recuperar nombre por id #
	private function recuperarNombreId($nombre, $grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.nombre
				FROM PrincipalBundle:Acl u
				WHERE  u.nombre = :nombre
				AND u.grupo = :grupo'
		)->setParameter('nombre', $nombre)->setParameter('grupo', $grupo);
		$datos = $query->getResult();
		return $datos;
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

	private function ipInterfaces($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM interfaces WHERE descripcion = '$grupo'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$grupos=$stmt->fetchAll();
		return $grupos;
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarNombreTarget($ubicacion, $grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre, u.descripcion, u.ubicacion
				FROM PrincipalBundle:Target u
				WHERE  u.ubicacion = :ubicacion
				AND u.grupo =:grupo'
		)->setParameter('ubicacion', $ubicacion)->setParameter('grupo', $grupo);
		$target = $query->getResult();
		return $target;
	}

	# Funcion para mostrar los grupos #
	public function gruposAclAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$grupos = $this->recuperarTodosGrupos();
				return $this->render("@Principal/acl/gruposAcl.html.twig", array(
					"grupo"=>$grupos
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR" or $role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("listaAcl");
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

	# Funcion para mostrar los acl #
	public function listaAclAction()
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
			#$u = $this->getUser();
			$ubicacion = $_POST['ubicacion'];
			if($u != null)
			{
		        #$role=$u->getRole();
		        #$grupo=$u->getGrupo();
		        $ubicacion = $_REQUEST['ubicacion'];
		        if($role == "ROLE_SUPERUSER")
		        {
		        	#$id=$_REQUEST['id'];
					$acl = $this->recuperarTodoAclGrupo($grupo, $ubicacion);
					return $this->render('@Principal/acl/listaAcl.html.twig', array(
						"acl"=>$acl,
						'ubicacion'=>$ubicacion
					));
		        }
		        if($role == "ROLE_ADMINISTRATOR")
		        {
		        	$acl = $this->recuperarTodoAclGrupo($grupo, $ubicacion);
					return $this->render('@Principal/acl/listaAcl.html.twig', array(
						"acl"=>$acl,
						'ubicacion'=>$ubicacion
					));
		        }
		        if($role == "ROLE_USER")
		        {
		        	$acl = $this->recuperarTodoAclGrupo($grupo, $ubicacion);
					return $this->render('@Principal/acl/listaAcl.html.twig', array(
						"acl"=>$acl,
						'ubicacion'=>$ubicacion
					));
		        }
		    }
		}
		return $this->render('@Principal/plantillas/solicitudGrupo.html.twig', array(
			'ipGrupos'=>$ipGrupos
		));
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarTodoAclGrupo($grupo, $ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.estatus, u.nombre, u.descripcion, u.grupo, u.ubicacion
				FROM PrincipalBundle:Acl u
				WHERE  u.grupo = :grupo
				AND u.ubicacion = :ubicacion'
		)->setParameter('grupo', $grupo)->setParameter('ubicacion', $ubicacion);
		$acl = $query->getResult();
		return $acl;
	}

	public function editarAclAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$acl = $em->getRepository("PrincipalBundle:Acl")->find($id);
		$form = $this->createForm(AclEditType::class,$acl);
		$grupo = $this->recuperarGrupoTarget($id);
		$target = $this->recuperarNombreTarget($grupo[0]['ubicacion'],$grupo[0]['grupo']);
		$recuperarDatosId = $this->recuperarDatosId($id);
		$target_rule= explode(' ',$recuperarDatosId[0]['targetRulesList']);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
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
			return $this->redirectToRoute("gruposAcl");
		}
		return $this->render('@Principal/acl/editarAcl.html.twig', array(
			'form'=>$form->createView(),
			"target"=>$target,
			"target_rule"=>$target_rule
		));
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarGrupoTarget($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.grupo, u.ubicacion
				FROM PrincipalBundle:Acl u
				WHERE  u.id = :id'
		)->setParameter('id', $id);
		$target = $query->getResult();
		return $target;
	}

	private function recuperarDatosId($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.targetRulesList
				FROM PrincipalBundle:Acl u
				WHERE  u.id = :id'
		)->setParameter('id', $id);
		$datos = $query->getResult();
		return $datos;
	}

	# Funcion para eliminar un target #
	public function eliminarAclAction($id)
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
		return $this->redirectToRoute("gruposAcl");
	}

	public function crearXMLAclAction()
	{
		if(isset($_POST['enviar']))
		{
			foreach ($_POST['ip'] as $ips) 
			{
				$recuperarTodoDatos = $this->recuperarTodoDatos($ips);
				$contenido = "<?xml version='1.0'?>\n";
				$contenido .= "\t<squidguardacl>\n";
				foreach ($recuperarTodoDatos as $acl) 
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
				#change_to_do#
				$change_to_do = fopen("change_to_do.txt", 'w');
				fwrite($change_to_do,'aclgroups.py'."\n");
				fclose($change_to_do);
				$changetodo = "change_to_do.txt";
				# Mover el archivo a la carpeta #
				$archivoConfig = "conf.xml";
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
		$grupos = $this->ipGruposSolo($id);
		return $this->render("@Principal/grupos/aplicarCambios.html.twig", array(
			"grupos"=>$grupos
		));
	}

	private function ipGruposSolo($grupo)
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

	# Funcion para recuperar toda la informacion de target #
	private function recuperarTodoDatos($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.estatus, u.nombre, u.cliente, u.targetRule, u.notAllowIp, u.redirectMode, u.redirect, u.descripcion, u.log
				FROM PrincipalBundle:Acl u
				WHERE  u.ubicacion = :ubicacion'
		)->setParameter('ubicacion', $grupo);
		$datos = $query->getResult();
		return $datos;
	}
}
