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

	# Funcion para mostrar el nombre de los grupos #
	public function grupos_aliasesAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$grupos = $this->obtener_grupo_nombre();
				return $this->render("@Principal/aliases/grupos_aliases.html.twig", array(
					"grupo"=>$grupos
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR" or $role == "ROLE_USER")
	        	return $this->redirectToRoute("lista_aliases");
	    }
	}

	# Funcion registro de aliases #
	public function registro_aliasesAction(Request $request)
	{
		$alias = new Aliases();
		$form = $this ->createForm(AliasesType::class, $alias);
		$form->handleRequest($request);
		$u = $this->getUser();
		$grupo=$_REQUEST['id'];
		$informacion_interfaces_plantel = $this->informacion_interfaces_plantel($grupo);
		$informacion_interfaces_nombre = $this->informacion_interfaces_nombre($grupo);
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$recuperar_nombre = $this->recuperar_nombre($form->get("nombre")->getData(), $grupo);
			if(count($recuperar_nombre)==0)
			{
				# Variables obtenidas en el formulario #
				$nombre_interfas = $_POST['nombre_interfas'];
				$nombre = $form->get("nombre")->getData();
				$descripcion = $form->get("descripcion")->getData();
				$tipo = $form->get("tipo")->getData();
				//ip = $_POST['ip'];
				$descripcion_ip_port = implode(" ||",$_POST['descripcion_ip_port']);
				$archivo_ip_interfaces=fopen("archivo_ip_interfaces.txt","w") or die("Problemas con el servidor intente mas tarde.");
				# Consulta para obtener la ip de la interfaz selecionada #
				foreach($_POST['plantel'] as $plantel_grupo)
				{
					$informacion_interfaces_ip = $this->informacion_interfaces_ip($nombre_interfas, $plantel_grupo);
					# Crear archivo temporal de las IPs #
					$archivo_ip=fopen("archivo_ip.txt","w") or die("Problemas con el servidor intente mas tarde.");
					foreach ($informacion_interfaces_ip as $obtener_ip_interfas) 
					{
						foreach ($obtener_ip_interfas as $ip_interfas) 
						{
							foreach ($_POST['ip_port'] as $octeto_ip)
							{
								$ip_completa = $ip_interfas . $octeto_ip . " ";
								# Guardar IP completa en el archivo temporal #
								fputs($archivo_ip,$ip_completa);
							}
							# Formato para la lectura del archivo temporal #
							fputs($archivo_ip,"|"."\n");
							$delimitador=file('archivo_ip.txt');
							foreach($delimitador as $dem)
							{
								list($ip_interfas) = explode('|', $dem);
							}
							$formato_delimitador = trim($ip_interfas, " |");
							$insertar = "INSERT INTO aliases VALUES (nextval('aliases_id_seq'),'$nombre','$descripcion','$tipo','$formato_delimitador','$descripcion_ip_port','$grupo','$plantel_grupo')";
							$stmt = $db->prepare($insertar);
							$params =array();
							$stmt->execute($params);
						}
					}
				}
				unlink("archivo_ip.txt");
				if($stmt == null)
				{
					$estatus="Problems with the server try later.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
				else
				{
					$estatus="Successfully registration.";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("grupos_aliases");
				}
			}
			else
				echo '<script>alert("The name you are trying to register already exists in device ' . $plantel_grupo .'. Try again.");</script>';
		}
		return $this->render('@Principal/aliases/registro_aliases.html.twig', array(
			'form'=>$form->createView(),
			'informacion_interfaces_plantel'=>$informacion_interfaces_plantel,
			'informacion_interfaces_nombre'=>$informacion_interfaces_nombre,
			'grupo'=>$grupo
		));
	}

	# Funcion ver lista de aliases #
	public function lista_aliasesAction()
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
			$aliases = $this->obtener_datos_aliases($grupo, $ubicacion);
			return $this->render('@Principal/aliases/lista_aliases.html.twig', array(
				"aliases"=>$aliases,
				'ubicacion'=>$ubicacion
			));
		}

	    return $this->render('@Principal/plantillas/solicitud_grupo.html.twig', array(
			'informacion_grupos_descripcion'=>$informacion_grupos_descripcion
		));
	}

	public function editar_aliasesAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$alias = $em->getRepository("PrincipalBundle:Aliases")->find($id);
		$form = $this->createForm(AliasesType::class,$alias);
		$form->handleRequest($request);
		$informacion_aliases_ip_descripcion = $this->informacion_aliases_ip_descripcion($id);
		$ip_port= explode(' ',$informacion_aliases_ip_descripcion[0]['ip_port']);
		$descripcion_ip_port = explode(" ||",$informacion_aliases_ip_descripcion[0]['descripcion_ip_port']);
		if($form->isSubmitted() && $form->isValid())
		{
			$ip_port = implode(" ",$_POST['ip_port']);
			$descripcion_ip_port = implode(" ||",$_POST['descripcion_ip_port']);
			$alias->setIp_port($ip_port);
			$alias->setDescripcion_ip_port($descripcion_ip_port);
			$em->persist($alias);
			$flush=$em->flush();
			return $this->redirectToRoute("grupos_aliases");
		}
		return $this->render("@Principal/aliases/editar_aliases.html.twig",array(
			"form"=>$form->createView(),
			"ip_port"=>$ip_port,
			"descripcion_ip_port"=>$descripcion_ip_port
		));
	}

	public function eliminar_aliasesAction($id)
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
		return $this->redirectToRoute("grupos_aliases");
	}

	public function crear_xml_aliasesAction()
	{
		if(isset($_POST['enviar']))
		{
			foreach ($_POST['ip'] as $ips) 
			{
				$obtener_datos_grupos_xml = $this->obtener_datos_grupos_xml($ips);
				$contenido = "<?xml version='1.0'?>\n";
				$contenido .= "\t<aliases>\n";
				foreach ($obtener_datos_grupos_xml as $alias) 
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
	# Funcion utilizada en registro_aliases #
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
	# Funcion utilizada en registro_aliases #	
	private function informacion_interfaces_nombre($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT DISTINCT nombre FROM interfaces WHERE grupo = '$grupo'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$grupos=$stmt->fetchAll();
		return $grupos;
	}
	# Funcion utilizada en registro_aliases #
	private function informacion_interfaces_ip($nombre, $plantel)
	{
		$em = $this->getDoctrine()->getEntityManager();
	    $db = $em->getConnection();
		$query = "SELECT DISTINCT ip FROM interfaces WHERE nombre = '$nombre' AND descripcion = '$plantel'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$grupos=$stmt->fetchAll();
		return $grupos;
	}
	# Funcion utilizada en registro_aliases #
	private function recuperar_nombre($nombre, $grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT u.nombre FROM PrincipalBundle:Aliases u WHERE  u.nombre = :nombre
				AND u.ubicacion = :grupo')->setParameter('nombre', $nombre)->setParameter('grupo', $grupo);
		$datos = $query->getResult();
		return $datos;
	}
	# Funcion utilizada en lista_aliases #
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
	# Funcion utilizada en lista_aliases #
	private function obtener_datos_aliases($grupo, $ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT u.id, u.nombre, u.ip_port, u.grupo, u.ubicacion FROM PrincipalBundle:Aliases u
			WHERE  u.grupo = :grupo AND u.ubicacion = :ubicacion')->setParameter('grupo', $grupo)->setParameter('ubicacion', $ubicacion);
		$grupoAliases = $query->getResult();
		return $grupoAliases;
	}
	# Funcion utilizada en editar_aliases #
	private function informacion_aliases_ip_descripcion($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT u.ip_port, u.descripcion_ip_port FROM PrincipalBundle:Aliases u WHERE  u.id = :id')->setParameter('id', $id);
		$datos = $query->getResult();
		return $datos;
	}
	# Funcion utilizada en crear_xml_aliases #
	private function obtener_datos_grupos_xml($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT u.id, u.nombre, u.tipo, u.descripcion, u.ip_port, u.descripcion_ip_port
			FROM PrincipalBundle:Aliases u WHERE  u.ubicacion = :ubicacion ORDER BY u.id')->setParameter('ubicacion', $grupo);
		$datos = $query->getResult();
		return $datos;
	}
	# Funcion utilizada en grupos_aliases #
	private function obtener_grupo_nombre()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT DISTINCT g.nombre FROM PrincipalBundle:Grupos g ORDER BY g.nombre ASC');
		$grupos = $query->getResult();
		return $grupos;
	}
}