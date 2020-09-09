<template>
    <div>
        <v-loading :loading="loading" :is-full-page="true"></v-loading>

        <div class="row">
            <div class="col s12">
                <nav class="otimize">
                    <div class="nav-wrapper">
                        <a href="/" class="breadcrumb" title="Pagina inicial"><i class="material-icons">home</i></a>
                        <a href="/" class="breadcrumb">Página inicial</a>
                        <a href="!#" class="breadcrumb">Produtos</a>
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
                        <h4 class="col xl6 l6 m6 s12">Lista de produtos cadastrados</h4>

                        <div class="col xl6 l6 m6 s12">
                            <a class="btn-floating indigo light-blue waves-effect waves-light right"
                               @click="cadastraNovoProduto"
                               :title="fornecedoresCadastrados.length == 0 ? 'Não há fornecedores cadastrados para poder adicionar produtos.' : 'Adicionar produto'"
                               :disabled="fornecedoresCadastrados.length == 0">
                                <i class="material-icons">add</i>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <p v-if="produtosCadastrados.length == 0 && loading"
                               class="label-carregamento">Buscando os produtos cadastrados...</p>

                            <p v-else-if="produtosCadastrados.length == 0"
                               class="label-info-nao-encontrada">Não há produtos cadastrados até o momento.</p>

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
                                                    <span>Id</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'id')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyId }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Produto</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'nome')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyProduto }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Categoria</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'categoria')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyCategoria }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Fornecedor</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'fornecedor.nome_ou_razao_social')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyFornecedor }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Quantidade</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'quantidade_itens')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyQuantidade }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Valor do Produto</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'valor_produto')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyValor }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Status</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'status')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyStatus }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Data de Cadastro</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'data_cadastro')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyDataCadastro }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Data de Exclusão</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'data_exclusao')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyDataExclusao }">sort</i>
                                                    </a>
                                                </th>

                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(produto, i) in listaFiltrada">
                                                <td>
                                                    <span>{{produto.id}}</span>
                                                </td>

                                                <td>
                                                    <span>{{produto.nome}}</span>
                                                </td>

                                                <td>
                                                    <span>{{produto.categoria}}</span>
                                                </td>

                                                <td>
                                                    <span style="font-weight: bold">{{produto.fornecedor.nome_ou_razao_social}}</span>
                                                    <p><span>(CPF/CNPJ: {{produto.fornecedor.cpf_cnpj}})</span></p>
                                                </td>

                                                <td>
                                                    <span>{{produto.quantidade_itens}}</span>
                                                </td>

                                                <td>
                                                    <span>{{produto.valor_produto_formatado}}</span>
                                                </td>

                                                <td><span class="new badge" :class="{'green' : produto.status == 'Ativo', 'red' : produto.status == 'Excluído'}" data-badge-caption="">{{produto.status}}</span></td>

                                                <td>
                                                    <span>{{produto.data_cadastro}}</span>
                                                </td>

                                                <td>
                                                    <span>{{produto.data_exclusao == null ? 'N/a' : produto.data_exclusao}}</span>
                                                </td>

                                                <td>
                                                    <a style="cursor: pointer;"
                                                       data-toggle="tooltip"
                                                       data-placement="top"
                                                       title="Alterar"
                                                       @click="editaProduto(produto)">
                                                        <i class="small material-icons grey-text text-darken-3">edit</i>
                                                    </a>
                                                    &nbsp;
                                                    <a v-if="produto.status == 'Ativo'"
                                                       style="cursor: pointer;"
                                                       data-toggle="tooltip"
                                                       data-placement="top"
                                                       title="Excluir"
                                                       @click="deletaProduto(produto)">
                                                        <i class="small material-icons red-text">delete</i>
                                                    </a>
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

        <vue-cadastra-altera-produtos ref="cadastra_altera_produto"
                                      :fornecedores-cadastrados="fornecedoresCadastrados"
                                      @emissaoDeDadosSalvos="listaProdutosCadastrados"></vue-cadastra-altera-produtos>

    </div>
</template>

<script>
export default {
    name: "ListaProdutos",
    data(){
        return {
            produtosCadastrados: [],
            fornecedoresCadastrados: [],
            loading: false,

            filtro: {
                sortProperty: 'nome_ou_razao_social',
                sortDirection: 'asc',
                filterTerm: '',
                sortPropertyId: true,
                sortPropertyProduto: false,
                sortPropertyCategoria: false,
                sortPropertyFornecedor: false,
                sortPropertyQuantidade: false,
                sortPropertyValor: false,
                sortPropertyStatus: false,
                sortPropertyDataCadastro: false,
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

            self.filtro.listaOrdenada = _.orderBy(self.produtosCadastrados.filter(function (item) {
                var searchRegex = new RegExp(self.filtro.filterTerm, 'i')
                return (
                    searchRegex.test(item.id) ||
                    searchRegex.test(item.nome) ||
                    searchRegex.test(item.categoria) ||
                    searchRegex.test(item.fornecedor.nome_ou_razao_social) ||
                    searchRegex.test(item.fornecedor.cpf_cnpj) ||
                    searchRegex.test(item.quantidade_itens) ||
                    searchRegex.test(item.valor_produto_formatado) ||
                    searchRegex.test(item.status) ||
                    searchRegex.test(item.data_cadastro) ||
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
        this.listaProdutosCadastrados()
        this.listaFornecedoresCadastrados()
    },
    methods: {
        //Busca de dados na api
        listaProdutosCadastrados() {
            this.$refs.alert.limpaMensagens()

            this.loading = true

            axios.get('api/produtos')
                .then((response) => {
                    this.loading = false;
                    this.produtosCadastrados = response.data;
                }).catch(error => {
                this.loading = false;
                this.$refs.alert.abreMensagens(error.response);
            })
        },
        listaFornecedoresCadastrados() {
            this.$refs.alert.limpaMensagens()

            //this.loading = true

            axios.get('api/fornecedores')
                .then((response) => {
                    //this.loading = false;
                    this.fornecedoresCadastrados = response.data;
                }).catch(error => {
                //this.loading = false;
                this.$refs.alert.abreMensagens(error.response);
            })
        },

        //Demais métodos da pagina
        cadastraNovoProduto(){
            this.$refs.cadastra_altera_produto.cadastraNovoProduto()
        },
        editaProduto(produto){
            this.$refs.cadastra_altera_produto.editaProduto(produto)
        },
        deletaProduto(produto){
            if(confirm('Tem certeza que deseja excluir o produto ' + produto.nome + '?')){
                this.$refs.alert.limpaMensagens()
                this.loading = true

                axios.delete('api/produtos/' + produto.id).then(response => {
                    this.loading = false
                    this.listaFornecedoresCadastrados()
                    Swal.fire('Ótimo', 'Produto excluído com sucesso.', 'success')
                }).catch(error => {
                    this.loading = false
                    this.$refs.alert.abreMensagens(error.response)
                    Swal.fire('Oops!.', error.response.data.message, 'error')
                })
            }
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
                case 'id':
                    this.filtro.sortPropertyId = value
                    break;
                case 'nome':
                    this.filtro.sortPropertyProduto = value
                    break;
                case 'categoria':
                    this.filtro.sortPropertyCategoria = value
                    break;
                case 'fornecedor.nome_ou_razao_social':
                    this.filtro.sortPropertyFornecedor = value
                    break;
                case 'quantidade_itens':
                    this.filtro.sortPropertyQuantidade = value
                    break;
                case 'valor_produto':
                    this.filtro.sortPropertyValor = value
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
