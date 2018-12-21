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
			$actualizarIntentos = ("UPDATE usuario SET intentos = '$contador' WHERE email = '$lastUsername'");
			$stmtIntentos = $db->prepare($actualizarIntentos);
			$stmtIntentos->execute(array());
			if($contador > 4)
			{
				$estatus="Your user is blocked. They have made several attempts to login. Communicate with the admin to change your status.";
				$this->session->getFlashBag()->add("estatus",$estatus);
				$em = $this->getDoctrine()->getEntityManager();
				$db = $em->getConnection();
				$actualizarEstado = ("UPDATE usuarios set estado = 'disabled' where email = '$lastUsername'");
				$stmt = $db->prepare($actualizarEstado);
				$stmt->execute(array());
			}
		}
		return $this->render('@Principal/Default/index.html.twig', array('error'=> $error, 'last_username' => $lastUsername));
	}

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
		return $this->render('@Principal/administrator/dashboard.html.twig');
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
			$query = $em->createQuery("SELECT u FROM PrincipalBundle:Usuarios u WHERE u.email = :email")->setParameter("email",$form->get("email")->getData());
			$resultado = $query->getResult();
			if(count($resultado)==0)
			{
				//$usuarios->setNameGroup($id);
				$factory = $this->get("security.encoder_factory");
				$encoder = $factory->getEncoder($usuarios);
				$password = $encoder->encodePassword($form->get("password")->getData(),$usuarios->getSalt());
				$usuarios->setPassword($password);
				$em->persist($usuarios);
				$flush=$em->flush();
				if($flush == null)
				{
					return $this->redirectToRoute("registroUsuarios");
				}
				echo "Problems with the server";
				die();
			}
			else
			{
				echo "User exit";
				die();
			}
		}
		return $this->render("@Principal/usuarios/registro-usuarios.html.twig",
			array(
				"form"=>$form->createView()
		));
	}
}
