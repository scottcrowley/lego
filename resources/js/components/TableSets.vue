<template>
    <div>
        <table class="table">
            <thead class="">
                <tr>
                    <th>Set Number</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Pieces</th>
                </tr>
            </thead>
            <tbody class="">
                <tr v-show="loading">
                    <td colspan="4" class="loading"></td>
                </tr>
                <tr v-for="(set, index) in sets" :key="index" v-show="!loading">
                    <td v-text="set.set_num"></td>
                    <td v-text="set.name"></td>
                    <td v-text="set.year"></td>
                    <td v-text="set.num_parts"></td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4 flex">
            <p class="text-sm"><span v-text="setsData.total"></span> Sets found on Rebrickable</p>
            <div class="page-navigation flex ml-auto">
                <button class="btn is-small is-narrow" @click.prevent="pager">Prev</button>
                <button class="btn is-small is-narrow ml-1" @click.prevent="">1</button>
                <button class="btn is-small is-narrow ml-1" @click.prevent="">2</button>
                <button class="btn is-small is-narrow ml-1" @click.prevent="">3</button>
                <div class="ml-1">...</div>
                <button class="btn is-small is-narrow ml-1" @click.prevent="" v-text="setsData.last_page - 1"></button>
                <button class="btn is-small is-narrow ml-1" @click.prevent="" v-text="setsData.last_page"></button>
                <button class="btn is-small is-narrow ml-1" @click.prevent="">Next</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                sets: [],
                setsData: [],
                loading: true
            }
        },

        created() {
            axios.get('/api/lego/sets')
            .then(({ data }) => {
                console.log(data);
                this.loading = false;
                this.setsData = data;
                this.sets = data.data;

            });
        },

        method: {
            pager() {

            }
        }
    };
</script>
