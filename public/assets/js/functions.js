// assets/js/comum.js

const formatarTempo = segundos => {
    if (!segundos) return '0:00';
    const min = Math.floor(segundos / 60);
    const seg = segundos % 60;
    return `${min}:${seg.toString().padStart(2, '0')}`;
};

const renderizarFaixas = (faixas, containerId = 'corpoListaFaixas') => {
    const corpoTabela = document.getElementById(containerId);
    if (!corpoTabela) return;

    corpoTabela.innerHTML = '';
    
    faixas.forEach(f => {
        const tr = document.createElement('div'); // Use div ou tr conforme seu CSS
        tr.className = 'faixa-item-linha'; // Sua classe de estilo
        
        // Flexibilidade: aceita 'position' (Discogs) ou 'numero_faixa' (Banco)
        const pos = f.position || f.numero_faixa || '';
        const titulo = f.title || f.titulo || 'Sem título';
        const duracao = f.duration || f.duracao || 0;

        tr.innerHTML = `
            <span class="col-pos">${pos}</span>
            <span class="col-titulo">${titulo}</span>
            <span class="col-duracao">${formatarTempo(duracao)}</span>
        `;
        corpoTabela.appendChild(tr);
    });
};