<header>

    <div class="navbar-fixed">
        <nav class="indigo darken-4">
            <div class="content">
                <div class="nav-wrapper">

                    <a id="logo-container" href="/" class="brand-logo">
                        <span class="left-align white-text">Ger-Estoque</span>
                    </a>

                    <a tabindex="0" href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons white-text">menu</i></a>

                    <ul class="right hide-on-med-and-down">
                        <li><a href="/"><i class="small material-icons white-text">home</i></a></li>
                        <li><a href="{{route('clientes.index')}}" class="white-text">Clientes</a></li>
                        <li><a href="{{route('fornecedores.index')}}" class="white-text">Fornecedores</a></li>
                        <li><a href="{{route('produtos.index')}}" class="white-text">Produtos</a></li>
                        <li><a href="{{route('vendas.index')}}" class="white-text">Vendas</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>


    <ul id="slide-out" class="sidenav indigo darken-4">
        <li>
            <div class="user-view">

                <a id="logo-container" tabindex="0" href="/" class="brand-logo">
                    <span class="left-align white-text">Ger-Estoque</span>
                </a>

                <span class="name" class="white-text">Jenisvaldo Silveira</span>
                <span class="email" class="white-text">jenisval.monteiro@lorenipsun.com</span>

            </div>
        </li>


        <li><a href="/" class="white-text">Página Inicial</a></li>
        <li><a href="{{route('clientes.index')}}" class="white-text">Clientes</a></li>
        <li><a href="{{route('fornecedores.index')}}" class="white-text">Fornecedores</a></li>
        <li><a href="{{route('produtos.index')}}" class="white-text">Produtos</a></li>
        <li><a href="{{route('vendas.index')}}" class="white-text">Vendas</a></li>
    </ul>


</header>
