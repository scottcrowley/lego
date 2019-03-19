<template>
    <div>
        <div class="w-full h-8 loader loader-lg" v-show="loading"></div>
        <div class="w-full mb-4 flex" v-show="!loading">
            <div class="relative">
                <select id="selectSort" @change="updateSort($event)">
                    <option value="">Sort By</option>
                    <option v-for="valname in valnames" :value="valname['field']" v-if="valname['sortable']">{{ valname['label'] }}</option>
                </select>
                <div class="select-menu-icon">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
            <div class="relative ml-3">
                <select id="selectOrder" @change="updateSortOrder($event)">
                    <option value="0">Ascending</option>
                    <option value="1">Descending</option>
                </select>
                <div class="select-menu-icon">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="card-container" v-show="!loading">
            <div class="card card-horizontal" v-for="(data, index) in dataSet" :key="index">
                <div class="card-content">
                    <div class="card-image" v-if="data[image_field] != ''">
                        <img class="" :src="data[image_field]" :alt="data[image_label_field]">
                    </div>
                    <div class="card-body">
                        <p v-for="valname in valnames" :class="(valname['title']) ? 'title' : ''">
                            <span class="font-bold" v-if="!valname['title']">{{ valname['label'] }}:</span> {{ data[valname['field']] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 flex flex-col md:flex-row items-center" v-show="!loading">
            <p class="text-sm flex-1 mb-2 md:mb-0">
                <span v-text="allData.total"></span> <span v-text="label"></span> found on Rebrickable
            </p>
            <div class="mb-2 md:mb-0">
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
            image_field: {
                type: String,
                default: ''
            }, 
            image_label_field: {
                type: String,
                default: ''
            }, 
            per_page: {
                type: String,
                default: '12'
            }, 
            valnames: {
                type: Array,
                default: [{}]
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
                let cols = this.valnames;

                cols.forEach((col, index) => {
                    if (col.sortable && col.sorted) {
                        let element = document.getElementById('selectSort');
                        element.value = this.sortedCol = col.field;
                        if (col.sortDesc) {
                            this.sortDesc = true;
                            let order = document.getElementById('selectOrder');
                            order.value = 1;
                        }
                    }
                });
            },
            updateSort(event) {
                let value = event.target.value;
                
                if (value != '' && value != this.sortedCol) {
                    this.sortedCol = value;
                    this.getResults();
                }
            },
            updateSortOrder(event) {
                let value = (event.target.value == '1') ? true : false;
                
                if (value != this.sortDesc) {
                    this.sortDesc = value;
                    this.getResults();
                }
            },
            getResults(page = 1) {
                this.loading = true;

                if (!page) {
                    page = 1;
                }

                let params = '?page=' + page + '&perpage=' + this.per_page;
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
        }
    };
</script>
