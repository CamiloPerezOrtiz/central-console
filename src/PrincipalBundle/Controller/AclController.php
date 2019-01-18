<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\Acl;
use PrincipalBundle\Form\AclType;
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
		$id=$_REQUEST['id'];
		$target = $this->recuperarNombreTarget($id);
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$verificarNombre = $this->recuperarNombreId($form->get("nombre")->getData());
			if(count($verificarNombre)==0)
			{
				$u = $this->getUser();
				$role=$u->getRole();
				if($role == 'ROLE_SUPERUSER')
				{
					//$id=$_REQUEST['id'];
					$acl->setGrupo($id);
				}
				if($role == 'ROLE_ADMINISTRATOR')
					$acl->setGrupo($u->getGrupo());
				if($_POST['target_rule'] == "none")
				 	$acl->setTargetRulesList("all [ all]");
				else
				{
					$target_rule = implode(" ",$_POST['target_rule']);
					$acl->setTargetRulesList($target_rule." all [ all]");
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
		$form = $this->createForm(AclType::class,$acl);
		$target = $this->recuperarNombreTarget($id);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em->persist($acl);
			$flush=$em->flush();
			return $this->redirectToRoute("gruposAcl");
		}
		return $this->render('@Principal/acl/registroAcl.html.twig', array(
			'form'=>$form->createView(),
			"target"=>$target
		));
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
		foreach ($recuperarTodoDatos as $target) 
		{
		    $contenido .= "\t\t\t<config>\n";
		    $contenido .= "\t\t\t\t<name>" . $target['nombre'] . "</name>\n";
		    $contenido .= "\t\t\t\t<domains>" . $target['domainList'] . "</domains>\n";
		    $contenido .= "\t\t\t\t<urls>" . $target['urlList'] . "</urls>\n";
		    $contenido .= "\t\t\t\t<expressions>" . $target['regularExpression'] . "</expressions>\n";
		    $contenido .= "\t\t\t\t<redirect_mode>" . $target['redirectMode'] . "</redirect_mode>\n";
		    $contenido .= "\t\t\t\t<redirect>" . $target['redirect'] . "</redirect>\n";
		    $contenido .= "\t\t\t\t<description>" . $target['descripcion'] . "</description>\n";
		    if($target['log'] == true)
		    	$contenido .= "\t\t\t\t<enablelog>on</enablelog>\n";
		    else
		    	$contenido .= "\t\t\t\t<enablelog>" . $target['log'] . "</enablelog>\n";
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
		return $this->redirectToRoute("gruposTarget");
	}

	# Funcion para recuperar toda la informacion de target #
	private function recuperarTodoDatos($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.nombre, u.domainList, u.urlList, u.regularExpression, u.redirectMode, u.redirect, u.descripcion, u.log
				FROM PrincipalBundle:Target u
				WHERE  u.grupo = :grupo'
		)->setParameter('grupo', $grupo);
		$datos = $query->getResult();
		return $datos;
	}

	# funcion para correr el script aplicar cambios en target #
	public function aplicarXMLTargetAction($id)
	{
    	$archivo = fopen("change_to_do.txt", 'w');
		fwrite($archivo, "targetcategories.py");
		fwrite ($archivo, "\n");
		fclose($archivo); 
		# Mover el archivo a la carpeta #
		$archivoConfig = 'change_to_do.txt';
		$destinoConfig = "centralizedConsole/change_to_do.txt";
	   	if (!copy($archivoConfig, $destinoConfig)) 
		   echo "Error al copiar $archivoConfig...\n";
		unlink("change_to_do.txt");
    	return $this->redirectToRoute('gruposTarget');
	}
}
