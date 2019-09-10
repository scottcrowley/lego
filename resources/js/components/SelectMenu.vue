<template>
    <div>
        <div class="relative" :class="parent_classes">
            <select :class="select_classes" ref="selectmenuelement" @change="selectChanged($event)">
                <option v-if="default_label != ''" v-text="default_label" :value="default_value"></option>
                <option v-for="data in dataSet" :value="data[selected_field]">{{ data[label_field] }}</option>
            </select>
            <div class="select-menu-icon">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                </svg>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'parent_classes',
            'select_classes',
            'selected_value',
            'selected_field',
            'data',
            'label_field',
            'default_label',
            'default_value',
            'change_endpoint'
        ],
        data() {
            return {
                dataSet: this.data
            }
        },

        mounted() {
            this.$refs.selectmenuelement.value = this.selected_value;
        },

        methods: {
            selectChanged(e) {
                if (this.change_endpoint != '') {
                    let redirect = this.generateEndpointUrl(e.target.value);
                    this.redirect(redirect);
                }
            },

            generateEndpointUrl(value) {
                let url = this.change_endpoint;
                let token = url.match(/\{.+\}/ig);
                
                if (value && token !== null) {
                    return url.replace(token[0], value);
                }

                return url;
            },

            redirect(url) {
                window.location.href = url;
            }
        }
    }
</script>