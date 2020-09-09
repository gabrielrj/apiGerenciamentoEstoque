<template>
    <div class="modal-cadastro">
        <div id="modalCadastroCliente" class="modal">
            <v-loading :loading="form.loading" :is-full-page="false"></v-loading>

            <div class="modal-content">
                <h4>{{form.cliente.id == 0 || form.cliente.id == null ? 'Cadastrar novo cliente' : 'Alterar cliente'}}</h4>

                <v-alert ref="alert"></v-alert>

                <form novolidate>

                    <div class="row">
                        <div class="input-field col xl6 l6 m6 s12">
                            <i class="far fa-id-card prefix"></i>
                            <the-mask name="cpf_cnpj"
                                      ref="cpf_cnpj"
                                      id="cpf_cnpj"
                                      :class="getValidationClass('cpf_cnpj')"
                                      @change.native="resetValidationProp('cpf_cnpj')"
                                      v-model="form.cliente.cpf_cnpj"
                                      :disabled="form.loading"
                                      :masked="false"
                                      :mask="['###.###.###-##', '##.###.###/####-##']"/>
                            <label for="cpf_cnpj">CPF/CNPJ</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.cliente.cpf_cnpj.required"></span>
                            <span class="helper-text" data-error="Mínimo de 14 caracteres!" v-else-if="!$v.form.cliente.cpf_cnpj.minlength"></span>
                            <span class="helper-text" data-error="Máximo de 18 caracteres!" v-else-if="!$v.form.cliente.cpf_cnpj.maxlength"></span>
                        </div>

                        <div class="input-field col xl6 l6 m6 s12">
                            <i class="material-icons prefix">account_circle</i>
                            <input name="nome_ou_razao_social"
                                   id="nome_ou_razao_social"
                                   type="text"
                                   :class="getValidationClass('nome_ou_razao_social')"
                                   @change="resetValidationProp('nome_ou_razao_social')"
                                   maxlength="128"
                                   v-model="form.cliente.nome_ou_razao_social"
                                   :disabled="form.loading"
                                   oninput="this.value = this.value.toUpperCase()"/>
                            <label for="nome_ou_razao_social">Nome/Razão Social</label>
                            <span class="helper-text" data-error="Campo obrigatório!" v-if="!$v.form.cliente.nome_ou_razao_social.required"></span>
                            <span class="helper-text" data-error="Mínimo de 2 caracteres!" v-else-if="!$v.form.cliente.nome_ou_razao_social.minlength"></span>
                            <span class="helper-text" data-error="Máximo de 128 caracteres!" v-else-if="!$v.form.cliente.nome_ou_razao_social.maxlength"></span>
                        </div>

                    </div>

                    <div class="row">

                        <div class="input-field col xl6 l6 m6 s12">
                            <i class="material-icons prefix">phone</i>
                            <the-mask name="telefone"
                                      id="telefone"
                                      ref="telefone"
                                      v-model="form.cliente.telefone"
                                      :disabled="form.loading"
                                      maxlength="15"
                                      placeholder="Opcional"
                                      :masked="false"
                                      :mask="['(##) ####-####', '(##) #####-####']">
                            </the-mask>
                            <label for="telefone">Telefone</label>
                        </div>

                        <div class="input-field col xl6 l6 m6 s12">
                            <i class="material-icons prefix">date_range</i>
                            <input type="text" class="datepicker"
                                   id="data_nascimento"
                                   name="data_nascimento"
                                   tipo="periodo_antigo"
                                   placeholder="Data"
                                   :disabled="form.loading"
                                   v-model.lazy="form.cliente.data_nascimento">
                            <label for="data_nascimento">Data de Nascimento</label>
                        </div>

                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <!--E-mail-->
                            <i class="material-icons prefix">email</i>
                            <input name="email"
                                   id="email"
                                   type="email"
                                   :class="getValidationClass('email')"
                                   @change="resetValidationProp('email')"
                                   maxlength="256"
                                   v-model="form.cliente.email"
                                   :disabled="form.loading"
                                   placeholder="Opcional"/>
                            <label for="email">E-mail</label>
                            <span class="helper-text" data-error="Máximo de 256 caracteres!" v-if="!$v.form.cliente.email.maxlength"></span>
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
                   :disabled="form.loading">
                    <i class="material-icons left">save</i>
                    SALVAR
                </a>
            </div>
        </div>

    </div>
</template>

<script>
import {maxLength, minLength, email, required} from "vuelidate/lib/validators";

