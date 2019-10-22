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
                <div class="card-content">
                    <div class="card-image">
                        <div class="w-full my-0 mx-auto p-0" v-if="data[image_field] == '' || data[image_field] == null"></div>
                        <img :src="data[image_field]" :alt="data['name']" :data-alt-image="data[image_label_field]" v-else class="" @click.prevent="swapImageUrl($event)">
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
    import dataCore from '../mixins/dataCore';
    import dataGridCore from '../mixins/dataGridCore';
    import SelectMenu from '../components/SelectMenu';
    import SelectMenuSpecial from '../components/SelectMenuSpecial';

    export default {
        components: {SelectMenuSpecial, SelectMenu},
        mixins: [dataCore, dataGridCore],

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
        }
    };
</script>
