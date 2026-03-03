<?php require_once "src/php/new-task.php"; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo list PHP</title>
    <link rel="stylesheet" href="public/style/style.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"> -->
    <link rel="icon" href="#" />
</head>

<body>
    <main>
        <div class="card bg-color">
            <div class="card-header">
                <h1 class="text-dark fw-normal text-center my-2">Todo list</h1>
            </div>

            <section id="all-tasks">
                <ul class="text-dark list-unstyled">
                    <?php require_once "src/php/display-task.php" ?>
                </ul>
                <!-- <p><= $message ?></p> -->
                <!-- <p><= $error ?></p> -->
            </section>

            <div class="create-task-div">
                <form action="index.php" method="post" id="task-form" class="d-flex text-dark">
                    <fieldset id="create-task-form">
                        <legend>
                            <h2 class="fw-normal text-dark">Créer une nouvelle tâche :</h2>
                        </legend>

                        <!-- Nom de la tâche -->
                        <div class="mb-3 form-group">
                            <label for="name" class="form-label">Nom de la tâche :</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Ex : Faire les courses" required />
                        </div>

                        <!-- Catégories -->
                        <div class="mb-3 form-group">
                            <label for="category" class="form-label">Catégorie :</label>
                            <select id="category" name="category" class="form-select">
                                <?php require "views/inc/_categories.php"; ?>
                            </select>
                        </div>

                        <!-- Priorité -->
                        <div class="mb-3 form-group">
                            <label for="priority" class="form-label">Priorité :</label>
                            <select id="priority" name="priority" class="form-select"
                                aria-label="Default select example">
                                <?php require "views/inc/_priorities.php"; ?>
                            </select>
                        </div>

                        <!-- Bouton de validation -->
                        <input type="submit" id="add" name="add" value="Ajouter la tâche" class="btn btn-primary" />
                    </fieldset>
                </form>
            </div>

            <!-- <hr class=""> -->

            <div class="filter-tasks-div">
                <form action="index.php" method="post" class="d-flex text-light" id="filter-form">
                    <fieldset id="create-filter-form">
                        <legend>
                            <h2 class="text-dark fw-normal">Filtrer par :</h2>
                        </legend>

                        <div class="mb-3 form-group">
                            <label for="filter-category" class="form-label text-dark">Catégorie :</label>
                            <select name="filter-category" id="filter-category" class="form-select"
                                aria-label="Default select example">
                                <?php require "views/inc/_categories.php"; ?>
                            </select>
                        </div>

                        <input type="submit" name="display" id="display" value="Afficher les tâches" class="btn btn-primary" />
                    </fieldset>
                </form>
            </div>

            <div class="card-footer" style="border-color: #aaaaaa;">
                <div id="all-buttons" class="d-flex">
                    <ul class="list-unstyled">
                        <li><button type="submit" id="delete-task-btn" class="btn btn-success">Supprimer les tâches terminées</button></li>
                        <li><button type="submit" id="delete-all-btn" class="btn btn-danger">Supprimer toutes les tâches</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- <script type="text/javascript" src="js/app.js"></script> -->
</body>

</html>