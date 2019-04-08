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

	public function lista_aliasesAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
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
			$xml = simplexml_load_file("clients/Ejemplo_2/$ubicacion/info_aliases.xml");
			$aliases = $this->obtener_datos_aliases($grupo, $ubicacion);
			return $this->render('@Principal/aliases/lista_aliases.html.twig', array(
				"aliases"=>$aliases,
				'ubicacion'=>$ubicacion,
				'xmls'=>$xmls= $xml->alias
			));
		}
	    return $this->render('@Principal/plantillas/solicitud_grupo.html.twig', array(
			'informacion_grupos_descripcion'=>$informacion_grupos_descripcion
		));
	}

	public function editar_aliasesAction()
	{
		$plantel=$_POST['plantel'];
		$xml = simplexml_load_file("clients/Ejemplo_2/$plantel/info_aliases.xml");
		if(isset($_POST['guardar']))
		{
			$ip_port = implode(" ",$_POST['ip_port']);
			$descripcion_ip_port = implode("||",$_POST['descripcion_ip_port']);
			foreach($xml->alias as $alias)
			{
				if($alias->name==$_POST['nombre'])
				{
					$alias->descr = $_POST['descripcion'];
					$alias->type = $_POST['tipo'];
					$alias->address = $ip_port;
					$alias->detail = $descripcion_ip_port;
				}
			}
			$xml->asXML('clients/Ejemplo_2/Plantel_Xola/info_aliases.xml');
			return $this->redirectToRoute("grupos_aliases");
		}
		foreach($xml->alias as $alias)
		{
			if($alias->name== $_POST['valor'] )
			{
				$nombre = $alias->name;
				$descripcion = $alias->descr;
				$tipo = $alias->type;
				$valor = $alias->address;
				$ip_port= explode(" ",$valor);
				$detalles = $alias->detail;
				$descripcion_ip_port = explode("||",$detalles);
				break;
			}
		}
		return $this->render("@Principal/aliases/editar_aliases.html.twig",array(
			"ip_port"=>$ip_port,
			"descripcion_ip_port"=>$descripcion_ip_port,
			"nombre"=>$nombre,
			"descripcion"=>$descripcion,
			"tipo"=>$tipo
		));
	}

	public function eliminar_aliasesAction()
	{
		$plantel=$_POST['plantel'];
		$libreria_dom = new \DOMDocument; 
	    $libreria_dom->load("clients/Ejemplo_2/$plantel/info_aliases.xml");
	    $aliases = $libreria_dom->documentElement;
	    $alias = $aliases->getElementsByTagName('alias');
	    foreach ($alias as $nodo) 
	    {
	    	$uri = $nodo->getElementsByTagName('name');
        	$valor = $uri->item(0)->nodeValue;
	        if($valor == $_POST['valor'])
	            $aliases->removeChild($nodo);
	    }
	    echo $libreria_dom->save('clients/Ejemplo_2/Plantel_Norte/info_aliases.xml');
		return $this->redirectToRoute("grupos_aliases");
	}

	# Funcion registro de aliases #
	public function registro_aliasesAction(Request $request)
	{
		$grupo=$_REQUEST['id'];
		$informacion_interfaces_plantel = $this->informacion_interfaces_plantel($grupo);
		$informacion_interfaces_nombre = $this->informacion_interfaces_nombre($grupo);
		if(isset($_POST['guardar']))
		{
			$nombre_interfas = $_POST['nombre_interfas'];
			$nombre = $_POST['nombre'];
			$descripcion = $_POST['descripcion'];
			$tipo = $_POST['tipo'];
			$descripcion_ip_port = implode(" ||",$_POST['descripcion_ip_port']);
			# Consulta para obtener la ip de la interfaz selecionada #
			foreach($_POST['plantel'] as $plantel_grupo)
			{
				$xml = simplexml_load_file("clients/Ejemplo_2/$plantel_grupo/info_aliases.xml");
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
						$product = $xml->addChild('alias');
						$product->addChild('name', $nombre);
						$product->addChild('type', $tipo);
						$product->addChild('address', $formato_delimitador);
						$product->addChild('descr', $descripcion);
						$product->addChild('detail', $descripcion_ip_port);
						file_put_contents("clients/Ejemplo_2/$plantel_grupo/info_aliases.xml", $xml->asXML());
					}
				}
			}
			unlink("archivo_ip.txt");
			return $this->redirectToRoute("grupos_aliases");
		}
		return $this->render('@Principal/aliases/registro_aliases.html.twig', array(
			'informacion_interfaces_plantel'=>$informacion_interfaces_plantel,
			'informacion_interfaces_nombre'=>$informacion_interfaces_nombre,
			'grupo'=>$grupo
		));
	}

	# Area de consultas #
	# Funcion utilizada en lista_aliases #
	private function obtener_datos_aliases($grupo, $ubicacion)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT u.id, u.nombre, u.ip_port, u.grupo, u.ubicacion FROM PrincipalBundle:Aliases u
			WHERE  u.grupo = :grupo AND u.ubicacion = :ubicacion')->setParameter('grupo', $grupo)->setParameter('ubicacion', $ubicacion);
		$grupoAliases = $query->getResult();
		return $grupoAliases;
	}
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
	# Funcion utilizada en grupos_aliases #
	private function obtener_grupo_nombre()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT DISTINCT g.nombre FROM PrincipalBundle:Grupos g ORDER BY g.nombre ASC');
		$grupos = $query->getResult();
		return $grupos;
	}
}