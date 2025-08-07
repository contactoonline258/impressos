document.addEventListener('DOMContentLoaded', function () {
  // Menu responsivo
  const menuToggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('menu');

  menuToggle.addEventListener('click', function () {
    menu.classList.toggle('show');
    this.setAttribute('aria-expanded', menu.classList.contains('show'));
  });

  // Fechar menu ao clicar em um link
  const menuLinks = document.querySelectorAll('.menu a');
  menuLinks.forEach(link => {
    link.addEventListener('click', function () {
      menu.classList.remove('show');
      menuToggle.setAttribute('aria-expanded', 'false');
    });
  });

  // Enviar formulário de contacto via AJAX
  const formContacto = document.getElementById('form-contacto');
  if (formContacto) {
    formContacto.addEventListener('submit', async function (e) {
      e.preventDefault();

      const nome = this.nome.value.trim();
      const email = this.email.value.trim();
      const mensagem = this.mensagem.value.trim();

      if (!nome || !email || !mensagem) {
        alert('Por favor, preencha todos os campos.');
        return;
      }

      const formData = new FormData(this);

      try {
        const response = await fetch('processa_contacto.php', {
          method: 'POST',
          body: formData
        });

        const data = await response.json();

       if (data.sucesso) {
  alert('Mensagem enviada com sucesso! Obrigado pelo contacto.');
  this.reset();
  this.nome.focus();
} 
else {
  alert('Erro ao enviar mensagem: ' + data.mensagem);
}
      } catch (error) {
        console.error('Erro na submissão do formulário:', error);
        alert('Erro inesperado. Tente novamente mais tarde.');
      }
    });
  }

  // Scroll suave para links internas
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const targetId = this.getAttribute('href');
      const targetElement = document.querySelector(targetId);

      if (targetElement) {
        e.preventDefault();
        window.scrollTo({
          top: targetElement.offsetTop - 80,
          behavior: 'smooth'
        });
      }
    });
  });
});
