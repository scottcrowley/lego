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
            <select-menu-special
                select_id="selectPerPage"
                parent_classes="ml-auto"
                :options_list="[{label: '10', value: 10}, {label: '15', value: 15}, {label: '25', value: 25}, {label: '50', value: 50}, {label: '75', value: 75}, {label: '100', value: 100}]"
                change_method="updatePerPage"
                default_label="Per Page"
                default_value=0
            />
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
 
        <div v-if="use_location">
            <div class="mb-6 flex flex-col md:flex-row items-center justify-center md:justify-start" v-show="!loading">
                <button class="btn is-small" @click.prevent="addAll()">Add All Parts To Location</button>
                <button class="btn is-small mt-2 md:mt-0 ml-0 md:ml-2" @click.prevent="removeAll()">Remove All Parts To Location</button>
            </div>
        </div>

        <div class="card-container" v-show="!loading">
            <div class="card card-horizontal" v-for="(data, index) in dataSet" :key="index">
                <div class="card-content">
                    <div class="card-image">
                        <img class="" :src="data[image_field]" :alt="data[image_label_field]" v-if="data[image_field] != '' && data[image_field] != null" @click.prevent="swapImageUrl($event)">
                        <div class="w-24 h-24 my-0 mx-auto p-0" v-if="data[image_field] == '' || data[image_field] == null"></div>
                    </div>
                    <div class="card-body">
                        <div v-for="valname in valnames">
                            <p :class="(valname.title) ? 'title' : ''">
                                <span class="font-bold" v-if="!valname.title">{{ valname.label }}:</span> 
                                {{ (
                                    (valname.boolean === true) ? (
                                        (data[valname.field] == true || data[valname.field] == 't') ? 'Yes' : 'No'
                                    ) : data[valname.field]
                                ) }}
                            </p>
                        </div>
                        <div v-if="use_location">
                            <p v-if="(data.location !== null && data.location.id != location_id)">
                                <span class="font-bold">Storage Location:</span> 
                                {{ data.location.location_name }}
                            </p>
                            <button v-else class="btn is-primary is-narrow is-small mt-2" @click.prevent="executeEndpoint(index)">
                                {{ (data.location !== null && data.location.id == location_id) ? 'Remove Part' : 'Add Part' }}
                            </button>
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
    import dataGridCore from '../mixins/dataGridCore';
    import SelectMenuSpecial from '../components/SelectMenuSpecial';

    export default {
        components: {SelectMenuSpecial},

        mixins: [dataGridCore],

        props: {
            use_location: {
                type: Boolean,
                default: false
            }, 
            filters: {
                type: Array,
                default: []
            }
        },

        data() {
            return {
                filtersShow: false,
                filterParams: [],
                filterModels: {},
                filter_name: '',
                filter_part_num: '',
                filter_category_label: '',
                filter_exclude_assigned: '',
            }
        },

        mounted() {
            this.populateFilters();
            this.populateFilterParams(this.presentParamsString);
        },
        
        methods: {
            executeEndpoint(index, multiple = false) {
                let endpoint = this.generateLinkUrl(this.toggle_end_point, index);
                console.log(endpoint);
                axios.get(endpoint)
                    .then(response => {
                        this.dataSet[index].location = response.data.location;
                        if (multiple === false) {
                            flash('Part association successfully updated!');
                        }
                    })
                    .catch(error => {
                        this.processError(error);
                    });
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
                this.filters.forEach((f, index) => {
                    this.filterParams[index] = f.param;
                    let filterName = 'filter_' + f.param;
                    this.filterModels[filterName] = '';
                });
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
            applyFilters() {
                this.presentParamsString = this.stripParams(this.presentParamsString, this.filterParams);

                this.filterParams.forEach((p, index) => {
                    let input = 'filter_' + p;
                    let value = this.filterModels[input];
                    if (value === true) {
                        value = this.location_id;
                    }
                    if (value != '') {
                        this.presentParamsString = this.presentParamsString + '&' + p + '=' + value;
                    }
                });
                
                this.getResults(1);
            },
            clearFilters() {
                this.filterParams.forEach((p, index) => {
                    let input = 'filter_' + p;
                    this.filterModels[input] = '';
                });

                this.presentParamsString = this.stripParams(this.presentParamsString, this.filterParams);

                this.getResults(1);
            },
            addAll() {
                let keys = Object.keys(this.dataSet);
                let processed = 0;
                keys.forEach((key) => {
                    if (this.dataSet[key].location !== null) {
                        return;
                    }
                    this.executeEndpoint(key, true);
                    processed++;
                });
                
                flash(processed+' Parts added successfully!');
            },
            removeAll() {
                let keys = Object.keys(this.dataSet);
                let processed = 0;
                keys.forEach((key) => {
                    if (this.dataSet[key].location !== null && this.dataSet[key].location.id == this.location_id) {
                        this.executeEndpoint(key, true);
                        processed++;
                    }
                });

                flash(processed+' Parts removed successfully!');
            },
        }
    };
</script>