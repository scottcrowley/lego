<template>
    <div>
        <div class="w-full md:w-3/4 mb-5 mx-auto border border-t-0 rounded-b-lg px-4 py-4">
            <div class="flex">
                <p class="title">Filters:</p>
                <button class="block ml-auto btn is-small is-narrow" @click.prevent="filtersShow = !filtersShow" v-text="(filtersShow) ? 'hide' : 'show'"></button>
            </div>
            <div class="ml-2 pt-4" v-show="filtersShow">
                <form @submit.prevent="applyFilters()">
                    <div class="field-group flex items-center mb-2" v-for="filter in filters">
                        <label :for="'filter_'+filter.param" v-text="filter.label"></label>
                        <input :type="filter.type" :name="'filter_'+filter.param" :id="'filter_'+filter.param" :class="filter.classes" v-model="filterModels['filter_'+filter.param]">
                    </div>
                    <div class="field-group flex items-center mb-2">
                        <button class="btn is-primary" @click.prevent="applyFilters()">Apply Filters</button>
                        <button class="btn ml-3" @click.prevent="clearFilters()">Clear Filters</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="w-full h-8 loader loader-lg" v-show="loading"></div>
        <div class="w-full mb-4 flex" v-show="!loading">
            <div class="relative">
                <select id="selectSort" @change="updateSort($event)">
                    <option value="">Sort By</option>
                    <option v-for="valname in valnames" :value="valname.field" v-if="valname.sortable">{{ valname.label }}</option>
                </select>
                <div class="select-menu-icon">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
            <select-menu-special
                select_id="selectOrder"
                parent_classes="ml-3"
                :options_list="[{label: 'Ascending', value: 0}, {label: 'Descending', value: 1}]"
                change_method="updateSortOrder"
                default_label=""
            />
            <div class="relative ml-auto">
                <select id="selectPerPage" class="w-full" @change="updatePerPage">
                    <option value="0">Per Page</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="250">250</option>
                    <option :value="allData.total">All</option>
                </select>
                <div class="select-menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="fill-current h-4 w-4">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="mt-4 mb-6 flex flex-col md:flex-row items-center" v-show="!loading">
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

        <div class="card-container" v-show="!loading">
            <div class="card card-horizontal" v-for="(data, index) in dataSet" :key="index">
                <div :class="(data.dimmed) ? 'dim' : ''" class="dim-container">
                    <div class="card-content z-20">
                        <div class="card-image">
                            <div class="w-full my-0 mx-auto p-0" v-if="data[image_field] == '' || data[image_field] == null"></div>
                            <img :src="data[image_field]" :alt="data['name']" :data-alt-image="data[image_label_field]" v-else class="" @click.prevent="swapImageUrl($event)">
                        </div>
                        <div class="card-body z-10" @click.prevent="toggleSelection($event, index)">
                            <div class="z-0">
                                <p :class="(valname.title) ? 'title' : ''" class="relative" v-for="valname in valnames" style="z-index: -1;">
                                    <span v-if="!valname.link">
                                        <span class="font-bold" v-if="!valname.title">{{ valname.label }}:</span> 
                                        {{ (
                                            (valname.boolean === true) ? (
                                                (data[valname.field] == true || data[valname.field] == 't') ? 'Yes' : 'No'
                                            ) : data[valname.field]
                                        ) }}
                                    </span>
                                    <a :href="generateLinkUrl(valname.linkUrl, index)" v-else>{{ data[valname.field] }}</a>
                                </p>
                            </div>
                        </div>
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
    import dataCore from '../mixins/dataCore';
    import dataGridCore from '../mixins/dataGridCore';
    import dataFilterCore from '../mixins/dataFilterCore';
    import SelectMenuSpecial from '../components/SelectMenuSpecial';

    export default {
        components: {SelectMenuSpecial},

        mixins: [dataGridCore, dataCore, dataFilterCore],

        props: { 
            selection_get_endpoint: {
                type: String,
                default: ''
            },
            selection_update_endpoint: {
                type: String,
                default: ''
            }
        },

        data() {
            return {
                selection: {},
                postResultsFunction: 'checkPartSelected',
            }
        },
        
        methods: {
            checkPartSelected() {
                this.getCachedSelected();
            },

            updateSelectedParts() {
                let keys = Object.keys(this.dataSet);
                keys.forEach((key) => {
                    let cacheKey = this.dataSet[key]['part_num'] + '-' + this.dataSet[key]['color_id'] + '-' + this.dataSet[key]['is_spare'];
                    if (this.selection[cacheKey]) {
                        this.dataSet[key].dimmed = true;
                    }
                });
            },

            toggleSelection(event, index) {
                let el = event.target.closest('.dim-container');
                let selected = ! el.classList.contains('dim');
                
                this.toggleDim(el);
                this.updateCache(this.dataSet[index], selected);
            },

            toggleDim(el) {
                el.classList.toggle('dim');
            },

            getCachedSelected() {
                axios.get(this.selection_get_endpoint)
                    .then(response => {
                        this.selection = response.data;

                        this.updateSelectedParts();
                    })
                    .catch(error => {
                        this.processError(error);
                    });
            },

            updateCache(data, selected) {
                axios.post(this.selection_update_endpoint, {
                    inventory_id: data.inventory_id,
                    part_num: data.part_num,
                    color_id: data.color_id,
                    is_spare: data.is_spare,
                    selected: selected,
                }).then(response => {
                    this.selection = response.data;
                }).catch(error => {
                    this.processError(error);
                });
            },
        }
    };
</script>
