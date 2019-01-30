<template>
    <div :class="classes"
         style="right: 25px; top: 25px;"
         role="alert"
         v-show="show"
         v-text="body">
    </div>
</template>

<script>
    export default {
        props: ['message', 'baselevel'],

        data() {
            return {
                body: this.message,
                level: this.baselevel,
                show: false
            }
        },

        computed: {
            classes() {
                let defaults = ['fixed', 'p-4', 'border'];

                if (this.level === 'warning') defaults.push('bg-yellow', 'border-yellow-dark', 'text-grey-dark');
                else if (this.level === 'danger') defaults.push('bg-red', 'border-red-dark', 'text-white');
                else defaults.push('bg-green', 'border-green-dark', 'text-white');

                return defaults;
            }
        },

        created() {
            if (this.message) {
                this.flash();
            }

            window.events.$on(
                'flash', data => this.flash(data)
            );
        },

        methods: {
            flash(data) {
                if (data) {
                    this.body = data.message;
                    this.level = data.level;
                }

                this.show = true;

                this.hide();
            },

            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    };
</script>
