<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use PrincipalBundle\Entity\Usuarios;
use PrincipalBundle\Form\UsuariosType;
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
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$_SESSION['contadorLogin'] = 0;
			$query = ("SELECT estado, intentos FROM usuarios WHERE email = '$lastUsername'");
			$stmt = $db->prepare($query);
			$params =array();
			$stmt->execute($params);
			$resultado=$stmt->fetchAll();
			foreach ($resultado as $value) 
			{
				$estado = $value['estado'];
				$intentos = $value['intentos'];
				if($estado == false)
				{
			        echo '<script> 
		                		alert("Your user has been blocked You have exceeded the number of attempts to enter. Communicate with the administrator to be unlocked.");
		                		window.location.href="logout";
		             		 </script>
		             		';
		            die();
				}
			}
			$_SESSION['contadorLogin'] = $_SESSION['contadorLogin'] + 1;
			$contador = $_SESSION['contadorLogin'] + $intentos;
			$actualizarIntentos = ("UPDATE usuarios SET intentos = '$contador' WHERE email = '$lastUsername'");
			$stmtIntentos = $db->prepare($actualizarIntentos);
			$stmtIntentos->execute(array());
			if($contador > 4)
			{
				$estatus="Your user is blocked. They have made several attempts to login. Communicate with the admin to change your status.";
				$this->session->getFlashBag()->add("estatus",$estatus);
				$em = $this->getDoctrine()->getEntityManager();
				$db = $em->getConnection();
				$actualizarEstado = ("UPDATE usuarios set estado = false where email = '$lastUsername'");
				$stmt = $db->prepare($actualizarEstado);
				$stmt->execute(array());
			}
		}
		return $this->render('@Principal/Default/index.html.twig', array('error'=> $error, 'last_username' => $lastUsername));
	}

	# Funcion para validar el inicio de session #
	public function validacionLoginAction()
	{
		$usuario = $this->getUser();
		$email=$usuario->getEmail();
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$query = ("SELECT estado FROM usuarios WHERE email = '$email'");
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$resultado=$stmt->fetchAll();
		foreach ($resultado as $value) 
		{
			$res = $value['estado'];
		}
		if($res == true)
		{
			$update = ("UPDATE usuarios SET intentos = 0 WHERE email = '$email'");
			$stmt2 = $db->prepare($update);
			$stmt2->execute(array());
	        return $this->redirectToRoute("dashboard");
		}
		else
		{
			echo '<script> 
                		alert("Your user is blocked. Communicate with the admin to change your status.");
                		window.location.href="logout";
             		 </script>
             		';
            die();
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
				return $this->render('@Principal/usuarios/listaUsuarios.html.twig', array("usuario"=>$usuarios));
	        }
	        if($role == "ROLE_ADMINISTRATOR")
	        {
	        	$query = "SELECT * FROM usuarios ORDER BY usuarios";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$usuarios=$stmt->fetchAll();
				return $this->render('@Principal/usuarios/listaUsuarios.html.twig', array("usuario"=>$usuarios));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$query = "SELECT * FROM usuarios ORDER BY usuarios";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$usuarios=$stmt->fetchAll();
				return $this->render('@Principal/usuarios/listaUsuarios.html.twig', array("usuario"=>$usuarios));
	        }
	    }
	}

	# Funcion para agregar un nuevo usuario #
	public function registroUsuariosAction(Request $request)
	{
		$usuarios =  new Usuarios();
		$form = $this->createForm(UsuariosType::class,$usuarios);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager(); 
			$query = $em->createQuery("SELECT u FROM PrincipalBundle:Usuarios u WHERE u.email = :email")
				->setParameter("email",$form->get("email")
				->getData());
			$resultado = $query->getResult();
			if(count($resultado)==0)
			{
				$usuarios->setEstado(true);
				$usuarios->setIntentos(0);
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
				echo "Problems with the server";
				die();
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

	# Funcion para actualizar el status cualquier actor #
	public function actualizarEstadoAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$query = ("SELECT estado FROM usuarios WHERE id = '$id'");
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$estado=$stmt->fetchAll();
		foreach ($estado as $tipoEstado) 
		{
			$resultado = $tipoEstado['estado'];
			if($resultado == true)
			{
		        $query = ("UPDATE usuarios SET estado = false WHERE id = '$id'");
				$stmt = $db->prepare($query);
				$stmt->execute(array());
				$queyIntenteos = ("UPDATE usuarios SET intentos = 0 WHERE id = '$id'");
				$stmtIntentos = $db->prepare($queyIntenteos);
				$stmtIntentos->execute(array());
				if($stmtIntentos == null)
				{
					$estatus="Problems with the server try later.";	
					$this->session->getFlashBag()->add("estatus",$estatus);			
				}
				else
				{
					$estatus="Successfully updated status.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
			}
			if($resultado == false)
			{
		        $query = ("UPDATE usuarios SET estado = true WHERE id = '$id'");
				$stmt = $db->prepare($query);
				$stmt->execute(array());
				#Reiniciar los intentos #
				$queyIntenteos = ("UPDATE usuarios SET intentos = 0 WHERE id = '$id'");
				$stmtIntentos = $db->prepare($queyIntenteos);
				$stmtIntentos->execute(array());
				if($stmtIntentos == null)
				{
					$estatus="Problems with the server try later.";
				}
				else
				{
					$estatus="Successfully updated status.";
				}
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}	
		return $this->redirectToRoute("listaUsuarios");
	}

	# Funcion para editar usuarios #
	public function editarUsuarioAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$usuarios = $em->getRepository("PrincipalBundle:Usuarios")->find($id);
		$form = $this->createForm(UsuariosType::class,$usuarios);
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
		return $this->render("@Principal/usuarios/registroUsuarios.html.twig",array("form"=>$form->createView()));
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
}
