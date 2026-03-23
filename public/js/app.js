const coverBackground = document.getElementById('cover-background');
const burgerMenuBtn = document.getElementById('check');

burgerMenuBtn.addEventListener('click', function () {

    if (burgerMenuBtn.checked) { // Si l'élément du burger menu a l'attribut "checked" alors :

        coverBackground.classList.add('cover'); // J'ajoute la classe .cover à l'élément coverBackground
    } else { // Sinon (si l'élément n'a pas l'attribut "checked") :

        coverBackground.classList.remove('cover'); // Je retire la classe .cover à l'élément coverBackground
    }
});