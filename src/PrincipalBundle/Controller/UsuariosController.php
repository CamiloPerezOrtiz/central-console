<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\Usuarios;
use PrincipalBundle\Form\UsuariosType;
use PrincipalBundle\Entity\EditarUsuarios;
use PrincipalBundle\Form\EditarUsuariosType;
use Symfony\Component\HttpFoundation\Session\Session;

class UsuariosController extends Controller
{
	# Variables #
	private $session; 

	# Constructor # 
	public function __construct()
	{
		$this->session = new Session();
	}

	# Funcion para iniciar sesion #
	public function loginAction()
	{
		$authenticationUtils = $this->get("security.authentication_utils");
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		if ($error == true) 
		{ 
			$recuperarEmail = $this->recuperarEmail($lastUsername);
			if(!count($recuperarEmail) == 0)
			{
				$_SESSION['contadorLogin'] = 0;
				$recuperarEstado = $this->recuperarEstado($lastUsername);
				if($recuperarEstado[0]['estado'] === false)
				{
					$estatus="Your user has been blocked. Communicate with the company to be unlocked.";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->render('@Principal/Default/index.html.twig', array('error'=> $error, 'last_username' => $lastUsername));
				}
				$_SESSION['contadorLogin'] = $_SESSION['contadorLogin'] + 1;
				$contador = $_SESSION['contadorLogin'] + $recuperarEstado[0]['intentos'];
				$actualizarIntentos = $this->actualizarIntentos($contador, $lastUsername);
				if($contador > 4)
				{
					$estatus="Your user is blocked. They have made several unsuccessful attempts to login. Communicate with the company to be unlocked.";
					$this->session->getFlashBag()->add("estatus",$estatus);
					$actualizarEstado = $this->actualizarEstado($lastUsername);
				}
			}	
		}
		return $this->render('@Principal/Default/index.html.twig', array(
			'error'=> $error, 
			'last_username' => $lastUsername
		));
	}

