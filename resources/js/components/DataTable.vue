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
                        class="px-1 py-0 text-xs text-secondary-700 font-semibold text-left"
                        :class="calculateClass(colname)" 
                        v-text="colname.name" 
                        @click.prevent="updateSort($event)"></div>
                </div>
            </div>
            
            <div>
                <div class="" v-show="loading">
                    <div class="h-8 w-8 mx-auto loader loader-lg"></div>
                </div>
                <div class="flex text-xs text-secondary-600 font-light text-left" v-for="(data, index) in dataSet" :key="index" v-show="!loading">
                    <div 
                        class="p-1"
                        :class="(colnames[vIndex].width != '') ? colnames[vIndex].width : ''" 
                        v-for="(valname, vIndex) in valnames">
                        <span v-if="valname == 'rgb'" 
                            class="mr-1 -mb-px inline-block border border-secondary-700 w-3 h-3" 
                            :style="showColor(data[valname])"></span>
                        <span v-if="!colnames[vIndex].link">
                            {{ (
                                (colnames[vIndex].boolean === true) ? (
                                    (data[valname] == true || data[valname] == 't') ? 'Yes' : 'No'
                                ) : data[valname]
                            ) }}
                        </span>
                        <a :href="generateLinkUrl(colnames[vIndex].linkUrl, index)" v-else>{{ data[valname] }}</a>
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
            }
            this.updateSortedColumn();

            this.getResults();
        },

        methods: {
            updateSort(event) {
                let classList = event.target.classList;
                if (! classList.contains('sortable-col')) {
                    return;
                }

                let parent = document.querySelector('#colnames');
                let colIndex = Array.prototype.indexOf.call(parent.children, event.target);
                this.sortdesc = false;
                this.sortedCol = this.valnames[colIndex];

                if (classList.contains('sorted-col')) {
                    this.sortdesc = (classList.contains('desc')) ? false : true;
                } 
                this.sortCols = (this.sortdesc) ? ['-' + this.sortedCol] : [this.sortedCol];

                this.updateSortedColumn();
                
                this.getResults();
            },
            updateSortedColumn() {
                if (this.sortedCol == '') {
                    return;
                }

                let colIndex = this.valnames.findIndex(c => c == this.sortedCol);
                let colHeads = document.querySelectorAll('#colnames div');

                colHeads.forEach(col => {
                    col.classList.remove('sorted-col','desc');
                });

                if (this.colnames[colIndex].sortable) {
                    colHeads[colIndex].classList.add('sorted-col');
                    if (this.sortdesc) {
                        colHeads[colIndex].classList.add('desc');
                    }
                }
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
