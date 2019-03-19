<template>
    <div>
        <table class="table">
            <thead class="" v-show="!loading">
                <tr id="colnames">
                    <th v-for="colname in colnames" 
                        :class="(colname.sortable) ? 'sortable-col' : ''" 
                        v-text="colname.name" 
                        @click.prevent="updateSort($event)"></th>
                </tr>
            </thead>
            <tbody class="">
                <tr v-show="loading">
                    <td colspan="4" class="h-8 loader loader-lg"></td>
                </tr>
                <tr v-for="(data, index) in dataSet" :key="index" v-show="!loading">
                    <td v-for="valname in valnames">
                        <span v-if="valname == 'rgb'" class="mr-1 -mb-px inline-block border border-secondary-darker w-3 h-3" :style="showColor(data[valname])"></span>
                        {{ (data[valname] == true) ? 'Yes' : ((data[valname] == false) ? 'No' : data[valname]) }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4 flex items-center" v-show="!loading">
            <p class="text-sm flex-1">
                <span v-text="allData.total"></span> <span v-text="label"></span> found on Rebrickable
            </p>
            <div>
                <a href="" class="text-xs text-right block" @click.prevent="clearCache">Clear Cache</a>
            </div>
            <div class="page-navigation ml-2">
                <pagination
                        class="mb-0"
                        :data="allData"
                        @pagination-change-page="getResults"
                        :limit="limit"
                        :show-disabled="showDisabled"
                        :size="size"
                        :align="align" />
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            label: {
                type: String,
                default: ''
            }, 
            colnames: {
                type: Array,
                default: [{}]
            }, 
            valnames: {
                type: Array,
                default: []
            }, 
            endpoint: {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                limit: 2,
                showDisabled: true,
                size: 'small',
                align: 'left',
                dataSet: [],
                allData: {},
                loading: true,
                sortedCol: '',
                sortDesc: false
            }
        },

        mounted() {
            this.checkSorted();
            this.getResults();
        },

        methods: {
            checkSorted() {
                let cols = this.colnames;

                cols.forEach((col, index) => {
                    if (col.sortable && col.sorted) {
                        let element = document.getElementById('colnames').childNodes[index];
                        element.classList.add('sorted-col');
                        this.sortedCol = this.valnames[index];
                        if (col.sortDesc) {
                            this.sortDesc = true;
                            element.classList.add('desc');
                        }
                    }
                });
            },
            updateSort(event) {
                let classList = event.target.classList;
                if (! classList.contains('sortable-col')) return;

                let cols = event.target.parentElement.childNodes;

                if (classList.contains('sorted-col')) {
                    if (classList.contains('desc')) {
                        this.sortDesc = false;
                        classList.remove('desc');
                    } else {
                        this.sortDesc = true;
                        classList.add('desc');
                    }
                } else {
                    this.sortDesc = false;

                    cols.forEach((col, index) => {
                        col.classList.remove('sorted-col', 'desc');
                        if (col == event.target) {
                            this.sortedCol = this.valnames[index];
                            col.classList.add('sorted-col');
                        }
                    });
                }

                this.getResults();
            },
            getResults(page = 1) {
                this.loading = true;

                if (!page) {
                    page = 1;
                }

                let params = '?page=' + page;
                if (this.sortedCol != '') {
                    params = params + '&sort' + (this.sortDesc ? 'desc' : '') + '=' + this.sortedCol;
                }

                axios.get(this.endpoint + params)
                    .then(response => {
                        this.loading = false;
                        this.allData = response.data;
                        this.dataSet = response.data.data;
                    })
                    .catch(function(error) {
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            let message = error.response.status + ': ' + error.response.data;
                            flash(message, 'danger');

                            console.log('Data', error.response.data);
                            console.log('Status', error.response.status);
                            console.log('Headers', error.response.headers);
                        } else if (error.request) {
                            // The request was made but no response was received
                            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                            // http.ClientRequest in node.js
                            console.log('Request', error.request);
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            console.log('Error', error.message);
                        }
                        console.log('Config', error.config);
                    });
            },
            clearCache() {
                let types = this.endpoint.split('/');
                let type = types[types.length-1];
                this.dataSet = [];
                this.allData = {};
                this.loading = true;

                axios.get('/api/lego/clear/' + type)
                    .then(response => {
                        this.getResults();
                    });
            },
            showColor(rgb) {
                return 'background-color: #' + rgb;
            }
        }
    };
</script>
