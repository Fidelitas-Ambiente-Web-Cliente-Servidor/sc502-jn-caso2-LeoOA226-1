<?php
class Solicitud
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($tallerId, $usuarioId)
    {
        $query = "INSERT INTO solicitudes (taller_id, usuario_id, estado) VALUES (?, ?, 'pendiente')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $tallerId, $usuarioId);
        return $stmt->execute();
    }

    public function existeSolicitudActiva($tallerId, $usuarioId)
    {
        $query = "SELECT id 
                  FROM solicitudes 
                  WHERE taller_id = ? 
                  AND usuario_id = ? 
                  AND (estado = 'pendiente' OR estado = 'aprobada')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $tallerId, $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function getPendientes()
    {
        $query = "SELECT 
                    s.id,
                    s.taller_id,
                    s.usuario_id,
                    s.fecha_solicitud,
                    s.estado,
                    t.nombre AS taller_nombre,
                    u.username AS username
                  FROM solicitudes s
                  INNER JOIN talleres t ON s.taller_id = t.id
                  INNER JOIN usuarios u ON s.usuario_id = u.id
                  WHERE s.estado = 'pendiente'
                  ORDER BY s.fecha_solicitud ASC";
        $result = $this->conn->query($query);
        $solicitudes = [];
        while ($row = $result->fetch_assoc()) {
            $solicitudes[] = $row;
        }
        return $solicitudes;
    }


    public function getById($id)
    {
        $query = "SELECT * FROM solicitudes WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function aprobar($solicitudId)
    {
        $query = "UPDATE solicitudes SET estado = 'aprobada' WHERE id = ? AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $solicitudId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function rechazar($solicitudId)
    {
        $query = "UPDATE solicitudes SET estado = 'rechazada' WHERE id = ? AND estado = 'pendiente'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $solicitudId);
        $stmt->execute();
        return $stmt->affected_rows > 0;

}