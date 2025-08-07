document.addEventListener('DOMContentLoaded', function() {
    // Inicializar modal de consulta
    const modalConsulta = document.getElementById('modal-consulta');
    if (!modalConsulta) return;

    // Abrir modal ao clicar nos botões de consulta
    document.querySelectorAll('.btn-consulta').forEach(btn => {
        btn.addEventListener('click', function() {
            const servico = this.getAttribute('data-servico');
            document.getElementById('nome-servico').textContent = servico;
            document.getElementById('servico-hidden').value = servico;
            modalConsulta.style.display = 'block';
        });
    });

    // Fechar modal
    document.querySelector('.close-modal').addEventListener('click', function() {
        modalConsulta.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modalConsulta) {
            modalConsulta.style.display = 'none';
        }
    });

    // Envio do formulário de consulta
    const formConsulta = document.getElementById('form-consulta');
    if (formConsulta) {
        formConsulta.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btnSubmit = this.querySelector('button[type="submit"]');
            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Enviando...';
            
            try {
                const formData = new FormData(this);
                const response = await fetch('processa_copia.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.sucesso) {
                    alert('Consulta enviada com sucesso! Entraremos em contato em breve.');
                    formConsulta.reset();
                    modalConsulta.style.display = 'none';
                } else {
                    throw new Error(data.mensagem || 'Erro desconhecido no servidor');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao enviar consulta: ' + error.message);
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Enviar Consulta';
            }
        });
    }
});