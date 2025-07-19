<?php
require_once __DIR__ . '/ControllerBase.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../utils/Sanitizar.php';
require_once __DIR__ . '/../utils/Validar.php';

class UsuarioController extends ControllerBase
{
    public function index()
    {
        $model = new Usuario();
        $usuarios = $model->listarActivos();
        $this->view('usuarios/listar', ['usuarios' => $usuarios]);
    }

    public function form($id = null)
    {
        $model = new Usuario();
        $usuario = $id ? $model->findById($id) : null;
        $this->view('usuarios/formulario', ['usuario' => $usuario]);
    }

    public function save()
    {
        $nombre = Sanitizar::texto($_POST['nombre']);
        $correo = Sanitizar::texto($_POST['correo']);

        if (!Validar::email($correo)) {
            $_SESSION['error'] = ['correo' => 'Email inválido'];
            return $this->redirect('usuarios/form');
        }

        $model = new Usuario();
        $model->guardar($nombre, $correo);
        $this->redirect('usuarios');
    }

    public function delete($id)
    {
        $model = new Usuario();
        $model->bajaLogica($id);
        $this->redirect('usuarios');
    }
}
