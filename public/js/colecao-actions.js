/**
 * Funções de interação do Módulo Coleção
 */

function abrirModalColecao(dados) {
    const imgCapa = document.getElementById('modal-colecao-capa');
    const txtTitulo = document.getElementById('modal-colecao-titulo');
    const txtArtista = document.getElementById('modal-colecao-artista');
    const txtGravadora = document.getElementById('modal-colecao-gravadora');
    const txtAquisicao = document.getElementById('modal-colecao-aquisicao');
    const txtFormato = document.getElementById('modal-colecao-formato');
    const txtObservacoes = document.getElementById('modal-colecao-observacoes');
    
    if (imgCapa) {
        imgCapa.src = dados.capa || '';
        imgCapa.alt = dados.titulo || "Capa do Álbum";
    }

    if (txtTitulo) txtTitulo.innerText = dados.titulo || '';
    if (txtArtista) txtArtista.innerText = dados.artista || '';
    if (txtGravadora) txtGravadora.innerText = dados.gravadora || 'Independente/Outra';

    if (txtAquisicao) {
        if (dados.aquisicao && dados.aquisicao !== '0000-00-00') {
            const partes = dados.aquisicao.split('-');
            txtAquisicao.innerText = `${partes[2]}/${partes[1]}/${partes[0]}`;
        } else {
            txtAquisicao.innerText = '--/--/----';
        }
    }

    if (txtFormato) txtFormato.innerText = dados.formato || 'Não informado';
    
    if (txtObservacoes) {
        txtObservacoes.innerText = dados.observacoes && dados.observacoes.trim() !== '' 
            ? dados.observacoes 
            : 'Nenhuma observação registrada.';
    }

    const modalElement = document.getElementById('modalColecao');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}