<template>
    <div class="relative" :class="parent_classes">
        <select :id="select_id" @change="handleMethodCall(change_method,$event)" class="w-full">
            <option v-if="default_label != ''" v-text="default_label" :value="default_value"></option>
            <option v-for="option in options_list" :value="option.value">{{ option.label }}</option>
        </select>
        <div class="select-menu-icon">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
            </svg>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'select_id',
            'parent_classes',
            'options_list',
            'change_method',
            'default_label',
            'default_value',
        ],
        methods: {
            handleMethodCall(function_name, e) {
                this.$parent[function_name](e);
            },
        },
    }
</script>