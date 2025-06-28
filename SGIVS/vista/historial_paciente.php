<?php
require_once("../comunes/encabezado.php");
require_once("../comunes/menu.php");
require_once("../modelo/pacientes.php");

$id_paciente = $_GET['id_paciente'] ?? '';
$paciente = new pacientes();

// Aquí deberías consultar los datos del paciente y su historial médico.
// Por ejemplo, si tienes una tabla "historial_medico", consulta así:
$historial = [];
if ($id_paciente) {
    // Ejemplo de consulta, ajusta según tu modelo y base de datos
    $co = $paciente->conecta();
    $stmt = $co->prepare("SELECT * FROM historial_medico WHERE id_paciente = ?");
    $stmt->execute([$id_paciente]);
    $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $co = null;
}
?>
<div class="container mt-4">
    <h2>Historial Médico del Paciente</h2>
    <p>ID Paciente: <strong><?php echo htmlspecialchars($id_paciente); ?></strong></p>
    <?php if ($historial && count($historial) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Diagnóstico</th>
                    <th>Tratamiento</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial as $evento): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($evento['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($evento['descripcion']); ?></td>
                        <td><?php echo htmlspecialchars($evento['diagnostico']); ?></td>
                        <td><?php echo htmlspecialchars($evento['tratamiento']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No hay historial médico registrado para este paciente.</div>
    <?php endif; ?>
    <a href="pacientes.php" class="btn btn-secondary mt-3">Volver</a>
</div>
<?php require_once("../comunes/pie.php"); ?>