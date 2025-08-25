## Objectifs

Réaliser une todolist en JavaScript natif (sans framework).

## Instructions

### Données initiales

```javascript
const tasks = [
    {
        title: "Apprendre mon cours de JavaScript",
        priority: 1
    },
    {
        title: "Créer mon compte Github",
        priority: 2
    },
    {
        title: "Répondre à mes emails",
        priority: 3
    }
];
```

### Mettre en place le projet

* Créer un fichier *index.html*, un fichier *app.js* dans un sous-dossier *js* et un fichier *style.css* dans un sous-dossier *css*
* Sur le fichier *index.html*, créer une structure html classique ainsi que 2 sections : une pour la liste des tâches et une autre pour le formulaire d'ajout d'une tâche
* Sur le fichier *main.css*, créer des classes pour les couleurs des 3 priorités (1 pour élevée, 2 pour normale, 3 pour basse)

### Afficher la liste des tâches

Lors du chargement de la page, afficher sous forme de liste toutes les tâches du tableau *myTasks*.

Une tâche devra ressembler à ça :
```html
    <li>
        <label>
            <input type="checkbox">
            Nom de la tâche
        </label>
    </li>
```

Le texte de la tâche doit s'afficher en rouge si la priorité est élevée (valeur 1), en vert si la priorité est normale (valeur 2), en bleu si la priorité est basse (valeur 3).

### Ajouter une nouvelle tâche

Créer un formulaire avec un champ texte, un menu déroulant et un bouton. Le champ texte correspondra au texte de la tâche et le menu déroulant sa priorité (il y aura donc 3 valeurs possibles : 1, 2, 3). Lorsque l'on valide le formulaire en cliquant sur le bouton, une nouvelle tâche est créée dans la liste, avec la bonne couleur pour la priorité.

Si aucune priorité n'est sélectionnée, afficher la tâche en noir.

### Supprimer une tâche

Créer en-dessous du formulaire un bouton "Supprimer une tâche". Lorsque l'on clique sur ce bouton cela supprime toutes les tâches qui ont été cochées.

### Afficher un message de notification

Lorsqu'une ou plusieurs tâches ont été supprimées, afficher un message de notification à l'utilisateur du type "x tâches supprimées avec succès".

### Tri des tâches

La liste des tâches s'affichent de la priorité la plus élevée à la moins élevée.

### Persistence des tâches

Lorsque vous réactualisez, toutes les tâches créées disparaissent. Mettre en place un système pour les conserver (localstorage).


# Mes consignes

### Ajouter une catégorie

Permettre à l'utilisateur d'ajouter une catégorie à ses tâches, gérer l'absence de catégorie.

### Supprimer toutes les tâches

Créer en-dessous du formulaire un bouton "Supprimer toutes les tâches". Lorsque l'on clique sur ce bouton cela supprime toutes les tâches, qu'elles soient terminées ou non.

### Etat des tâches

Faire en sorte que les tâches terminées ou non restent comme tel même après avoir actualisé la page.

### Modification d'une tâche

Permettre à l'utilisateur de modifier la priorité, la catégorie et le nom d'une tâche.

### Filtrer des tâches

Filtrer les tâches en fonction de leur catégorie.

### Texte de todo list vide

Quand il n'y a pas de tâches à afficher, afficher le texte suivant : "Pas de tâches à afficher."

### Responsive

Le projet doit être responsive.