<template>
    <div class="dropdown-menu" ref="dropdowncontainer">
        <div role="button" class="dropdown-toggle-wrap" @click.prevent="toggle">
            <slot name="link"></slot>
        </div>

        <div v-show="open" class="dropdown-items-wrap">
            <slot name="dropdown-items"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                open: false
            }
        },

        created() {
            window.addEventListener('click', this.close)
        },

        beforeDestroy() {
            window.removeEventListener('click', this.close)
        },

        methods: {
            toggle() {
                this.open = ! this.open;
            },

            close(e) {
                if (! this.$refs.dropdowncontainer.contains(e.target)) {
                    this.open = false;
                }
            }
        }
    }
</script>