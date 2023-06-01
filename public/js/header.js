// Seleciona o botão do menu hamburger e o menu principal
const menuToggle = document.querySelector('.menu-toggle');
const navbar = document.querySelector('.navbar');

// Adiciona um ouvinte de eventos para o clique no
menuToggle.addEventListener('click', () => {
    navbar.classList.toggle('show');
  });
