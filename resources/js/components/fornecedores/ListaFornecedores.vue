<template>
    <div>
        <v-loading :loading="loading" :is-full-page="true"></v-loading>

        <div class="row">
            <div class="col s12">
                <nav class="otimize">
                    <div class="nav-wrapper">
                        <a href="/" class="breadcrumb" title="Pagina inicial"><i class="material-icons">home</i></a>
                        <a href="/" class="breadcrumb">Página inicial</a>
                        <a href="!#" class="breadcrumb">Fornecedores</a>
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
                        <h4 class="col xl6 l6 m6 s12">Lista de fornecedores cadastrados</h4>

                        <div class="col xl6 l6 m6 s12">
                            <a class="btn-floating indigo darken-4 waves-effect waves-light right"
                               @click="cadastraNovoFornecedor">
                                <i class="material-icons">add</i>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <p v-if="fornecedoresCadastrados.length == 0 && loading"
                               class="label-carregamento">Buscando os fornecedores cadastrados...</p>

                            <p v-else-if="fornecedoresCadastrados.length == 0"
                               class="label-info-nao-encontrada">Não há fornecedores cadastrados até o momento.</p>

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
                                                    <span>Nome/Razão Social</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'nome_ou_razao_social')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyNomeOuRazaoSocial }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>CPF/CNPJ</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'cpf_cnpj')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyCpfOuCnpj }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Data de Nascimento/Abertura</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'data_nascimento')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyDataNascimento }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>Telefone</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'telefone')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyTelefone }">sort</i>
                                                    </a>
                                                </th>

                                                <th>
                                                    <span>E-mail</span>
                                                    <a class="left" href="#" @click="toggleOrder($event, 'email')">
                                                        <i class="material-icons" v-bind:class="{ 'red-text': filtro.sortPropertyEmail }">sort</i>
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
                                            <tr v-for="(fornecedor, i) in listaFiltrada">
                                                <td>
                                                    <span>{{fornecedor.nome_ou_razao_social}}</span>
                                                </td>

                                                <td>
                                                    <span>{{fornecedor.cpf_cnpj}}</span>
                                                </td>

                                                <td>
                                                    <span>{{fornecedor.data_nascimento}}</span>
                                                </td>

                                                <td>
                                                    <span>{{fornecedor.telefone}}</span>
                                                </td>

                                                <td>
                                                    <span>{{fornecedor.email}}</span>
                                                </td>

                                                <td><span class="new badge" :class="{'green' : fornecedor.status == 'Ativo', 'red' : fornecedor.status == 'Excluído'}" data-badge-caption="">{{fornecedor.status}}</span></td>

                                                <td>
                                                    <span>{{fornecedor.data_cadastro}}</span>
                                                </td>

                                                <td>
                                                    <span>{{fornecedor.data_exclusao == null ? 'N/a' : fornecedor.data_exclusao}}</span>
                                                </td>

                                                <td>
                                                    <a style="cursor: pointer;"
                                                       data-toggle="tooltip"
                                                       data-placement="top"
                                                       title="Alterar"
                                                       @click="editaFornecedor(fornecedor)">
                                                        <i class="small material-icons grey-text text-darken-3">edit</i>
                                                    </a>
                                                    &nbsp;
                                                    <a v-if="fornecedor.status == 'Ativo'"
                                                       style="cursor: pointer;"
                                                       data-toggle="tooltip"
                                                       data-placement="top"
                                                       title="Excluir"
                                                       @click="deletaFornecedor(fornecedor)">
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

        <vue-cadastra-altera-fornecedores ref="cadastra_altera_fornecedor"
                                      @emissaoDeDadosSalvos="listaFornecedoresCadastrados"></vue-cadastra-altera-fornecedores>
    </div>
</template>

<script>
export default {
    name: "ListaFornecedores",
    data(){
        return {
            fornecedoresCadastrados: [],
            loading: false,

            filtro: {
                sortProperty: 'nome_ou_razao_social',
                sortDirection: 'asc',
                filterTerm: '',
                sortPropertyNomeOuRazaoSocial: true,
                sortPropertyCpfOuCnpj: false,
                sortPropertyDataNascimento: false,
                sortPropertyEmail: false,
                sortPropertyTelefone: false,
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

            self.filtro.listaOrdenada = _.orderBy(self.fornecedoresCadastrados.filter(function (item) {
                var searchRegex = new RegExp(self.filtro.filterTerm, 'i')
                return (
                    searchRegex.test(item.nome_ou_razao_social) ||
                    searchRegex.test(item.cpf_cnpj) ||
                    searchRegex.test(item.data_nascimento) ||
                    searchRegex.test(item.email) ||
                    searchRegex.test(item.telefone) ||
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
        this.listaFornecedoresCadastrados()
    },
    methods: {
        //Busca de dados na api
        listaFornecedoresCadastrados() {
            this.$refs.alert.limpaMensagens()

            this.loading = true

            axios.get('api/fornecedores')
                .then((response) => {
                    this.loading = false;
                    this.fornecedoresCadastrados = response.data;
                }).catch(error => {
                this.loading = false;
                this.$refs.alert.abreMensagens(error.response);
            })
        },

        //Demais métodos da pagina
        cadastraNovoFornecedor(){
            this.$refs.cadastra_altera_fornecedor.cadastraNovoFornecedor()
        },
        editaFornecedor(fornecedor){
            this.$refs.cadastra_altera_fornecedor.editaFornecedor(fornecedor)
        },
        deletaFornecedor(fornecedor){
            if(confirm('Tem certeza que deseja excluir o fornecedor ' + fornecedor.nome_ou_razao_social + '?')){
                this.$refs.alert.limpaMensagens()
                this.loading = true

                axios.delete('api/fornecedores/' + fornecedor.id).then(response => {
                    this.loading = false
                    this.listaFornecedoresCadastrados()
                    Swal.fire('Ótimo', 'Fornecedor excluído com sucesso.', 'success')
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
                case 'nome_ou_razao_social':
                    this.filtro.sortPropertyNomeOuRazaoSocial = value
                    break;
                case 'cpf_cnpj':
                    this.filtro.sortPropertyCpfOuCnpj = value
                    break;
                case 'data_nascimento':
                    this.filtro.sortPropertyDataNascimento = value
                    break;
                case 'email':
                    this.filtro.sortPropertyEmail = value
                    break;
                case 'telefone':
                    this.filtro.sortPropertyTelefone = value
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
