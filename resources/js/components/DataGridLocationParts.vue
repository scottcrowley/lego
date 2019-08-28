<template>
    <div>
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
            <div class="relative ml-3">
                <select id="selectOrder" @change="updateSortOrder($event)">
                    <option value="0">Ascending</option>
                    <option value="1">Descending</option>
                </select>
                <div class="select-menu-icon">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
                </div>
            </div>
            <div class="relative ml-auto">
                <select id="selectPerPage" @change="updatePerPage($event)">
                    <option value="0">Per Page</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="75">75</option>
                    <option value="100">100</option>
                </select>
                <div class="select-menu-icon">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                    </svg>
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
                        <div>
                            <p v-if="(data.location !== null && data.location.id != location_id)">
                                <span class="font-bold">Storage Location:</span> 
                                {{ (data.location.nickname != '') ? data.location.nickname : data.location.name }}
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

    export default {
        mixins: [dataGridCore],
        
        methods: {
            executeEndpoint(index) {
                let endpoint = this.generateLinkUrl(this.toggle_end_point, index);

                axios.get(endpoint)
                    .then(response => {
                        this.dataSet[index].location = response.data.location;
                        flash('Part association successfully updated!');
                    })
                    .catch(error => {
                        this.processError(error);
                    });
            }
        }
    };
</script>
