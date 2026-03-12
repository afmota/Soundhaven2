document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('modalDetalhesColecao');
    const cards = document.querySelectorAll('.album-card');
    const closeBtn = modal.querySelector('.close-modal');

    const btnEditar = document.getElementById('btnEditarColecao');
    const btnDescartar = document.getElementById('btnDescartarColecao');

    const cacheFaixas = {};

    const formatarMoeda = valor =>
        new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor || 0);

    const formatarTempo = tempo => {

        if (!tempo) return '--:--';

        const partes = tempo.split(':');

        if (partes.length === 3) {

            const [h, m, s] = partes;

            return h === '00'
                ? `${m}:${s}`
                : `${h}:${m}:${s}`;
        }

        return tempo;
    };

    const setTxt = (id, text) => {

        const el = document.getElementById(id);

        if (el) el.textContent = text || 'N/D';
    };

    const renderizarTags = (containerId, dadosString, extraClass = '') => {

        const container = document.getElementById(containerId);

        if (!container) return;

        container.innerHTML = '';

        if (!dadosString) {
            container.innerHTML =
                '<span class="no-data">Nenhum registro encontrado</span>';
            return;
        }

        dadosString.split('|').forEach(item => {

            const span = document.createElement('span');

            span.className = `tag-item ${extraClass}`;
            span.textContent = item.trim();

            container.appendChild(span);
        });
    };

    const carregarFaixas = async midiaId => {

        const corpoTabela = document.getElementById('corpoTabelaFaixas');

        if (!corpoTabela) return;

        if (cacheFaixas[midiaId]) {
            renderizarFaixas(cacheFaixas[midiaId]);
            return;
        }

        corpoTabela.innerHTML =
            '<tr><td colspan="3">Carregando faixas...</td></tr>';

        try {

            const res = await fetch(`index.php?url=buscar_faixas&midia_id=${midiaId}`);

            const faixas = await res.json();

            cacheFaixas[midiaId] = faixas;

            renderizarFaixas(faixas);

        } catch (e) {

            corpoTabela.innerHTML =
                '<tr><td colspan="3">Erro ao carregar faixas</td></tr>';

            console.error(e);
        }
    };

    const renderizarFaixas = faixas => {

        const corpoTabela = document.getElementById('corpoTabelaFaixas');

        corpoTabela.innerHTML = '';

        if (!faixas.length) {

            corpoTabela.innerHTML =
                '<tr><td colspan="3">Nenhuma faixa cadastrada</td></tr>';

            return;
        }

        faixas.forEach(f => {

            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${f.numero_faixa}</td>
                <td>${f.titulo}</td>
                <td>${formatarTempo(f.duracao)}</td>
            `;

            corpoTabela.appendChild(tr);
        });
    };

    cards.forEach(card => {

        card.addEventListener('click', () => {

            const album = JSON.parse(card.dataset.album);

            modal.setAttribute('data-current-midia-id', album.midia_id);

            document.getElementById('detalheCapa').src =
                album.capa_url || 'assets/images/placeholder.jpg';

            setTxt('detalheTitulo', album.titulo);
            setTxt('detalheArtista', album.artista_nome);
            setTxt('detalheGravadora', album.gravadora_nome);
            setTxt('detalheCatalogo', album.numero_catalogo);
            setTxt('detalhePreco', formatarMoeda(album.preco));
            setTxt('detalheObservacoes', album.observacoes);

            const lanc =
                album.data_lancamento
                    ? new Date(album.data_lancamento + 'T12:00:00')
                        .toLocaleDateString('pt-BR')
                    : 'N/D';

            setTxt('detalheLancamento', lanc);

            const aquis =
                album.data_aquisicao
                    ? new Date(album.data_aquisicao + 'T12:00:00')
                        .toLocaleDateString('pt-BR')
                    : 'N/D';

            setTxt('detalheAquisicao', aquis);

            const tag = document.getElementById('detalheFormatoTag');

            tag.textContent = album.formato_nome;
            tag.style.backgroundColor = album.formato_cor;

            renderizarTags('containerTagsGeneros', album.generos);
            renderizarTags('containerTagsEstilos', album.estilos);
            renderizarTags('containerTagsProdutores', album.produtores);

            carregarFaixas(album.midia_id);

            modal.style.display = 'block';
        });
    });

    btnEditar.onclick = () => {

        const midiaId = modal.getAttribute('data-current-midia-id');

        if (!midiaId) return;

        window.location.href =
            `index.php?url=editar_album&midia_id=${midiaId}`;
    };

    btnDescartar.onclick = async () => {

        const midiaId = modal.getAttribute('data-current-midia-id');

        if (!midiaId) return;

        if (!confirm('Remover este álbum da coleção?')) return;

        const formData = new URLSearchParams();

        formData.append('midia_id', midiaId);

        try {

            const res = await fetch('index.php?url=descartar_album', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (data.success) location.reload();
            else alert(data.error || 'Erro ao descartar');

        } catch (e) {
            console.error(e);
        }
    };

    closeBtn.onclick = () => modal.style.display = 'none';

    window.onclick = e => {
        if (e.target === modal) modal.style.display = 'none';
    };
});