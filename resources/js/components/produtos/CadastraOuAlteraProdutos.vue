<template>
    <div class="modal-cadastro">
        <div id="modalCadastroProduto" class="modal">
            <v-loading :loading="form.loading" :is-full-page="false"></v-loading>

            <div class="modal-content">
                <h4>{{form.produto.id == 0 || form.produto.id == null ? 'Cadastrar novo produto' : 'Alterar produto'}}</h4>

                <v-alert ref="alert"></v-alert>

                <form novolidate>

                    <div class="row">
                        <p v-if="fornecedores.length == 0"
                           class="label-info-nao-encontrada">Não há fornecedores cadastrados!</p>
                        <div v-else class="input-field col s12">
                            <select name="sel_fornecedor_id" id="sel_fornecedor_id"
                                    @change="resetValidationProp('fornecedor_id', true)"
                                    :disabled="form.loading"
                                    v-model="form.produto.fornecedor_id">
                                <option value="" disabled>Selecione o fornecedor do produto</option>
                                <option v-for="(fornecedor, i) in fornecedores"
                                        :key="'fornecedor_i_' + i"
                                        :value="fornecedor.id"
                                        :disabled="fornecedor.status == 'Excluído'"
                                        :title="fornecedor.nome_ou_razao_social">
                                    <span :class="{'fornecedor-excluido' : fornecedor.status == 'Excluído'}">{{fornecedor.nome_ou_razao_social}}</span>
                                    <span v-if="fornecedor.status == 'Excluído'" class="new badge red" data-badge-caption="">Excluído</span>
                                </option>
                            </select>
                            <label>Selecione o fornecedor do produto.</label>
                        </div>
                    </div>

                    <div class="row">

                        <div class="input-field col xl6 l6 m6 s12">
                            <input name="nome"
                                   id="nome"
                                   type="text"
                                   :class="getValidationClass('nome')"
                                   @change="resetValidationProp('nome')"
                                   maxlength="128"
                                   v-model="form.produto.nome"
                                   :disabled="form.loading"
                                   oninput="this.value = this.value.toUpperCase()"/>
                            <label for="nome">Nome do produto</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.produto.nome.required"></span>
                            <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.produto.nome.minlength"></span>
                            <span class="helper-text" data-error="Máximo de 128 caracteres!" v-else-if="!$v.form.produto.nome.maxlength"></span>
                        </div>

                        <div class="input-field col xl6 l6 m6 s12">
                            <input name="categoria"
                                   id="categoria"
                                   type="text"
                                   :class="getValidationClass('categoria')"
                                   @change="resetValidationProp('categoria')"
                                   v-model="form.produto.categoria"
                                   :disabled="form.loading"
                                   oninput="this.value = this.value.toUpperCase()" />
                            <label for="categoria">Categoria</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.produto.categoria.required"></span>
                            <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.produto.categoria.minlength"></span>
                            <span class="helper-text" data-error="Máximo de 128 caracteres!" v-else-if="!$v.form.produto.categoria.maxlength"></span>
                        </div>

                    </div>

                    <div class="row">

                        <div class="input-field col xl6 l6 m6 s12">
                            <input name="quantidade_itens"
                                   id="quantidade_itens"
                                   type="number"
                                   :class="getValidationClass('quantidade_itens')"
                                   @change="resetValidationProp('quantidade_itens')"
                                   v-model="form.produto.quantidade_itens"
                                   :disabled="form.loading" />
                            <label for="quantidade_itens">Quantidade de itens</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.produto.quantidade_itens.required"></span>
                            <span class="helper-text" data-error="A quantidade de itens deve ser um número inteiro positivo!" v-else-if="!$v.form.produto.quantidade_itens.integer"></span>
                            <span class="helper-text" data-error="Mínimo de 1 item por produto!" v-else-if="!$v.form.produto.quantidade_itens.minValue"></span>
                        </div>

                        <div class="input-field col xl6 l6 m6 s12">
                            <money id="valor_produto" ref="valor_produto" name="valor_produto"
                                   :class="getValidationClass('valor_produto')"
                                   @change="resetValidationProp('valor_produto')"
                                   :disabled="form.loading"
                                   v-bind="form.mascaraMonetaria"
                                   v-model.lazy="form.produto.valor_produto"></money>
                            <label for="valor_produto">Valor (unidade)</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.produto.valor_produto.required"></span>
                            <span class="helper-text" data-error="O valor do produto deve ser um número decimal positivo!" v-else-if="!$v.form.produto.valor_produto.decimal"></span>
                            <span class="helper-text" data-error="Mínimo de 1 centavo!" v-else-if="!$v.form.produto.valor_produto.minValue"></span>
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
                   :title="fornecedoresCadastrados.length == 0 ? 'Não é possível salvar produtos sem fornecedores cadastrados' : 'Salvar'"
                   :disabled="form.loading || fornecedoresCadastrados.length == 0">
                    <i class="material-icons left">save</i>
                    SALVAR
                </a>
            </div>
        </div>

    </div>
</template>

<script>
import {maxLength, minLength, minValue, required, integer, decimal} from "vuelidate/lib/validators";
import {Money} from 'v-money';

