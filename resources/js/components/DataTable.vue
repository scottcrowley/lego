<template>
    <div>
        <div class="mt-4 mb-6 flex items-center" v-show="!loading">
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
    import dataCore from '../mixins/dataCore';

    export default {
        mixins: [dataCore],
        props: {
            colnames: {
                type: Array,
                default: [{}]
            }, 
            valnames: {
                type: Array,
                default: []
            },
        },
        data() {
            return {
                limit: 2,
                showDisabled: true,
                size: 'small',
                align: 'left',
            }
        },

        mounted() {
            let updateSort = this.checkUriParams();
            if (!updateSort) {
                this.checkSortDefault();
            } else {
                this.updateSortedColumn();
            }

            this.getResults();
        },

        methods: {
            checkSortDefault() {
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
                        this.updateUri(params);
                    })
                    .catch(function(error) {
                        this.processError(error);
                    });
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
