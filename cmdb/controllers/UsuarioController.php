<?php

// Llamamos para constuir el controlador de usuario
require_once __DIR__ . '/ControllerBase.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../utils/Sanitizar.php';
require_once __DIR__ . '/../utils/Validar.php';

// Con esta clase manejamos todas las acciones de los usuarios
// Esta clase hereda las funciones de nuestro controlador base
class UsuarioController extends ControllerBase
{
    // Esta función muestra la lista de usuarios
    public function index()
    {
        $model = new Usuario();
        $usuarios = $model->listarTodos();
        $this->view('usuarios/listar', ['usuarios' => $usuarios]);
    }

    // Aquí mostramos el formulario de registro o edición dependiendo de lo que se vaya a hacer
    public function form($id = null)
    {
        $model = new Usuario();
        $usuario = $id ? $model->findById($id) : null;
        $this->view('usuarios/formulario', ['usuario' => $usuario]);
    }

    // Con esta función guardamos los datos del formulario de usuario después de sanitizarlos y validarlos
    public function save()
    {
        $nombre  = Sanitizar::texto($_POST['nombre']);
        $correo  = Sanitizar::texto($_POST['correo']);
        $cedula  = Sanitizar::texto($_POST['cedula']);

        // Validamos el correo
        if (!Validar::email($correo)) {
            $_SESSION['error'] = ['correo' => 'Email inválido'];
            return $this->redirect('usuarios/form');
        }

        // Si sale bien, intentamos guardar con el modelo
        $model = new Usuario();
        $resultado = $model->guardar($nombre, $correo, $cedula);

        // Si hay algún error al guardar, lo guardamos en la sesión y redirigimos al formulario
        if ($resultado !== true) {
            $_SESSION['error'] = ['general' => $resultado];
            return $this->redirect('usuarios/form');
        }

        // Si todo eso salió bien, entonces redirigimos al listado de usuarios
        $this->redirect('usuarios');
    }

    // Esta función se usa para dar de baja a un usuario
    public function delete($id)
    {
        $model = new Usuario();
        $model->bajaLogica($id);

        // Redirigimos a la lista de usuarios
        header('Location: usuarios_listar.php');
        exit;
    }
}

?>