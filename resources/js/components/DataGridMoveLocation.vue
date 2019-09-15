<template>
    <div>
        <div class="w-full h-8 loader loader-lg" v-show="loading"></div>
        <div class="block sm:flex mt-3 mb-8 justify-center" v-show="!loading">
            <form v-if="all_move_locations.length" class="sm:flex w-full sm:w-auto" @submit.prevent="">
                <div>
                    <select-menu-special
                        select_id="moveToLocation"
                        parent_classes="w-full"
                        :options_list="locationOptions"
                        change_method="updateMoveBtn"
                        default_label="Choose a location to move to..."
                        default_value=""
                    />
                </div>
                <button class="mt-3 sm:mt-0 sm:ml-3 w-full sm:w-auto block sm:inline btn is-narrow is-primary" :disabled="moveSelectedDisabled" @click.prevent="moveSelected()">Move Selected</button>
                <button class="mt-1 sm:mt-0 sm:ml-3 w-full sm:w-auto block sm:inline btn is-narrow is-primary" :disabled="moveAllDisabled" @click.prevent="moveAll()">Move All</button>
            </form>
            <p v-else>There are currently no other storage locations in the database.</p>
        </div>
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

        <div v-show="!loading">
            <p class="title mb-2 text-center">Select the parts below to move</p>
        </div>

        <div class="card-container" v-show="!loading">
            <div class="card card-horizontal" v-for="(data, index) in dataSet" :key="index">
                <div class="card-content cursor-pointer" @click.prevent="selectPart($event, index)">
                    <div class="card-image">
                        <img class="" :src="data[image_field]" :alt="data[image_label_field]" v-if="data[image_field] != '' && data[image_field] != null" @click.prevent="swapImageUrl($event)">
                        <div class="w-24 h-24 my-0 mx-auto p-0" v-if="data[image_field] == '' || data[image_field] == null"></div>
                    </div>
                    <div class="card-body">
                        <div v-for="valname in valnames">
                            <button v-if="valname.label == 'button'" class="btn is-primary is-narrow is-small mt-2" @click.prevent="executeEndpoint(valname.url, index, valname.successMsg)">
                                {{ valname.text }}
                            </button>
                            <p v-else :class="(valname.title) ? 'title' : ''">
                                <span v-if="!valname.link">
                                    <span class="font-bold" v-if="!valname.title">{{ valname.label }}:</span> 
                                    {{ (
                                        (valname.boolean === true) ? (
                                            (data[valname.field] == true || data[valname.field] == 't') ? 'Yes' : 'No'
                                        ) : data[valname.field]
                                    ) }}
                                </span>
                                <a :href="generateLinkUrl(valname.linkUrl, index)" v-if="valname.link">{{ data[valname.field] }}</a>
                            </p>
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
    import SelectMenu from '../components/SelectMenu';
    import SelectMenuSpecial from '../components/SelectMenuSpecial';

    export default {
        components: {SelectMenuSpecial, SelectMenu},
        mixins: [dataGridCore],

        props: {
            all_move_locations: {
                type: Array,
                default: []
            }, 
            move_endpoint: {
                type: String,
                default: ''
            }
        },

        data() {
            return {
                moveAllDisabled: true,
                moveSelectedDisabled: true,
                partsSelected: {},
                selected: false
            }
        },

        mounted () {
            this.postResultsFunction = 'updateSelected';
        },

        computed: {
            locationOptions() {
                if (this.all_move_locations.length) {
                    let options = [];
                    this.all_move_locations.forEach((location, index) => {
                        options[index] = {
                            label: location.location_name,
                            value: location.id
                        };
                    });
                    return options; 
                }
            }
        },

        methods: {
            executeEndpoint(url, index, successMsg) {
                let endpoint = this.generateLinkUrl(url, index);

                axios.get(endpoint)
                    .then(response => {
                        this.getResults(this.currentPage);
                        flash(successMsg);
                    })
                    .catch(error => {
                        this.processError(error);
                    });
            },

            checkSelected() {
                let values = Object.values(this.partsSelected);
                let selected = values.filter(function(value) {
                    return value === true;
                });
                return selected.length != 0;
            },

            selectPart(event, index) {
                event.target.classList.toggle('border-primary');
                this.partsSelected[index] = ! this.partsSelected[index];
                this.selected = this.checkSelected();
                this.updateMoveBtn();
            },

            updateSelected(selected = false) {
                this.partsSelected = {};
                this.selected = selected;
                let keys = Object.keys(this.dataSet);
                keys.forEach((key) => {
                    this.partsSelected[key] = selected;
                });
            },
            
            updateMoveBtn() {
                let moveTo = document.querySelector('#moveToLocation').value;
                if (moveTo == '') {
                    this.moveAllDisabled = true;
                    this.moveSelectedDisabled = true;
                    return false;
                }
                this.moveAllDisabled = false;
                this.moveSelectedDisabled = ! this.selected;
            },

            moveParts(parts, endpoint) {
                axios.post(endpoint, parts)
                    .then(response => {
                        this.finalizeMove(response.data);
                    }).catch(error => {
                        this.processError(error);
                    });
            },

            moveAll() {
                this.updateSelected(true);
                this.moveSelected();
            },

            moveSelected() {
                let endpoint = this.move_endpoint + '/' + document.querySelector('#moveToLocation').value;
                let parts  = [];

                let keys = Object.keys(this.partsSelected);
                keys.forEach((key) => {
                    if (! this.partsSelected[key]) {
                        return;
                    }

                    parts.push(this.dataSet[key]);
                });

                if (! parts.length) {
                    flash('You have not selected any parts to move.', 'danger');
                    return;
                }
                this.moveParts(parts, endpoint);
            },

            finalizeMove(count) {
                document.querySelectorAll('.card-container .border-primary').forEach(el => { 
                    el.classList.remove('border-primary'); 
                });
                this.getResults(this.currentPage);
                this.updateSelected();
                flash('Successfully moved '+count+' parts!');
            },
        }
    };
</script>
