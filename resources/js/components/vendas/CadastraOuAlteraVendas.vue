<template>
    <div class="modal-cadastro">
        <div id="modalCadastroVenda" class="modal">
            <v-loading :loading="form.loading" :is-full-page="false"></v-loading>

            <div class="modal-content">
                <h4>{{form.venda.id == 0 || form.venda.id == null ? 'Cadastrar nova venda' : 'Alterar venda'}}</h4>

                <v-alert ref="alert"></v-alert>

                <form novolidate>

                    <div class="row">

                        <p v-if="form.loading && clientesAtivos.length == 0" class="label-carregamento">Buscando os clientes ativos na api...</p>

                        <p v-else-if="clientesAtivos.length == 0"
                           class="label-info-nao-encontrada">Não há clientes ativos cadastrados até o momento!</p>

                        <div v-else class="input-field col s12">
                            <select name="sel_cliente_id" id="sel_cliente_id"
                                    :disabled="form.loading"
                                    v-model="form.venda.cliente_id">
                                <option value="" disabled>Selecione o cliente</option>
                                <option v-for="(cliente, i) in clientesAtivos"
                                        :key="'cliente_i_' + i"
                                        :value="cliente.id"
                                        :title="cliente.nome_ou_razao_social">
                                    <span>{{cliente.nome_ou_razao_social}}</span>
                                </option>
                            </select>
                            <label>Selecione o cliente</label>
                        </div>

                    </div>

                    <div class="row">
                        <div class="input-field col m6 s12">
                            <money id="valor_total_venda"
                                   ref="valor_total_venda"
                                   name="valor_total_venda"
                                   :value="valorTotalDaVenda"
                                   disabled
                                   v-bind="form.mascaraMonetaria"></money>
                            <label for="valor_total_venda">Valor total da venda</label>
                        </div>

                        <div class="input-field col m6 s12">
                            <money id="desconto_total_aplicado"
                                   ref="desconto_total_aplicado"
                                   name="desconto_total_aplicado"
                                   :value="descontoTotalAplicado"
                                   disabled
                                   v-bind="form.mascaraMonetaria"></money>
                            <label for="desconto_total_aplicado">Desconto total aplicado</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <div class="card-panel">
                                <div class="row">
                                    <h5 class="col xl6 l6 m6 s12">Adicione os itens da venda</h5>

                                    <div class="col xl6 l6 m6 s12">
                                        <a class="btn-small btn-floating indigo waves-effect waves-light right"
                                           @click="adicionaItem"><i class="material-icons">add</i></a>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col s12">
                                        <p v-if="form.venda.itens.length == 0" class="label-info-nao-encontrada">Ainda não foram adicionados produtos a esse registro de venda.</p>

                                        <ul v-else class="collapsible">
                                            <li v-for="(item_venda, i) in form.venda.itens">
                                                <div class="collapsible-header">
                                                    <i class="fas fa-shopping-cart small"></i>
                                                    <span v-if="item_venda.produto_id == null" class="label-info-nao-encontrada">Selecione um produto</span>
                                                    <span v-else>{{retornaProdutoPorId(item_venda.produto_id).nome}}</span>
                                                </div>

                                                <div class="collapsible-body">
                                                    <div class="row">

                                                        <p v-if="form.loading && produtosAtivos.length == 0" class="label-carregamento">Buscando os produtos ativos na api...</p>

                                                        <p v-else-if="produtosAtivos.length == 0"
                                                           class="label-info-nao-encontrada">Não há produtos ativos cadastrados até o momento!</p>

                                                        <div v-else class="input-field col s12">
                                                            <select name="sel_produto_id"
                                                                    :id="'sel_produto_id_' + i"
                                                                    :disabled="form.loading"
                                                                    v-model="item_venda.produto_id">
                                                                <option value="" disabled>Selecione o produto</option>
                                                                <option v-for="(produto, j) in produtosAtivos"
                                                                        :key="'item_' + i +'_produto_' + j"
                                                                        :disabled="desabilitaProdutoSelecionado(produto.id)"
                                                                        :value="produto.id"
                                                                        :title="produto.nome">
                                                                    <span>{{produto.nome}}</span>
                                                                </option>
                                                            </select>
                                                            <label>Selecione o produto</label>
                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                        <div class="input-field col xl4 l4 m4 s12">
                                                            <input name="quantidade_itens"
                                                                   :id="'quantidade_itens_' + i"
                                                                   :ref="'quantidade_itens_' + i"
                                                                   type="number"
                                                                   v-model="item_venda.quantidade_itens"
                                                                   :disabled="form.loading" />
                                                            <label :for="'quantidade_itens_' + i">Quantidade de itens</label>
                                                        </div>

                                                        <div class="input-field col xl4 l4 m4 s12">
                                                            <money :id="'desconto_' + i"
                                                                   :ref="'desconto_' + i"
                                                                   name="desconto"
                                                                   :disabled="form.loading"
                                                                   v-bind="form.mascaraMonetaria"
                                                                   v-model.lazy="item_venda.desconto"></money>
                                                            <label :for="'desconto_' + i">Desconto</label>
                                                        </div>

                                                        <div class="input-field col xl4 l4 m4 s12">
                                                            <money :id="'valor_total_' + i"
                                                                   :ref="'valor_total_' + i"
                                                                   name="valor_total"
                                                                   disabled
                                                                   v-bind="form.mascaraMonetaria"
                                                                   :value="item_venda.valor_item"></money>
                                                            <label :for="'valor_total_' + i">Valor total</label>
                                                        </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col s12">
                                                            <a class="btn-small grey darken-1 waves-effect waves-light"
                                                               @click="excluiItem(i)">Excluir</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                <a class="btn red darken-1 waves-effect waves-light" @click="fechaJanela">
                    <i class="material-icons left">cancel</i>
                    CANCELAR
                </a>
                <a class="btn blue darken-4 waves-effect waves-light"
                   @click="salva"
                   :title="clientesAtivos.length == 0 || produtosAtivos.length == 0 ? 'Não é possível cadastrar novas vendas sem clientes ou produtos ativos cadastrados' : 'Salvar'"
                   :disabled="form.loading || clientesAtivos.length == 0 || produtosAtivos.length == 0">
                    <i class="material-icons left">save</i>
                    SALVAR
                </a>
            </div>
        </div>

    </div>
