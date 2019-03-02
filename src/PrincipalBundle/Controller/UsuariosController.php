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
		{
			echo '<script> alert("Your user has been blocked. Communicate with the company to be unlocked.");window.location.href="logout";</script>';
		}
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
	        $role=$u->getRole();
	        $grupo=$u->getGrupo();
	        if($role == "ROLE_SUPERUSER")
	        {
				$usuarios = $this->recuperarTodosUsuarios();
				return $this->render('@Principal/usuarios/listaUsuarios.html.twig', array(
					"usuario"=>$usuarios
				));
	        }
	        if($role == "ROLE_ADMINISTRATOR")
	        {
	        	$usuarios = $this->recuperarUsuarios($grupo);
				return $this->render('@Principal/usuarios/listaUsuarios.html.twig', array(
					"usuario"=>$usuarios
				));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$usuarios = $this->recuperarUsuarios($grupo);
				return $this->render('@Principal/usuarios/listaUsuarios.html.twig', array(
					"usuario"=>$usuarios
				));
	        }
	    }
	}

	# Funcion para recuperar los usuarios #
	private function recuperarUsuarios($grupo)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre, u.apellidos, u.email, u.role, u.estado, u.grupo
				FROM PrincipalBundle:Usuarios u
				WHERE  u.role = :roleAdministrator
				AND u.grupo = :grupo
				Or u.role = :roleUser
				AND u.grupo = :grupo'
		)->setParameter('roleAdministrator', 'ROLE_ADMINISTRATOR')
		->setParameter('grupo', $grupo)
		->setParameter('roleUser', 'ROLE_USER')
		->setParameter('grupo', $grupo);
		$usuarios = $query->getResult();
		return $usuarios;
	}

	# Funcion para recuperar los todos los usuarios #
	private function recuperarTodosUsuarios()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.id, u.nombre, u.apellidos, u.email, u.role, u.estado, u.grupo
				FROM PrincipalBundle:Usuarios u'
		);
		$usuarios = $query->getResult();
		return $usuarios;
	}

	# Funcion para agregar un nuevo usuario #
	public function registroUsuarioAction(Request $request)
	{
		$usuarios =  new Usuarios();
		$form = $this->createForm(UsuariosType::class,$usuarios);
		$u = $this->getUser();
		$grupo=$u->getGrupo();
		$role=$u->getRole();
		if($role == 'ROLE_SUPERUSER')
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
				if($role == 'ROLE_SUPERUSER')
					$usuarios->setGrupo("NULL");
				else
					$usuarios->setGrupo($grupo);
				if($role == 'ROLE_SUPERUSER')
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
		if($role == 'ROLE_SUPERUSER')
			return $this->render("@Principal/usuarios/registroSuperUsuario.html.twig",
				array(
					"form"=>$form->createView()
			));
		else
			return $this->render("@Principal/usuarios/registroUsuarios.html.twig",
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
	public function editarUsuarioAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$usuarios = $em->getRepository("PrincipalBundle:EditarUsuarios")->find($id);
		$form = $this->createForm(EditarUsuariosType::class,$usuarios);
		$recuperarGrupoId = $this->recuperarGrupoId($id);
		if($recuperarGrupoId[0]['grupo'] == "NULL")
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
		if($recuperarGrupoId[0]['grupo'] == "NULL")
			return $this->render("@Principal/usuarios/registroSuperUsuario.html.twig",array(
				"form"=>$form->createView()
			));
		else
			return $this->render("@Principal/usuarios/registroUsuarios.html.twig",array(
				"form"=>$form->createView()
			));
	}

	# Funcion para recuperar el grupo por id #
	private function recuperarGrupoId($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$query = $em->createQuery(
			'SELECT u.grupo
				FROM PrincipalBundle:Usuarios u
				WHERE  u.id = :id'
		)->setParameter('id', $id);
		$grupo = $query->getResult();
		return $grupo;
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

	# Funcion para agregar un nuevo usuario #
	public function registroUsuarioGrupoAction(Request $request, $id)
	{
		$usuarios =  new Usuarios();
		$form = $this->createForm(UsuariosType::class,$usuarios);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
			$recuperarEmail = $this->recuperarEmail($form->get("email")->getData());
			if(count($recuperarEmail)==0)
			{
				$usuarios->setEstado(true);
				$usuarios->setIntentos(0);
				$usuarios->setGrupo($id);
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
		return $this->render("@Principal/usuarios/registroUsuarios.html.twig",
			array(
				"form"=>$form->createView()
		));
	}

	public function llenarAction()
	{
		for ($i=0; $i < 100; $i++) 
		{ 
			$nombre = $this->sa(10);
			$apellidos = $this->sa(10);
			$email = $this->sa(15).'@'.'warriorslabs.com';
			$password = '$2a$04$EI1wf24P.G4Nd5K3An34puMsRZhOpSXbbBcqyAgyPy5UUjcuXGMDW';
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$query = "INSERT INTO Usuarios VALUES (nextval('usuarios_id_seq'),'$nombre','$apellidos','$email','$password','ROLE_ADMINISTRATOR','t',0,'Kasa')";
			$stmt = $db->prepare($query);
			$params =array();
			$stmt->execute($params);
			$flush=$em->flush();
		}
		return $this->redirectToRoute("listaUsuarios");
	}

	private function sa($long)
	{
		$caracteres = 'asdfghjklqwertyuiopzxcvbnm1234567890ASDFGHJKLQWERTYUIOPZXCVBNM';
		$num_caracteres = strlen($caracteres);
		$string_aletorio = '';
		for ($i=0; $i < $long; $i++) 
		{ 
			$string_aletorio .=$caracteres[rand(0, $num_caracteres - 1)];
		}
		return $string_aletorio;
	}
}