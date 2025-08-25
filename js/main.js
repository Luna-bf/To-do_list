const elements = {
    addTaskNameInput: document.getElementById("task-name"),
    allTasksUl: document.querySelector("#all-tasks ul"),
    form: document.getElementById('task-form'),
    addTaskButton: document.getElementById("add-task-btn"),
}

let tasks = [];

document.addEventListener('DOMContentLoaded', function () {

    console.log('DOM loaded');
    createNewTask();
})

function createNewTask() {

    elements.allTasksUl.innerHTML = '';

    // Pour chaque task du tableau tasks:
    tasks.forEach(task => {

        // Je créé un élément "li" et "input" puis j'assigne un type 'checkbox' à mon élément input
        const li = document.createElement('li');
        // const input = document.createElement('input');
        // const label = document.createElement('label');
        // input.type = "checkbox";

        li.textContent += task.title; //J'accède aux propriétés de l'objet newTask pour obtenir le texte de la tâche
        elements.allTasksUl.append(li);
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
    };

    // J'ajoute cette nouvelle tâche (objet) au tableau tasks
    tasks.push(newTask);

    //Puis je met le contenu à jour en appelant la fonction createNewTask
    createNewTask();
});