</template>

<script>
import {minValue, required, integer, decimal} from "vuelidate/lib/validators";
import {Money} from 'v-money';

export default {
    name: "CadastraOuAlteraVendas",
    components: {
        Money
    },
    data() {
        return {
            form: {
                mascaraMonetaria: {decimal: ',', thousands: '.', prefix: 'R$ ', suffix: '', precision: 2, masked: false /* doesn't work with directive */},

                venda: {
                    id: null,
                    cliente_id: null,
                    itens: [],
                },

                loading: false,
            },
            clientesAtivos: [],
            produtosAtivos: [],
            atualizaCollapsible: false,
            abreJanelaForm: false,
            janelaForm: null,
        }
    },
    computed: {
        descontoTotalAplicado(){
            let descontoTotal = 0

            if(this.form.venda.itens.length > 0){
                let descontosAplicados = _.uniq(_.map(this.form.venda.itens, 'desconto'))

                descontoTotal = _.sum(descontosAplicados)
            }

            return descontoTotal
        },
        valorTotalDaVenda(){
            let valorTotal = 0

            if(this.form.venda.itens.length > 0){
                let valoresAplicados = _.uniq(_.map(this.form.venda.itens, 'valor_item'))

                valorTotal = _.sum(valoresAplicados)
            }

            return valorTotal
        },
        itensDeVenda(){
            let itens = []

            _.forEach(this.form.venda.itens, (item, i) => {
                itens.push({
                    indice: i,
                    produto_id: item.produto_id,
                    quantidade: item.quantidade_itens,
                    desconto: item.desconto,
                })
            })

            return itens
        },
        quantidadesDeItens(){
            return _.map(this.form.venda.itens, 'quantidade_itens')
        },
        descontosDeItens(){
            return _.map(this.form.venda.itens, 'desconto')
        }
    },
    watch: {
        abreJanelaForm(newValue) {
            if(newValue) {
                this.janelaForm.open()
            }else{
                this.janelaForm.close()
                this.janelaForm.destroy()
            }
        },
        fornecedoresCadastrados(newValue){
            this.fornecedores = newValue
        },

        itensDeVenda: {
            handler: function (newValue){
                if(newValue !== undefined && newValue != null && newValue.length > 0)
                    this.atualizaValorTotalDoItemDeVenda(newValue)
            },
            deep: true
        },
    },
    mounted() {
        this.inicializaJanelaModal()
        this.inicializaDemaisComponentes()
        this.atualizaCollapsible = true
    },
    updated() {
        M.updateTextFields();
        $('select[name="sel_cliente_id"]').formSelect()
        $('select[name="sel_produto_id"]').formSelect()

        if(this.atualizaCollapsible)
            $('.collapsible').collapsible()
    },
    methods: {
        //inicializaTela
        inicializaJanelaModal(){
            const elemento = document.getElementById('modalCadastroVenda')
            //const instancia = M.Modal.init(elemento, {dismissible: false})
            const instancia = M.Modal.init(elemento, {
                dismissible: false,
                onCloseEnd: () => {
                    this.eventoDeFechamentoDeJanela()
                },
                onOpenEnd: () => {
                    $(elemento).css('transform', '')
                },
            })
            this.janelaForm = instancia
        },
        inicializaDemaisComponentes(){
            this.listaClientesAtivosCadastrados()
            this.listaProdutosAtivosCadastrados()
        },

        //Busca de dados na api
        listaClientesAtivosCadastrados() {
            this.$refs.alert.limpaMensagens()

            this.loading = true

            axios.get('api/clientes/lista/ativos')
                .then((response) => {
                    this.loading = false;
                    this.clientesAtivos = response.data;
                }).catch(error => {
                this.loading = false;
                this.$refs.alert.abreMensagens(error.response);
            })
        },
        listaProdutosAtivosCadastrados() {
            this.$refs.alert.limpaMensagens()

            this.loading = true

            axios.get('api/produtos/lista/ativos')
                .then((response) => {
                    this.loading = false;
                    this.produtosAtivos = response.data;
                }).catch(error => {
                this.loading = false;
                this.$refs.alert.abreMensagens(error.response);
            })
        },

        sobeParaTopoDaJanela(){
            $('#modalCadastroVenda').scrollTop(0)
        },
        desceParaFimDaJanela(){
            $('#modalCadastroVenda').scrollTop($('#modalCadastroVenda').height)
        },

        abreJanela() {
            this.abreJanelaForm = true
        },

        fechaJanela() {
            this.limpaCamposFormulario()
            this.limpaMensagensDeErro()
            this.abreJanelaForm = false
        },

        eventoDeFechamentoDeJanela() {
            this.abreJanelaForm = false
        },

        limpaCamposFormulario() {
            this.form.venda.id = null
            this.form.venda.cliente_id = null
            this.form.venda.itens = []
        },

        limpaMensagensDeErro() {
            //this.$v.$reset()
            this.$refs.alert.limpaMensagens()
        },

        cadastraNovaVenda() {
            this.limpaCamposFormulario()
            this.limpaMensagensDeErro()
            this.abreJanela()
        },

        adicionaItem(){
            this.form.venda.itens.push({
                valor_item: 0,
                desconto: 0,
                quantidade_itens: 1,
                produto_id: null,
            })

            this.atualizaCollapsible = true
        },
        excluiItem(indice){
            if(confirm('Tem certeza que deseja excluir esse item da venda?')){
                this.form.venda.itens.splice(indice, 1)
                this.atualizaCollapsible = true
            }
        },
        retornaProdutoPorId(produto_id){
            return _.find(this.produtosAtivos, (produto) => {
                return produto.id == produto_id
            })
        },

        // Eventos de validação do formulário
        valida() {
            if(this.form.venda.cliente_id == null){
                Swal.fire('Oops!', 'Não é possível efetuar a venda pois o cliente não foi selecionado.', 'error')
                return false
            }

            if(this.form.venda.itens.length == 0){
                Swal.fire('Oops!', 'Não é possível efetuar uma venda sem itens de compra.', 'error')
                return false
            }

            if(_.filter(this.form.venda.itens, (item) => {return item.produto_id == null}).length > 0){
                Swal.fire('Oops!', 'Não é possível efetuar uma venda quando existem itens que ainda não tiveram seu produto escolhido.', 'error')
                return false
            }

            return true
        },
        // Eventos de validação do formulário

        salva() {
            if (this.valida()) {
                this.limpaMensagensDeErro()
                this.form.loading = true

                axios.post('api/vendas', this.form.venda)
                    .then(response => {
                        this.form.loading = false
                        Swal.fire('Ótimo!', 'Venda cadastrada com sucesso.', 'success')
                        this.concluiSalvamentoDosDados()
                    })
                    .catch(error => {
                        this.form.loading = false
                        Swal.fire('Oops!','Ocorreu um erro ao tentar cadastrar essa venda, suba para ver mais detalhes.','error')
                        this.$refs.alert.abreMensagens(error.response)
                        this.sobeParaTopoDaJanela()
                    })
            }
        },

        concluiSalvamentoDosDados() {
            this.$emit('emissaoDeDadosSalvos')
            this.fechaJanela()
        },

        desabilitaProdutoSelecionado(produtoId){
            let produtosSelecionadosId = _.uniq(_.map(this.form.venda.itens, 'produto_id'))

            return produtoId !== undefined && produtoId != null && _.includes(produtosSelecionadosId, produtoId)
        },
        atualizaValorTotalDoItemDeVenda(itensDeVenda){
            _.forEach(itensDeVenda, (item) => {
                if(item.produto_id !== undefined && item.produto_id != null){
                    let produto = this.retornaProdutoPorId(item.produto_id)

                    let itemVenda = this.form.venda.itens[item.indice]

                    if(item.quantidade > 0)
                        itemVenda.valor_item = produto.valor_produto * item.quantidade
                    else{
                        Swal.fire('Oops!', 'Não é permitido colocar menos que 1 item.', 'error')
                        itemVenda.quantidade_itens = 1
                    }

                    if(item.desconto >= 0)
                        itemVenda.valor_item = itemVenda.valor_item - item.desconto
                    else {
                        Swal.fire('Oops!', 'Não é permitido aplicar descontos negativos.', 'error')
                        itemVenda.desconto = 0
                    }
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
