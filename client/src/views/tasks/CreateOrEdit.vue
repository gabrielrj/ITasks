<template>
    <div class="h-100 container">
        <div class="row mt-5">
            <div class="col">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><router-link to="dashboard">Dashboard</router-link></li>
                        <li class="breadcrumb-item" aria-current="page">Tarefas</li>
                        <li class="breadcrumb-item active" aria-current="page">{{ form.uuid == null ? 'Nova tarefa' : 'Edição de tarefa' }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <ErrorAlerts ref="errorAlerts"></ErrorAlerts>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ form.uuid == null ? 'Cadastre uma nova tarefa' : `Alteração da tarefa ${form.name}` }}</h5>
                        <div class="row">
                            <div class="col">
                                <form @submit.prevent.stop="saveTask">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Título</label>
                                        <input type="text" class="form-control" id="title" placeholder="Título" v-model="form.name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Descrição</label>
                                        <textarea class="form-control" id="description" rows="3" placeholder="Descrição" v-model="form.description"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="user" class="form-label">Usuário</label>
                                        <select class="form-select" id="user" v-model="form.users_id">
                                            <option v-for="(user, user_i) in form.users"
                                                    :key="user_i"
                                                    :value="user.uuid">{{ user.name }}</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" v-model="form.status">
                                            <option value="created" selected>Created</option>
                                            <option value="started" v-if="form.uuid !== null">Started</option>
                                            <option value="cancelled" v-if="form.uuid !== null">Cancelled</option>
                                            <option value="completed" v-if="form.uuid !== null">Completed</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" :class="{'disabled' : form.sending}" class="btn btn-primary">Salvar</button>
                                        &nbsp;
                                        <button type="button" :class="{'disabled' : form.sending}" class="btn btn-danger" @click="cancelOperation">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import taskManager from "@/services/taskManager";
import userManager from "@/services/userManager";
import Swal from "sweetalert2";
import router from "@/router";
import ErrorAlerts from "@/components/default/ErrorAlerts";

export default {
    name: "CreateOrEdit",
    components: {ErrorAlerts},
    data() {
        return {
            form: {
                users: [],

                uuid: null,
                name: null,
                description: null,
                status: 'created',
                users_id: null,

                sending: false,
            }
        }
    },
    methods: {
        cancelOperation(){
            router.back()
        },
        async findTaskForEdition(id){
            const task = await taskManager.findTaskById(id)

            this.form.uuid = task.uuid
            this.form.name = task.name
            this.form.description = task.description
            this.form.status = task.status
            this.form.users_id = task.user.uuid
        },
        async getUsersToSelect(){
            this.form.users = await userManager.getAllUsers()
        },
        async saveTask() {
            this.form.sending = true
            const payload = this.form

            if(!payload.uuid){
                await taskManager.createTask(payload)
                .then(() => {
                    router.push({ name: 'Tasks', params: { msg: 'Tarefa cadastrada com sucesso.' } })
                })
                .catch(error => {
                    // eslint-disable-next-line no-prototype-builtins
                    if(error.response.data.hasOwnProperty('validation_errors')){
                        this.$refs.errorAlerts.showMessages(error.response.data.validation_errors)
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops! Ocorreu um erro.',
                            text: error.response.message,
                            footer: '<b>' + error.response.exception + '</b>'
                        })
                    }
                }).finally(() => {
                    this.form.sending = false
                })
            }else{
                await taskManager.updateTask(payload)
                .then(() => {
                    router.push({ name: 'Tasks', params: { msg: 'Tarefa alterada com sucesso.' } })
                })
                .catch(error => {
                    // eslint-disable-next-line no-prototype-builtins
                    if(error.response.data.hasOwnProperty('validation_errors')){
                        this.$refs.errorAlerts.showMessages(error.response.data.validation_errors)
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops! Ocorreu um erro.',
                            text: error.response.message,
                            footer: '<b>' + error.response.exception + '</b>'
                        })
                    }
                }).finally(() => {
                    this.form.sending = false
                })
            }
        }
    },
    mounted() {
        this.getUsersToSelect()

        // eslint-disable-next-line no-prototype-builtins
        if(this.$route.params.hasOwnProperty('taskEditId'))
            this.findTaskForEdition(this.$route.params.taskEditId)
    }
}
</script>

<style scoped>

</style>
