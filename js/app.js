//Je récupère mes élèments HTML
const elements = {
    allTasks: document.querySelector('#all-tasks ul'),
    form: document.querySelector('#task-form form'),
    deleteBtn: document.querySelector('#delete-task-btn')
};

//J'indique mes priorités et leur valeur
const PRIORITY_HIGH = 1;
const PRIORITY_NORMAL = 2;
const PRIORITY_LOW = 3;

let myTasks = [
{
    title: 'Finir ma todo list',
    priority: PRIORITY_HIGH,
    isFinished: false
},

{
    title: 'Préparer les cadeaux de Noël',
    priority: PRIORITY_NORMAL,
    isFinished: false
},

{
    title: 'Finir Hollow Knight à 112%',
    priority: PRIORITY_LOW,
    isFinished: false
}

];

console.log(myTasks);
