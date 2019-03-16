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
		$grupo=$_REQUEST['id'];
		$ipGrupos = $this->ipInterfaces($grupo);
		$grupo_plantel=$u->getGrupo();
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$verificarNombre = $this->recuperarNombreId($form->get("nombre")->getData(), $grupo);
			if(count($verificarNombre)==0)
			{
				$plantel = $_POST['plantel'];
				$nombre = $form->get("nombre")->getData();
				$descripcion = $form->get("descripcion")->getData();
				$tipo = $form->get("tipo")->getData();
				$descripcion_ip_port = implode(" ||",$_POST['descripcion_ip_port']);
				$ip = $_POST['ip'];
				$file=fopen("arreglo.txt","w") or die("Problemas");
				foreach ($_POST['ip'] as $ips)
				{
					foreach ($_POST['ip_port'] as $p) 
					{
						$ip_port_res = $ips. $p . " ";
						fputs($file,$ip_port_res);
					}
					fputs($file,"|"."\n");
					$filas=file('arreglo.txt');
					foreach($filas as $value)
					{
						list($ip) = explode('|', $value);
					}
					$res = trim($ip, " |");
					$query2 = "INSERT INTO aliases VALUES (nextval('aliases_id_seq'),'$nombre','$descripcion','$tipo','$res','$descripcion_ip_port','$grupo_plantel','$plantel')";
					$stmt2 = $db->prepare($query2);
					$params2 =array();
					$stmt2->execute($params2);
				};
				unlink("arreglo.txt");
				if($stmt == null)
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
		$ubicacion = $_POST['ubicacion'];
		return $this->render('@Principal/aliases/registroAliases.html.twig', array(
			'form'=>$form->createView(),
			'ipGrupos'=>$ipGrupos,
			'grupo'=>$grupo
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
					$aliases = $this->recuperarTodoAliasesGrupo($grupo, $ubicacion);
					return $this->render('@Principal/aliases/listaAliases.html.twig', array(
						"aliases"=>$aliases,
						'ubicacion'=>$ubicacion
					));
		        }
		        if($role == "ROLE_ADMINISTRATOR")
		        {
		        	$aliases = $this->recuperarTodoAliasesGrupo($grupo, $ubicacion);
					return $this->render('@Principal/aliases/listaAliases.html.twig', array(
						"aliases"=>$aliases,
						'ubicacion'=>$ubicacion
					));
		        }
		        if($role == "ROLE_USER")
		        {
		        	$aliases = $this->recuperarTodoAliasesGrupo($grupo, $ubicacion);
					return $this->render('@Principal/aliases/listaAliases.html.twig', array(
						"aliases"=>$aliases,
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
	private function recuperarTodoAliasesGrupo($grupo, $ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre, u.ip_port, u.grupo, u.ubicacion
				FROM PrincipalBundle:Aliases u
				WHERE  u.grupo = :grupo
				AND u.ubicacion = :ubicacion'
		)->setParameter('grupo', $grupo)->setParameter('ubicacion', $ubicacion);
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
	public function crearXMLAliasesAction()
	{
		if(isset($_POST['enviar']))
		{
			//$ubicacion=$_POST['ubicacion'];
			foreach ($_POST['ip'] as $ips) 
			{
				$recuperarTodoDatos = $this->recuperarTodoDatos($ips);
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
				$archivo = fopen("$ips.xml", 'w');
				fwrite($archivo, $contenido);
				fclose($archivo);
				$change_to_do = fopen("change_to_do.txt", 'w');
				fwrite($change_to_do,'aliases.py'."\n");
				fclose($change_to_do);
				$changetodo = "change_to_do.txt";
				# Mover el archivo a la carpeta #
				$archivoConfig = "$ips.xml";
				$serv = '/var/www/html/central-console/web/clients/Ejemplo_2/';
				$destinoConfig = $serv . $ips;
				$res = $destinoConfig . "/conf.xml";
			   	copy($archivoConfig, $res);
			   	$destinoConfigchange_to_do = $destinoConfig . '/change_to_do.txt';
			   	copy($changetodo, $destinoConfigchange_to_do);
				unlink("$ips.xml");
				unlink("change_to_do.txt");
			}
			echo '<script> alert("The configuration has been saved.");</script>';
		}
		$id=$_REQUEST['id'];
		//ubicacion=$_REQUEST['ubicacion'];
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
			'SELECT u.id, u.nombre, u.tipo, u.descripcion, u.ip_port, u.descripcion_ip_port
				FROM PrincipalBundle:Aliases u
				WHERE  u.ubicacion = :ubicacion
				ORDER BY u.id'
		)->setParameter('ubicacion', $grupo);
		$datos = $query->getResult();
		return $datos;
	}
}