```javascript

let tasks = [];

function createNewTask() {

    elements.allTasksUl.innerHTML = '';
    
    tasks.forEach(task => {

        const li = document.createElement('li');

        li.textContent += task.title; //J'accède aux propriétés de l'objet newTask pour obtenir le texte de la tâche
        elements.allTasksUl.append(li);
    });
}

elements.form.addEventListener('submit', function (e) {
    e.preventDefault();
    
    const formData = new FormData(elements.form);

    const newTask = {
        title: formData.get('task_name'),
    };
    
    tasks.push(newTask);

    createNewTask();
});

/* Comment est-ce que l'objet newTask et le tableau tasks interagissent ensemble ?

A la fin de l'event listener, j'ajoute plusieurs objets "newTask" au tableau "tasks", en code cela donne :
        
    let tasks = [
        {
            title: "Aller faire les courses"
        },

        {
            title: "Comprendre mon code"
        },

        {
            title: "Trouver un stage"
        }
    ];
    
Dans la fonction createNewTask(), j'utilise une boucle forEach pour parcourir le tableau tasks qui est désormais un tableau 
d'objet, en utilisant l'élément task (élément traité lors du tour de boucle) pour parcourir le tableau task afin de renvoyer
du texte dans l'élément li (la propriété "title").

Chaque tour de boucle renverra la valeur de la propriété "title", donc :
    tasks[0] ("Aller faire les courses")
    tasks[1] ("Comprendre mon code")
    tasks[2] ("Trouver un stage")
*/
```