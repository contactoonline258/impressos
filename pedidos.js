document.addEventListener('DOMContentLoaded', function() {
    initModals();
    
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');
    
    if (menuToggle && menu) {
        menuToggle.addEventListener('click', function() {
            menu.classList.toggle('show');
        });
    }
});

function initModals() {
    const modal = document.getElementById('modal-pedido');
    if (!modal) return;

    document.querySelectorAll('.btn-pedido').forEach(btn => {
        btn.addEventListener('click', function() {
            const produto = this.getAttribute('data-produto');
            document.getElementById('nome-produto').textContent = produto;
            document.getElementById('produto-hidden').value = produto;
            modal.style.display = 'block';
        });
    });

    document.querySelector('.close-modal').addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    const formPedido = document.getElementById('form-pedido');
    if (formPedido) {
        formPedido.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btnSubmit = this.querySelector('button[type="submit"]');
            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Enviando...';
            
            try {
                const formData = new FormData(this);
                const response = await fetch('processa_pedido.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.sucesso) {
                    alert('Pedido enviado com sucesso! Entraremos em contato em breve.');
                    formPedido.reset();
                    modal.style.display = 'none';
                } else {
                    throw new Error(data.mensagem || 'Erro desconhecido no servidor');
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao enviar pedido: ' + error.message);
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Enviar Pedido';
            }
        });
    }
}