export default {
    name: "CadastraOuAlteraProdutos",
    props: {
        fornecedoresCadastrados: {
            type: Array,
            default: [],
        }
    },
    components: {
        Money
    },
    data() {
        return {
            form: {
                mascaraMonetaria: {decimal: ',', thousands: '.', prefix: 'R$ ', suffix: '', precision: 2, masked: false /* doesn't work with directive */},

                produto: {
                    id: null,
                    nome: null,
                    categoria: null,
                    valor_produto: 0.00,
                    quantidade_itens: null,
                    fornecedor_id: null
                },

                loading: false,
            },
            fornecedores: this.fornecedoresCadastrados,
            abreJanelaForm: false,
            janelaForm: null,
        }
    },
    mounted() {
        const elemento = document.getElementById('modalCadastroProduto')
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
    updated() {
        M.updateTextFields();
        $('#sel_fornecedor_id').formSelect()
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
        }
    },
    validations: {
        form: {
            produto: {
                nome: {
                    required,
                    minLength: minLength(2),
                    maxLength: maxLength(128)
                },
                categoria: {
                    required,
                    minLength: minLength(2),
                    maxLength: maxLength(128)
                },
                quantidade_itens: {
                    required,
                    integer,
                    minValue: minValue(1)
                },
                valor_produto: {
                    required,
                    decimal,
                    minValue: minValue(0.01)
                },
            }
        }
    },
    methods: {
        sobeParaTopoDaJanela(){
            $('#modalCadastroProduto').scrollTop(0)
        },
        desceParaFimDaJanela(){
            $('#modalCadastroProduto').scrollTop($('#modalCadastroProduto').height)
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
            this.form.produto.id = null
            this.form.produto.nome = null
            this.form.produto.categoria = null
            this.form.produto.valor_produto = 0.00
            this.form.produto.quantidade_itens = null
            this.form.produto.fornecedor_id = null
        },

        preencheDadosFormulario(produto){
            this.form.produto.id = produto.id
            this.form.produto.nome = produto.nome
            this.form.produto.categoria = produto.categoria
            this.form.produto.valor_produto = produto.valor_produto
            this.form.produto.quantidade_itens = produto.quantidade_itens
            this.form.produto.fornecedor_id = produto.fornecedor_id
        },

        limpaMensagensDeErro() {
            this.$v.$reset()
            this.$refs.alert.limpaMensagens()
        },

        cadastraNovoProduto() {
            this.limpaCamposFormulario()
            this.limpaMensagensDeErro()
            this.abreJanela()
        },

        editaProduto(produtoParaEdicao) {
            this.limpaCamposFormulario()
            this.limpaMensagensDeErro()
            this.preencheDadosFormulario(produtoParaEdicao)
            this.abreJanela()
        },

        // Eventos de validação do formulário
        resetValidationProp(fieldName, isSelect = false){
            if(isSelect){
                if (this.form.produto.fornecedor_id != null && this.form.produto.fornecedor_id !== 0)
                    $('#sel_' + fieldName).parents('div.select-wrapper').removeClass('invalid')
            }else{
                let fieldSplit = fieldName.split('.')
                let fieldOrigin = this.$v.form['produto']
                let fieldFiltered = null
                let contador = 0

                _.forEach(fieldSplit, (fieldItem) => {
                    if(contador == 0)
                        fieldFiltered = fieldOrigin[fieldItem]
                    else
                        fieldFiltered = fieldFiltered[fieldItem]

                    contador++
                })
                //let field = null

                const field = fieldFiltered

                field.$reset()
            }
        },
        getValidationClass (fieldName) {
            let fieldSplit = fieldName.split('.')
            let fieldOrigin = this.$v.form['produto']
            let fieldFiltered = null
            let contador = 0

            _.forEach(fieldSplit, (fieldItem) => {
                if(contador == 0)
                    fieldFiltered = fieldOrigin[fieldItem]
                else
                    fieldFiltered = fieldFiltered[fieldItem]

                contador++
            })
            //let field = null

            const field = fieldFiltered

            if (field) {
                return {
                    'invalid': field.$invalid && field.$dirty
                }
            }
        },
        valida() {
            let produto_valido = this.form.produto.fornecedor_id != null && this.form.produto.fornecedor_id !== 0
            if(!produto_valido)
                $('#sel_fornecedor_id').parents('div.select-wrapper').addClass('invalid')

            this.$v.$touch()

            return (!this.$v.$invalid && produto_valido)
        },
        // Eventos de validação do formulário

        salva() {
            if (this.valida()) {
                this.limpaMensagensDeErro()
                this.form.loading = true

                if (this.form.produto.id == null || this.form.produto.id === 0) {
                    axios.post('api/produtos', this.form.produto)
                        .then(response => {
                            this.form.loading = false
                            Swal.fire('Ótimo!', 'Produto cadastrado com sucesso.', 'success')
                            this.concluiSalvamentoDosDados()
                        })
                        .catch(error => {
                            this.form.loading = false
                            Swal.fire('Oops!','Ocorreu um erro ao tentar cadastrar esse produto, feche para ver mais detalhes.','error')
                            this.$refs.alert.abreMensagens(error.response)
                            this.sobeParaTopoDaJanela()
                        })
                } else {
                    axios.put('api/produtos/' + this.form.produto.id, this.form.produto)
                        .then(response => {
                            this.form.loading = false
                            Swal.fire('Ótimo!','Produto alterado com sucesso','success')
                            this.concluiSalvamentoDosDados()
                        })
                        .catch(error => {
                            this.form.loading = false
                            Swal.fire('Oops!','Ocorreu um erro ao tentar alterar esse produto, feche para ver mais detalhes.','error')
                            this.$refs.alert.abreMensagens(error.response)
                            this.sobeParaTopoDaJanela()
                        })
                }
            }
        },

        concluiSalvamentoDosDados() {
            this.$emit('emissaoDeDadosSalvos')
            this.fechaJanela()
        }
    }
}
</script>

<style lang="scss">
.fornecedor-excluido{
    font-style: italic;
    text-decoration: line-through red;
}

</style>
