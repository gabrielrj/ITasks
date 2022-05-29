<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title">Lista de tarefas</h5>
                    </div>
                    <div class="col">
                        <router-link to="/tasks/create" class="btn btn-primary float-end">Adicionar</router-link>
                    </div>
                </div>

                <div class="row mt-2 mb-2" v-if="$route.params.msg != null">
                    <div class="col">
                        <div class="alert alert-success alert-dismissible d-flex align-items-center" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>&nbsp;
                            <strong>Sucesso!</strong>&nbsp; {{ $route.params.msg }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>

                <ErrorAlerts ref="errorAlerts"></ErrorAlerts>

                <div class="row">
                    <div class="col">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Título</th>
                                <th scope="col">Usuário</th>
                                <th scope="col">Status</th>
                                <th scope="col">Data de Cadastro</th>
                                <th scope="col">Data de Conclusão</th>
                            </tr>
                            </thead>
                            <tbody class="table-group-divider">
                            <tr v-for="(task, task_i) in tasks" :key="task_i">
                                <th scope="row">{{ (task_i + 1) }}</th>
                                <td>{{ task.name }}</td>
                                <td>{{ task.user.name }}</td>
                                <td>{{ task.status_task }}</td>
                                <td>{{ task.registration_date }}</td>
                                <td>{{ task.completed_task == null ? 'N/a' : task.completed_task }}</td>
                                <td>
                                    <router-link :to="{ name: 'TaskEdit', params: { taskEditId: task.uuid }}" class="btn btn-dark" title="Edit" aria-label="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                    </router-link>
                                    &nbsp;
                                    <button type="button" class="btn btn-danger" title="Remove" aria-label="Remove"
                                            @click="deleteTask(task.uuid)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
import taskManager from "@/services/taskManager";
import Swal from "sweetalert2";
import ErrorAlerts from "@/components/default/ErrorAlerts";

export default {
    name: "ListAllTasks",
    components: {ErrorAlerts},
    data() {
        return {
            tasks: []
        }
    },
    computed: {
        listAllTasks() {
            return this.tasks
        }
    },
    methods: {
        async getAllTasks() {
            try {
                this.tasks = await taskManager.getAllTasks()
            }catch (e) {
                console.log(e)
            }
        },
        async deleteTask(id){
            Swal.fire({
                title: 'Você confirma a exclusão?',
                text: "Essa operação não poderá ser revertida!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim!',
                cancelButtonText: 'Cancelar.'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await taskManager.deleteTask(id).then(() => {
                        Swal.fire(
                            'Sucesso!',
                            'Tarefa excluída com sucesso.',
                            'success'
                        )

                        this.getAllTasks()
                    }).catch(error => {
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
                    })

                }
            })
        },

    },
    mounted() {
        this.getAllTasks()
    }
}
</script>

<style scoped>

</style>
