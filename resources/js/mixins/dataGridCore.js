export default {
    props: {
        label: {
            type: String,
            default: ''
        }, 
        location_id: {
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
        toggle_end_point: {
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
        allow_image_swap: {
            type: Boolean,
            default: false
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
                .catch(error => {
                    this.processError(error);
                });
        },
        updateUrl(params) {
            history.pushState(null, null, params);
        },
        generateLinkUrl(url, index) {
            let token = url.match(/\{.+\}/ig);
            if (token == null) {
                return url;
            }

            let key = token[0].substr(1, token[0].length - 2);
            let value = this.dataSet[index][key];
            
            if (value) {
                return url.replace(token[0], value);
            }

            return url;
        },
        swapImageUrl(e) {
            if (this.allow_image_swap) {
                let src = e.target.src;
                let alt = e.target.alt;

                e.target.src = alt;
                e.target.alt = src;
            }
        },
        processError(error) {
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
        }
    }
}