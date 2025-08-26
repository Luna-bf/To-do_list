// DOM
const addTaskInput = document.querySelector("#add-task input");
const addTaskButton = document.querySelector("#add-task button");
const tasksList = document.querySelector("#tasks ul");

// CONSTS/VARS
/* Je lis (lire) ce qu'il y a en mémoire et je converti la string tasks ("[{name: 'salut', isComplete: false}]") en tableau
([{name: 'salut', isComplete: false}]) puis je le récupère, si il n'y a rien, je renvoie un tableau vide */
const tasks = JSON.parse(localStorage.getItem("tasks")) || [];

// EVENTS
// Lorsque le btn est cliqué, on appelle la function addTask()
addTaskButton.addEventListener('click', addTask); // Pas besoin de preventDefault car le bouton n'est pas dans un formulaire
tasksList.addEventListener('click', updateTask); // Ecouteur sur le ul, comme on lui a passé la fonction updateTask en paramètre, il va détecter tout les événements passés dans cette fonction et y réagira

function addTask() {
    if (!addTaskInput.value.trim()) { // Si la valeur de l'input est vide (voir documentation de trim())
        return; // Je ne renvoie rien
    }

    // Structure d'une tâche
    const task = {
        id: tasks.length + 1, // Pour que l'id s'incrémente à chaque ajout d'une nouvelle tâche
        name: addTaskInput.value.trim(),
        isComplete: false,
    }

    tasks.push(task);
    addTaskInput.value = '';

    // Ajout du tableau tasks dans localStorage (converti en chaîne de caractères)
    localStorage.setItem("tasks", JSON.stringify(tasks));

    render(); // Appel de la fonction render() à chaque fois qu'une tâche est créée
}

function updateTask(e) {
    // Je récupère le parent de l'élément visé (ici, la balise li)
    const li = e.target.parentElement; // Va assigner le code ci-dessous à tout les parent de l'élément button (ici, la balise li)

    const taskId = li.dataset.id;
    const task = tasks.find(task => task.id == taskId); // Vérifie si l'id de la tâche cliquée (task) est identique à celui d'une des tâches du tableau (taskId)

    console.log(task);

    if (e.target.tagName === 'SPAN') {

        // Doc pour dataset = https://developer.mozilla.org/fr/docs/Web/API/HTMLElement/dataset
        // console.log(li.dataset.id);
        task.isComplete = !task.isComplete; // Lorsque je clique sur span, j'inverse la propriété isComplete, si je re-clique dessus, la propriété de isComplete sera 'false'
        e.target.classList.toggle('done');
    }

    // Mise à jour du localStorage
    localStorage.setItem("tasks", JSON.stringify(tasks));
}

// RENDER (affichage en HTML)
function render() {

    tasksList.innerHTML = '';

    tasks.forEach((task) => {
        // Chaque tâche ajoutée aura cette structure. Cette méthode est beaucoup plus rapide, je dois l'utiliser pour mon projet de to-do list
        // Doc pour data-* = https://developer.mozilla.org/fr/docs/Web/HTML/Reference/Global_attributes/data-*
        tasksList.innerHTML += `
            <li data-id=${task.id}>
                <span>${task.name}</span>
                <button>Supprimer</button>
            </li>
        `;
    })
}

// INIT
render();

// Console -> Application -> localStorage (pour accéder au localStorage)
/*
localStorage.setItem('name', 'Luna');
localStorage.setItem('age', '20');

console.log(localStorage.getItem("age"));
*/