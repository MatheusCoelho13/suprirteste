//! parte de abrir o menu
const hamburger = document.querySelector(".hamburger");
const nav = document.querySelector(".sidebar");
const menucloset=document.querySelector(".close-menuu")
// console.log(menucloset);
hamburger.addEventListener("click", () => nav.classList.toggle("active") ,console.log("logged out") );
hamburger.addEventListener("click", () =>console.log("logged out") );
menucloset.addEventListener("click", () => nav.classList.remove("active"))
//!parte da api de email ( mandar email)
// Exibe o e-mail no console
console.log(email);

// Fetch para buscar dados do servidor
fetch('../../admin/dashboard.php')
    .then(response => response.json())
    .then(data => {
        console.log(data); // Aqui você pode manipular os dados recebidos
    })
    .catch(error => console.error('Erro ao buscar dados:', error));

// Função para abrir a janela de justificativa com SweetAlert
function abrirjanela(tipo) {
    console.log("funciona porra")
    let subject = ""; // Supondo que o subject seja algo fixo, você pode personalizar
    if (tipo === "bom") { // "bom" é uma string
        subject = "sucess";
    } else {
        subject = "negado";
    }

    // Exibe o valor do 'subject' para debug
    console.log("Assunto: ", subject);

    // Lógica para exibir a janela de justificativa com SweetAlert
    Swal.fire({
        title: 'Digite sua justificativa',
        html: `<textarea id="justificativa" class="swal2-textarea" placeholder="Sua justificativa aqui"></textarea>`,
        showCancelButton: true,
        confirmButtonText: 'Enviar E-mail',
        preConfirm: () => {
            const justificativa = Swal.getPopup().querySelector('#justificativa').value;
            if (!justificativa) {
                Swal.showValidationMessage('Por favor, insira uma justificativa');
                return false; // Impede o envio se não tiver justificativa
            }
            return { justificativa: justificativa };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const email1 = email; // Aqui você pode colocar dinamicamente o email do usuário
            enviarEmailaceito(email1, subject, result.value.justificativa); // Envia o email
        }
    });
}



// Função para enviar o e-mail com a justificativa
function enviarEmailaceito(email1, subject, justificativa) {
    // Verifique se o e-mail, subject e justificativa são válidos antes de enviar
    console.log("Enviando e-mail para: ", email1);
    console.log("Assunto: ", subject);
    console.log("Justificativa: ", justificativa);

    // Envia a requisição para o servidor com o e-mail e a justificativa
    fetch('../auth/email.php', {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/x-www-form-urlencoded' 
        },
        body: new URLSearchParams({
            email: email1, // Passando o e-mail aqui
            subject: subject,
            justificativa: justificativa, // Passando a justificativa aqui
        }).toString()
    })
    .then(response => response.text())  // Supondo que o servidor retorne texto simples
    .then(data => {
        // Trate a resposta, mostrando uma mensagem para o usuário
        Swal.fire('Resultado', data, 'success');
    })
    .catch(error => {
        console.error('Erro:', error);
        Swal.fire('Erro', 'Ocorreu um problema ao enviar o e-mail.', 'error');
    });
      return; // A função é interrompida e não continua após este ponto
}
function alerta_erro(){
     Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: errorMsg,
                });
            }
            