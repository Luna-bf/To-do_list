const coverBackground = document.getElementById('cover-background');
const burgerMenuBtn = document.getElementById('check');

burgerMenuBtn.addEventListener('click', function () {

    if (burgerMenuBtn.checked) { // Si l'élément du burger menu a l'attribut "checked" alors :

        coverBackground.classList.add('cover'); // J'ajoute la classe .cover à l'élément coverBackground
    } else { // Sinon (si l'élément n'a pas l'attribut "checked") :

        coverBackground.classList.remove('cover'); // Je retire la classe .cover à l'élément coverBackground
    }
});

// event représente l'événement qui vient de se produire (ici, le click du bouton faisant apparaître le burger menu)
document.addEventListener('click', function(event) {

    if(coverBackground.contains(event.target)) {
        burgerMenuBtn.checked = false; // Je supprime l'attribut "checked"
        coverBackground.classList.remove('cover'); // Et je retire l'effet d'assombrissement de l'écran
    }
});