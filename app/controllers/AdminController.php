<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Solicitud.php';
require_once __DIR__ . '/../models/Taller.php';

class AdminController
{
    private $solicitudModel;
    private $tallerModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->solicitudModel = new Solicitud($db);
        $this->tallerModel = new Taller($db);
    }

    public function solicitudes()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=login');
            return;
        }
        require __DIR__ . '/../views/admin/solicitudes.php';
    }
    
    public function getSolicitudesJson()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }

        $solicitudes = $this->solicitudModel->getPendientes();
        header('Content-Type: application/json');
        echo json_encode($solicitudes);
    }

    // Aprobar solicitud
    public function aprobar()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $solicitudId = $_POST['id_solicitud'] ?? 0;
        
        try {
            $solicitud = $this->solicitudModel->getById($solicitudId);

            if (!$solicitud) {
                echo json_encode(['success' => false, 'error' => 'Solicitud no encontrada']);
                return;
            }

            if ($solicitud['estado'] !== 'pendiente') {
                echo json_encode(['success' => false, 'error' => 'La solicitud ya fue procesada']);
                return;
            }

            $taller = $this->tallerModel->getById($solicitud['taller_id']);

            if (!$taller) {
                echo json_encode(['success' => false, 'error' => 'Taller no encontrado']);
                return;
            }

            if ((int)$taller['cupo_disponible'] <= 0) {
                echo json_encode(['success' => false, 'error' => 'No hay cupo disponible para aprobar esta solicitud']);
                return;
            }

            if (!$this->tallerModel->descontarCupo($solicitud['taller_id'])) {
                echo json_encode(['success' => false, 'error' => 'No se pudo descontar el cupo']);
                return;
            }

            if (!$this->solicitudModel->aprobar($solicitudId)) {
                echo json_encode(['success' => false, 'error' => 'No se pudo aprobar la solicitud']);
                return;
            }

            echo json_encode(['success' => true, 'message' => 'Solicitud aprobada correctamente']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public function rechazar()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }
        
        $solicitudId = $_POST['id_solicitud'] ?? 0;
        
        if ($this->solicitudModel->rechazar($solicitudId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar']);
        }
    }
}