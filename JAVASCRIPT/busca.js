// BUSCA

document.addEventListener("DOMContentLoaded", function () {
    const campoBusca = document.querySelector("#campoBusca");
    const resultadosBusca = document.querySelector("#resultadosBusca");
  
    // Função para carregar os dados de busca
    async function carregarDadosBusca() {
      const response = await fetch("dadosBusca.json");
      return await response.json();
    }
  
    // Função para filtrar resultados
    function filtrarResultados(query, dados) {
      return dados.filter(item =>
        item.titulo.toLowerCase().includes(query.toLowerCase()) ||
        item.descricao.toLowerCase().includes(query.toLowerCase())
      );
    }
  
    // Exibir resultados da busca
    function exibirResultados(resultados) {
      resultadosBusca.innerHTML = ""; // Limpa resultados anteriores
      if (resultados.length === 0) {
        resultadosBusca.style.display = "none";
        return;
      }
      resultadosBusca.style.display = "block";
  
      resultados.forEach(resultado => {
        const div = document.createElement("div");
        div.textContent = resultado.titulo;
        div.addEventListener("click", () => {
          window.location.href = resultado.url;
        });
        resultadosBusca.appendChild(div);
      });
    }
  
    // Evento de busca
    campoBusca.addEventListener("input", async function () {
      const query = campoBusca.value.trim();
      if (query.length === 0) {
        resultadosBusca.style.display = "none";
        return;
      }
  
      const dados = await carregarDadosBusca();
      const resultados = filtrarResultados(query, dados);
      exibirResultados(resultados);
    });
  
    // Esconde os resultados ao clicar fora
    document.addEventListener("click", function (e) {
      if (!document.querySelector("#busca").contains(e.target)) {
        resultadosBusca.style.display = "none";
      }
    });
  });
  