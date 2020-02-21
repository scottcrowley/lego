export default {
    props: {
        label: {
            type: String,
            default: ''
        }, 
        per_page: {
            type: String,
            default: '25'
        }, 
        allowedparams: {
            type: Array,
            default: []
        },
        sortOrder: {
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
            dataSet: [],
            allData: {},
            loading: true,
            sortedCol: '',
            sortdesc: false,
            sortCols: [],
            presentParamsString: '',
            perpage: this.per_page,
            defaultPage: 0,
            location: null,
            currentPage: 1,
            preResultsFunction: null,
            postResultsFunction: null
        }
    },

    methods: {
        checkUriParams() {
            this.location = window.location;
            let url = new URL(this.location.href);
            this.checkAllowedParams(url.search);

            let page = url.searchParams.get('page');
            let sort = url.searchParams.get('sort');
            let perpage = url.searchParams.get('perpage');
            let updateSort = (sort != null && sort != '') ? true : false;

            if (updateSort) {
                let sortCols = this.getSortColumns(sort);

                this.sortdesc = (sortCols[0].substr(0,1) == '-');
                this.sortedCol = (this.sortdesc) ? sortCols[0].substr(1) : sortCols[0];
            }

            if (page) {
                this.defaultPage = page;
            }

            this.perpage = (perpage) ? perpage : this.perpage;

            let element = document.getElementById('selectPerPage');
            if (element) {
                element.value = this.perpage;
            }

            return updateSort;
        },
        checkAllowedParams(search) {
            if (search.startsWith('?')) {
                search = search.substr(1);
            }
            if (search) {
                let urlParams = search.split('&');
                urlParams.forEach(this.addParamToQuery);
            }
        },
        checkSortDefault() {
            let sortCols = this.sortOrder;

            if (! sortCols.length) {
                return;
            }

            if (sortCols[0] != '') {
                this.sortdesc = (sortCols[0].substr(0,1) == '-');
                this.sortedCol = (this.sortdesc) ? sortCols[0].substr(1) : sortCols[0];
            }

            this.sortCols = sortCols;
        },
        getSortColumns(sort) {
            let sortCols = sort.split(',');
            this.sortCols = sortCols;
            return sortCols;
        },
        addParamToQuery(param) {
            let details = param.split('=');
            if (
                details[0] != 'page'
                && details[0] != 'sort'
                && this.allowedparams.includes(details[0])
            ) {
                this.presentParamsString = this.presentParamsString + '&' + param;
            }
        },
        updateUri(params) {
            history.pushState(null, null, params);
        },
        getResults(page = 1) {
            this.loading = true;

            if (this.preResultsFunction) {
                this[this.preResultsFunction]();
            }

            if (!page && this.defaultPage == 0) {
                page = 1;
            } else if (this.defaultPage != 0) {
                page = this.defaultPage;
                this.defaultPage = 0;
            }

            this.currentPage = (this.currentPage != page) ? page : this.currentPage;

            let params = '?page=' + page + '&perpage=' + this.perpage;
            if (this.sortedCol != '') {
                params = params + '&sort=' + this.sortCols.join(',');
            }

            params = params + this.presentParamsString;

            axios.get(this.endpoint + params)
                .then(response => {
                    this.loading = false;
                    this.allData = response.data;
                    this.dataSet = response.data.data;
                    this.updateUri(params);
                    if (this.postResultsFunction !== null) {
                        this[this.postResultsFunction]();
                    }
                })
                .catch(error => {
                    this.processError(error);
                });
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
    }
}