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

        <div class="card-container" v-show="!loading">
            <div 
                class="card card-horizontal" 
                v-for="(data, index) in dataSet" 
                :key="index"
            >
                <div>
                    <div class="card-content">
                        <div class="card-image">
                            <div class="w-full my-0 mx-auto p-0" v-if="data[image_field] == '' || data[image_field] == null"></div>
                            <img 
                                :src="data[image_field]" 
                                :alt="data['name']" 
                                :data-alt-image="data[image_label_field]" 
                                v-else 
                                class="" 
                                @click.prevent="swapImageUrl($event)"
                            >
                        </div>
                        <div class="card-body">
                            <div>
                                <p 
                                    :class="(valname.title) ? 'title' : ''" 
                                    class="relative" 
                                    v-for="valname in valnames" 
                                >
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
                                <p class="mt-0 field-group-special">
                                    <label :for="'stickered-' + index">
                                        Stickered Parts
                                    </label>
                                    <input 
                                        type="number" 
                                        :name="'stickered-' + index" 
                                        :id="'stickered-' + index"
                                        v-model="stickeredModels[index]" 
                                        @change="changeStickered(index)" 
                                    >
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
            stickeredEndPoint: {
                type: String,
                default: ''
            }
        },

        data() {
            return {
                selection: {},
                stickeredModels: {},
                postResultsFunction: 'checkStickeredParts',
            }
        },
        
        methods: {
            checkStickeredParts() {
                let keys = Object.keys(this.dataSet);
                keys.forEach((key) => {
                    this.stickeredModels[key] = this.dataSet[key].stickered_parts_count;
                });
            },

            changeStickered(key) {
                let part = this.dataSet[key];
                let diff = this.stickeredModels[key] - part.stickered_parts_count;
                let total = part.stickered_parts_count + diff;
                if (total < 0) {
                    diff = 0 - part.stickered_parts_count;
                    total = 0;
                }
                if (total > part.quantity) {
                    diff = 0;
                    this.updateStickeredModels(key, part.quantity);
                }
                if (diff == 0) {
                    return;
                }
                return (diff > 0) ?
                    this.addStickered(part, key) :
                    this.removeStickered(part, key);
            },

            addStickered(part, key) {
                document.getElementById('stickered-' + key).disabled = true;
                axios.post(this.stickeredEndPoint, {
                    part_num: part.part_num,
                    color_id: part.color_id,
                }).then(response => {
                    let count = part.stickered_parts_count + 1;
                    this.updateStickeredModels(key, count);
                    this.dataSet[key].stickered_parts_count = count;
                    document.getElementById('stickered-' + key).disabled = false;
                }).catch(error => {
                    this.processError(error);
                });
            },

            removeStickered(part, key) {
                document.getElementById('stickered-' + key).disabled = true;
                let endpoint = this.stickeredEndPoint + '/' + part.part_num + '/' + part.color_id;
                axios.delete(endpoint)
                .then(response => {
                    let count = part.stickered_parts_count - 1;
                    this.updateStickeredModels(key, count);
                    this.dataSet[key].stickered_parts_count = count;
                    document.getElementById('stickered-' + key).disabled = false;
                }).catch(error => {
                    this.processError(error);
                });
            },

            updateStickeredModels(key, value) {
                this.stickeredModels[key] = value;
                document.getElementById('stickered-' + key).value = value;
            },
        }
    };
</script>
