<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\Aliases;
use PrincipalBundle\Form\AliasesType;
use Symfony\Component\HttpFoundation\Session\Session;

class AliasesController extends Controller
{
	# Variables #
	private $session; 

	# Constructor # 
	public function __construct()
	{
		$this->session = new Session();
	}

	# Funcion para registrar un nuevo aliases #
	public function registroAliasesAction(Request $request)
	{
		$alias = new Aliases();
		$form = $this ->createForm(AliasesType::class, $alias);
		$form->handleRequest($request);
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
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$verificarNombre = $this->recuperarNombreId($form->get("nombre")->getData(), $grupo);
			if(count($verificarNombre)==0)
			{
				$u = $this->getUser();
				$grupo=$u->getGrupo();
				$role=$u->getRole();
				$ip_port = implode(" ",$_POST['ip_port']);
				$descripcion_ip_port = implode(" ||",$_POST['descripcion_ip_port']);
				$alias->setIp_port($ip_port);
				$alias->setDescripcion_ip_port($descripcion_ip_port);
				if($role == 'ROLE_SUPERUSER')
				{
					$id=$_REQUEST['id'];
					$alias->setGrupo($id);
				}
				if($role == 'ROLE_ADMINISTRATOR')
					$alias->setGrupo($grupo);
				$em->persist($alias);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="Successfully registration.";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("gruposAliases");
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
		return $this->render('@Principal/aliases/registroAliases.html.twig', array(
			'form'=>$form->createView(),
			'ipGrupos'=>$ipGrupos
		));
	}

	# Funcion para recuperar nombre por id #
	private function recuperarNombreId($nombre, $grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.nombre
				FROM PrincipalBundle:Aliases u
				WHERE  u.nombre = :nombre
				AND u.grupo = :grupo'
		)->setParameter('nombre', $nombre)->setParameter('grupo', $grupo);
		$datos = $query->getResult();
		return $datos;
	}

	# Funcion para aplicar cambios #
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

	# Funcion para recuperar los todos los aliases #
	private function recuperarIp($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT * FROM grupos WHERE nombre = '$grupo' ORDER BY id";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$ip=$stmt->fetchAll();
		return $ip;
	}

	# Funcion para mostrar los grupos #
	public function gruposAliasesAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$grupos = $this->recuperarTodosGrupos();
				return $this->render("@Principal/aliases/gruposAliases.html.twig", array(
					"grupo"=>$grupos
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR" or $role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("listaAliases");
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

	# Funcion para mostrar los aliases #
	public function listaAliasesAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$id=$_REQUEST['id'];
				$aliases = $this->recuperarTodoAliasesGrupo($id);
				return $this->render('@Principal/aliases/listaAliases.html.twig', array(
					"aliases"=>$aliases
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR")
	        {
	        	$aliases = $this->recuperarTodoAliasesGrupo($grupo);
				return $this->render('@Principal/aliases/listaAliases.html.twig', array(
					"aliases"=>$aliases
				));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$aliases = $this->recuperarTodoAliasesGrupo($grupo);
				return $this->render('@Principal/aliases/listaAliases.html.twig', array(
					"aliases"=>$aliases
				));
	        }
	    }
	}

	# Funcion para recuperar los todos los aliases #
	private function recuperarTodoAliasesGrupo($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre, u.ip_port, u.grupo
				FROM PrincipalBundle:Aliases u
				WHERE  u.grupo = :grupo'
		)->setParameter('grupo', $grupo);
		$grupoAliases = $query->getResult();
		return $grupoAliases;
	}

	public function editarAliasesAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$alias = $em->getRepository("PrincipalBundle:Aliases")->find($id);
		$form = $this->createForm(AliasesType::class,$alias);
		$form->handleRequest($request);
		$recuperarDatosId = $this->recuperarDatosId($id);
		$ip_port= explode(' ',$recuperarDatosId[0]['ip_port']);
		$descripcion_ip_port = explode(" ||",$recuperarDatosId[0]['descripcion_ip_port']);
		if($form->isSubmitted() && $form->isValid())
		{
			$ip_port = implode(" ",$_POST['ip_port']);
			$descripcion_ip_port = implode(" ||",$_POST['descripcion_ip_port']);
			$alias->setIp_port($ip_port);
			$alias->setDescripcion_ip_port($descripcion_ip_port);
			$em->persist($alias);
			$flush=$em->flush();
			return $this->redirectToRoute("gruposAliases");
		}
		return $this->render("@Principal/aliases/editarAliases.html.twig",array(
			"form"=>$form->createView(),
			"ip_port"=>$ip_port,
			"descripcion_ip_port"=>$descripcion_ip_port
		));
	}

	private function recuperarDatosId($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.ip_port, u.descripcion_ip_port
				FROM PrincipalBundle:Aliases u
				WHERE  u.id = :id'
		)->setParameter('id', $id);
		$datos = $query->getResult();
		return $datos;
	}

	# Funcion para eliminar un aliases #
	public function eliminarAliasesAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:Aliases")->find($id);
		$em->remove($formato);
		$flush=$em->flush();
		if($flush == null)
			$estatus="Successfully delete registration";
		else
			$estatus="Problems with the server try later.";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("gruposAliases");
	}

	# Funcion para crear el XML de aliases #
	public function crearXMLAliasesAction($id)
	{
		$recuperarTodoDatos = $this->recuperarTodoDatos($id);
		$contenido = "<?xml version='1.0'?>\n";
		$contenido .= "\t<aliases>\n";
		foreach ($recuperarTodoDatos as $alias) 
		{
			$contenido .= "\t\t<alias>\n";
		    $contenido .= "\t\t\t<name>" . $alias['nombre'] . "</name>\n";
		    $contenido .= "\t\t\t<type>" . $alias['tipo'] . "</type>\n";
		    $contenido .= "\t\t\t<address>" . $alias['ip_port'] . "</address>\n";
		    $contenido .= "\t\t\t<descr>" . $alias['descripcion'] . "</descr>\n";
		    $contenido .= "\t\t\t<detail>" . $alias['descripcion_ip_port'] . "</detail>\n";
		    $contenido .= "\t\t</alias>\n";
		}
		$contenido .= "\t</aliases>";
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
		return $this->redirectToRoute("gruposAliases");
	}

	# Funcion para recuperar toda la informacion de target #
	private function recuperarTodoDatos($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.nombre, u.tipo, u.descripcion, u.ip_port, u.descripcion_ip_port
				FROM PrincipalBundle:Aliases u
				WHERE  u.grupo = :grupo'
		)->setParameter('grupo', $grupo);
		$datos = $query->getResult();
		return $datos;
	}

	# funcion para correr el script aplicar cambios en aliases #
	public function aplicarXMLAliasesAction($id)
	{
    	$archivo = fopen("change_to_do.txt", 'w');
		fwrite($archivo, "aliases.py");
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