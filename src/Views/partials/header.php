<header class="main-header">
    <div class="nav-content">
        <a href="?url=loja" class="header-logo-container">
            <img src="assets/images/SoundHaven.png" alt="Logo SoundHaven" class="header-logo-img" onerror="this.src='https://placehold.co/40x40/1db954/white?text=SH'">
            <div class="header-logo-text">
                <span class="logo-main-title">SoundHaven</span>
                <span class="logo-subtitle">Acervo Digital</span>
            </div>
        </a>
        
        <div class="header-right-menu">
            <a href="?url=album/novo" class="btn-adicionar-album">
                <i class="fas fa-plus-circle"></i> Adicionar Álbum
            </a>

            <div class="profile-dropdown-container" id="profileDropdown">
                <div class="profile-avatar-trigger" onclick="toggleDropdown()"> 
                    <img src="assets/images/default-avatar.png" alt="Perfil" class="profile-avatar" onerror="this.src='https://ui-avatars.com/api/?background=1db954&color=fff'">
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>

                <nav class="dropdown-menu" id="myDropdown">
                    <ul>
                        <li><a href="?url=dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                        <li><a href="?url=colecao"><i class="fas fa-list-alt"></i> Minha Coleção</a></li>
                        <li><a href="?url=loja"><i class="fas fa-store"></i> Loja</a></li>
                        <li class="separator"></li>
                        <li><a href="?url=perfil"><i class="fas fa-user-circle"></i> Meu Perfil</a></li>
                        <li><a href="?url=logout" class="logout-link"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header>

<script>
function toggleDropdown() {
    document.getElementById("myDropdown").classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.closest('.profile-dropdown-container')) {
        var dropdowns = document.getElementsByClassName("dropdown-menu");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
</script>