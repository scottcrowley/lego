<template>
    <div>
        <div class="mt-4 flex items-center mb-4" v-show="!loading">
            <p class="text-sm flex-1">
                <span v-text="allData.total"></span> <span v-text="label"></span> found
            </p>
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
        <div class="w-full">
            <div class="" v-show="!loading">
                <div id="colnames" class="flex">
                    <div v-for="colname in colnames" 
                        class="px-1 py-0 text-xs text-secondary-darker font-semibold text-left"
                        :class="calculateClass(colname)" 
                        v-text="colname.name" 
                        @click.prevent="updateSort($event)"></div>
                </div>
            </div>
            
            <div>
                <div class="" v-show="loading">
                    <div class="h-8 w-8 mx-auto loader loader-lg"></div>
                </div>
                <div class="flex" v-for="(data, index) in dataSet" :key="index" v-show="!loading">
                    <div 
                        class="p-1 text-xs text-secondary-dark font-hairline text-left"
                        :class="(colnames[vIndex].width != '') ? colnames[vIndex].width : ''" 
                        v-for="(valname, vIndex) in valnames">
                        <span v-if="valname == 'rgb'" 
                            class="mr-1 -mb-px inline-block border border-secondary-darker w-3 h-3" 
                            :style="showColor(data[valname])"></span>
                        {{ (
                            (colnames[vIndex].boolean === true) ? (
                                (data[valname] == true || data[valname] == 't') ? 'Yes' : 'No'
                            ) : data[valname]
                        ) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 flex items-center" v-show="!loading">
            <p class="text-sm flex-1">
                <span v-text="allData.total"></span> <span v-text="label"></span> found
            </p>
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
            allowedparams: {
                type: Array,
                default: []
            },
            endpoint: {
                type: String,
                default: ''
            },
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
                sortdesc: false,
                presentParamsString: '',
                defaultPage: 0,
                location: null
            }
        },

        mounted() {
            let checkedUrl = this.checkUrl();
            if (!checkedUrl) {
                this.checkSorted();
            } else {
                this.updateSortedColumn();
            }

            this.getResults();
        },

        methods: {
            checkUrl() {
                this.location = window.location;
                let url = new URL(this.location.href);
                this.checkAllowedParams(url.search);
                
                let page = url.searchParams.get('page');
                let sort = url.searchParams.get('sort');
                let sortdesc = url.searchParams.get('sortdesc');
                let updateSort = (sort != null || sortdesc != null) ? true : false;

                if (sort != null) {
                    this.sortedCol = sort;
                } else if (sortdesc != null) {
                    this.sortdesc = true;
                    this.sortedCol = sortdesc;
                }

                if (page) {
                    this.defaultPage = page;
                }

                return updateSort;
            },
            checkAllowedParams(search) {
                if (search.startsWith('?')) {
                    search = search.substr(1);
                }
                if (search) {
                    let urlParams = search.split('&');
                    urlParams.forEach(this.processParam);
                }
            },
            processParam(param) {
                let details = param.split('=');
                if (
                    details[0] != 'page'
                    && details[0] != 'sort'
                    && details[0] != 'sortdesc'
                    && this.allowedparams.includes(details[0])
                ) {
                    this.presentParamsString = this.presentParamsString + '&' + param;
                }
            },
            checkSorted() {
                let cols = this.colnames;

                cols.forEach((col, index) => {
                    if (col.sortable && col.sorted) {
                        let element = document.getElementById('colnames').childNodes[index];
                        element.classList.add('sorted-col');
                        this.sortedCol = this.valnames[index];
                        if (col.sortdesc) {
                            this.sortdesc = true;
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
                    this.sortdesc = (classList.contains('desc')) ? false : true;
                } else {
                    this.sortdesc = false;

                    cols.forEach((col, index) => {
                        if (col == event.target) {
                            this.sortedCol = this.valnames[index];
                        }
                    });
                }
                this.updateSortedColumn();
                
                this.getResults();
            },
            updateSortedColumn() {
                let cols = this.colnames;
                let trs = document.getElementById('colnames').childNodes;
                let classList = [];

                cols.forEach((col, index) => {
                    classList = trs[index].classList;
                    classList.remove('sorted-col','desc');
                    if (this.valnames[index] == this.sortedCol && col.sortable) {
                        classList.add('sorted-col');
                        if (this.sortdesc) {
                            classList.add('desc');
                        }
                    }
                });
            },
            getResults(page = 1) {
                this.loading = true;

                if (!page && this.defaultPage == 0) {
                    page = 1;
                } else if (this.defaultPage != 0) {
                    page = this.defaultPage;
                    this.defaultPage = 0;
                }

                let params = '?page=' + page;
                if (this.sortedCol != '') {
                    params = params + '&sort' + (this.sortdesc ? 'desc' : '') + '=' + this.sortedCol;
                }

                params = params + this.presentParamsString;

                axios.get(this.endpoint + params)
                    .then(response => {
                        this.loading = false;
                        this.allData = response.data;
                        this.dataSet = response.data.data;
                        this.updateUrl(params);
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
            updateUrl(params) {
                history.pushState(null, null, params);
            },
            showColor(rgb) {
                return 'background-color: #' + rgb;
            },
            calculateClass(colname) {
                let classList = '';
                classList += (colname.sortable) ? 'sortable-col' : '';
                classList += (colname.width != '') ? ' ' + colname.width : '';
                return classList; 
            }
        }
    };
</script>