export default {
    name: "CadastraOuAlteraClientes",
    data() {
        return {
            form: {
                cliente: {
                    id: null,
                    nome_ou_razao_social: null,
                    cpf_cnpj: null,
                    data_nascimento: null,
                    email: null,
                    telefone: null,
                },

                loading: false,
            },
            abreJanelaForm: false,
            janelaForm: null,
        }
    },
    mounted() {
        const elemento = document.getElementById('modalCadastroCliente')
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
    },
    watch: {
        abreJanelaForm(newValue) {
            if(newValue) {
                this.janelaForm.open()
            }else{
                this.janelaForm.close()
                this.janelaForm.destroy()
            }
        }
    },
    validations: {
        form: {
            cliente: {
                nome_ou_razao_social: {
                    required,
                    minLength: minLength(2),
                    maxLength: maxLength(128)
                },
                cpf_cnpj: {
                    required,
                    minLength: minLength(11),
                    maxLength: maxLength(14)
                },
                email: {
                    email,
                    maxLength: maxLength(256)
                },
                telefone: {
                    maxLength: maxLength(15)
                },
            }
        }
    },
    methods: {
        sobeParaTopoDaJanela(){
            $('#modalCadastroCliente').scrollTop(0)
        },
        desceParaFimDaJanela(){
            $('#modalCadastroCliente').scrollTop($('#modalCadastroCliente').height)
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
            this.$refs.cpf_cnpj.lastValue = null
            this.$refs.cpf_cnpj.display = null
            this.$refs.telefone.lastValue = null
            this.$refs.telefone.display = null
            this.form.cliente.id = null
            this.form.cliente.nome_ou_razao_social = null
            this.form.cliente.cpf_cnpj = null
            this.form.cliente.data_nascimento = null
            this.form.cliente.email = null
            this.form.cliente.telefone = null
            this.form.loading = false
        },

        preencheDadosFormulario(cliente){
            this.form.cliente.id = cliente.id
            this.form.cliente.nome_ou_razao_social = cliente.nome_ou_razao_social
            this.form.cliente.cpf_cnpj = cliente.cpf_cnpj
            this.form.cliente.data_nascimento = cliente.data_nascimento
            this.form.cliente.email = cliente.email
            this.form.cliente.telefone = cliente.telefone
        },

        limpaMensagensDeErro() {
            this.$v.$reset()
            this.$refs.alert.limpaMensagens()
        },

        cadastraNovoCliente() {
            this.limpaCamposFormulario()
            this.limpaMensagensDeErro()
            this.abreJanela()
        },

        editaCliente(clienteParaEdicao) {
            this.limpaCamposFormulario()
            this.limpaMensagensDeErro()
            this.preencheDadosFormulario(clienteParaEdicao)
            this.abreJanela()
        },

        // Eventos de validação do formulário
        resetValidationProp(fieldName) {
            const field = this.$v.form['cliente'][fieldName]

            field.$reset()

        },
        getValidationClass(fieldName) {
            const field = this.$v.form['cliente'][fieldName]

            if (field) {

                return {
                    'invalid': field.$invalid && field.$dirty
                }
            }
        },
        valida() {
            this.$v.$touch()

            return !this.$v.$invalid
        },
        // Eventos de validação do formulário

        salva() {
            if (this.valida()) {
                this.limpaMensagensDeErro()
                this.form.loading = true

                if (this.form.cliente.id == null || this.form.cliente.id === 0) {
                    axios.post('api/clientes', this.form.cliente)
                        .then(response => {
                            this.form.loading = false
                            Swal.fire('Ótimo!', 'Cliente cadastrado com sucesso.', 'success')
                            this.concluiSalvamentoDosDados()
                        })
                        .catch(error => {
                            this.form.loading = false
                            Swal.fire('Oops!','Ocorreu um erro ao tentar cadastrar esse cliente, feche para ver mais detalhes.','error')
                            this.$refs.alert.abreMensagens(error.response)
                            this.sobeParaTopoDaJanela()
                        })
                } else {
                    axios.put('api/clientes/' + this.form.cliente.id, this.form.cliente)
                        .then(response => {
                            this.form.loading = false
                            Swal.fire('Ótimo!','Cliente alterado com sucesso','success')
                            this.concluiSalvamentoDosDados()
                        })
                        .catch(error => {
                            this.form.loading = false
                            Swal.fire('Oops!','Ocorreu um erro ao tentar alterar esse cliente, feche para ver mais detalhes.','error')
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

<style scoped>

</style>
