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
		$role=$u->getRole();
		if($role == 'ROLE_SUPERUSER')
			$id=$_REQUEST['id'];
		else
			$id=$u->getGrupo();
		$target = $this->recuperarNombreTarget($id);
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$verificarNombre = $this->recuperarNombreId($form->get("nombre")->getData());
			if(count($verificarNombre)==0)
			{
				if($role == 'ROLE_SUPERUSER')
					$acl->setGrupo($id);
				if($role == 'ROLE_ADMINISTRATOR')
					$acl->setGrupo($u->getGrupo());
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
			{
				$estatus="The name of alias that you are trying to register already exists try again.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		return $this->render('@Principal/acl/registroAcl.html.twig', array(
			'form'=>$form->createView(),
			"target"=>$target
		));
	}

	# Funcion para recuperar nombre por id #
	private function recuperarNombreId($nombre)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.nombre
				FROM PrincipalBundle:Acl u
				WHERE  u.nombre = :nombre'
		)->setParameter('nombre', $nombre);
		$datos = $query->getResult();
		return $datos;
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarNombreTarget($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre, u.descripcion
				FROM PrincipalBundle:Target u
				WHERE  u.grupo = :grupo'
		)->setParameter('grupo', $grupo);
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
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$id=$_REQUEST['id'];
				$acl = $this->recuperarTodoAclGrupo($id);
				return $this->render('@Principal/acl/listaAcl.html.twig', array(
					"acl"=>$acl
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR")
	        {
	        	$acl = $this->recuperarTodoAclGrupo($grupo);
				return $this->render('@Principal/acl/listaAcl.html.twig', array(
					"acl"=>$acl
				));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$acl = $this->recuperarTodoAclGrupo($grupo);
				return $this->render('@Principal/acl/listaAcl.html.twig', array(
					"acl"=>$acl
				));
	        }
	    }
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarTodoAclGrupo($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.estatus, u.nombre, u.descripcion, u.grupo
				FROM PrincipalBundle:Acl u
				WHERE  u.grupo = :grupo'
		)->setParameter('grupo', $grupo);
		$acl = $query->getResult();
		return $acl;
	}

	public function editarAclAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$acl = $em->getRepository("PrincipalBundle:Acl")->find($id);
		$form = $this->createForm(AclEditType::class,$acl);
		$grupo = $this->recuperarGrupoTarget($id);
		$target = $this->recuperarNombreTarget($grupo);
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
			'SELECT u.grupo
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

	# Funcion para crear el XML de target #
	public function crearXMLAclAction($id)
	{
		$recuperarTodoDatos = $this->recuperarTodoDatos($id);
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
		$archivo = fopen("conf.xml", 'w');
		fwrite($archivo, $contenido);
		fclose($archivo); 
		# Mover el archivo a la carpeta #
		$archivoConfig = 'conf.xml';
		$destinoConfig = "centralizedConsole/conf.xml";
	   	if (!copy($archivoConfig, $destinoConfig)) 
		    echo "Error al copiar $archivoConfig...\n";
		unlink("conf.xml");
		$estatus="The configuration has been saved";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("gruposAcl");
	}

	# Funcion para recuperar toda la informacion de target #
	private function recuperarTodoDatos($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.estatus, u.nombre, u.cliente, u.targetRule, u.notAllowIp, u.redirectMode, u.redirect, u.descripcion, u.log
				FROM PrincipalBundle:Acl u
				WHERE  u.grupo = :grupo'
		)->setParameter('grupo', $grupo);
		$datos = $query->getResult();
		return $datos;
	}

	# funcion para correr el script aplicar cambios en target #
	public function aplicarXMLAclAction($id)
	{
    	$archivo = fopen("change_to_do.txt", 'w');
		fwrite($archivo, "aclgroups.py");
		fwrite ($archivo, "\n");
		fclose($archivo); 
		# Mover el archivo a la carpeta #
		$archivoConfig = 'change_to_do.txt';
		$destinoConfig = "centralizedConsole/change_to_do.txt";
	   	if (!copy($archivoConfig, $destinoConfig)) 
		   echo "Error al copiar $archivoConfig...\n";
		unlink("change_to_do.txt");
    	return $this->redirectToRoute('grupos');
	}
}
