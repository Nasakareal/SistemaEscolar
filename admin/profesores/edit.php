<?php
include('../../app/config.php');

$teacher_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$teacher_id) {
    echo "ID de usuario inválido.";
    exit;
}

include('../../admin/layout/parte1.php');
include('../../app/controllers/profesores/datos_del_profesor.php');
include('../../app/controllers/programas/listado_de_programas.php');
include('../../app/controllers/cuatrimestres/listado_de_cuatrimestres.php');
include('../../app/controllers/relacion_profesor_materias/listado_de_relacion.php');
include('../../app/controllers/materias/listado_de_materias.php');
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <br>
    <div class="content">
        <div class="container">
            <div class="row">
                <h1>Modificar profesor: <?= htmlspecialchars($nombres); ?></h1>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Llene los datos</h3>
                        </div>
                        <div class="card-body">
                            <form action="<?= APP_URL; ?>/app/controllers/profesores/update.php" method="post">
                                <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id); ?>">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Nombres del profesor</label>
                                            <input type="text" name="nombres" value="<?= htmlspecialchars($nombres); ?>" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Programa</label>
                                            <select name="programa_id" id="programa_id" class="form-control" required>
                                                <option value="">Seleccione un programa</option>
                                                <?php foreach ($programs as $program): ?>
                                                    <option value="<?= $program['program_id']; ?>" <?= (isset($programa_id) && $programa_id == $program['program_id']) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($program['programa']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Cuatrimestre</label>
                                            <select name="cuatrimestre_id" id="cuatrimestre_id" class="form-control" required>
                                                <option value="">Seleccione un cuatrimestre</option>
                                                <?php foreach ($terms as $term): ?>
                                                    <option value="<?= $term['term_id']; ?>" <?= (isset($cuatrimestre_id) && $cuatrimestre_id == $term['term_id']) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($term['term_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Materias disponibles</label>
                                        <select id="materias_disponibles" name="materia_ids[]" class="form-control" multiple>
                                            <?php foreach ($materias_disponibles as $materia): ?>
                                                <option value="<?= $materia['subject_id']; ?>" <?php if (in_array($materia['subject_id'], $materias_ids_asignadas))
                                                      echo 'disabled'; ?>>
                                                    <?= htmlspecialchars($materia['subject_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Materias asignadas</label>
                                        <select id="materias_asignadas" name="materias_asignadas[]" class="form-control" multiple>
                                            <?php foreach ($materias_ids_asignadas as $id): ?>
                                                <?php
                                                $materia = array_filter($materias_disponibles, function ($m) use ($id) {
                                                    return $m['subject_id'] == $id;
                                                });
                                                ?>
                                                <?php if ($materia): ?>
                                                    <option value="<?= $id; ?>">
                                                        <?= htmlspecialchars(current($materia)['subject_name']); ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="button" id="add_subject" class="btn btn-primary"> > </button>
                                        <button type="button" id="remove_subject" class="btn btn-primary"> < </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                        <a href="<?= APP_URL; ?>/admin/profesores" class="btn btn-secondary">Cancelar</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include('../../admin/layout/parte2.php');
include('../../layout/mensajes.php');
?>

<script>
    /* JavaScript para mover materias entre listas */
    document.getElementById('add_subject').addEventListener('click', function() {
        const available = document.getElementById('materias_disponibles');
        const assigned = document.getElementById('materias_asignadas');

        Array.from(available.selectedOptions).forEach(option => {
            assigned.appendChild(option);
        });
    });

    document.getElementById('remove_subject').addEventListener('click', function() {
        const assigned = document.getElementById('materias_asignadas');
        const available = document.getElementById('materias_disponibles');

        Array.from(assigned.selectedOptions).forEach(option => {
            available.appendChild(option);
        });
    });
</script>
