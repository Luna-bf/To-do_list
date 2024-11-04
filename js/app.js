//Je récupère mes élèments HTML
const elements = {
    allTasks: document.querySelector('#all-tasks ul'),
    form: document.querySelector('#task-form form'),
    deleteBtn: document.querySelector('#delete-task-btn'),
    deleteAllBtn: document.querySelector('#delete-all-btn')
};

//J'indique mes priorités et leur valeur
const priorityHigh = 1;
const priorityNormal = 2;
const priorityLow = 3;

let myTasks = [
{
    title: 'Finir ma todo list',
    priority: priorityHigh,
    isDone: false
},

{
    title: 'Préparer les cadeaux de Noël',
    priority: priorityNormal,
    isDone: false
},

{
    title: 'Finir Hollow Knight à 112%',
    priority: priorityLow,
    isDone: false
}

];

console.log(myTasks);


//La fonction qui va me permettre d'ajouter des tâches
function addTask() {
    
    //J'utilise une boucle car je veux que cela se REPETE pour CHAQUE tâche
    for(let myTask of myTasks) {
        
        const li = document.createElement('li'); //Je créé l'élément qui va me servir pour cette tâche
        const label = document.createElement('label');
        const input = document.createElement('input');
        const type = document.createElement('type', 'checkbox');
        const icon = e.appendChild('<i class="fa-solid fa-pen" aria-hidden="false"></i>');
    }
    /*
    let newTask = document.createElement('label');
    
    let checkbox = document.createElement('input');
    checkbox.setAttribute('type', 'checkbox');
    
    newTask.appendChild(checkbox);
    
    myTasks.push(newTask);*/
}
        //<label><input type="checkbox">Task</label>

elements.allTasks.addEventListener('click', (e) => {
    e.preventDefault();
});


//La fonction qui va me permettre de supprimer uniquement les tâches terminées
elements.deleteBtn.addEventListener('click', (e) => {
    
    //J'empêche le navigateur de se recharger
    e.preventDefault();
    
});


//La fonction qui va me permettre de supprimer toutes les tâches
elements.deleteAllBtn.addEventListener('click', (e) => {
    
    //J'empêche le navigateur de se recharger
    e.preventDefault();
    
    myTasks.pop();
});
