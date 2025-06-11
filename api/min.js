document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#elementor-form").addEventListener("submit", function (event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        let formData = new FormData(this);

        fetch("./api/controller/enviardados.php", {
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
