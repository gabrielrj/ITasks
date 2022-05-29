<template>
    <div class="h-100 container">
        <div class="row mt-5">
            <div class="col">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><router-link to="dashboard">Dashboard</router-link></li>
                        <li class="breadcrumb-item active" aria-current="page">Tarefas</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <ListAllTasks ref="listAllTasks"></ListAllTasks>
            </div>
        </div>

    </div>

</template>

<script>

import ListAllTasks from "@/components/tasks/ListAllTasks";

export default {
    name: "Index",
    components: {ListAllTasks},

}
</script>

<style scoped>

</style>
