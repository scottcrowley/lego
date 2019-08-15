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
            <div class="relative ml-auto">
                <select id="selectPerPage" @change="updatePerPage($event)">
                    <option value="0">Per Page</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
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
                        <img class="" :src="getImageSrc(data[image_field])" :alt="data[image_label_field]">
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
                <span v-text="allData.total"></span> <span v-text="label"></span> found
            </p>
            <div class="page-navigation ml-2">
                <pagination
                        class="mb-0"
                        :data="allData"
                        @pagination-change-page="getResults"
                        :limit="pagerLimit"
                        :show-disabled="pagerShowDisabled"
                        :size="pagerSize"
                        :align="pagerAlign" />
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
            image_base_url: {
                type: String,
                default: ''
            },
            image_extension: {
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
                default: '25'
            }, 
            valnames: {
                type: Array,
                default: [{}]
            }, 
            allowedparams: {
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
                pagerLimit: 2,
                pagerShowDisabled: true,
                pagerSize: 'small',
                pagerAlign: 'left',
                dataSet: [],
                allData: {},
                loading: true,
                sortedCol: '',
                sortdesc: false,
                presentParamsString: '',
                perpage: this.per_page,
                currentPage: 1,
                defaultPage: 0,
                location: null
            }
        },

        mounted() {
            let checkedUrl = this.checkUrl();
            if (!checkedUrl) {
                this.checkSorted();
            } else {
                this.updateSortSelect();
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
                let perpage = url.searchParams.get('perpage');
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

                this.perpage = (perpage) ? perpage : this.perpage;
                document.getElementById('selectPerPage').value = this.perpage;

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
                let cols = this.valnames;

                cols.forEach((col, index) => {
                    if (col.sortable && col.sorted) {
                        document.getElementById('selectSort').value = this.sortedCol = col.field;
                        if (col.sortdesc) {
                            this.sortdesc = true;
                            document.getElementById('selectOrder').value = 1;
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
                
                if (value != this.sortdesc) {
                    this.sortdesc = value;
                    this.getResults();
                }
            },
            updatePerPage(event) {
                if (event.target.value == 0) return;
                this.perpage = event.target.value;

                this.getResults(this.currentPage);
            },
            updateSortSelect() {
                let element = document.getElementById('selectSort');
                element.value = this.sortedCol;

                let sortdesc = (this.sortdesc) ? 1 : 0;
                element = document.getElementById('selectOrder');
                element.value = sortdesc;
            },
            getResults(page = 1) {
                this.loading = true;

                if (!page && this.defaultPage == 0) {
                    page = 1;
                } else if (this.defaultPage != 0) {
                    page = this.defaultPage;
                    this.defaultPage = 0;
                }

                this.currentPage = (this.currentPage != page) ? page : this.currentPage;

                let params = '?page=' + page + '&perpage=' + this.perpage;
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
            getImageSrc(img) {
                return this.image_base_url + img + this.image_extension;
            }
        }
    };
</script>
