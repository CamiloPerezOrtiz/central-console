<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use PrincipalBundle\Entity\Grupos;
use PrincipalBundle\Form\GruposType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GruposController extends Controller
{
	# Funcion para leer el archivo txt y guardar los datos en la base #
	public function leerArchivoTxtAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		# Archivo interfaces #
		$u = $this->getUser();
		$role=$u->getRole();
		if($role == "ROLE_ADMINISTRATOR")
	    {
	    	$grupo=$u->getGrupo();
	    	$grupos = $this->obtener_nombre_grupo($grupo);
	    	$interfaces = array("wan", "lan", "opt1", "opt2","opt3");
	    	$interfaces_archivo = fopen("interfaces.txt", "w");
	    	foreach ($grupos as $equipo_grupos) 
			{
				foreach ($equipo_grupos as $equipo) 
				{
					$xml = simplexml_load_file("clients/Ejemplo_2/$equipo/info.xml");
					foreach ($interfaces as $interfaces_equipo) 
					{
						$tipo_interfas = $xml->xpath("/interfaces/$interfaces_equipo/if");
		        		$nombre = $xml->xpath("/interfaces/$interfaces_equipo/descr");
		        		$ip = $xml->xpath("/interfaces/$interfaces_equipo/ipaddr");
		        		foreach ($tipo_interfas as $interfas) 
						{
							#echo $interfas . "|";
							fwrite($interfaces_archivo, $interfas."|");
						}
						foreach ($nombre as $nombre_interfas) 
						{
							fwrite($interfaces_archivo, $nombre_interfas . "|".$interfaces_equipo."|");
						}
						foreach ($ip as $ip_equipo) 
						{
							$ip_nueva = preg_replace('/\d{1,3}$/', '', $ip_equipo);
							if($ip_nueva == "dhcp")
							{
								fwrite($interfaces_archivo, "192.168.0." . "|UI|".$equipo."|\n");
							}
							else
							{
								$resultado_ip = $ip_nueva . "|UI|$equipo|\n";
								fwrite($interfaces_archivo, $resultado_ip);
							}
						}
					}
				}
			}
			fclose($interfaces_archivo);
	    }
		# Query para borrar la tabla grupos de la base de datos #
		$queryDrop = "DELETE FROM grupos";
		$stmtDrop = $db->prepare($queryDrop);
		$paramsDrop =array();
		$stmtDrop->execute($paramsDrop);
		$flushDrop=$em->flush();
		# Query para que la secuencia del contador regrese a 1 #
		$queryReset = "ALTER SEQUENCE grupos_id_seq RESTART WITH 1";
		$stmtReset = $db->prepare($queryReset);
		$paramsReset =array();
		$stmtReset->execute($paramsReset);
		$flushReset=$em->flush();
		#####
		# Query para borrar la tabla grupos de la base de datos #
		$q = "DELETE FROM interfaces";
		$s = $db->prepare($q);
		$p =array();
		$s->execute($p);
		$f=$em->flush();
		# Query para que la secuencia del contador regrese a 1 #
		$qu = "ALTER SEQUENCE interfaces_id_seq RESTART WITH 1";
		$st = $db->prepare($qu);
		$pa =array();
		$st->execute($pa);
		$fl=$em->flush();
		#####
		# Variable para leer el archivo informacionGrupo.txt #
		$filas=file('informacion.txt'); 
		foreach($filas as $value)
		{
			list($ip, $nombre, $descripcion) = explode("|", $value);
			'ip: '.$ip.'<br/>';
			'nombre: '.$nombre.'<br/>';
			'descripcion: '.$descripcion.'<br/><br/>';
			$query = "INSERT INTO grupos VALUES (nextval('grupos_id_seq'),'$ip','$nombre','$descripcion')";
			$stmt = $db->prepare($query);
			$params =array();
			$stmt->execute($params);
			$flush=$em->flush();
		}
		$archivo_interfaces=file('interfaces.txt'); 
		foreach($archivo_interfaces as $archivo_interfas)
		{
			list($interfaz, $tipo, $nombre, $ip, $grupo, $descripcion) = explode("|", $archivo_interfas);
			'interfaz: '.$interfaz.'<br/>';
			'tipo: '.$tipo.'<br/>'; 
			'nombre: '.$nombre.'<br/>'; 
			'ip: '.$ip.'<br/>';
			'grupo: '.$grupo.'<br/>';
			'descripcion: '.$descripcion.'<br/><br/>';
			$query_interfaces = "INSERT INTO interfaces VALUES (nextval('interfaces_id_seq'),'$interfaz','$tipo','$nombre','$ip','$grupo','$descripcion')";
			$stmt_interfaces = $db->prepare($query_interfaces);
			$params_interfaces =array();
			$stmt_interfaces->execute($params_interfaces);
			$flush_interfaces=$em->flush();
		}
		return $this->redirectToRoute("grupos");
	}

	private function obtener_nombre_grupo($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery('SELECT g.descripcion FROM PrincipalBundle:Grupos g WHERE g.nombre = :grupo')->setParameter('grupo', $grupo);;
		$grupos = $query->getResult();
		return $grupos;
	}

	# Funcion para mostrar los grupos #
	public function gruposAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	/*$plantel = array("Plantel_Xola", "Plantel_Ciudad_Azteca", "Plantel_Ermita", "Plantel_Norte");
	        	$interfaces = array("wan", "lan", "opt1", "opt2","opt3");
	        	$interfaces_archivo = fopen("interfaces_equipo.txt", "w");
	        	foreach ($plantel as $equipo) 
				{
					$xml = simplexml_load_file("clients/Ejemplo_2/$equipo/info.xml");
					foreach ($interfaces as $interfaces_equipo) 
					{
						$tipo_interfas = $xml->xpath("/interfaces/$interfaces_equipo/if");
		        		$nombre = $xml->xpath("/interfaces/$interfaces_equipo/descr");
		        		$ip = $xml->xpath("/interfaces/$interfaces_equipo/ipaddr");
		        		foreach ($tipo_interfas as $interfas) 
						{
							#echo $interfas . "|";
							fwrite($interfaces_archivo, $interfas."|");
						}
						foreach ($nombre as $nombre_interfas) 
						{
							#echo $nombre_interfas . "|$interfaces_equipo|";
							fwrite($interfaces_archivo, $nombre_interfas . "|".$interfaces_equipo."|");
						}
						foreach ($ip as $ip_equipo) 
						{
							$ip_nueva = preg_replace('/\d{1,3}$/', '', $ip_equipo);
							if($ip_nueva == "dhcp")
							{
								#echo $resultado_ip = "192.168.0." . "|UI|$equipo|\n";
								fwrite($interfaces_archivo, "192.168.0." . "|UI|".$equipo."|\n");
							}
							else
							{
								$resultado_ip = $ip_nueva . "|UI|$equipo|\n";
								fwrite($interfaces_archivo, $resultado_ip);
							}
						}
					}
				}
				fclose($interfaces_archivo);
	        	die();*/
	        	$grupos = $this->recuperarTodosGrupos();
				return $this->render("@Principal/grupos/grupos.html.twig", array(
					"grupo"=>$grupos
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR" or $role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("aplicarCambios");
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
	

	# Funcion para listar las Ip #
	public function aplicarCambiosSuperUsuarioAction(Request $request, $id)
	{
		$grupos = $this->aplicarCambiosGrupo($id);
		return $this->render("@Principal/grupos/aplicarCambiosIp.html.twig", array(
			"grupos"=>$grupos
		));
	}

	# Funcion para aplicar cambios #
	private function aplicarCambiosGrupo($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT g.id, g.ip, g.descripcion
				FROM PrincipalBundle:Grupos g
				WHERE g.nombre = :nombre
				ORDER BY g.descripcion ASC'
		)->setParameter('nombre', $grupo);
		$grupos = $query->getResult();
		return $grupos;
	}

	# Funcion para listar las Ip #
	public function aplicarCambiosAction(Request $request)
	{
		$grupo =  new Grupos();
		$apply = $this->createForm(GruposType::class,$grupo);
		$apply->handleRequest($request);
		if($apply->isSubmitted() && $apply->isValid())
		{
			$ip = $_POST['ip'];
			$file=fopen("centralizedConsole/changes.txt","w") or die("Problemas");
			foreach ($ip as $ipSelecion) 
			{
				fputs($file,$ipSelecion."\n");
			}
			fclose($file);
			$process = new Process('python centralizedConsole/apply.py');
			$process->run();
			if (!$process->isSuccessful()) {
			    throw new ProcessFailedException($process);
			}
			echo '<script>alert("Successfully executed changes.");window.history.go(-1);</script>';
		}
		if(isset($_POST['reset']))
		{
			$process = new Process('python centralizedConsole/ctrlz.py');
			$process->run();
			if (!$process->isSuccessful()) {
			    throw new ProcessFailedException($process);
			}
			echo '<script>alert("Successfully executed changes.");window.history.go(-1);</script>';
		}
		$u = $this->getUser();
		$grupo=$u->getGrupo();
		$grupos = $this->aplicarCambiosGrupo($grupo);
		return $this->render("@Principal/grupos/aplicarCambiosIp.html.twig", array(
			"grupos"=>$grupos,
			"apply"=>$apply->createView()
		));
	}
}
