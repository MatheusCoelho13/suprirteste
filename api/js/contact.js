document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#formContato").addEventListener("submit", function (event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        let formData = new FormData(this);

        fetch("../controller/contacts.php", {
            method: "POST",
            body: formData
        })
   
        .then(response => {
            if (!response.ok) {
                throw new Error("Erro na conexão com o servidor.");
            }
            return response.json();
        })
        .then(data => {
            if (data.status) {
                Swal.fire({
                    icon: "success",
                    title: "Sucesso!",
                    text: data.msg,
                    confirmButtonText: "OK"
                }).then(() => {
                
                });
            } else {
                let errorMsg = data.msg;
                if (data.error) {
                    errorMsg += "\n\nErro técnico: " + data.error;+ console.log(formData); // Adiciona detalhes do erro
                }

                Swal.fire({
                    icon: "error",
                    title: "Erro!",
                    text: errorMsg,
                });
            }
        })
        .catch(error => {
            console.error("Erro na requisição:", error);
            Swal.fire({
                icon: "error",
                title: "Erro de conexão!",
                text: "O sistema não conseguiu se conectar ao servidor. Verifique se o arquivo 'mandar_contatos.php' existe e está acessível.",
            });
        });
    });
});
