export default {
    props: {
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
        valnames: {
            type: Array,
            default: [{}]
        }, 
        allow_image_swap: {
            type: Boolean,
            default: false
        },
    },

    data() {
        return {
            pagerLimit: 2,
            pagerShowDisabled: true,
            pagerSize: 'small',
            pagerAlign: 'left',
            currentPage: 1,
            postResultsFunction: null
        }
    },

    mounted() {
        let updateSort = this.checkUriParams();
        if (!updateSort) {
            this.checkSortDefault();
        } else {
            this.updateSortSelect();
        }

        this.getResults();
    },

    methods: {
        checkSortDefault() {
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
        updateSortSelect() {
            document.getElementById('selectSort').value = this.sortedCol;
            document.getElementById('selectOrder').value = (this.sortdesc) ? 1 : 0;
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
                params = params + '&sort' + (this.sortdesc ? 'desc' : '') + '=' + this.sortedCol;
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
            if (event.target.value == 0 || event.target.value == this.perpage) return;
            this.perpage = event.target.value;

            this.getResults(this.currentPage);
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
    }
}