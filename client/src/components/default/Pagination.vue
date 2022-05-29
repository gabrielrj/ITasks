<template>
    <div>
        <nav aria-label="...">
            <ul class="pagination">
                <li class="page-item" :class="{'disabled' : currentPage === 1}">
                    <a class="page-link" href="#" aria-label="Previous"
                       @click.prevent.stop="previousPage">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item" v-for="page in totalPages" :key="page">
                    <a class="page-link" href="#" @click.prevent.stop="pageUpdated(page)">{{ page }}</a>
                </li>
                <li class="page-item" :class="{'disabled' : currentPage === totalPages}">
                    <a class="page-link" href="#" aria-label="Next"
                       @click.prevent.stop="nextPage">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    name: "Teste",
    props: {
        totalOfRegisters: {
            type: Number,
            required: false,
            default: 1
        },
        totalOfPages: {
            type: Number,
            required: false,
            default: 1
        },
        currentPageClicked: {
            type: Number,
            required: false,
            default: 1
        }
    },
    data() {
        return {
            totalRegs: this.totalOfRegisters === undefined ? 1 : this.totalOfRegisters,
            totalPages: this.totalOfPages === undefined ? 1 : this.totalOfPages,
            currentPage: this.currentPageClicked === undefined ? 1 : this.currentPageClicked,
        }
    },
    methods: {
        pageUpdated(page) {
            console.log(page)

            this.currentPage = page

            this.$emit('pageUpdated', page)
        },

        nextPage(){
            if(this.currentPage < this.totalPages)
                this.pageUpdated(this.currentPage + 1)
        },

        previousPage(){
            if(this.currentPage > 1)
                this.pageUpdated(this.currentPage - 1)
        },
    },
}
</script>

<style scoped>

</style>
