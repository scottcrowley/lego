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
            preResultsFunction: null,
            postResultsFunction: null
        }
    },

    mounted() {
        let updateSort = this.checkUriParams();
        if (!updateSort) {
            this.checkSortDefault();
        }
        
        this.updateSortSelect();
        this.getResults();
    },

    methods: {
        checkSortDefault() {
            let sortCols = this.sortOrder;

            if (sortCols[0] != '') {
                this.sortdesc = (sortCols[0].substr(0,1) == '-');
                this.sortedCol = (this.sortdesc) ? sortCols[0].substr(1) : sortCols[0];
            }

            this.sortCols = sortCols;
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
        updateSort(event) {
            let value = event.target.value;
            
            if (value != '' && value != this.sortedCol) {
                this.sortedCol = value;
                let col = (this.sortdesc ? '-' : '') + value;
                this.sortCols = [col];
                this.getResults();
            }
        },
        updateSortOrder(event) {
            let value = (event.target.value == '1') ? true : false;
            
            if (value != this.sortdesc) {
                this.sortdesc = value;
                let col = (value ? '-' : '') + this.sortedCol;
                this.sortCols[0] = col;
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
        swapImageUrl(event) {
            if (this.allow_image_swap) {
                let src = event.target.src;
                let alt = event.target.dataset.altImage;

                event.target.src = alt;
                event.target.dataset.altImage = src;
            }
        },
    }
}