<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GruposController extends Controller
{
	# Funcion para leer el archivo txt y guardar los datos en la base #
	public function leerArchivoTxtAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		# Query para borrar la tabla txtip de la base de datos #
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
		}
		return $this->redirectToRoute("grupos");
	}

	# Funcion para mostrar los grupos #
	public function gruposAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
			$em = $this->getDoctrine()->getEntityManager();
	        $db = $em->getConnection();
	        $role=$u->getRole();
	        if($role == "ROLE_SUPERUSER")
	        {
				$query = "SELECT nombre FROM grupos ORDER BY nombre ASC";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$grupos=$stmt->fetchAll();
				return $this->render("@Principal/grupos/grupos.html.twig", array("grupo"=>$grupos));
	        }
	        if($role == "ROLE_ADMINISTRATOR")
	        {
	        	$query = "SELECT nombre FROM grupos ORDER BY nombre ASC";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$grupos=$stmt->fetchAll();
				return $this->render("@Principal/grupos/grupos.html.twig", array("grupo"=>$grupos));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$query = "SELECT nombre FROM grupos ORDER BY nombre ASC";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$grupos=$stmt->fetchAll();
				return $this->render("@Principal/grupos/grupos.html.twig", array("grupo"=>$grupos));
	        }
	    }
	}

	# Funcion para listar las Ip #
	public function aplicarCambiosAction()
	{
		$u = $this->getUser();
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$query = "SELECT * FROM grupos";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$grupo=$stmt->fetchAll();
		return $this->render("@Principal/grupos/aplicarCambios.html.twig", array("grupos"=>$grupo));
	}
}
