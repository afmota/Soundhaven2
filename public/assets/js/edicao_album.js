// assets/js/edicao_album.js

document.addEventListener('DOMContentLoaded', function() {
    const inputCapa = document.getElementById('edicaoCapaUrl');
    const imgPreview = document.getElementById('edicaoImg');

    // Atualiza a prévia da imagem sempre que o usuário mudar a URL
    inputCapa.addEventListener('input', function() {
        const novaUrl = this.value.trim();
        if (novaUrl) {
            imgPreview.src = novaUrl;
        } else {
            imgPreview.src = 'assets/img/default-cover.png'; // Uma imagem padrão caso limpe
        }
    });

    console.log("Módulo de edição carregado de forma independente.");
});

document.addEventListener('DOMContentLoaded', function() {
    
    // Delegar o clique para qualquer ícone de remover tag dentro dos containers
    const containers = ['containerGeneros', 'containerEstilos', 'containerProdutores'];
    
    containers.forEach(id => {
        const container = document.getElementById(id);
        if (!container) return;

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-tag')) {
                const tag = e.target.closest('.tag-item');
                
                // Efeito visual de saída
                tag.style.opacity = '0';
                tag.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    tag.remove();
                    // Aqui chamaremos a função para atualizar os inputs ocultos futuramente
                }, 200);
            }
        });
    });
});

document.querySelectorAll('.btn-add-tag').forEach(btn => {
    btn.addEventListener('click', function() {
        const target = this.getAttribute('data-target'); // Ex: 'Generos'
        const searchBox = document.getElementById('searchContainer' + target);
        
        // Toggle: Se estiver visível, esconde. Se estiver escondido, mostra e foca.
        if (searchBox.style.display === 'none') {
            searchBox.style.display = 'flex';
            searchBox.querySelector('input').focus();
        } else {
            searchBox.style.display = 'none';
        }
    });
});

// Dentro do seu edicao_album.js

document.querySelectorAll('.input-search-tag').forEach(input => {
    // Escuta o "Enter" para adicionar a tag
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Evita que o formulário seja enviado
            adicionarTag(this);
        }
    });

    // Escuta a seleção via datalist (evento change)
    input.addEventListener('change', function() {
        if (this.value.trim() !== '') {
            adicionarTag(this);
        }
    });
});

function adicionarTag(input) {
    const valor = input.value.trim();
    if (valor === '') return;

    // Descobre qual é o tipo (generos, estilos ou produtores)
    // baseado no ID do container pai ou um data-attribute
    const tipo = input.getAttribute('data-tipo'); // Ex: 'generos'
    const container = document.getElementById('container' + capitalize(tipo));

    // 1. Criar o elemento da Tag
    const span = document.createElement('span');
    span.className = 'tag-item';
    span.innerHTML = `
        ${valor}
        <input type="hidden" name="${tipo}[]" value="${valor}">
        <i class="fas fa-times remove-tag"></i>
    `;

    // 2. Adicionar ao container
    container.appendChild(span);

    // 3. Limpar e esconder o campo de busca
    input.value = '';
    input.parentElement.style.display = 'none';
}

function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Pegamos o número atual de faixas para continuar a contagem
let faixaIndex = document.querySelectorAll('.faixa-item').length;

document.getElementById('btnAdicionarFaixa').addEventListener('click', function() {
    const corpo = document.getElementById('corpoListaFaixas');
    
    // Sugestão de próxima posição (última + 1)
    const proximaPos = corpo.querySelectorAll('.faixa-item').length + 1;

    const novaLinha = document.createElement('div');
    novaLinha.className = 'faixa-item';
    novaLinha.innerHTML = `
        <input type="hidden" name="faixas[${faixaIndex}][id]" value="new">
        <input type="number" name="faixas[${faixaIndex}][posicao]" value="${proximaPos}" class="input-pos">
        <input type="text" name="faixas[${faixaIndex}][titulo]" placeholder="Título da música" class="input-titulo">
        <input type="text" name="faixas[${faixaIndex}][duracao]" placeholder="00:00" class="input-duracao mask-tempo">
        <button type="button" class="btn-remove-faixa"><i class="fas fa-trash"></i></button>
    `;

    corpo.appendChild(novaLinha);
    faixaIndex++;
    
    // Focar no título da nova faixa
    novaLinha.querySelector('.input-titulo').focus();
});

// Delegação de evento para remover a faixa (mesmo as que acabaram de ser criadas)
document.getElementById('corpoListaFaixas').addEventListener('click', function(e) {
    if (e.target.closest('.btn-remove-faixa')) {
        const linha = e.target.closest('.faixa-item');
        
        if (confirm('Deseja remover esta faixa da lista?')) {
            linha.style.opacity = '0';
            setTimeout(() => linha.remove(), 200);
        }
    }
});

document.getElementById('corpoListaFaixas').addEventListener('input', function(e) {
    if (e.target.classList.contains('input-duracao')) {
        let v = e.target.value.replace(/\D/g, ''); // Remove o que não é número
        if (v.length > 4) v = v.substring(0, 4);   // Limita a 4 dígitos
        
        if (v.length >= 3) {
            v = v.substring(0, v.length - 2) + ':' + v.substring(v.length - 2);
        }
        e.target.value = v;
    }
});

// Ajuste na máscara de tempo para suportar HH:MM:SS ou garantir o formato TIME
document.getElementById('corpoListaFaixas').addEventListener('input', function(e) {
    if (e.target.classList.contains('input-duracao')) {
        let v = e.target.value.replace(/\D/g, ''); 
        
        // Se o cara digitar 4 números (MMSS), a gente formata
        if (v.length >= 3 && v.length <= 4) {
            v = v.substring(0, v.length - 2) + ':' + v.substring(v.length - 2);
        } 
        // Se for mais que isso, a gente começa a pensar em HH:MM:SS
        else if (v.length > 4) {
            v = v.substring(0, v.length - 4) + ':' + v.substring(v.length - 4, v.length - 2) + ':' + v.substring(v.length - 2);
        }
        
        e.target.value = v.substring(0, 8); // Limite do formato TIME
    }
});