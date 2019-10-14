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