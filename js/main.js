const elements = {
    addTaskNameInput: document.getElementById("task-name"),
    allTasksOl: document.querySelector("#all-tasks ol"),
    form: document.getElementById("task-form"),
    addTaskButton: document.getElementById("add-task-btn"),
    deleteAllTasksButton: document.getElementById("delete-all-tasks-btn"),
}

let tasks = [];

document.addEventListener('DOMContentLoaded', function () {

    console.log('DOM loaded');
    createNewTask();

    deleteAllTasks();
});

function createNewTask() {

    elements.allTasksOl.innerHTML = '';

    // Pour chaque task du tableau tasks:
    tasks.forEach(task => {

        elements.allTasksOl.innerHTML = `
            <li>
                <label>
                    <input type="checkbox">
                    ${task.title}
                </label>
            </li>
        `;

        // Evènement pour gérer l'état d'une tâche (complétée ou non) avec la propriété "change"
        inputCheckbox.addEventListener("change", function () {

            if (inputCheckbox.checked) {
                task.isDone = true;
                label.style.textDecoration = "line-through";

            } else {
                task.isDone = false;
                label.style.textDecoration = "none";
            }
        });
    });
}

elements.form.addEventListener('submit', function (e) {
    e.preventDefault();

    /* La méthode FormData permet de récupérer les données d'un formulaire en utilisant les champs valides
    (tout les champs comportant l'attribut 'name'). Ici je récupère toutes les clés et valeurs du formulaire */
    const formData = new FormData(elements.form);

    /* Puis je créé un objet dans lequel je range les données de la tâche, cela rend le code plus maintenable si je décide
    d'ajouter de nouvelles fonctionnalités (id, priorité, catégorie...) */
    const newTask = {
        title: formData.get('task_name'), //Je récupère la donnée de l'input nommé "task_name" (name="task_name")
        isDone: false, //Par défaut, la tâche n'est pas terminée
    };

    // J'ajoute cette nouvelle tâche (objet) au tableau tasks
    tasks.push(newTask);

    localStorage.setItem("tasks", JSON.stringify(tasks));
    const storedTasks = JSON.parse(localStorage.getItem("tasks"));
    console.log(storedTasks);

    //Puis je met le contenu à jour en appelant la fonction createNewTask
    createNewTask();
    elements.form.reset();
});


function deleteAllTasks() {

    elements.deleteAllTasksButton.addEventListener('click', function (e) {
        e.preventDefault();

        localStorage.clear(tasks); // Supprime les éléments de localStorage
        elements.allTasksOl.innerHTML = ''; // Supprime les éléments de la page HTML

        console.log(tasks);
    })
};

console.log(localStorage);