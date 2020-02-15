export default {
    props: { 
        filters: {
            type: Array,
            default: []
        },
    },

    data() {
        return {
            filtersShow: false,
            filterParams: [],
            filterModels: {},
            preResultsFunction: 'checkFilters'
        }
    },
    
    methods: {
        checkFilters() {
            this.populateFilters();
            this.populateFilterParams(this.presentParamsString);
            this.applyFilters(false);
        },
        stripParams(params, removeList) {
            let paramList = params.split('&');
            let newParams = '';

            paramList.forEach((p, index) => {
                if (p == '') {
                    return;
                }

                let details = p.split('=');
                if (! removeList.includes(details[0])) {
                    newParams = newParams + '&' + p;
                }
            });

            return newParams;
        },
        populateFilters() {
            let defaultExist = false;
            this.filters.forEach((f, index) => {
                this.filterParams[index] = f.param;
                let filterName = 'filter_' + f.param;
                defaultExist = (f.defaultvalue && f.defaultvalue != '');
                this.filterModels[filterName] = (defaultExist) ? f.defaultvalue : '';
            });
            this.filtersShow = defaultExist;
        },
        populateFilterParams(currentParams) {
            let paramList = currentParams.split('&');
            paramList.forEach((p, index) => {
                if (p == '') {
                    return;
                }

                let details = p.split('=');
                if (this.filterParams.includes(details[0])) {
                    let input = 'filter_' + details[0];
                    this.filterModels[input] = decodeURIComponent(details[1]);

                    this.filtersShow = true;
                }
            });
        },
        applyFilters(getResults = true) {
            this.presentParamsString = this.stripParams(this.presentParamsString, this.filterParams);

            this.filterParams.forEach((p, index) => {
                let input = 'filter_' + p;
                let value = this.filterModels[input];
                if (value === true) {
                    value = this.filters[index].value;
                }
                if (value != '') {
                    value = value.trim();
                    if (value.endsWith(',')) {
                        value = value.slice(0,-1);
                    }
                    this.presentParamsString = this.presentParamsString + '&' + p + '=' + value;
                }
            });

            if (this.presentParamsString == '') {
                this.filtersShow = false;
            }
            
            if (getResults) {
                this.getResults(1);
            }
        },
        clearFilters() {
            this.filterParams.forEach((p, index) => {
                let input = 'filter_' + p;
                this.filterModels[input] = '';
            });

            this.presentParamsString = this.stripParams(this.presentParamsString, this.filterParams);

            this.getResults(1);
        },
    }
}