@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col s12">
            <nav class="otimize">
                <div class="nav-wrapper">
                    <a href="/" class="breadcrumb"><i class="material-icons">home</i></a>
                    <a href="/" class="breadcrumb">Página inicial</a>
                </div>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="row">
                    <div class="col s12">
                        <div id="profile-card" class="card" style="overflow: visible;">
                            <div class="card-content blue-clac">
                                <h5 class="thin white-text center">Painel do Usuário</h5>
                            </div>

                            <div class="card-content ">
                                <ul class="collection-item avatar center">
                                    <li><i class="large material-icons circle">person</i></li>
                                </ul>

                                <h2 class="card-title activator grey-text text-darken-4 center">Jenisvaldo Silveira Monteiro</h2><br><br>
                                <p><i class="material-icons">assignment_ind</i> Login: jenisval.monteiro</p>
                                <hr>
                                <p><i class="material-icons">security</i> Perfil: Administrador do Sistema</p>
                                <hr>
                                <p><i class="material-icons">email</i> Email: jenisval.monteiro@lorenipsun.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
