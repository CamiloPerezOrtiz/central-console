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
		# Variable para leer el archivo informacionGrupo.txt #
		$filas=file('informacionGrupos.txt'); 
		foreach($filas as $value)
		{
			list($ip, $cliente, $descripcion) = explode("|", $value);
			'ip: '.$ip.'<br/>'; 
			'cliente: '.$cliente.'<br/>'; 
			'descripcion: '.$descripcion.'<br/><br/>';
			$query = "INSERT INTO grupos VALUES (nextval('grupos_id_seq'),'$ip','$cliente','$descripcion')";
			$stmt = $db->prepare($query);
			$params =array();
			$stmt->execute($params);
			$flush=$em->flush();
			$serv = '/var/www/html/central-console/web/Groups/';
			$ruta = $serv . $cliente;
			if(!file_exists($ruta))
			{
			  mkdir ($ruta);
			  echo "Se ha creado el directorio: " . $ruta;
			} 
			else 
			  echo "la ruta: " . $ruta . " ya existe ";
		}
		# Interfaces #
		$queryI = "DELETE FROM interfaces";
		$stmtI = $db->prepare($queryI);
		$paramsI =array();
		$stmtI->execute($paramsI);
		$flushI=$em->flush();
		# Query para que la secuencia del contador regrese a 1 #
		$queryIn = "ALTER SEQUENCE interfaces_id_seq RESTART WITH 1";
		$stmtIn = $db->prepare($queryIn);
		$paramsIn =array();
		$stmtIn->execute($paramsIn);
		$flushIn=$em->flush();
		# Variable para leer el archivo informacionGrupo.txt #
		$filasIn=file('interfaces.txt'); 
		foreach($filasIn as $interfaces)
		{
			list($interfas, $grupo) = explode("|", $interfaces);
			'interfas: '.$interfas.'<br/>'; 
			'grupo: '.$grupo.'<br/><br/>';
			$queryInt = "INSERT INTO interfaces VALUES (nextval('interfaces_id_seq'),'$interfas','$grupo')";
			$stmtInt = $db->prepare($queryInt);
			$paramsInt =array();
			$stmtInt->execute($paramsInt);
			$flushInt=$em->flush();
		}
		# Octetos #
		$query_delete_informacion_ip = "DELETE FROM informacion_ip";
		$consulta_delete_informacion_ip = $db->prepare($query_delete_informacion_ip);
		$ejecutar_delete_informacion_ip =array();
		$consulta_delete_informacion_ip->execute($ejecutar_delete_informacion_ip);
		$ejecutar_informacion_ip=$em->flush();

		$sequencia_actulizar_informacion_ip = "ALTER SEQUENCE informacion_ip_id_seq RESTART WITH 1";
		$consulta_actulizar_informacion_ip = $db->prepare($sequencia_actulizar_informacion_ip);
		$ejecutar_actulizar_informacion_ip =array();
		$consulta_actulizar_informacion_ip->execute($paramsIn);
		$actulizar_informacion_ip=$em->flush();

		$ip_rangos=file('ip_rangos.txt'); 
		foreach($ip_rangos as $txt_ip_rangos)
		{
			list($primer_octeto, $segundo_octeto, $tercer_octeto, $cuarto_octeto, $mascara) = explode("|", $txt_ip_rangos);
			'primer_octeto: '.$primer_octeto.'<br/>'; 
			'segundo_octeto: '.$segundo_octeto.'<br/>';
			'tercer_octeto: '.$tercer_octeto.'<br/>'; 
			'cuarto_octeto: '.$cuarto_octeto.'<br/>';
			'mascara: '.$mascara.'<br/><br/>';
			$query_insertar_ip_rango = "INSERT INTO informacion_ip VALUES (nextval('informacion_ip_id_seq'),'$primer_octeto','$segundo_octeto','$tercer_octeto','$cuarto_octeto','$mascara')";
			$consulta_insertar_ip_rango = $db->prepare($query_insertar_ip_rango);
			$ejecutar_insertar_ip_rango =array();
			$consulta_insertar_ip_rango->execute($ejecutar_insertar_ip_rango);
			$insertar_ip_rango=$em->flush();
		}
		return $this->redirectToRoute("grupos");
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
		$grupos = $this->aplicarCambiosGrupo($id);
		return $this->render("@Principal/grupos/aplicarCambios.html.twig", array(
			"grupos"=>$grupos,
			"apply"=>$apply->createView()
		));
	}

	# Funcion para aplicar cambios #
	private function aplicarCambiosGrupo($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT g.id, g.ip, g.nombre, g.descripcion
				FROM PrincipalBundle:Grupos g
				WHERE g.nombre = :nombre'
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
		return $this->render("@Principal/grupos/aplicarCambios.html.twig", array(
			"grupos"=>$grupos,
			"apply"=>$apply->createView()
		));
	}
}
