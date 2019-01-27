<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\Target;
use PrincipalBundle\Form\TargetType;
use Symfony\Component\HttpFoundation\Session\Session;

class TargetController extends Controller
{
	# Variables #
	private $session; 

	# Constructor # 
	public function __construct()
	{
		$this->session = new Session();
	}

	# Funcion para registrar un nuevo aliases #
	public function registroTargetAction(Request $request)
	{
		$target = new Target();
		$form = $this ->createForm(TargetType::class, $target);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$verificarNombre = $this->recuperarNombreId($form->get("nombre")->getData());
			if(count($verificarNombre)==0)
			{
				$u = $this->getUser();
				//$grupo=$u->getGrupo();
				$role=$u->getRole();
				if($role == 'ROLE_SUPERUSER')
				{
					$id=$_REQUEST['id'];
					$target->setGrupo($id);
				}
				if($role == 'ROLE_ADMINISTRATOR')
					$target->setGrupo($u->getGrupo());
				$em->persist($target);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="Successfully registration.";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("gruposTarget");
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
		return $this->render('@Principal/target/registroTarget.html.twig', array(
			'form'=>$form->createView()
		));
	}

	# Funcion para recuperar nombre por id #
	private function recuperarNombreId($nombre)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.nombre
				FROM PrincipalBundle:Target u
				WHERE  u.nombre = :nombre'
		)->setParameter('nombre', $nombre);
		$datos = $query->getResult();
		return $datos;
	}

	# Funcion para mostrar los grupos #
	public function gruposTargetAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$grupos = $this->recuperarTodosGrupos();
				return $this->render("@Principal/target/gruposTarget.html.twig", array(
					"grupo"=>$grupos
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR" or $role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("listaTarget");
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
	public function listaTargetAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$id=$_REQUEST['id'];
				$target = $this->recuperarTodoTargetGrupo($id);
				return $this->render('@Principal/target/listaTarget.html.twig', array(
					"target"=>$target
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR")
	        {
	        	$target = $this->recuperarTodoTargetGrupo($grupo);
				return $this->render('@Principal/target/listaTarget.html.twig', array(
					"target"=>$target
				));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$target = $this->recuperarTodoTargetGrupo($grupo);
				return $this->render('@Principal/target/listaTarget.html.twig', array(
					"target"=>$target
				));
	        }
	    }
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarTodoTargetGrupo($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre, u.redirect, u.descripcion, u.grupo
				FROM PrincipalBundle:Target u
				WHERE  u.grupo = :grupo'
		)->setParameter('grupo', $grupo);
		$target = $query->getResult();
		return $target;
	}

	public function editarTargetAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$target = $em->getRepository("PrincipalBundle:Target")->find($id);
		$form = $this->createForm(TargetType::class,$target);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em->persist($target);
			$flush=$em->flush();
			return $this->redirectToRoute("gruposTarget");
		}
		return $this->render('@Principal/target/registroTarget.html.twig', array(
			'form'=>$form->createView()
		));
	}

	# Funcion para eliminar un target #
	public function eliminarTargetAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:Target")->find($id);
		$em->remove($formato);
		$flush=$em->flush();
		if($flush == null)
			$estatus="Successfully delete registration";
		else
			$estatus="Problems with the server try later.";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("gruposTarget");
	}

	# Funcion para crear el XML de target #
	public function crearXMLTargetAction($id)
	{
		$recuperarTodoDatos = $this->recuperarTodoDatos($id);
		$contenido = "<?xml version='1.0'?>\n";
		$contenido .= "\t<squidguarddest>\n";
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
		$contenido .= "\t</squidguarddest>";
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
    	return $this->redirectToRoute('grupos');
	}
}