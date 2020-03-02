<template>
    <div class="dropdown relative">
        <div class="dropdown-toggle"
             aria-haspopup="true"
             :aria-expanded="isOpen"
             @click.prevent="isOpen = !isOpen"
        >
            <slot name="trigger"></slot>
        </div>

        <div v-show="isOpen"
             class="dropdown-menu absolute bg-white py-2 rounded shadow-lg mt-2 z-10"
             :class="align === 'left' ? 'left-0' : 'right-0'"
             :style="{ width }"
        >
            <slot></slot>
        </div>
    </div>
</template>

<script>
    // Dropdown menu - Hat-tip to Jeffery Way.
    export default {
        props: {
            width: { default: 'auto' },
            align: { default: 'left' }
        },
        data() {
            return { isOpen: false }
        },
        watch: {
            isOpen(isOpen) {
                if (isOpen) {
                    document.addEventListener('click', this.closeIfClickedOutside);
                }
            }
        },
        methods: {
            closeIfClickedOutside(event) {
                let dropdown = event.target.closest('.dropdown');

                if (
                    ! dropdown
                    || (dropdown && !this.$el.contains(dropdown))
                ) {
                    this.isOpen = false;
                    document.removeEventListener('click', this.closeIfClickedOutside);
                }
            }
        }
    }
</script>