	# Funcion para recuperar el correo electronico #
	private function recuperarEmail($lastUsername)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.email
				FROM PrincipalBundle:Usuarios u
				WHERE  u.email = :email'
		)->setParameter('email', $lastUsername);
		$email = $query->getResult();
		return $email;
	}

	# Funcion para recuperar el estado y los intentos #
	private function recuperarEstado($lastUsername)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.estado, u.intentos
				FROM PrincipalBundle:Usuarios u
				WHERE  u.email = :email'
		)->setParameter('email', $lastUsername);
		$estado = $query->getResult();
		return $estado;
	}

	# Funcion para actualizar los intentos #
	private function actualizarIntentos($contador, $lastUsername)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'UPDATE PrincipalBundle:Usuarios u
				SET u.intentos = :intentos
				WHERE  u.email = :email'
		)->setParameter('intentos', $contador)
		->setParameter('email', $lastUsername);
		$intentos = $query->getResult();
		return $intentos;
	}

	# Funcion para actualizar el estado #
	private function actualizarEstado($lastUsername)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'UPDATE PrincipalBundle:Usuarios u
				SET u.estado = :estado
				WHERE  u.email = :email'
		)->setParameter('estado', false)
		->setParameter('email', $lastUsername);
		$estado = $query->getResult();
		return $estado;
	}

	# Funcion para validar el inicio de session #
	public function validacionLoginAction()
	{
		$usuario = $this->getUser();
		$email=$usuario->getEmail();
		$recuperarEstado = $this->recuperarEstado($email);
		if($recuperarEstado[0]['estado'] == false)
			echo '<script> alert("Your user has been blocked. Communicate with the company to be unlocked.");window.location.href="logout";</script>';
		else
		{
			$actualizarIntentos = $this->actualizarIntentos($contador = 0, $email);
			return $this->redirectToRoute("dashboard");	
		}
	}

	# Funcion para mostrar el dashboard #
	public function dashboardAction()
	{
		return $this->render('@Principal/usuarios/dashboard.html.twig');
	}

	# Funcion para mostrar los usuarios #
	public function listaUsuariosAction()
	{
		$u = $this->getUser();
		if($u != null)
		{
			$em = $this->getDoctrine()->getEntityManager();
	        $db = $em->getConnection();
	        $role=$u->getRole();
	        if($role == "ROLE_SUPERUSER")
	        {
				$query = "SELECT * FROM usuarios ORDER BY usuarios";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$usuarios=$stmt->fetchAll();
				return $this->render('@Principal/usuarios/listaUsuarios.html.twig', array(
					"usuario"=>$usuarios
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR")
	        {
	        	$query = "SELECT * FROM usuarios ORDER BY usuarios";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$usuarios=$stmt->fetchAll();
				return $this->render('@Principal/usuarios/listaUsuarios.html.twig', array(
					"usuario"=>$usuarios
				));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$query = "SELECT * FROM usuarios ORDER BY usuarios";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$usuarios=$stmt->fetchAll();
				return $this->render('@Principal/usuarios/listaUsuarios.html.twig', array(
					"usuario"=>$usuarios
				));
	        }
	    }
	}

	# Funcion para agregar un nuevo usuario #
	public function registroSuperUsuarioAction(Request $request)
	{
		$usuarios =  new Usuarios();
		$form = $this->createForm(UsuariosType::class,$usuarios);
		$form->remove('role');
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$recuperarEmail = $this->recuperarEmail($form->get("email")->getData());
			if(count($recuperarEmail)==0)
			{
				$usuarios->setEstado(true);
				$usuarios->setIntentos(0);
				$usuarios->setGrupo("NULL");
				$usuarios->setRole("ROLE_SUPERUSER");
				$factory = $this->get("security.encoder_factory");
				$encoder = $factory->getEncoder($usuarios);
				$password = $encoder->encodePassword($form->get("password")->getData(),$usuarios->getSalt());
				$usuarios->setPassword($password);
				$em->persist($usuarios);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="Successfully registration.";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("listaUsuarios");
				}
				else
				{
					$estatus="Problems with the server try later.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
			}
			else
			{
				$estatus="The email you are trying to register already exists try again.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		return $this->render("@Principal/usuarios/registroSuperUsuario.html.twig",
			array(
				"form"=>$form->createView()
		));
	}

	# Funcion para actualizar el status cualquier actor #
	public function actualizarEstadoAction($id)
	{
		$recuperarEstadoId = $this->recuperarEstadoId($id);
		if($recuperarEstadoId[0]['estado'] === true)
		{
			if($actualizarIntentosEstadosId = $this->actualizarIntentosEstadosId($estado = false , $contador = 0, $id))
				$estatus="Successfully updated status.";	
			else
				$estatus="Problems with the server try later.";
		}
		else
		{
			if($actualizarIntentosEstadosId = $this->actualizarIntentosEstadosId($estado = true , $contador = 0, $id))
				$estatus="Successfully updated status.";
			else
				$estatus="Problems with the server try later.";
		}
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("listaUsuarios");
	}

	# Funcion para recuperar el estado por id #
	private function recuperarEstadoId($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.estado
				FROM PrincipalBundle:Usuarios u
				WHERE  u.id = :id'
		)->setParameter('id', $id);
		$estado = $query->getResult();
		return $estado;
	}

	# Funcion para actualizar los intentos y el estado po id #
	private function actualizarIntentosEstadosId($estado, $contador, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'UPDATE PrincipalBundle:Usuarios u
				SET u.estado = :estado, u.intentos = :intentos
				WHERE  u.id = :id'
		)->setParameter('estado', $estado)
		->setParameter('intentos', $contador)
		->setParameter('id', $id);
		$intentos = $query->getResult();
		return $intentos;
	}

	# Funcion para editar usuarios #
	public function editarSuperUsuarioAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$usuarios = $em->getRepository("PrincipalBundle:EditarUsuarios")->find($id);
		$form = $this->createForm(EditarUsuariosType::class,$usuarios);
		$form->remove('role');
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{			
			$password = $form->get('password')->getData();
			if(!empty($password))
			{
				$factory = $this->get("security.encoder_factory");
				$encoder = $factory->getEncoder($usuarios);
				$passwordEncrypt = $encoder->encodePassword($form
					->get("password")
					->getData(),$usuarios
					->getSalt());
				$usuarios->setPassword($passwordEncrypt);
			}
			else
			{
				$recuperarPassword = $this->recuperarPassword($id);
				$usuarios->setPassword($recuperarPassword[0]['password']);
			}
			$usuarios->setEstado(true);
			$usuarios->setIntentos(0);
			$em->persist($usuarios);
			$flush=$em->flush();
			if($flush == null)
			{
				$estatus="Successfully updated registration";
				$this->session->getFlashBag()->add("estatus",$estatus);
				return $this->redirectToRoute("listaUsuarios");
			}
			else
			{
				$estatus="Problems with the server try later.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		return $this->render("@Principal/usuarios/registroSuperUsuario.html.twig",array("form"=>$form->createView()));
	}

	# Funcion para recuperar la contraseña #
	private function recuperarPassword($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.password
				FROM PrincipalBundle:Usuarios u
				WHERE  u.id = :id'
		)->setParameter('id', $id);
		$passwordEncrypt = $query->getResult();
		return $passwordEncrypt;
	}

	# Funcion para eliminar un usuario #
	public function eliminarUsuarioAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:Usuarios")->find($id);
		$em->remove($formato);
		$flush=$em->flush();
		if($flush == null)
			$estatus="Successfully delete registration";
		else
			$estatus="Problems with the server try later.";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("listaUsuarios");
	}

	# Funcion para agregar un usuario en un grupo #
	public function registerUserAdministratorAction(Request $request, $id)
	{
		$usuarios =  new Usuarios();
		$form = $this->createForm(UsuariosType::class,$usuarios);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$query = $em->createQuery("SELECT u FROM PrincipalBundle:Administrator u WHERE u.email = :email")->setParameter("email",$form->get("email")->getData());
			$resultado = $query->getResult();
			$query2 = $em->createQuery("SELECT u FROM PrincipalBundle:Administrator u WHERE u.name = :name")->setParameter("name",$form->get("name")->getData());
			$resultado2 = $query2->getResult();
			if(count($resultado)==0 && count($resultado2)==0)
			{
				$usuarios->setNameGroup($id);
				$factory = $this->get("security.encoder_factory");
				$encoder = $factory->getEncoder($usuarios);
				$p = $encoder->encodePassword($form->get("password")->getData(),$usuarios->getSalt());
				$usuarios->setPassword($p);
				$em->persist($usuarios);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="Successfully registration";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("listusuarios");
				}
				else
				{
					$estatus="Problems with the server try later.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
			}
			else
			{
				$estatus="The name or email you are trying to register already exists try again.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		return $this->render("@Principal/usuarios/registerUserAdministrator.html.twig",array("form"=>$form->createView()));
	}
}
