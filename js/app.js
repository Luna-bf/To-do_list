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
    isChecked: false
},

{
    title: 'Préparer les cadeaux de Noël',
    priority: priorityNormal,
    isChecked: false
},

{
    title: 'Finir Hollow Knight à 112%',
    priority: priorityLow,
    isChecked: false
}

];

console.log(myTasks);

//La fonction qui va me permettre d'ajouter des tâches
function displayTasks() {
    const newTask = document.createElement('label');
    newTask = document.createTextNode('input');

    
}

console.log(displayTasks());
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
