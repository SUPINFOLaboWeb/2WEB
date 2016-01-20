var menu = document.getElementById('menu');

menu.style.color = 'blue';

// Création de l'élément puce
var puce = document.createElement('li');
// Ajout du contenu de la puce
puce.innerText = 'Puce ajoutée en JS';
// Ajout de la puce à la fin de la liste
menu.appendChild(puce);

function changeColor() {
    menu.style.color = 'red';
}