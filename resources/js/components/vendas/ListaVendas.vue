<template>
    <div>
        <v-loading :loading="loading" :is-full-page="true"></v-loading>

        <div class="row">
            <div class="col s12">
                <nav class="otimize">
                    <div class="nav-wrapper">
                        <a href="/" class="breadcrumb" title="Pagina inicial"><i class="material-icons">home</i></a>
                        <a href="/" class="breadcrumb">Página inicial</a>
                        <a href="!#" class="breadcrumb">Vendas</a>
                    </div>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <v-alert ref="alert"></v-alert>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <div class="card-panel">
                    <div class="row">
                        <h4 class="col xl6 l6 m6 s12">Lista de vendas cadastradas</h4>

                        <div class="col xl6 l6 m6 s12">
                            <a class="btn-floating indigo light-blue waves-effect waves-light right">
                                <i class="material-icons">add</i>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <p v-if="vendasCadastradas.length == 0 && loading"
                               class="label-carregamento">Buscando as vendas cadastradas...</p>

                            <p v-else-if="vendasCadastradas.length == 0"
                               class="label-info-nao-encontrada">Não há vendas cadastradas até o momento.</p>

                            <section v-else>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix ">search</i>
                                        <input tipo="materialize" id="search" type="text" class="validate" v-model="filtro.filterTerm">
                                        <label for="search">Pesquisar</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s12">
                                        <table class="responsive-table striped">
                                            <thead>
                                            <tr>
                                                <th>
                                                    <span>Data de Cadastro</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'created_at')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyDataCadastro }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Id</span>
                                                </th>

                                                <th>
                                                    <span>Cliente</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'cliente.nome_ou_razao_social')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyNomeOuRazaoSocial }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Produtos</span>
                                                </th>

                                                <th>
                                                    <span>Valor total</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'valor_total')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyValorTotal }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Status</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'status')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyStatus }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Data de Exclusão</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'data_exclusao')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyDataExclusao }">sort</i>
                                                    </a>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(venda, i) in listaFiltrada">
                                                <td>
                                                    <span>{{venda.data_cadastro}}</span>
                                                </td>

                                                <td>
                                                    <span>{{venda.id}}</span>
                                                </td>

                                                <td>
                                                    <span style="font-weight: bold">{{venda.cliente.nome_ou_razao_social}}</span>
                                                    <p><span>(CPF/CNPJ: {{venda.cliente.cpf_cnpj}})</span></p>
                                                </td>

                                                <td>
                                                    <ul class="browser-default">
                                                        <li v-for="(item_compra, j) in venda.itens">
                                                            <span>{{item_compra.produto.nome}}</span>
                                                            &nbsp;&nbsp;-&nbsp;&nbsp;
                                                            <span class="blue-text text-darken-3">({{item_compra.quantidade_itens}} itens à {{item_compra.valor_item_formatado}} cada.)</span>
                                                        </li>
                                                    </ul>
                                                </td>

                                                <td>
                                                    <span>{{venda.valor_total}}</span>
                                                </td>

                                                <td><span class="new badge" :class="{'green' : venda.status == 'Efetivado', 'red' : venda.status == 'Excluído'}" data-badge-caption="">{{venda.status}}</span></td>

                                                <td>
                                                    <span>{{venda.data_exclusao == null ? 'N/a' : venda.data_exclusao}}</span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col s12">
                                        <div class="left">
                                            <strong>Total de registros por pagina</strong>
                                            <select name="total_regs_pag"
                                                    id="total_regs_pag"
                                                    v-model="paginacao.regsPorPage">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col s12">
                                        <div class="center">
                                            <v-paginate :totalPage="paginacao.totalPages"
                                                        v-model="paginacao.currentPage"
                                                        nextText="Próximo"
                                                        prevText="Anterior"
                                                        :withNextPrev="true"
                                                        :customActiveBGColor="'#000099'"
                                                        @btnClick="mudaPagina"></v-paginate>
                                        </div>
                                    </div>
                                </div>

                            </section>


                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: "ListaVendas",
    data(){
        return {
            vendasCadastradas: [],
            loading: false,

            filtro: {
                sortProperty: 'nome_ou_razao_social',
                sortDirection: 'asc',
                filterTerm: '',
                sortPropertyDataCadastro: true,
                sortPropertyNomeOuRazaoSocial: false,
                sortPropertyValorTotal: false,
                sortPropertyStatus: false,
                sortPropertyDataExclusao: false,
                listaOrdenada: [],

            },
            paginacao: {
                regsPorPage: 5,
                totalPages: Number,
                totalRegs: Number,
                currentPage: 1,
            }
        }

    },
    computed: {
        listaFiltrada: function () {
            var self = this

            self.filtro.listaOrdenada = _.orderBy(self.vendasCadastradas.filter(function (item) {
                var searchRegex = new RegExp(self.filtro.filterTerm, 'i')
                return (
                    searchRegex.test(item.data_cadastro) ||
                    searchRegex.test(item.id) ||
                    searchRegex.test(item.valor_total) ||
                    searchRegex.test(item.cliente.nome_ou_razao_social) ||
                    searchRegex.test(item.cliente.cpf_cnpj) ||
                    searchRegex.test(item.status) ||
                    searchRegex.test(item.data_exclusao)
                )
            }), this.filtro.sortProperty, this.filtro.sortDirection)

            this.paginacao.totalRegs = _.size(this.filtro.listaOrdenada)
            this.paginacao.totalPages = (this.paginacao.totalRegs > this.filtro.regsPorPage) ? _.floor(_.divide(this.paginacao.totalRegs, this.paginacao.regsPorPage)) : 1
            let restoDaDivisao = (this.paginacao.totalRegs % this.paginacao.regsPorPage)

            if (this.paginacao.totalRegs > this.paginacao.regsPorPage) {
                if (restoDaDivisao != 0)
                    this.paginacao.totalPages++
            } else {
                this.paginacao.currentPage = 1
            }

            if (this.paginacao.currentPage > this.paginacao.totalPages)
                this.paginacao.currentPage = 1

            if (this.paginacao.totalRegs > 0) {
                if (this.paginacao.currentPage == 1)
                    this.filtro.listaOrdenada = _.slice(this.filtro.listaOrdenada, 0, (this.paginacao.currentPage * this.paginacao.regsPorPage))
                else
                    this.filtro.listaOrdenada = _.slice(this.filtro.listaOrdenada, ((this.paginacao.currentPage - 1) * this.paginacao.regsPorPage), (this.paginacao.currentPage * this.paginacao.regsPorPage))
            }

            return this.filtro.listaOrdenada

        },
    },
    updated() {
        $('#total_regs_pag').formSelect()
    },
    mounted() {
        this.listaVendasCadastradas()
    },
    methods: {
        //Busca de dados na api
        listaVendasCadastradas() {
            this.$refs.alert.limpaMensagens()

            this.loading = true

            axios.get('api/vendas')
                .then((response) => {
                    this.loading = false;
                    this.vendasCadastradas = response.data;
                }).catch(error => {
                this.loading = false;
                this.$refs.alert.abreMensagens(error.response);
            })
        },

        //Outros métodos da pagina
        adicionaZeros(numero, quantidade){
            return _.padStart(numero, quantidade, '0')
        },

        //Métodos da filtragem e paginação
        mudaPagina: function(page){
            this.paginacao.currentPage = page
        },
        toggleOrder(ev, field) {
            ev.preventDefault()
            if (this.filtro.sortProperty == field) {
                if (this.filtro.sortDirection == 'asc')
                    this.filtro.sortDirection = 'desc'
                else
                    this.filtro.sortDirection = 'asc'
            } else {
                this.iconsColor(this.filtro.sortProperty, false)
                this.iconsColor(field, true)
                this.filtro.sortProperty = field
                this.filtro.sortDirection = 'asc'
            }
        },
        iconsColor(field, value) {
            switch (field) {
                case 'cliente.nome_ou_razao_social':
                    this.filtro.sortPropertyNomeOuRazaoSocial = value
                    break;
                case 'valor_total':
                    this.filtro.sortPropertyValorTotal = value
                    break;
                case 'status':
                    this.filtro.sortPropertyStatus = value
                    break;
                case 'data_cadastro':
                    this.filtro.sortPropertyDataCadastro = value
                    break;
                case 'data_exclusao':
                    this.filtro.sortPropertyDataExclusao = value
                    break;
                default:
            }
        },
    }
}
</script>

<style scoped>

</style>
